<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;


class Login extends CI_Controller {

    private $model;

    function __construct() {
        parent::__construct(); 
    }

    public function index() {
        $result = LoginService::login();

        if ($result['loginState'] === Constants::S_AUTH) {
            $this->users->storeUserInfo($result['userinfo']['userinfo']);
            $result['loginUser']=$this->users->findUserByOpenId($result['userinfo']['userinfo']->openId);
            $this->session->set_userdata($result);
            
            $this->json([
                'code' => 0,
                'data' => $result['userinfo']
            ]);
        } else {
            $this->json([
                'code' => -1,
                'error' => $result['error']
            ]);
        }
    }

}
