<?php

use Illuminate\Database\Capsule\Manager as DB;

// $name='history_search';


$name_user = 'thaontp';
$page = 'request';
// $page = 'manager';

// $user = DB::table('users')->where('username', $name)->where('page', $page)->orderBy('id')->get();

$keyword_of_user = DB::table('history_search')->where('user_id', $name_user)->where('page', $page)->where('isdelete', 0)->orderBy('id')->get();

if(!empty($keyword_of_user)){
    $results = ['status' => 'success', 'data' => $keyword_of_user, 'time' => time()];
} 



// 1. get lịch sử tìm kiếm của user đang đăng nhập 
// 2. nếu keyword tìm kiếm không tồn tại thì insert vào csdl, tồn tại xóa keyword tìm kiếm cũ vào insert từ khóa tìm kiếm mới vào
// (mục đích là đẩy từ khóa tìm kiếm lên đầu) nếu keyword tìm kiếm đó trùng với keyword gần nhất trong csdl thì không insert 


// - khi click vào ô input tìm kiếm thì call api get từ khóa tìm kiếm 
// - khi click vào button tìm kiếm thì call api post  từ khóa tìm kiếm 
// check page của request 
















