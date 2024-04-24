<?php
    /**
     * Validator Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Validator.php, v1.00 4/26/2023 3:51 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Validator;
    
    use Exception;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Validator
    {
        protected static ?Validator $instance = null;
        public array $raw = array();
        public array $clean = array();
        protected array $_data = array();
        protected array $_errors = array();
        
        private bool $next = true;
        
        public static array $trues = [true, 'yes', 'on'];
        public static array $falses = [false, 'no', 'off'];
        
        public static array $basic_tags = array('br', 'p', 'a', 'strong', 'b', 'i', 'em', 'img', 'blockquote', 'code', 'dd', 'dl', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'label', 'ul', 'li', 'span', 'sub', 'sup', 'u', 'div');
        
        public static array $script_tags = array('script');
        
        public static array $advanced_tags = array(
            'iframe', 'body', 'html', 'section', 'article', 'video', 'audio', 'source', 'div', 'table', 'td', 'tr', 'th', 'tbody', 'thead', 'img', 'svg', 'figure', 'br', 'p', 'a', 'strong', 'u', 'b', 'i', 'em', 'img', 'pre', 'blockquote', 'code', 'dd', 'dl', 'hr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'label', 'ol', 'ul', 'li', 'span', 'sub', 'sup', 'button', 'defs', 'path', 'clipPath', 'use', 'image', 'style', 'ellipse', 'circle', 'g', 'polyline', 'line', 'rect', 'polygon', 'form', 'input', 'select', 'option'
        );
        
        /**
         * @param array $raw
         */
        private function __construct(array $raw)
        {
            $this->raw = $raw;
        }
        
        /**
         * Validator::Run()
         *
         * @param array $raw
         * @return Validator|null
         */
        public static function run(array $raw): ?Validator
        {
            if (self::$instance === null) {
                self::$instance = new self($raw);
            }
            return self::$instance;
        }
        
        /**
         * set
         *
         * @param string $value
         * @param string $alias
         * @return $this
         */
        public function set(string $value, string $alias): Validator
        {
            if (array_key_exists($value, $this->raw)) {
                $this->_data['value'] = $this->raw[$value];
                $this->next = true;
            } else {
                $this->_data['value'] = null;
                $this->next = false;
            }
            $this->_data['name'] = $value;
            $this->_data['text'] = $alias;
            return $this;
        }
        
        /**
         * required
         *
         * @return $this
         */
        public function required(): Validator
        {
            if (!self::exists() and ($this->_data['value'] !== '0' or $this->_data['value'] != 0)) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R100;
                $this->next = false;
            }
            return $this;
        }
        
        /**
         * exists
         *
         * @return bool
         */
        private function exists(): bool
        {
            
            if (!isset($this->_data['value']) or !$this->_data['value'] or !array_key_exists('value', $this->_data)) {
                return false;
            }
            return true;
        }
        
        /**
         * safe
         *
         * @return object
         */
        public function safe(): object
        {
            return (object) $this->clean;
        }
        
        /**
         * string
         *
         * @param bool $clean
         * @param bool $multiline
         * @return $this
         */
        public function string(bool $clean = true, bool $multiline = false): static
        {
            $pattern = $multiline ? '/(^&amp)*[<>%;`(){}]+/' : '/(^&amp)*[<>\n%;`(){}]+/';
            if ($this->next and !preg_match_all($pattern, $this->_data['value'] ?? '') === false) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R8;
                $this->next = false;
            } else {
                if ($clean) {
                    $this->clean[$this->_data['name']] = $this->_data['value'] = trim(htmlspecialchars($this->_data['value'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
                }
            }
            
            return $this;
        }
        
        /**
         * email
         *
         * @return $this
         */
        public function email(): static
        {
            if ($this->exists()) {
                if ($this->next and filter_var($this->_data['value'], FILTER_VALIDATE_EMAIL) === false) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R18;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * alpha
         *
         * @param string $type
         * @return $this
         */
        public function alpha(string $type = 'full'): static
        {
            $pattern = $type == 'full' ? '/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i' : '/^([a-zA-Z])+$/i';
            if ($this->exists()) {
                if ($this->next and !preg_match($pattern, $this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R8;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * alpha_numeric
         *
         * @param string $type
         * @return $this
         */
        public function alpha_numeric(string $type = 'full'): static
        {
            $pattern = $type == 'full' ? '/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i' : '/^([a-zA-Z0-9])+$/i';
            if ($this->exists()) {
                if ($this->next and !preg_match($pattern, $this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R9;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * contains
         *
         * @param mixed $chars
         * @param bool $separator
         * @return $this
         */
        public function contains(mixed $chars, bool $separator = false): static
        {
            $str = '';
            if (!is_array($chars)) {
                if (!$separator or is_null($chars)) {
                    $str = $chars;
                    $chars = array();
                } else {
                    $chars = explode($separator, $chars);
                    $str = implode(', ', $chars);
                }
            }
            if ($this->exists()) {
                if ($this->next and !in_array($this->_data['value'], $chars)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[VAL]', $str, Language::$word->FIELD_R11);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * lowercase
         *
         * @return $this
         */
        public function lowercase(): static
        {
            if ($this->exists()) {
                if ($this->next and $this->_data['value'] !== mb_strtolower($this->_data['value'], mb_detect_encoding($this->_data['value']))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R23;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * uppercase
         *
         * @return $this
         */
        public function uppercase(): static
        {
            if ($this->exists()) {
                if ($this->next and $this->_data['value'] !== mb_strtoupper($this->_data['value'], mb_detect_encoding($this->_data['value']))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R24;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * min_len
         *
         * @param int $value
         * @return $this
         */
        public function min_len(int $value): static
        {
            if ($this->exists()) {
                if ($this->next and mb_strlen($this->_data['value']) < $value) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[X]', $value, Language::$word->FIELD_R2);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * max_len
         *
         * @param int $value
         * @return $this
         */
        public function max_len(int $value): static
        {
            if ($this->exists()) {
                if ($this->next and mb_strlen($this->_data['value']) > $value) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[X]', $value, Language::$word->FIELD_R1);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * exact_len
         *
         * @param int $value
         * @return $this
         */
        public function exact_len(int $value): static
        {
            if ($this->exists()) {
                if ($this->next and mb_strlen($this->_data['value']) != $value) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[X]', $value, Language::$word->FIELD_R7);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * numeric
         *
         * @return $this
         */
        public function numeric(): static
        {
            if ($this->exists()) {
                if ($this->next and !is_numeric($this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R5;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = filter_var($this->_data['value'], FILTER_SANITIZE_NUMBER_INT);
            return $this;
        }
        
        /**
         * integer
         *
         * @return $this
         */
        public function integer(): static
        {
            if ($this->exists()) {
                if ($this->next and !filter_var($this->_data['value'], FILTER_VALIDATE_INT)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R6;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = intval($this->_data['value']);
            return $this;
        }
        
        /**
         * float
         *
         * @return $this
         */
        public function float(): static
        {
            if ($this->exists()) {
                if ($this->next and !is_float(filter_var($this->_data['value'], FILTER_VALIDATE_FLOAT))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R19;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = filter_var($this->_data['value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            return $this;
        }
        
        /**
         * boolean
         *
         * @return $this
         */
        public function boolean(): static
        {
            if ($this->next and !is_bool($this->_data['value'])) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R25;
                $this->next = false;
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = in_array($this->_data['value'], self::$trues, true);
            return $this;
        }
        
        /**
         * min_numeric
         *
         * @param int $value
         * @return $this
         */
        public function min_numeric(int $value): static
        {
            if ($this->exists()) {
                if ($this->next and (intval($this->_data['value']) < $value)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[X]', $value, Language::$word->FIELD_R5_1);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * max_numeric
         *
         * @param int $value
         * @return $this
         */
        public function max_numeric(int $value): static
        {
            if ($this->exists()) {
                if ($this->next and (intval($this->_data['value']) > $value)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[X]', $value, Language::$word->FIELD_R5_2);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * equals
         *
         * @param string|int|float $value
         * @param bool $identical
         * @return $this
         */
        public function equals(string|int|float $value, bool $identical = false): static
        {
            $verify = ($identical === true ? $this->_data['value'] === $value : strtolower($this->_data['value']) == strtolower($value));
            if ($this->exists()) {
                if ($this->next and !$verify) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[VAL]', $value, Language::$word->FIELD_R13);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * date
         *
         * @return $this
         */
        public function date(): static
        {
            if ($this->exists()) {
                $error = date_parse($this->_data['value']);
                if ($this->next and $error['warning_count'] == 1 or $error['error_count'] == 1) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R4;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * time
         *
         * @return $this
         */
        public function time(): static
        {
            if ($this->exists()) {
                $error = preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $this->_data['value']);
                if ($this->next and !$error) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R4_1;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * path
         *
         * @return $this
         */
        public function path(): static
        {
            if ($this->exists()) {
                $pattern = '#^([a-z0-9_/: -.])+$#i';
                if ($this->next and !preg_match($pattern, $this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R21;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * url
         *
         * @return $this
         */
        public function url(): static
        {
            if ($this->exists()) {
                if ($this->next and (!filter_var($this->_data['value'], FILTER_VALIDATE_URL))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R14;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * start
         *
         * @param string $value
         * @return $this
         */
        public function start(string $value): static
        {
            if ($this->exists()) {
                if ($this->next and (!str_starts_with($this->_data['value'], $value))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[VAL]', $value, Language::$word->FIELD_R12);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * end
         *
         * @param string $value
         * @return $this
         */
        public function end(string $value): static
        {
            if ($this->exists()) {
                if ($this->next and (!str_ends_with($this->_data['value'], $value))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace('[VAL]', $value, Language::$word->FIELD_R12_1);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * color
         *
         * @return $this
         */
        public function color(): static
        {
            if ($this->exists()) {
                $pattern = '/(?:#|0x)(?:[a-f0-9]{3}|[a-f0-9]{6})\b|(?:rgb|hsl)a?\([^)]*\)/m';
                if ($this->next and (!preg_match_all($pattern, $this->_data['value']))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R20;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * json
         *
         * @return $this
         */
        public function json(): static
        {
            if ($this->exists()) {
                try {
                    json_decode($this->_data['value'], true, 512, JSON_THROW_ON_ERROR);
                } catch (Exception) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R26;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * text
         *
         * @param string $type
         * @return $this
         */
        public function text(string $type = 'default'): static
        {
            $clean = '';
            if ($this->exists() and $this->next) {
                $clean = match ($type) {
                    'advanced' => self::$advanced_tags,
                    'basic' => self::$basic_tags,
                    'script' => self::$script_tags,
                    default => null,
                };
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = strip_tags($this->_data['value'], $clean);
            return $this;
        }
        
        /**
         * one
         *
         * @return $this
         */
        public function one(): static
        {
            if (!array_key_exists($this->_data['name'], $this->raw)) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Language::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Language::$word->FIELD_R22;
                $this->next = false;
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * sanitize
         *
         * @param string|null $data
         * @param string $type
         * @param int $trim
         * @return array|mixed|string|string[]|null
         */
        public static function sanitize(string|null $data = '', string $type = 'default', int $trim = 0): mixed
        {
            switch ($type) {
                case 'string':
                    $data = preg_replace('/\x00|<[^>]*>?/', '', $data ?? '');
                    $data = str_replace(["'", '"'], ['&#39;', '&#34;'], $data);
                    $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $data = $trim ? mb_substr($data, 0, $trim) : $data;
                    break;
                
                case 'search':
                    $data = str_replace(array('_', '%'), array('', ''), $data);
                    $data = strip_tags($data);
                    break;
                
                case 'email':
                    $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                    break;
                
                case 'url':
                    $data = filter_var($data, FILTER_SANITIZE_URL);
                    break;
                
                case 'alpha':
                    $data = preg_replace('/[^A-Za-z]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case 'alphalow':
                    $data = preg_replace('/[^a-z]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case 'alphahi':
                    $data = preg_replace('/[^A-Z]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case 'alphanumeric':
                    $data = preg_replace('/[^A-Za-z0-9]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case 'chars':
                case 'spchar':
                    $data = htmlspecialchars(str_replace(array('\'', '"'), '', $data), ENT_QUOTES, 'UTF-8');
                    break;
                
                case 'emailalt':
                    $data = preg_replace('/[^a-zA-Z0-9\/_|@+ .-]/', '', $data);
                    break;
                
                case 'year':
                    $data = substr(preg_replace('/[^0-9]/', '', $data), 0, 4);
                    break;
                
                case 'time':
                    $data = preg_replace('/[^0-9:]/', '', $data);
                    break;
                
                case 'date':
                    $data = preg_replace('/[^0-9,-]/', '', $data);
                    break;
                
                case 'file':
                    $data = preg_replace('/[^a-zA-Z0-9\/_ .-]/', '', $data);
                    break;
                
                case 'implode':
                    $data = preg_replace('/[^0-9,]/', '', $data);
                    break;
                
                case 'int':
                    $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case 'float':
                    $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    break;
                
                case 'db':
                    $data = preg_replace('/[^A-Za-z0-9_\-]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case 'text':
                    $data = filter_var($data, FILTER_UNSAFE_RAW);
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = strip_tags($data);
                    $data = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $data);
                    $data = $trim ? self::truncate($data, $trim) : $data;
                    break;
                
                case 'default':
                default:
                    $data = htmlspecialchars($data ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = strip_tags($data);
                    $data = $trim ? self::truncate($data, $trim) : $data;
                    break;
            }
            
            return $data;
            
        }
        
        /**
         * alphaBits
         *
         * @param string $url
         * @param string $class
         * @return string
         */
        public static function alphaBits(string $url, string $class = 'wojo small horizontal compact divided list'): string
        {
            $charset = explode(',', Language::$word->CHARSET);
            $html = "<div class=\"$class\">\n";
            foreach ($charset as $key) {
                $active = ($key == self::get('letter')) ? ' active' : null;
                $html .= "<a class=\"item$active\" href=\"" . $url . '?letter=' . $key . "\">" . $key . "</a>\n";
            }
            $active = (!self::get('letter')) ? ' active' : null;
            $html .= "<a class=\"item$active\" href=\"" . $url . "\">" . Language::$word->ALL . "</a>\n";
            $html .= "</div>\n";
            unset($key);
            
            return $html;
        }
        
        /**
         * censored
         *
         * @param string $string
         * @param string $blacklist
         * @return string|array|string[]|null
         */
        public static function censored(string $string, string $blacklist): string|array|null
        {
            $array = explode(',', $blacklist);
            if (count($array) > 1) {
                foreach ($array as $row) {
                    $string = preg_replace("`$row`", '***', $string);
                }
            }
            return $string;
        }
        
        /**
         * truncate
         *
         * @param string $string
         * @param int $length
         * @param bool $ellipsis
         * @return string
         */
        public static function truncate(string $string, int $length, bool $ellipsis = true): string
        {
            $wide = mb_strlen(preg_replace('/[^A-Z0-9_@#%$&]/', '', $string));
            $length = round($length - $wide * 0.2);
            $clean_string = preg_replace('/&[^;]+;/', '-', $string);
            if (mb_strlen($clean_string) <= $length) {
                return $string;
            }
            $difference = $length - mb_strlen($clean_string);
            $result = mb_substr($string, 0, $difference);
            if ($result != $string and $ellipsis) {
                $result = self::add_ellipsis($result);
            }
            return $result;
        }
        
        /**
         * add_ellipsis
         *
         * @param string $string
         * @return string
         */
        public static function add_ellipsis(string $string): string
        {
            $string = mb_substr($string, 0, mb_strlen($string) - 3);
            return trim(preg_replace('/ .{1,3}$/', '', $string)) . '...';
        }
        
        /**
         * cleanOut
         *
         * @param string $data
         * @return string
         */
        public static function cleanOut(string $data = ''): string
        {
            
            $data = strtr($data ?? '', array('\r\n' => '', '\r' => '', '\n' => ''));
            $data = html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return stripslashes(trim($data));
        }
        
        /**
         * arrayClean
         *
         * @param array $data
         * @return array
         */
        public static function arrayClean(array $data): array
        {
            foreach ($data as $k => $v) {
                $data[$k] = strip_tags($v);
            }
            
            return $data;
        }
        
        /**
         * compareNumbers
         *
         * @param int|float|string $float1
         * @param int|float|string $float2
         * @param string $operator
         * @return bool|void
         */
        public static function compareNumbers(int|float|string $float1, int|float|string $float2, string $operator = '=')
        {
            // Check numbers to 5 digits of precision
            $epsilon = 0.00001;
            
            $float1 = (float) $float1;
            $float2 = (float) $float2;
            
            switch ($operator) {
                // equal
                case '=':
                case 'eq':
                    if (abs($float1 - $float2) < $epsilon) {
                        return true;
                    }
                    break;
                // less than
                case '<':
                case 'lt':
                    if (abs($float1 - $float2) < $epsilon) {
                        return false;
                    } else {
                        if ($float1 < $float2) {
                            return true;
                        }
                    }
                    break;
                // less than or equal
                case '<=':
                case 'lte':
                    if (self::compareNumbers($float1, $float2, '<') || self::compareNumbers($float1, $float2)) {
                        return true;
                    }
                    break;
                // greater than
                case '>':
                case 'gt':
                    if (abs($float1 - $float2) < $epsilon) {
                        return false;
                    } else {
                        if ($float1 > $float2) {
                            return true;
                        }
                    }
                    break;
                // greater than or equal
                case '>=':
                case 'gte':
                    if (self::compareNumbers($float1, $float2, '>') || self::compareNumbers($float1, $float2)) {
                        return true;
                    }
                    break;
                
                case '<>':
                case '!=':
                case 'ne':
                    if (abs($float1 - $float2) > $epsilon) {
                        return true;
                    }
                    break;
                default:
                    die("Unknown operator '" . $operator . "' in compareNumbers()");
            }
            
            return false;
        }
        
        /**
         * request
         *
         * @param string $var
         * @return mixed|void
         */
        public static function request(string $var)
        {
            if (isset($_REQUEST[$var])) {
                return $_REQUEST[$var];
            }
        }
        
        /**
         * post
         *
         * @param string $var
         * @return mixed|void
         */
        public static function post(string $var)
        {
            if (isset($_POST[$var])) {
                return $_POST[$var];
            }
        }
        
        /**
         * isPostSet
         *
         * @param string $key
         * @param string $val
         * @return bool
         */
        public static function isPostSet(string $key, string $val): bool
        {
            return isset($_POST[$key]) and $_POST[$key] == $val;
        }
        
        /**
         * notEmptyPost
         *
         * @param string $var
         * @return mixed|void
         */
        public static function notEmptyPost(string $var)
        {
            if (array_key_exists($var, $_POST)) {
                return $_POST[$var];
            }
        }
        
        /**
         * checkPost
         *
         * @param string $index
         * @param string $msg
         * @return void
         */
        public static function checkPost(string $index, string $msg): void
        {
            
            if (!array_key_exists($index, $_POST)) {
                Message::$msgs[$index] = $msg;
            }
        }
        
        /**
         * checkSetPost
         *
         * @param string $index
         * @param string $msg
         * @return void
         */
        public static function checkSetPost(string $index, string $msg): void
        {
            
            if (!isset($_POST[$index])) {
                Message::$msgs[$index] = $msg;
            }
        }
        
        /**
         * get
         *
         * @param string $var
         * @return mixed|void
         */
        public static function get(string $var)
        {
            if (isset($_GET[$var])) {
                return $_GET[$var];
            }
        }
        
        /**
         * notEmptyGet
         *
         * @param string $var
         * @return mixed|void
         */
        public static function notEmptyGet(string $var)
        {
            if (array_key_exists($var, $_GET)) {
                return $_GET[$var];
            }
        }
        
        /**
         * isGetSet
         *
         * @param string $key
         * @param string $val
         * @return bool
         */
        public static function isGetSet(string $key, string $val): bool
        {
            return isset($_GET[$key]) and $_GET[$key] == $val;
        }
        
        /**
         * has
         *
         * @param string|null $value
         * @param string $string
         * @return string
         */
        public static function has(null|string $value, string $string = '-/-'): string
        {
            return (isset($value)) ? $value : $string;
        }
        
        /**
         * getChecked
         *
         * @param string $row
         * @param string $status
         * @return string|void
         */
        public static function getChecked(string $row, string $status)
        {
            if ($row == $status) {
                return "checked=\"checked\"";
            }
        }
        
        /**
         * getSelected
         *
         * @param string $row
         * @param string $status
         * @return false|string
         */
        public static function getSelected(string $row, string $status): false|string
        {
            if ($row == $status) {
                return "selected=\"selected\"";
            }
            return false;
        }
        
        /**
         * getActive
         *
         * @param string $row
         * @param string $status
         * @return false|string
         */
        public static function getActive(string $row, string $status): false|string
        {
            if ($row == $status) {
                return 'active';
            }
            return false;
        }
    }