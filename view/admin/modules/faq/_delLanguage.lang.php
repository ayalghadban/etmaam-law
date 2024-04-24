<?php
    /**
     * _delLanguage.lang.php
     *
     * @var object $abbr
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: _delLanguage.tpl.php, v1.00 5/11/2023 18:34 AM Gewa Exp $
     *
     */
    
    use Wojo\Database\Database;
    use Wojo\Module\Faq\Faq;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    //mod_faq
    Database::Go()->rawQuery('
    ALTER TABLE `' . Faq::mTable . "`
      DROP COLUMN question_$abbr,
      DROP COLUMN answer_$abbr
    ")->run();
    
    //mod_faq_categories
    Database::Go()->rawQuery('ALTER TABLE `' . Faq::cTable . "` DROP COLUMN name_$abbr")->run();