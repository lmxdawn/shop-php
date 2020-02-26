<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

//=======【基本信息设置】=====================================

return [

    // +----------------------------------------------------------------------
    // | 小程序配置
    // +----------------------------------------------------------------------
    // 小程序
    'miniapp' => [
        'appid' => 'wxd236764fdee7583c',
        'mch_id' => '',
        'key' => '',
        'appsecret' => '3fde0d48b25fe314e76021b8ffffdc89',
        //'cert_client' => Env::get('root_path') . 'cert/1530105491/apiclient_cert.pem',
        //'cert_key' => Env::get('root_path') . 'cert/1530105491/apiclient_key.pem',
        'curl_proxy_host' => '0.0.0.0',
        'curl_proxy_port' => '0',
        'report_levenl' => 1,
        'notify_url' => '',
        // 模板消息
        'template_msg' => [
        ],
    ],
    // 公众号
    'mp' => [
        'app_id' => 'wx60651d2816c91e7e', // 公众号 APPID
        'mch_id' => '1530105491',
        'key' => 'Y0B9ou8m5pgQ6JrKjpa73Nz98rUtfzga',
        'appsecret' => 'cc5071acd385ffe5f27a31407f51c6d3',
        'cert_client' => Env::get('root_path') . 'cert/1530105491/apiclient_cert.pem',
        'cert_key' => Env::get('root_path') . 'cert/1530105491/apiclient_key.pem',
        'curl_proxy_host' => '0.0.0.0',
        'curl_proxy_port' => '0',
        'report_levenl' => 1,
        'notify_url' => '',
        // 模板消息
        'template_msg' => [
            'study_refund' => '2nKplNxouiy7Q2Rw916Vc8OVVvOF62P7YvvCiOALcm0'
        ],
    ],
    // h5
    'wap' => [
        'appid' => 'wx2fb5fda68f385aa1',
        'mch_id' => '1530105491',
        'key' => '',
        'appsecret' => '594f27f309a0e524678bc5e6f54dab47',
        //'cert_client' => Env::get('root_path') . 'cert/1530105491/apiclient_cert.pem',
        //'cert_key' => Env::get('root_path') . 'cert/1530105491/apiclient_key.pem',
        'curl_proxy_host' => '0.0.0.0',
        'curl_proxy_port' => '0',
        'report_levenl' => 1,
        'notify_url' => '',
        // 模板消息
        'template_msg' => [
        ],
    ],
];
