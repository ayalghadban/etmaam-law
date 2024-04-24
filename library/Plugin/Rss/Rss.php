<?php
    /**
     * Rss Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Rss.php, v1.00 5/18/2023 1:31 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Rss;
    
    use DOMDocument;
    use Wojo\Database\Database;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Rss
    {
        const mTable = 'plug_rss';
        const timeToLive = 7200;
        
        /**
         * getAllRss
         *
         * @return mixed
         */
        public static function getAllRss(): mixed
        {
            return Database::Go()->select(self::mTable)->run();
        }
        
        /**
         * getRssById
         *
         * @param int $id
         * @return int|mixed
         */
        public static function getRssById(int $id): mixed
        {
            return Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run();
        }
        
        /**
         * render
         *
         * @param string $feed_url
         * @param int $max_item_cnt
         * @return array
         */
        public static function render(string $feed_url, int $max_item_cnt = 10): array
        {
            // get feeds and parse items
            $rss = new DOMDocument();
            $cache_file = FPLUGPATH . 'rss/cache/' . md5($feed_url);
            // load from file or load content
            if (self::timeToLive > 0 && is_file($cache_file) && (filemtime($cache_file) + self::timeToLive > time())) {
                $rss->load($cache_file);
            } else {
                $rss->load($feed_url);
                if (self::timeToLive > 0) {
                    $rss->save($cache_file);
                }
            }
            $feed = array();
            foreach ($rss->getElementsByTagName('item') as $node) {
                $item = array(
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                    'content' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                    'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                );
                $content = $node->getElementsByTagName('encoded');
                if ($content->length > 0) {
                    $item['content'] = $content->item(0)->nodeValue;
                }
                $feed[] = $item;
            }
            // real good count
            if ($max_item_cnt > count($feed)) {
                $max_item_cnt = count($feed);
            }
            
            return [$feed, $max_item_cnt];
        }
    }