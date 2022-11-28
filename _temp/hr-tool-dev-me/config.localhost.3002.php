<?php

$conf=[
    'secretkey'=> 'hr-tool-secretkey', //Mã bảo mật cho token
    'secretkeyrefresh'=> 'secretkeyrefresh-hr-tool', //Mã bảo mật cho  refresh token
    'timeexpires' => 480, // Thời gian hết hạn token
    'timeexpiresrefresh' => 43200, // Thời gian hết hạn token
    'ignore'=>["v1/login",'v1/faker/1',"v1/createsg/1","v1/meta","v1/request-auto","v1/mail", "v1/release_note","v1/test","v1/test_history", "v1/dashboard", "v1/dashboard_cv", "v1/type_work", "v1/position_point", "v1/positions", "v1/import"],//Đường dẫn public
    'ignore_post'=>["/v1/login"],//Đường dẫn public
    'block'=>["logs"],//Đường dẫn bị khóa
    'folder_upload'=>'uploads',
    'link_file'=>'http://hr-api-dev.azcloud.com.vn',
    'dbhost'=>'localhost',
    'dbuser'=>'root',
    'dbpass'=>'',
    'dbname'=>'hr-tool-dev',
    // 'dbhost'=>'localhost:4306',
    // 'dbuser'=>'root',
    // 'dbpass'=>'root',
    // 'dbname'=>'mysql_hr_tool',
    'debug'=> true,
    'queueserver' => '192.168.1.52',
    'queueport' => '5672',
    'queueuser' => 'admin',
    'queuepass' => 'admin',
    'queuename' => 'notification',
    'emailtest' => ''
];