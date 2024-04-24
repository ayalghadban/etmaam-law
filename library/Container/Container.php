<?php
    /**
     * Container Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Container.php, v1.00 4/28/2023 8:09 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Container;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    use ArrayAccess;
    use Closure;
    use Countable;
    use Iterator;
    use ReflectionClass;
    use ReflectionException;
    use SplObjectStorage;
    use Wojo\Psr\ContainerInterface;
    use Wojo\Debug\Debug;
    use Wojo\Exception\ContainerException;
    use Wojo\Exception\NotFoundException;
    
    class Container implements ArrayAccess, ContainerInterface, Countable, Iterator
    {
        
        private static ?Container $container = null;
        private static array $instance = array();
        
        protected array $entries = [];
        protected array $instances = [];
        protected object $factories;
        protected object $functions;
        protected object $services;
        protected int $pointer = 0;
        
        public static string $namespace = '';
        
        /**
         * @param array $entries
         */
        public function __construct(array $entries = [])
        {
            $this->reset();
            $this->entries = $entries;
            
            if (static::$container === null) {
                static::$container = $this;
            }
            return static::$container;
            
        }
        
        /**
         * instance
         *
         * @return Container
         */
        public static function instance(): Container
        {
            return static::$container;
        }
        
        /**
         * reset
         *
         * @return $this
         */
        public function reset(): static
        {
            $this->entries = [];
            $this->instances = [];
            $this->factories = new SplObjectStorage();
            $this->functions = new SplObjectStorage();
            $this->services = new SplObjectStorage();
            
            return $this;
        }
        
        /**
         * has
         *
         * Returns true/false if id is found
         * @param string $id
         * @return bool
         */
        public function has(string $id): bool
        {
            return array_key_exists($id, $this->entries);
        }
        
        /**
         * raw
         *
         * string id of the entry to look for.
         *
         * @param string $id
         * @return mixed
         * @throws NotFoundException
         */
        public function raw(string $id): mixed
        {
            if (!$this->has($id)) {
                throw new NotFoundException(sprintf('No entry was found for "%s" identifier.', $id));
            }
            
            return $this->entries[$id];
        }
        
        /**
         * get
         *
         * Finds and returns id
         *
         * @param string $id
         * @return mixed
         */
        public function get(string $id): mixed
        {
            if (isset($this->instances[$id])) {
                return $this->instances[$id];
            }
            try {
                $value = $this->raw($id);
                if ($this->isFactory($value)) {
                    return $value($this);
                }
                if ($this->isService($value)) {
                    $this->instances[$id] = $value($this);
                    
                    return $this->instances[$id];
                }
                if ($this->isComputed($value)) {
                    return $value($this);
                }
                return $value;
            } catch (NotFoundException) {
            }
            
            
        }
        
        /**
         * set
         *
         * Sets an array value by id.
         * @param string $id
         * @param $value
         * @return $this
         */
        public function set(string $id, $value): static
        {
            $this->entries[$id] = $value;
            
            return $this;
        }
        
        /**
         * delete
         *
         * Delete an entry.
         * @param string $id
         * @return $this
         * @throws NotFoundException
         */
        public function delete(string $id): static
        {
            if ($this->has($id)) {
                $value = $this->get($id);
                if ($this->isFactory($value)) {
                    $this->factories->detach($value);
                } else {
                    if ($this->isService($value)) {
                        $this->services->detach($value);
                    } else {
                        if ($this->isComputed($value)) {
                            $this->functions->detach($value);
                        }
                    }
                }
                unset($this->entries[$id], $this->instances[$id]);
            }
            
            return $this;
        }
        
        /**
         * deleteInstance
         *
         * Remove an instance.
         * @param string $id
         * @return $this
         */
        public function deleteInstance(string $id): static
        {
            unset($this->instances[$id]);
            
            return $this;
        }
        
        /**
         * deleteAllInstances
         *
         * Remove all instances.
         * @return $this
         */
        public function deleteAllInstances(): static
        {
            $this->instances = [];
            
            return $this;
        }
        
        /**
         * extend
         *
         * Extend a factory or service by creating a closure that will manipulate the instantiated instance.
         * @param string $id
         * @param Closure $closure
         * @return Closure
         * @throws NotFoundException
         */
        public function extend(string $id, Closure $closure): Closure
        {
            $value = $this->raw($id);
            if (!$this->isService($value) && !$this->isFactory($value) && !$this->isComputed($value)) {
                throw new ContainerException(sprintf('Identifier "%s" does not contain an object definition.', $id));
            }
            $extended = function (Container $container) use ($closure, $value) {
                return $closure($value($container), $container);
            };
            
            if ($this->isFactory($value)) {
                $this->factories->detach($value);
                $this->factories->attach($extended);
                
            } else {
                if ($this->isService($value)) {
                    $this->services->detach($value);
                    $this->services->attach($extended);
                } else {
                    if ($this->isComputed($value)) {
                        $this->functions->detach($value);
                        $this->functions->attach($extended);
                    }
                }
            }
            $this->entries[$id] = $extended;
            
            return $extended;
        }
        
        /**
         * factory
         *
         * Callable as a factory.
         * Factories return a new class instance every time you fetch them.
         * @param Closure $closure
         * @return Closure
         */
        public function factory(Closure $closure): Closure
        {
            $this->factories->attach($closure);
            
            return $closure;
        }
        
        /**
         * isFactory
         *
         * Checks if a value is a factory.
         * @param $value
         * @return bool
         */
        public function isFactory($value): bool
        {
            return is_object($value) && isset($this->factories[$value]);
        }
        
        /**
         * computed
         *
         * Callable as a computed value.
         * @param Closure $closure
         * @return Closure
         */
        public function computed(Closure $closure): Closure
        {
            $this->functions->attach($closure);
            
            return $closure;
        }
        
        /**
         * isComputed
         *
         * @param $value
         * @return bool
         */
        public function isComputed($value): bool
        {
            return is_object($value) && isset($this->functions[$value]);
        }
        
        /**
         * service
         *
         * Callable as a service.
         * Services return the same class instance every time you fetch them.
         * @param Closure $closure
         * @return Closure
         */
        public function service(Closure $closure): Closure
        {
            $this->services->attach($closure);
            
            return $closure;
        }
        
        /**
         * isService
         *
         * Checks if a value is a service.
         * @param $value
         * @return bool
         */
        public function isService($value): bool
        {
            return is_object($value) && isset($this->services[$value]);
        }
        
        /**
         * keys
         *
         * Get all array keys.
         * @return array
         */
        public function keys(): array
        {
            return array_keys($this->entries);
        }
        
        /**
         * current
         *
         * Return the current element.
         * @return mixed
         */
        public function current(): mixed
        {
            return $this->offsetGet($this->key());
        }
        
        /**
         * next
         *
         * Move forward to next element
         * @return void
         */
        public function next(): void
        {
            ++$this->pointer;
        }
        
        /**
         * key
         *
         * Return the key of the current element
         * @return mixed
         */
        public function key(): mixed
        {
            return $this->keys()[$this->pointer];
        }
        
        /**
         * valid
         *
         * Checks if current position is valid
         * @return bool
         */
        public function valid(): bool
        {
            return isset($this->keys()[$this->pointer]);
        }
        
        /**
         * rewind
         *
         * Rewind the Iterator to the first element
         * @return void
         */
        public function rewind(): void
        {
            $this->pointer = 0;
        }
        
        /**
         * offsetExists
         *
         * If offset exists.
         * @param mixed $offset
         * @return bool
         */
        public function offsetExists(mixed $offset): bool
        {
            return $this->has($offset);
        }
        
        /**
         * offsetGet
         *
         * Offset to retrieve.
         * @param mixed $offset
         * @return mixed
         */
        public function offsetGet(mixed $offset): mixed
        {
            return $this->get($offset);
        }
        
        /**
         * offsetSet
         *
         * Offset to set.
         * @param mixed $offset
         * @param mixed $value
         * @return void
         */
        public function offsetSet(mixed $offset, mixed $value): void
        {
            $this->set($offset, $value);
        }
        
        /**
         * offsetUnset
         *
         * Offset to unset.
         * @param mixed $offset
         * @return void
         * @throws NotFoundException
         */
        public function offsetUnset(mixed $offset): void
        {
            $this->delete($offset);
        }
        
        /**
         * count
         * Number of entries
         * @return int
         */
        public function Count(): int
        {
            return count($this->entries);
        }
        
        /**
         * __callStatic
         * Call Statically Container::ClassName()
         * @param string $name
         * @param array $args
         * @return mixed|object|string|void|null
         * @throws ReflectionException
         */
        public static function __callStatic(string $name, array $args): mixed
        {
            $name = self::$namespace . $name;
            try {
                if (!class_exists($name)) {
                    throw new NotFoundException('Class name ' . $name . ' does not exists.');
                }
                //make a new instance
                if (!in_array($name, array_keys(self::$instance))) {
                    //check for arguments
                    if (count($args) !== 0) {
                        //new keyword will accept a string in a variable
                        $instance = new $name();
                    } else {
                        //we need reflection to instantiate with an arbitrary number of args
                        $rc = new ReflectionClass($name);
                        $instance = $rc->newInstanceArgs($args);
                    }
                    self::$instance[$name] = $instance;
                } else {
                    //already have one
                    $instance = self::$instance[$name];
                }
                return $instance;
            } catch (NotFoundException $e) {
                Debug::addMessage('warnings', '<i>Warning</i>', $e->getMessage());
            }
        }
    }