<?php
    /**
     * Slider Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Slider.php, v1.00 5/18/2023 8:16 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Slider;
    
    use Wojo\Database\Database;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Slider
    {
        const mTable = 'plug_slider';
        const dTable = 'plug_slider_data';
        
        /**
         * getSliders
         *
         * @return mixed
         */
        public static function getSliders(): mixed
        {
            return Database::Go()->select(self::mTable)->run();
        }
        
        /**
         * getSlides
         *
         * @param int $id
         * @return mixed
         */
        public static function getSlides(int $id): mixed
        {
            return Database::Go()->select(self::dTable)->where('parent_id', $id, '=')->orderBy('sorting', 'ASC')->run();
        }
        
        /**
         * render
         *
         * @param int $id
         * @return mixed
         */
        public static function render(int $id): mixed
        {
            
            return Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run();
        }
        
        /**
         * slideTransitions
         *
         * @return string[]
         */
        public static function slideTransitions(): array
        {
            
            return array(
                'scale' => 'Scale',
                'fade' => 'Fade',
                'fade up' => 'Fade Up',
                'fade down' => 'Fade Down',
                'fade left' => 'Fade Left',
                'fade right' => 'Fade Right',
                'horizontal flip' => 'Horizontal Flip',
                'vertical flip' => 'Vertical Flip',
                'drop' => 'Drop',
                'fly up' => 'Fly Up',
                'fly down' => 'Fly Down',
                'fly left' => 'Fly Left',
                'fly right' => 'Fly Right',
                'swing up' => 'Swing Up',
                'swing down' => 'Swing Down',
                'swing left' => 'Swing Left',
                'swing right' => 'Swing Right',
                'browse left' => 'Browse Left',
                'browse right' => 'Browse Right',
                'slide up' => 'Slide Up',
                'slide down' => 'Slide Down',
                'slide left' => 'Slide Left',
                'slide right' => 'Slide Right',
            );
        }
    }