<?php

$conf=[
    'secretkey' => 'hr-tool-secretkey', //Mã bảo mật cho token
    'secretkeyrefresh' => 'secretkeyrefresh-hr-tool', //Mã bảo mật cho  refresh token
    'timeexpires' => 480, // Thời gian hết hạn token
    'timeexpiresrefresh' => 43200, // Thời gian hết hạn token
    'ignore'=>["v1/login",'v1/faker/1',"v1/createsg","v1/meta", "v1/mail","v1/request-auto", "v1/release_note","v1/test","v1/test_history", "v1/dashboard", "v1/users"],//Đường dẫn public
    'ignore_post'=>["/v1/login"],//Đường dẫn public
    'block'=>["logs"],//Đường dẫn bị khóa
    'folder_upload'=>'uploads',
    'link_file'=>'http://hr-api-dev.azcloud.com.vn',
    'dbhost'=>'192.168.1.32',
    'dbuser'=>'root',
    'dbpass'=>'123',
    'dbname'=>'hr-tool-dev',
    'debug'=> true,
    'queueserver'=> '192.168.1.52',
    'queueport'=> '5672',
    'queueuser'=> 'admin',
    'queuepass' => 'admin',
    'queuename'=> 'notification',
    'emailtest'=> ''
];

