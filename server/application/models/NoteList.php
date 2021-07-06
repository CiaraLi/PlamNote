<?php

class NoteList extends CI_Model {

    protected $table = 'note_list';
    protected $orderby = [];
    protected $limit = 8;
    protected $type = [1 => '文本', 2 => '清单', 3 => '网址'];
    protected $color = [1 => 'white', 2 => 'yellow', 3 => 'red', 4 => 'green', 5 => 'blue', 6 => 'black'];

    function __construct() {
        parent::__construct();
    }

    public function storeData($user_id, $note_id, $input) {

        if (!empty($user_id) && !empty($note_id)) {
            $create_time = time();
            $update_time = time();
            $list_status = 1;
            $data = compact('user_id', 'note_id', 'create_time', 'update_time', 'list_status');
            $data['list_content'] = empty($input['list_content']) ? "no title" : $input['list_content'];
            $data['list_checked'] = empty($input['list_checked']) ? 0 : intval($input['list_checked']);
            $data['list_color'] = empty($input['list_color']) ? 0 : intval($input['list_color']);
            $data['list_type'] = empty($input['list_type']) ? 0 : intval($input['list_type']);
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return-1;
    }

    public function updateData($list_id, $user_id, $note_id, $input) {

        if (!empty($list_id) && !empty($user_id) && !empty($note_id)) {
            $data['update_time'] = time();
            $data['note_id'] = intval($note_id);
            $data['list_content'] = empty($input['list_content']) ? "" : $input['list_content'];
            $data['list_checked'] = empty($input['list_checked']) ? 0 : intval($input['list_checked']);
            $data['list_color'] = empty($input['list_color']) ? 0 : intval($input['list_color']);
            $data['list_type'] = empty($input['list_type']) ? 0 : intval($input['list_type']);
            empty($input['is_del']) ? null : $data['list_status '] = -1;
            return $this->db->update($data, compact('list_id', 'user_id', 'note_id'));
        }
        return-1;
    }

    public function deleteData($user_id, $where) {
        if (!empty($user_id) && !empty($where)) {
            $where['user_id'] = intval($user_id);     
            return $this->db->where($where)->delete($this->table);
        }
        return-1;
    }

    public function findById($user_id, $note_id, $list_id) { 
        $where=compact('note_id', 'user_id', 'list_id');
        return $this->db->where($where)->get($this->table)->row();
    }

    public function getList($where, $field = '*', $order = '', $page = false) {

        if ($field) {
            $this->db->select($field);
        }
        if ($order) {
            $this->db->order_by($order);
        }
        if ($page !== false) {
            $this->db->limit($this->limit, ($page - 1) * $this->limit);
        }

        if ($where) {
            $count = $this->db->where($where)->count_all_results($this->table);
            $this->db->where($where);
        } else {
            $count = $this->db->count_all($this->table);
        }
        $result = $this->db->get($this->table)->result();
        return [$count, $result];
    }

}
