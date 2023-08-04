<?php

use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as DB;

throwError($container, $request, [
    'cv_id' => v::digit()->notEmpty()
]);

if (!isset($data->users) || !count($data->users)) {
    throw new Exception('Member empty');
}

$check_cv = DB::table('cv')->where('id', $data->cv_id)->where('isdelete', 0)->count();

if (!$check_cv) {
    throw new Exception('CV not exist');
}

function handleFollowerGroup($data, $id)
{
    if (!empty($data)) {
        DB::table('cv_has_follower_group')->where('follower_group_id', $id)->delete();

        foreach (json_decode($data) as $value) {
            $pradd = [
                'follower_group_id' => $id,
                'user_id' => $value->username
            ];

            DB::table('cv_has_follower_group')->insert($pradd);
        }
    }
}

function convertMember($data, $user)
{
    $user_first = DB::table('users')->where('username', $user)->first();

    if (!empty($user_first)) {
        $user = [
            [
                "username" => $user_first->username,
                "fullname" => $user_first->fullname
            ]
        ];

        return array_merge($data->users, $user);
    }

    return false;
}

function findMemberOnJson($data, $user)
{
    foreach ($data->users as $key => $value) {
        if ($value->username === $user->username) {
            return true;
        }
    }

    return false;
}

if ($check_cv) {
    $check_follower_group = DB::table('follower_group')->where('cv_id', $data->cv_id)->where('isdelete', 0)->first();

    if (!empty($check_follower_group)) {
        $check_member_in_res_json = findMemberOnJson($data, $user);

        if ($check_member_in_res_json) {
            $members = $data->users;
        } else {
            $members = convertMember($data, $user->username);
        }

        DB::table('follower_group')->where('cv_id', $data->cv_id)->update([
            "users" => json_encode($members)
        ]);

        handleFollowerGroup(json_encode($members), $check_follower_group->id);
    } else {
        $check_member_in_res_json = findMemberOnJson($data, $user);

        if ($check_member_in_res_json) {
            $members = $data->users;
        } else {
            $members = convertMember($data, $user->username);
        }

        $insertGetId = DB::table('follower_group')->insertGetId([
            "cv_id" => $data->cv_id,
            "author_id" => $user->username,
            "users" => json_encode($members),
            'datecreate' => time(),
            'datemodified' => time()
        ]);

        handleFollowerGroup(json_encode($members), $insertGetId);
    }
}