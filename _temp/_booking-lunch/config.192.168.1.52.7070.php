<?php

$conf=[
    'secretKeyCloak'=> "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtkrN4ZghMWF6IjJN8zv0Q2rlTRo26u4jTgVjSSlEDLDciWVo17Y7lYEPlri9SHgAInTgPUUXP3IOVu/VCtlut+1AXcVNrM/s+YU7jf1a8zkoC+INd9Aw88O4zR7CLyJQ2U1n5SLW9B2AQUFckZ3G0iAI9Mf+PwA70cxLh/eE8B/vqbVH2hainkbxrueNk3GyL9iQH6A9zL3efnkq4sWWa3YLF/d+VLNsZ76p5Cbui6AnX12ktM8XdxNDicG/VoXYte6zeCnSjSdZTz2By24KW6BU3ZrMrZMRl4eLrJydcuZE9pff6eKs4iFcWSZ7ec9pg81xi3k6pVkjIy2cBMgmjwIDAQAB",
    'secretkey' => 'booking-tool-secretkey', //Mã bảo mật cho token
    'secretkeyrefresh' => 'secretkeyrefresh-booking-tool', //Mã bảo mật cho  refresh token
    'timeexpires' => 480, // Thời gian hết hạn token
    'timeexpiresrefresh' => 43200, // Thời gian hết hạn token
    'ignore'=>["/v1/login",'/v1/faker/1',"/v1/createsg/1", "/v1/cron/1", '/v1/list-menu','/v1/report-today',"v1/release_note", "/v1/export"],//Đường dẫn public
    'ignore_post'=>["/v1/login"],//Đường dẫn public pt post
    'block'=>["logs"],//Đường dẫn bị khóa
    'folder_upload'=>'uploads',
    'link_file' => 'https://api-lunch.ossigroup.net',
    'dbhost' => '192.168.1.52',
    'dbuser'=>'root',
    'dbpass'=>'Abc123@#',
    'dbname'=> 'booking_lunch',
    'debug'=> false
];
