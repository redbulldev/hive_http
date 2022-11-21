<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
// print_r($data->id);
// die($data->id);

throwError($container, $request, [
    'title' => v::length(3, 50)->notEmpty(),
    'key' => v::length(2, 5)->notEmpty(),
    'rank' => v::length(1, 4)->notEmpty(),
]);

if (!empty($data->parent_id)) {
    if (!DB::table($name)->where(['id' => trim($data->parent_id), 'parent_id' => 0])->where('isdelete', 0)->count()) {
        throw new Exception('Department not exist');
    }

    if (empty($data->manager_id)) {
        throw new Exception('Manager not found');
    } else {
        if (!DB::table('users')->where('username', $data->manager_id)->where('isdelete', 0)->count()) {
            throw new Exception('Manager not exist');
        }
    }

    if (empty($data->requestor) || !is_array($data->requestor) || count($data->requestor) == 0) {
        throw new Exception('Requestor not found');
    }

    if (isset($data->requestor) && is_array($data->requestor) && count($data->requestor) > 0) {
        if (count($data->requestor) != DB::table('users')->whereIn('username', $data->requestor)->where('isdelete', 0)->count()) {
            throw new Exception('One of the requesters not found');
        }
    }

    if (empty($data->user_cvs) || !is_array($data->user_cvs) || count($data->user_cvs) == 0) {
        throw new Exception('User not found');
    }

    if (isset($data->user_cvs) && is_array($data->user_cvs) && count($data->user_cvs) > 0) {
        if (count($data->user_cvs) != DB::table('users')->whereIn('username', $data->user_cvs)->where('isdelete', 0)->count()) {
            throw new Exception('One of the user not found');
        }
    }
} else {
    $data->parent_id = 0;
}

if (empty($data->levels) || !is_array($data->levels) || count($data->levels) == 0) {
    throw new Exception('Level not found');
}

if (isset($data->levels) && is_array($data->levels) && count($data->levels) > 0) {
    if (count($data->levels) != DB::table('level')->whereIn('id', $data->levels)->where('isdelete', 0)->count()) {
        throw new Exception('One of the levels not found');
    }
}

if (isset($data->description)) {
    $data->description = substr($data->description, 0, 200);
}

if (isset($data->key)) {
    if (!empty($data->parent_id)) {
        if (DB::table($name)->where(['key' => trim($data->key), 'parent_id' => $data->parent_id])->where('isdelete', 0)->count()) {
            throw new Exception('Key already exists');
        }
    } else {
        if (DB::table($name)->where(['key' => trim($data->key), 'parent_id' => 0])->where('isdelete', 0)->count()) {
            throw new Exception('Key already exists');
        }
    }
}

if (isset($data->title)) {
    if (!empty($data->parent_id)) {
        if (DB::table($name)->where(['title' => trim($data->title), 'parent_id' => $data->parent_id])->where('isdelete', 0)->count()) {
            throw new Exception('Title already exists');
        }
    } else {
        if (DB::table($name)->where(['title' => trim($data->title), 'parent_id' => 0])->where('isdelete', 0)->count()) {
            throw new Exception('Title already exists');
        }
    }
}
