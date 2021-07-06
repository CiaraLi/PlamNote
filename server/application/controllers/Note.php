<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

class Note extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('notes');
        $this->load->model('noteGroup');
        $this->load->model('noteList');
    }

    /**
     * 获取手记列表
     */
    public function index() {
        $group_id = input('group_id');
        $user_id = $this->member->user_id;

        list($count, $data) = $this->notes->getList(compact('group_id', 'user_id'));
        $this->json([
            'code' => 1,
            'count' => $count,
            'data' => $data
        ]);
    }

    /**
     * 获取手记列表
     */
    public function detail() {
        $group_id = input('group_id');
        $note_id = input('note_id');
        $user_id = $this->member->user_id;

        $data = $this->notes->findById($user_id, $note_id);
        if ($data) {
            list($count, $list) = $this->noteList->getList(compact('note_id', 'user_id'));
            $data->listcount = $count;
            $data->list = $list;
            $this->json([
                'code' => 1,
                'data' => $data
            ]);
        } else {
            $this->json([
                'code' => -1,
                'data' => $data
            ]);
        }
    }

    /**
     * 获取手记内容清单
     */
    public function lists() {
        $note_id = input('note_id');
        $user_id = $this->member->user_id;

        list($count, $data) = $this->noteList->getList(compact('note_id', 'user_id'));
        $this->json([
            'code' => 1,
            'count' => $count,
            'data' => $data
        ]);
    }

    /**
     * 获取分组
     */
    public function group() {
        $user_id = $this->member->user_id;
        list($count, $data) = $this->noteGroup->getList(compact('user_id'));

        foreach ($data as $key => $value) {
            $group_id = $value->group_id;
            list($count1, $data1) = $this->notes->getList(compact('group_id', 'user_id'));
            $value->notecount = $count1;
        }
        $this->json([
            'code' => 1,
            'count' => $count,
            'data' => $data
        ]);
    }

    /**
     * 
     */
    function saveGroup() {
        $group_id = input('group_id');
        $group_name = input('group_name');
        $user_id = $this->member->user_id;
        if (empty($group_id)) {
            $status = $this->noteGroup->storeData($user_id, $group_name);
        } else {
            $status = $this->noteGroup->updateData($group_id, $user_id, $group_name);
        }
        $this->json([
            'code' => $status,
            'data' => []
        ]);
    }

    function saveNote() {
        $group_id = input('group_id');
        $note_id = input('note_id');
        $note_list = input('note_list') ? json_decode(input('note_list'), TRUE) : [];
        $user_id = $this->member->user_id;

        $this->db->trans_begin();
        try {
            if (empty($note_id)) {
                $status = $this->notes->storeData($user_id, $group_id, input(NULL));
                $note_id = $status;
            } else {
                $status = $this->notes->updateData($note_id, $user_id, $group_id, input(NULL));
            }
            if ($status) {
                foreach ($note_list as $key => $value) {
                    if (empty($value['list_id'])) {
                        $ref = $this->noteList->storeData($user_id, $note_id, $value);
                    } else {
                        $ref = $this->noteList->updateData($value['list_id'], $user_id, $note_id, $value);
                    }
                }
                $this->db->trans_commit();
            } else {
                $status = -1;
                $this->db->trans_rollback();
            }
        } catch (Exception $e) {
            $status = -1;
            $this->db->trans_rollback();
        }
        $this->json([
            'code' => $status,
            'data' => []
        ]);
    }

    function saveList() {
        $list_id = input('list_id');
        $note_id = input('note_id');
        $user_id = $this->member->user_id;
        if (empty($list_id)) {
            $status = $this->noteList->storeData($user_id, $note_id, input(NULL));
        } else {
            $status = $this->noteList->updateData($list_id, $user_id, $note_id, input(NULL));
        }
        $this->json([
            'code' => $status,
            'data' => []
        ]);
    }

    function deleteGroup() {
        $group_id = input('group_id');
        $user_id = $this->member->user_id;
        $list = $this->noteGroup->findById($user_id, $group_id);
        list($count, $data) = $this->notes->getList(compact('group_id', 'user_id'));
        if ($count == 0) {
            $status = $this->noteGroup->deleteData($user_id, compact('group_id'));
            $this->db->trans_commit();
        } else {
            $status = -2;
        }
        $this->json([
            'code' => $status,
            'data' => []
        ]);
    }

    function deleteNote() {
        $note_id = input('note_id');
        $user_id = $this->member->user_id;
        $list = $this->notes->findById($user_id, $note_id);
        if (!empty($list)) {
            $this->db->trans_begin();
            try {
                $status = $this->notes->deleteData($user_id, compact('note_id'));
                $status = $this->noteList->deleteData($user_id, compact('note_id'));
                $this->db->trans_commit();
            } catch (Exception $e) {
                $status = -1;
                $this->db->trans_rollback();
            }
        }
        $this->json([
            'code' => $status,
            'data' => []
        ]);
    }

    function deleteList() {
        $list_id = input('list_id');
        $note_id = input('note_id');
        $user_id = $this->member->user_id;
        $list = $this->noteList->findById($user_id, $note_id, $list_id);
        if (!empty($list)) {
            $status = $this->noteList->deleteData($user_id, compact('list_id', 'note_id'));
        }
        $this->json([
            'code' => $status,
            'data' => []
        ]);
    }

}
