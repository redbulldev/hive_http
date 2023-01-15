<?php

use Illuminate\Database\Capsule\Manager as DB;

$exception_feature = true;

function addKeyword($user, $keyword, $page){
    DB::table('history_search')->insert([
        'keyword' => $keyword,
        'user_id' => $user,
        'page' => $page,
    ]);
}

$keyword_of_user = DB::table('history_search')->where('keyword', $data->keyword)->where('user_id', $data->user)->where('page', $data->page)->where('isdelete', 0)->max('id');

// echo $keyword_of_user;
// die();

if(!empty($keyword_of_user)){
    $keyword_top = DB::table('history_search')->where('user_id', $data->user)->where('page', $data->page)->max('id');

    // echo $keyword_top;
    // print_r($keyword_top);
    // die();
    if($keyword_top != $keyword_of_user){
        DB::table('history_search')->where('id', $keyword_of_user)->delete();

        return addKeyword($data->user, $data->keyword, $data->page);
    } 
} else {
    return addKeyword($data->user, $data->keyword, $data->page);
}


// 1. get lịch sử tìm kiếm của user đang đăng nhập 
// 2. nếu keyword tìm kiếm không tồn tại thì insert vào csdl, tồn tại xóa keyword tìm kiếm cũ vào insert từ khóa tìm kiếm mới vào
// (mục đích là đẩy từ khóa tìm kiếm lên đầu) nếu keyword tìm kiếm đó trùng với keyword gần nhất trong csdl thì không insert 


// - khi click vào ô input tìm kiếm thì call api get từ khóa tìm kiếm 
// - khi click vào button tìm kiếm thì call api post  từ khóa tìm kiếm 
// check page của request 
















