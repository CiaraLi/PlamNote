<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('debug')) {

    /**
     * 调试专用
     */
    function debug() {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $messages = array();

        for ($i = 0; $i < $numargs; $i += 1) {
            $message = $arg_list[$i];

            if (is_array($message)) {
                if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                    $message = json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                } else {
                    $message = json_encode($message);
                }
            }

            if (is_string($message) || is_numeric($message)) {
                $messages[] = $message;
            }
        }

        log_message('debug', implode(' ', $messages) . "\n");
    }

}

if (!function_exists('checkAuth')) {

    /**
     * 调试专用
     */
    function checkAuth() {
        $CI = & get_instance();
        $loginState = $CI->session->userdata('loginState');
        if ($loginState === QCloud_WeApp_SDK\Constants::S_AUTH) {
            $userinfo = $CI->session->userdata('loginUser'); 
            return $userinfo;
        } else {
            return false;
        }
    }

}

if (!function_exists('input')) {

    /**
     * 调试专用
     */
    function input($inputs) {
        $CI = & get_instance();
        return $CI->input->post_get($inputs, TRUE);
    }

}
