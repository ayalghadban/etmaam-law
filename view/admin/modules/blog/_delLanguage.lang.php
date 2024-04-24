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
    use Wojo\Module\Blog\Blog;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }

    Database::Go()->rawQuery('
    ALTER TABLE `' . Blog::mTable . "`
      DROP COLUMN title_$abbr,
      DROP COLUMN slug_$abbr,
      DROP COLUMN tags_$abbr,
      DROP COLUMN body_$abbr,
      DROP COLUMN keywords_$abbr,
      DROP COLUMN description_$abbr
    ")->run();
    
    Database::Go()->rawQuery('
    ALTER TABLE `' . Blog::cTable . "`
      DROP COLUMN name_$abbr,
      DROP COLUMN slug_$abbr,
      DROP COLUMN keywords_$abbr,
      DROP COLUMN description_$abbr
    ")->run();
    
    Database::Go()->rawQuery('ALTER TABLE `' . Blog::tTable . "` DROP COLUMN tagname_$abbr")->run();