<?php

use Illuminate\Database\Capsule\Manager as DB;

/*
* @get
* v1/history_search?site=managercv
* site = managercv/request
*/

$site = $params['site'];

$user = $user->username;

$keyword_of_user = DB::table('history_search');

$keyword_of_user->where(function ($query) use ($user, $site) {
    $query->where('user_id', $user);

    $query->where('site', $site);

    $query->where('isdelete', 0);
})->orderBy('id', 'DESC');

if (!empty($keyword_of_user)) {
    $get_keyword = $keyword_of_user->take(7)->get();

    $count_keyword = $keyword_of_user->count();

    $results = [
        'status' => 'success',
        'data' => $get_keyword,
        'total' => $count_keyword,
        'time' => time()
    ];
}
