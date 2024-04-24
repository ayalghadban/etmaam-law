<?php
    /**
     * DatabaseTools Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: DatabaseTools.php, v1.00 4/30/2023 10:14 AM Gewa Exp $
     *
     */
    
    namespace Wojo\Database;
    
    use PDO;
    use Wojo\Container\Container;
    use Wojo\Debug\Debug;
    use Wojo\Language\Language;
    use Wojo\Message\Message;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class DatabaseTools
    {
        const suffix = 'd-M-Y_H-i-s';
        const nl = "\r\n";
        private static array $tables = array();
        
        /**
         * fetch
         *
         * @return false|string
         */
        public static function fetch(): false|string
        {
            
            $dump = '-- --------------------------------------------------------------------------------' . self::nl;
            $dump .= '-- ' . self::nl;
            $dump .= '-- @version: ' . DB_DATABASE . '.sql ' . date('M j, Y') . ' ' . date('H:i') . ' gewa' . self::nl;
            $dump .= '-- @package ' . Container::instance()->get('core')->wojon . ' v.' . Container::instance()->get('core')->wojov . self::nl;
            $dump .= '-- @author wojoscripts.com.' . self::nl;
            $dump .= '-- @copyright ' . date('Y') . self::nl;
            $dump .= '-- ' . self::nl;
            $dump .= '-- --------------------------------------------------------------------------------' . self::nl;
            $dump .= '-- Host: ' . DB_SERVER . self::nl;
            $dump .= '-- Database: ' . DB_DATABASE . self::nl;
            $dump .= '-- Time: ' . date('M j, Y') . '-' . date('H:i') . self::nl;
            $dump .= '-- MySQL version: ' . Database::Go()->getAttribute(PDO::ATTR_SERVER_VERSION) . self::nl;
            $dump .= '-- PHP version: ' . phpversion() . self::nl;
            $dump .= '-- --------------------------------------------------------------------------------' . self::nl . self::nl;
            
            $dump .= '#' . self::nl;
            $dump .= '# Database: `' . DB_DATABASE . '`' . self::nl;
            $dump .= '#' . self::nl . self::nl . self::nl;
            
            if (!($tables = self::getTables())) {
                return false;
            }
            foreach ($tables as $table) {
                if (!($table_dump = self::dumpTable($table))) {
                    Debug::addMessage('errors', '<i>Exception</i>', 'mySQL Error', 'session');
                    return false;
                }
                $dump .= $table_dump;
            }
            return $dump;
        }
        
        /**
         * getTables
         *
         * @return false|array
         */
        private static function getTables(): false|array
        {
            $value = array();
            
            $_oSTH = Database::Go()->prepare('SHOW TABLES');
            $_oSTH->execute();
            $aResults = $_oSTH->fetchAll(PDO::FETCH_NUM);
            
            if (!$aResults) {
                return false;
            }
            foreach ($aResults as $row) {
                if (count(self::$tables) === 0 or in_array($row[0], self::$tables)) {
                    $value[] = $row[0];
                }
            }
            if (!sizeof($value)) {
                Debug::addMessage('errors', '<i>Exception</i>', 'No tables found in database', 'session');
                return false;
            }
            return $value;
        }
        
        /**
         * dumpTable
         *
         * @param string $table
         * @return false|string
         */
        private static function dumpTable(string $table): false|string
        {
            
            $output = '-- --------------------------------------------------' . self::nl;
            $output .= '# -- Table structure for table `' . $table . '`' . self::nl;
            $output .= '-- --------------------------------------------------' . self::nl;
            $output .= 'DROP TABLE IF EXISTS `' . $table . '`;' . self::nl;
            
            $_oSTH = Database::Go()->prepare('SHOW CREATE TABLE ' . $table);
            if (!$_oSTH->execute()) {
                return false;
            }
            $row = $_oSTH->fetch();
            $output .= str_replace("\n", self::nl, $row['Create Table']) . ';';
            $output .= self::nl . self::nl;
            $output .= '-- --------------------------------------------------' . self::nl;
            $output .= '# Dumping data for table `' . $table . '`' . self::nl;
            $output .= '-- --------------------------------------------------' . self::nl . self::nl;
            $output .= self::insert($table);
            $output .= self::nl . self::nl;
            
            return $output;
        }
        
        /**
         * insert
         *
         * @param string $table
         * @return false|string
         */
        public static function insert(string $table): false|string
        {
            
            $output = '';
            if (!$query = Database::Go()->select($table)->run()) {
                return false;
            }
            foreach ($query as $result) {
                $fields = '';
                
                $array = get_object_vars($result);
                foreach (array_keys($array) as $value) {
                    $fields .= '`' . $value . '`, ';
                }
                $values = '';
                
                foreach ($array as $value) {
                    $value = $value ?? '';
                    $value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
                    $value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
                    $value = str_replace('\\', '\\\\', $value);
                    $value = str_replace('\'', '\\\'', $value);
                    $value = str_replace('\\\n', '\n', $value);
                    $value = str_replace('\\\r', '\r', $value);
                    $value = str_replace('\\\t', '\t', $value);
                    
                    $values .= '\'' . $value . '\', ';
                }
                
                $output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
            }
            return $output;
        }
        
        /**
         * save
         *
         * @param string $fname
         * @param string $sql
         * @param bool $gzip
         * @return void
         */
        public static function save(string $fname, string $sql, bool $gzip): void
        {
            if ($gzip) {
                if (!($zf = gzopen($fname, 'w9'))) {
                    Debug::addMessage('errors', '<i>Exception</i>', 'Can not write to ' . $fname, 'session');
                    return;
                }
                gzwrite($zf, $sql);
                gzclose($zf);
            } else {
                if (!($f = fopen($fname, 'w'))) {
                    Debug::addMessage('errors', '<i>Exception</i>', 'Can not write to ' . $fname, 'session');
                    return;
                }
                fwrite($f, $sql);
                fclose($f);
            }
        }
        
        /**
         * doRestore
         *
         * @param string $name
         * @return void
         */
        public static function doRestore(string $name): void
        {
            $filename = UPLOADS . 'backups/' . trim($name);
            $tempLine = '';
            $lines = file($filename);
            foreach ($lines as $line) {
                if (str_starts_with($line, '--') || str_starts_with($line, '#') || $line == '') {
                    continue;
                }
                
                $tempLine .= $line;
                if (str_ends_with(trim($line), ';')) {
                    if (!Database::Go()->rawQuery($tempLine)->run()) {
                        Debug::addMessage('errors', '<i>Exception</i>', 'during the following query ' . $tempLine, 'session');
                    }
                    $tempLine = '';
                }
            }
            
            Message::msgModalReply(true, 'success', str_replace('[NAME]', $_POST['title'], Language::$word->DBM_RES_OK), '');
        }
        
        /**
         * optimize
         *
         * @return string
         */
        public static function optimize(): string
        {
            $html = '<table class="wojo basic responsive table">';
            $html .= '<thead><tr>';
            $html .= '<th colspan="2">' . Language::$word->SYS_DBREPAIRING . '... </th>';
            $html .= '<th colspan="2">' . Language::$word->SYS_DBOPTIMIZING . '... </th>';
            $html .= '</tr></thead><tbody>';
            
            $sql = 'SHOW TABLES FROM ' . DB_DATABASE;
            $_oSTH = Database::Go()->prepare($sql);
            $_oSTH->execute();
            $result = $_oSTH->fetchAll(PDO::FETCH_COLUMN);
            $tables = '`' . implode('`, `', $result) . '`';
            Database::Go()->rawQuery('REPAIR TABLE ' . $tables)->run();
            Database::Go()->rawQuery('OPTIMIZE TABLE ' . $tables)->run();
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row . '</td>';
                $html .= '<td>';
                $html .= '<span class="icon-text right">' . Language::$word->SYS_DBSTATUS . ' <i class="positive icon check"></i></span>';
                $html .= '</td>';
                $html .= '<td>' . $row . '</td>';
                $html .= '<td>';
                $html .= '<span class="icon-text right">' . Language::$word->SYS_DBSTATUS . ' <i class="positive icon check"></i></span>';
                $html .= '</td></tr>';
            }
            $html .= '</tbody></table>';
            
            return $html;
        }
    }