<?php

class NoteGroup extends CI_Model {

    protected $table = 'note_group';
    protected $orderby = [];
    protected $limit = 8;

    function __construct() {
        parent::__construct();
    }

    public function storeData($user_id, $group_name) {
        if (!empty($user_id) && !empty($group_name)) {
            $create_time = time();
            $group_status = 1;
            $this->db->insert($this->table, compact('user_id', 'group_name', 'create_time', 'group_status'));
            return $this->db->insert_id();
        }
        return-1;
    }

    public function updateData($group_id, $user_id, $group_name) {

        if (!empty($group_id) && !empty($user_id) && !empty($group_name)) {

            return $this->db->update(
                            $this->table, compact('group_name'), compact('group_id', 'user_id')
            );
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

    public function findById($group_id) {
        $where = compact('note_id');
        return $this->db->where($where)->get($this->table)->row();
    }

    public function getList($where, $field = '*', $order = '', $page = false) {

        $model = $this->db;
        if ($where) {
            $model->where($where);
        }
        if ($field) {
            $model->select($field);
        }
        if ($order) {
            $model->order_by($order);
        }
        if ($page !== false) {
            $model->limit($this->limit, ($page - 1) * $this->limit);
        }
        $count = $model->count_all($this->table);
        $result = $model->get($this->table)->result();
        return [$count, $result];
    }

}
