<?php

use Illuminate\Database\Capsule\Manager as DB;

/*
* @get
* /v1/cv?keyword=Tech&limit=10&page=1
* /v1/request?keyword=Tech&limit=10&page=1
*/

$site = !empty($set_site) ? $set_site : '';

function callSite($keyword, $site, $user)
{
    if ($site === 'managercv' || $site === 'request') {
        $status = addKeyword($keyword, $site, $user);

        if (!empty($status)) {
            return true;
        }

        return false;
    }

    return false;
}

function addKeyword($keyword, $site, $user)
{
    try {
        return DB::table('history_search')->insert([
            'keyword' => $keyword,
            'user_id' => $user->username,
            'site' => $site,
            'datecreate' => time()
        ]);
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

function keywordOfUser($key, $site, $user)
{
    return DB::table('history_search')
        ->where('keyword', $key)
        ->where('user_id', $user->username)
        ->where('site', $site)
        ->where('isdelete', 0)
        ->max('id');
}

function keywordTop($site, $user)
{
    return DB::table('history_search')
        ->where('user_id', $user->username)
        ->where('site', $site)
        ->where('isdelete', 0)
        ->max('id');
}

function removeKeyword($keyword, $user)
{
    return DB::table('history_search')
        ->where('id', $keyword)
        ->where('user_id', $user->username)
        ->where('isdelete', 0)
        ->update(['isdelete' => 1]);
}

if (!empty($site) && !empty($key)) {
    $countSearch = $objSearch->count();

    if ($countSearch >= 1) {
        $keyword_of_user = keywordOfUser($key, $site, $user);

        if (!empty($keyword_of_user)) {
            $keyword_top = keywordTop($site, $user);

            if ($keyword_top !== $keyword_of_user) {
                $status = callSite($key, $site, $user);

                if (!empty($status)) {
                    removeKeyword($keyword_of_user, $user);
                }
            }
        } else {
            callSite($key, $site, $user);
        }
    }
}
