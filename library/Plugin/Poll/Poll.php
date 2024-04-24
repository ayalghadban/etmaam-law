<?php
    /**
     * Poll Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 6.20: Poll.php, v1.00 5/14/2023 8:35 PM Gewa Exp $
     *
     */
    
    namespace Wojo\Plugin\Poll;
    
    use Wojo\Core\Session;
    use Wojo\Database\Database;
    use Wojo\Validator\Validator;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Poll
    {
        const oTable = 'plug_poll_options';
        const qTable = 'plug_poll_questions';
        const vTable = 'plug_poll_votes';
        
        /**
         * getAllPolls
         *
         * @return object
         */
        public static function getAllPolls(): object
        {
            $sql = '
            SELECT q.question, q.id as id, o.value, IFNULL(COUNT(v.option_id), 0) as total
              FROM `' . self::oTable . '` as o
              LEFT JOIN `' . self::vTable . '` as v ON o.id = v.option_id
              JOIN `' . self::qTable . '` as q ON o.question_id = q.id
              GROUP BY o.id, o.position
              ORDER BY o.position';
            
            $query = Database::Go()->rawQuery($sql)->run('array');
            
            $data = array();
            if ($query) {
                $pid = null;
                foreach ($query as $i => $row) {
                    if ($pid != $row['id']) {
                        $pid = $row['id'];
                        $data[$row['id']]['name'] = $row['question'];
                        $data[$row['id']]['id'] = $row['id'];
                    }
                    $data[$row['id']]['totals'] = isset($data[$row['id']]['totals']) ? $data[$row['id']]['totals'] += $row['total'] : $row['total'];
                    $data[$row['id']]['opts'][$i]['value'] = $row['value'];
                    $data[$row['id']]['opts'][$i]['total'] = $row['total'];
                }
            }
            return json_decode(json_encode($data));
        }
        
        /**
         * getPollOptions
         *
         * @param int $id
         * @return mixed
         */
        public static function getPollOptions(int $id): mixed
        {
            $row = Database::Go()->select(self::oTable)->where('question_id', $id, '=')->orderBy('position', 'ASC')->run();
            return ($row) ?: 0;
        }
        
        /**
         * render
         *
         * @param int $id
         * @return int|mixed
         */
        public static function render(int $id): mixed
        {
            $sql = '
            SELECT q.question, q.id as id, o.value, o.id as oid, IFNULL(COUNT(v.option_id), 0) as total
              FROM `' . self::oTable . '` as o
              LEFT JOIN `' . self::vTable . '` as v ON o.id = v.option_id
              JOIN `' . self::qTable . '` as q ON o.question_id = q.id
              WHERE o.question_id = ?
              GROUP BY o.id, o.position
              ORDER BY o.position
            ';
            
            $query = Database::Go()->rawQuery($sql, array($id))->run();
            
            $data = array();
            if ($query) {
                $pid = null;
                foreach ($query as $i => $row) {
                    if ($pid != $row->id) {
                        $pid = $row->id;
                        $data[$row->id]['name'] = $row->question;
                        $data[$row->id]['id'] = $row->id;
                    }
                    $data[$row->id]['totals'] = isset($data[$row->id]['totals']) ? $data[$row->id]['totals'] += $row->total : $row->total;
                    $data[$row->id]['opts'][$i]['value'] = $row->value;
                    $data[$row->id]['opts'][$i]['total'] = $row->total;
                    $data[$row->id]['opts'][$i]['oid'] = $row->oid;
                }
            }
            
            $data = json_decode(json_encode($data));
            return ($data) ?: 0;
            
        }
        
        /**
         * updatePollResult
         *
         * @param int $id
         * @return bool
         */
        public static function updatePollResult(int $id): bool
        {
            if ($row = Database::Go()->select(self::oTable, array('id', 'question_id'))->where('id', $id, '=')->first()->run()) {
                $data['option_id'] = $row->id;
                $data['ip'] = Validator::sanitize($_SERVER['REMOTE_ADDR']);
                
                Database::Go()->insert(self::vTable, $data)->run();
                if (Database::Go()->affected()) {
                    Session::set('CMSPRO_voted', $row->question_id, true);
                    return true;
                }
                return false;
            }
            return false;
        }
    }