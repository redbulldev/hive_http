<?php

use Illuminate\Database\Capsule\Manager as DB;

/*
* @put
* /v1/cv_favorite/{id}?favorite=0
* ?favorite=0/1
*/

function checkCv($id)
{
    $count = DB::table('cv')->where('id', $id)->where('isdelete', 0)->count();

    if ($count > 0) {
        return true;
    }

    return false;
}

function checkCvFavorite($id)
{
    $count = DB::table('cv_favorite')->where('cv_id', $id)->count();

    if ($count > 0) {
        return true;
    }

    return false;
}

function favoriteIsTrue($id)
{
    $statusFavorite = checkCvFavorite($id);

    if ($statusFavorite) {
        return favoriteExist($id);
    } else {
        return favoriteNotExist($id);
    }
}

function favoriteExist($id)
{
    return DB::table('cv_favorite')->where('cv_id', $id)->update([
        'status' => 1
    ]);
}

function favoriteNotExist($id)
{
    return DB::table('cv_favorite')->insertGetId([
        'cv_id' => $id,
        'datecreate' => time()
    ]);
}

function favoriteIsFalse($id)
{
    $statusFavorite = checkCvFavorite($id);

    if ($statusFavorite) {
        return DB::table('cv_favorite')->where('cv_id', $id)->update([
            'status' => 0
        ]);
    }
}

/*
function saveHistoryOfFavorite($login_id, $action, $name, $id){
    if(!empty($loginid)){
        return historySave($login_id, $action, $name, $id); //$idlog 
    } else{
        return false;  // $results 
    }
}

function insertHistory($idlog, $user, $id, $description){
    if (isset($id) && isset($idlog)) {
        DB::table('cv_history')->insertGetId([
            'cv_id' => trim($id),
            'author_id' => $user->username,
            'description' => $description,
            'datecreate' => time(),
            'idlog' => $idlog
        ]);
    }
}

saveHistoryOfFavorite($login_id, 'insert', 'favorite', $id)
insertHistory($idlog, $user, $id, $description)
*/

$params = $request->getQueryParams();

$id = $args['id'];

$status_favorite = '';

$check_cv = '';

if (isset($id)) {
    $check_cv = checkCv($id);
}

if (isset($id) && isset($params['favorite'])) {
    $favorite = $params['favorite'];

    if ($favorite === '1' && $check_cv) {
        $status_favorite = 'like_successful';

        favoriteIsTrue($id);

        /*
        $check = checkCvFavorite($id);

        if($check){
             idlog = saveHistoryOfFavorite($login_id, 'update', 'favorite', $id);

            if(!empty($idlog)){
                $description = 'Yêu thích đã được cập nhật lại bởi ' . $user->username
                insertHistory($idlog, $user, $id, $description);
            }
        } 

        if(!$check){
             idlog = saveHistoryOfFavorite($login_id, 'insert', 'favorite', $id);

            if(!empty($idlog)){
                $description = 'Yêu thích đã được Thêm bởi ' . $user->username
                insertHistory($idlog, $user, $id, $description);
            }
        } 
        */
    }

    if ($favorite === '0' && $check_cv) {
        $status_favorite = 'dislike_successful';

        favoriteIsFalse($id);

        /*
        idlog = saveHistoryOfFavorite($login_id, 'insert', 'favorite', $id);

        if(!empty($idlog)){
            $description = 'Yêu thích đã được cập nhật lại bởi ' . $user->username
            insertHistory($idlog, $user, $id, $description);
        }
        */
    }
}

$results = [
    'status' => $status_favorite ? $status_favorite : 'false',
    'data' => $check_cv ? $id  : null,
    'total' => 1,
    'time' => time()
];
