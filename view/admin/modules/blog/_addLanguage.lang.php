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
    use Wojo\Module\Blog\Blog;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    //mod_blog
    $sql = '
    ALTER TABLE `' . Blog::mTable . "`
      ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
      ADD COLUMN slug_$flag_id VARCHAR (100) NOT NULL AFTER slug_en,
      ADD COLUMN tags_$flag_id VARCHAR (150) DEFAULT NULL AFTER tags_en,
      ADD COLUMN body_$flag_id TEXT AFTER body_en,
      ADD COLUMN keywords_$flag_id VARCHAR(200) DEFAULT NULL AFTER keywords_en,
      ADD COLUMN description_$flag_id TEXT AFTER description_en
    ";
    Database::Go()->rawQuery($sql)->run();
    
    Database::Go()->rawQuery('UPDATE `' . Blog::mTable . '` SET `title_' . $flag_id . '`=`title_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::mTable . '` SET `slug_' . $flag_id . '`=`slug_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::mTable . '` SET `tags_' . $flag_id . '`=`tags_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::mTable . '` SET `body_' . $flag_id . '`=`body_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::mTable . '` SET `keywords_' . $flag_id . '`=`keywords_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::mTable . '` SET `description_' . $flag_id . '`=`description_en`')->run();
    
    //mod_blog_categories
    $sql = '
    ALTER TABLE `' . Blog::cTable . "`
      ADD COLUMN name_$flag_id VARCHAR (100) NOT NULL AFTER name_en,
      ADD COLUMN slug_$flag_id VARCHAR (100) NOT NULL AFTER slug_en,
      ADD COLUMN keywords_$flag_id VARCHAR(200) DEFAULT NULL AFTER keywords_en,
      ADD COLUMN description_$flag_id TEXT AFTER description_en
    ";
    Database::Go()->rawQuery($sql)->run();
    
    Database::Go()->rawQuery('UPDATE `' . Blog::cTable . '` SET `name_' . $flag_id . '`=`name_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::cTable . '` SET `slug_' . $flag_id . '`=`slug_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::cTable . '` SET `keywords_' . $flag_id . '`=`keywords_en`')->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::cTable . '` SET `description_' . $flag_id . '`=`description_en`')->run();
    
    //mod_blog_tags
    $sql = '
    ALTER TABLE `' . Blog::tTable . "`
      ADD COLUMN tagname_$flag_id VARCHAR (60) NOT NULL AFTER tagname_en
    ";
    Database::Go()->rawQuery($sql)->run();
    Database::Go()->rawQuery('UPDATE `' . Blog::tTable . '` SET `tagname_' . $flag_id . '`=`tagname_en`')->run();