<?php
    /**
     * Paginator Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Paginator.php, v1.00 5/4/2023 8:53 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Database;
    
    use Wojo\Core\Router;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Paginator
    {
        private static ?Paginator $instance = null;
        public int $items_per_page;
        public int $items_total;
        public int $num_pages = 1;
        public string $limit;
        public int $current_page;
        public ?int $default_ipp = 10;
        public int $start_range;
        public int $end_range;
        public array $range = [];
        public string $path;
        public array $files = [];
        public array $filesArray = [];
        public string $filePath;
        
        private int $mid_range;
        private int $low;
        private int $high;
        private string $retdata = '';
        private string $querystring = '';
        
        
        /**
         *
         */
        private function __construct()
        {
            $this->current_page = 1;
            $this->mid_range = 7;
            $this->items_per_page = (isset($_GET['ipp']) and is_numeric($_GET['ipp'])) ? Validator::sanitize($_GET['ipp'], 'int') : $this->default_ipp;
        }
        
        /**
         * instance
         *
         * @return Paginator
         */
        public static function instance(): Paginator
        {
            if (!self::$instance) {
                self::$instance = new Paginator();
            }
            
            return self::$instance;
        }
        
        /**
         * Files
         *
         * @param array $files
         * @return void
         */
        public function files(array $files): void
        {
            
            if (isset($_GET['pg'])) {
                $this->current_page = (int) $_GET['pg'];
                $this->high = $this->current_page * $this->default_ipp;
                $this->low = $this->high - $this->default_ipp;
            }
            
            $this->files = $files;
            $this->filesArray = array_slice($this->files, $this->low, $this->default_ipp);
        }
        
        /**
         * paginate
         *
         * @return void
         */
        public function paginate(): void
        {
            $this->items_per_page = (isset($_GET['ipp']) and strlen($_GET['ipp']) !==0) ? intval($_GET['ipp']) : $this->default_ipp;
            $this->num_pages = ceil($this->items_total / $this->items_per_page);
            
            $this->current_page = intval(Validator::get('pg'));
            if ($this->current_page < 1 or !is_numeric($this->current_page)) {
                $this->current_page = 1;
            }
            if ($this->current_page > $this->num_pages) {
                $this->current_page = $this->num_pages;
            }
            $prev_page = $this->current_page - 1;
            $next_page = $this->current_page + 1;
            
            if (isset($_POST)) {
                foreach ($_POST as $key => $val) {
                    if ($key != 'pg' && $key != 'ipp') {
                        $this->querystring .= "&amp;$key=" . Validator::sanitize($val);
                    }
                }
            }
            
            if ($this->num_pages > 1) {
                if ($this->current_page != 1 && $this->items_total >= $this->default_ipp) {
                    $this->retdata = "<a class=\"item\" href=\"" . self::buildUrl($prev_page) . "\"><i class=\"icon chevron left\"></i></a>";
                } else {
                    $this->retdata = "<a class=\"disabled item\"><i class=\"icon chevron left\"></i></a>";
                }
                
                $this->start_range = $this->current_page - floor($this->mid_range / 2);
                $this->end_range = $this->current_page + floor($this->mid_range / 2);
                
                if ($this->start_range <= 0) {
                    $this->end_range += abs($this->start_range) + 1;
                    $this->start_range = 1;
                }
                if ($this->end_range > $this->num_pages) {
                    $this->start_range -= $this->end_range - $this->num_pages;
                    $this->end_range = $this->num_pages;
                }
                $this->range = range($this->start_range, $this->end_range);
                
                for ($i = 1; $i <= $this->num_pages; $i++) {
                    if ($this->range[0] > 2 && $i == $this->range[0]) {
                        $this->retdata .= "<a class=\"disabled item\"> ... </a>";
                    }
                    
                    if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                        if ($i == $this->current_page) {
                            $this->retdata .= "<a title=\"" . Language::$word->GOTO . $i . Language::$word->OF . $this->num_pages . "\" class=\"active item\">$i</a>";
                        } else {
                            $this->retdata .= "<a class=\"item\" title=\"Go To $i of $this->num_pages\" href=\"" . self::buildUrl($i) . "\">$i</a>";
                        }
                    }
                    
                    if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 && $i == $this->range[$this->mid_range - 1]) {
                        $this->retdata .= "<a class=\"disabled item\"> ... </a>";
                    }
                }
                
                if ($this->current_page != $this->num_pages && $this->items_total >= $this->default_ipp) {
                    $this->retdata .= "<a class=\"item\" href=\"" . self::buildUrl($next_page) . "\"><i class=\"icon chevron right\"></i></a>";
                } else {
                    $this->retdata .= "<a class=\"disabled item\"><i class=\"icon chevron right\"></i></a>";
                }
                
            } else {
                for ($i = 1; $i <= $this->num_pages; $i++) {
                    if ($i == $this->current_page) {
                        $this->retdata .= "<a class=\"active item\">$i</a>";
                    } else {
                        $this->retdata .= "<a class=\"item\" href=\"" . self::buildUrl($i) . "\">$i</a>";
                    }
                }
            }
            $this->low = ($this->current_page - 1) * $this->items_per_page;
            $this->high = $this->current_page * $this->items_per_page - 1;
            $this->limit = ($this->items_total == 0) ? '' : " LIMIT $this->low,$this->items_per_page";
        }
        
        /**
         * buildUrl
         *
         * @param string $value
         * @return string
         */
        public static function buildUrl(string $value): string
        {
            $parts = parse_url($_SERVER['REQUEST_URI']);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $qs);
            } else {
                $qs = array();
            }
            $qs['pg'] = $value;
            return '?' . $parts['query'] = http_build_query($qs);
        }
        
        
        /**
         * display
         *
         * @param $class
         * @return string
         */
        public function display($class = ''): string
        {
            return ($this->items_total > $this->items_per_page) ? '<div class="wojo ' . $class . ' pagination">' . $this->retdata . '</div>' : '';
        }
        
        /**
         * displayFiles
         *
         * @param $class
         * @return string
         */
        public function displayFiles($class = ''): string
        {
            
            $amount = count($this->files);
            $pages = ceil($amount / $this->default_ipp);
            
            $o = '';
            for ($i = 1; $i <= $pages; $i++) {
                $o .= sprintf(('<a href="%s" class="item%s">%s</a>'), Url::url(Router::$path, '?pg=' . $i), ($i == $this->current_page) ? ' active' : null, $i);
            }
            return '<div class="wojo ' . $class . ' pagination">' . $o . '</div>';
        }
        
    }