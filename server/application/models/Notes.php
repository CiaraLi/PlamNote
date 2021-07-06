<?php

class Notes extends CI_Model {

    protected $table = 'notes';
    protected $orderby = [];
    protected $limit = 8;

    function __construct() {
        parent::__construct();
    }

    public function storeData($user_id, $group_id, $input) {

        if (!empty($user_id) && !empty($group_id)) {
            $create_time = time();
            $update_time = time();
            $note_status = 1;
            $data = compact('user_id', 'group_id', 'create_time', 'update_time', 'note_status');
            $data['note_title'] = empty($input['note_title']) ? "no title" : $input['note_title'];
            $data['note_lable'] = empty($input['note_lable']) ? "" : $input['note_lable'];
            $data['note_checkbox'] = empty($input['note_checkbox']) ? 0 : intval($input['note_checkbox']);
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return-1;
    }

    public function updateData($note_id, $user_id, $input) {

        if (!empty($note_id) && !empty($user_id)) {
            $data['update_time'] = time();
            $data['note_title'] = empty($input['note_title']) ? "no title" : $input['note_title'];
            $data['note_lable'] = empty($input['note_lable']) ? "" : $input['note_lable'];
            $data['group_id'] = empty($input['group_id']) ? 0 : intval($input['group_id']);
            $data['note_checkbox'] = empty($input['note_checkbox']) ? 0 : intval($input['note_checkbox']);
            return $this->db->update($data, compact('note_id', 'user_id'));
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

    public function findById($user_id, $note_id) {
        $where = compact('user_id', 'note_id');
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
