<?php
    /**
     * rss
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: rss.php, v1.00 6/11/2023 9:19 AM Gewa Exp $
     *
     */
    
    use Wojo\Container\Container;
    use Wojo\Core\Content;
    use Wojo\Database\Database;
    use Wojo\Language\Language;
    use Wojo\Url\Url;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    include_once 'init.php';
    $core = Container::instance()->get('core');
    $lg = Language::$lang;
    
    header('Content-Type: text/xml');
    header('Pragma: no-cache');
    $html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $html .= "<rss version=\"2.0\" xmlns:atom=\"https://www.w3.org/2005/Atom\">\n\n";
    $html .= "<channel>\n";
    $html .= '<title><![CDATA[' . $core->site_name . "]]></title>\n";
    $html .= '<link><![CDATA[' . SITEURL . "]]></link>\n";
    $html .= '<description><![CDATA[Latest 20 Rss Feeds - ' . $core->company . "]]></description>\n";
    $html .= '<generator>' . $core->company . "</generator>\n";
    
    $sql = "
    SELECT body$lg as body, title$lg as title, slug$lg as slug, DATE_FORMAT(created, '%a, %d %b %Y %T GMT') as created
      FROM `" . Content::pTable . '`
      WHERE active = ?
      AND page_type = ?
      ORDER BY created
      DESC LIMIT 20
    ';
    $data = Database::Go()->rawQuery($sql, array(1, 'normal'))->run();
    
    foreach ($data as $row) {
        $title = $row->title;
        
        $new_body = '';
        $body = $row->body;
        $string = preg_replace('/%%(.*?)%%/', '', $body ?? '');
        $new_body = Validator::sanitize($string, 'string', 350);
        
        $date = $row->created;
        $slug = $row->slug;
        $url = Url::url($core->pageslug, $slug);
        
        $html .= "<item>\n";
        $html .= "<title><![CDATA[$title]]></title>\n";
        $html .= "<link><![CDATA[$url]]></link>\n";
        $html .= "<guid isPermaLink=\"true\"><![CDATA[$url]]></guid>\n";
        $html .= "<description><![CDATA[$new_body]]></description>\n";
        $html .= "<pubDate><![CDATA[$date]]></pubDate>\n";
        $html .= "</item>\n";
    }
    unset($row);
    $html .= "</channel>\n";
    $html .= '</rss>';
    echo $html;