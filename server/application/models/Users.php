<?php

class Users extends CI_Model {

    protected $table = 'users';

    function __construct() {
        parent::__construct();
    }

    public function storeUserInfo($userinfo) {
        $create_time = time();
        $open_id = $userinfo->openId;
        $nick_name = $userinfo->nickName;
        $user_head = $userinfo->avatarUrl;
        $country = $userinfo->country;
        $city = $userinfo->city;
        $gender = $userinfo->gender;

        $res = $this->db->get($this->table, compact('open_id'))->row();
        if ($res === NULL) {
            $this->db->insert($this->table, compact('open_id', 'nick_name', 'user_head', 'create_time', 'country', 'city', 'gender'));
        } else {
            $this->db->update(
                    $this->table, compact('nick_name', 'user_head', 'create_time', 'country', 'city', 'gender'), compact('open_id')
            );
        }
    }

    public function findUserById($user_id) {
        return $this->db->get($this->table, compact('user_id'))->row();
    }

    public function findUserByOpenId($open_id) {
        return $this->db->get($this->table, compact('open_id'))->row();
    }

}
