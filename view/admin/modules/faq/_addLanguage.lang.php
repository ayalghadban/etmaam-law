<?php
    /**
     * _addLanguage.lang.php
     *
     * @var object $flag_id
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: _addLanguage.tpl.php, v1.00 5/11/2023 18:34 AM Gewa Exp $
     *
     */
    
    use Wojo\Database\Database;
    use Wojo\Module\Faq\Faq;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    //mod_faq
    $sql = '
    ALTER TABLE `' . Faq::mTable . "`
	  ADD COLUMN question_$flag_id VARCHAR (100) NOT NULL AFTER question_en,
	  ADD COLUMN answer_$flag_id TEXT AFTER answer_en
	";
    Database::Go()->rawQuery($sql)->run();
    
    Database::Go()->rawQuery('UPDATE `' . Faq::mTable . '` SET `question_' . $flag_id . '`=`question_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Faq::mTable . '` SET `answer_' . $flag_id . '`=`answer_en`')->run();
    
    //mod_faq_categories
    $sql = '
    ALTER TABLE `' . Faq::cTable . "`
	  ADD COLUMN name_$flag_id VARCHAR (50) NOT NULL AFTER name_en
	";
    Database::Go()->rawQuery($sql)->run();
    
    Database::Go()->rawQuery('UPDATE `' . Faq::cTable . '` SET `name_' . $flag_id . '`=`name_en`')->run();