<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

class MY_Controller extends CI_Controller {

    protected $member;

    function __construct() {
        parent::__construct();
        $result = LoginService::check();
        $this->member = $this->getLoginUser($result);

        if ($result['loginState'] != Constants::S_AUTH || empty($this->member)) {
            show_error('login first', 403);
        }
    }

    function getLoginUser($result) {
        $openid = !empty($result['userinfo']) ? $result['userinfo']['openId'] : '';
        if ($openid) {
            $member = $this->users->findUserByOpenId($openid);
            $this->session->set_userdata($result);
            return $member;
        }
    }

    /*
      array(2) {
      ["loginState"]=>
      int(1)
      ["userinfo"]=>
      array(9) {
      ["openId"]=>
      string(28) "oC5kr5GYZhQsvHEhIibp6iBnppPc"
      ["nickName"]=>
      string(12) "彳£ 旅途"
      ["gender"]=>
      int(2)
      ["language"]=>
      string(5) "zh_CN"
      ["city"]=>
      string(0) ""
      ["province"]=>
      string(0) ""
      ["country"]=>
      string(5) "China"
      ["avatarUrl"]=>
      string(129) "https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqf3adyKb8l8VDJibIibhibkkvrTxqZPUdM5iaCZe2AqoibjysaYPewEkvibmVTdLz5JRRJaNCw6vvjnuLg/132"
      ["watermark"]=>
      array(2) {
      ["timestamp"]=>
      int(1539067737)
      ["appid"]=>
      string(18) "wxd43a377a651fde4b"
      }
      }
      }
     */
}
