<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

if (isset($data->fullname)) {
    throwError($container, $request,  [
        'fullname' => v::length(3, 200)->notEmpty(),
    ]);
}

if (isset($data->email)) {
    throwError($container, $request,  [
        'email' => v::email()->length(6, 200)->notEmpty(),
    ]);

    if (DB::table('cv')->where('email', $data->email)->where('id', '!=', $id)->where('isdelete', 0)->count()) {
        $alertmore = 'Email already exists';
    }
}

if (isset($data->mobile)) {
    if (!preg_match("/^\+?[0-9]{10,13}$/", $data->mobile)) {
        throw new Exception('Mobile must be valid');
    }

    if (DB::table('cv')->where('mobile', $data->mobile)->where('id', '!=', $id)->where('isdelete', 0)->count()) {
        $alertmore = 'Mobile already exists';
    }
}

if (isset($data->reviewer_id)) {
    throwError($container, $request,  [
        'reviewer_id' =>  v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    ]);

    if (!DB::table('users')->where('username', $data->reviewer_id)->where('isdelete', 0)->count()) {
        throw new Exception('Reviewer not exist');
    }
}

if (isset($data->interviewer_id)) {
    throwError($container, $request,  [
        'interviewer_id' =>  v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    ]);

    if (!DB::table('users')->where('username', $data->interviewer_id)->where('isdelete', 0)->count()) {
        throw new Exception('Interviewer not exist');
    }
}

if (isset($data->assignee_id)) {
    throwError($container, $request,  [
        'assignee_id' =>  v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    ]);

    if (!DB::table('users')->where('username', $data->assignee_id)->where('isdelete', 0)->count()) {
        throw new Exception('Assignee not exist');
    }
}

if (isset($data->position_id)) {
    throwError($container, $request,  [
        'position_id' => v::digit()->notEmpty()
    ]);

    $position = DB::table('positions')->where('id', $data->position_id)->where('isdelete', 0)->first();
    if (!$position) {
        throw new Exception('Position not exist');
    }
}

if (isset($data->level_id)) {
    throwError($container, $request,  [
        'level_id' => v::digit()->notEmpty()
    ]);

    $level = DB::table('level')->where('id', $data->level_id)->where('isdelete', 0)->first();

    if (!$level) {
        throw new Exception('Level not exist');
    }
}

if (isset($data->source_id)) {
    if (!DB::table('source')->where('id', $data->source_id)->where('isdelete', 0)->count()) {
        throw new Exception('Source not exist');
    }
}

if (empty($data->reviewer_id)) {
    unset($data->reviewer_id);
}

if (empty($data->interviewer_id)) {
    unset($data->interviewer_id);
}

if (isset($data->request_id)) {
    $request = DB::table('request')->where(['request.id' => $data->request_id, 'status' => 2, 'isdelete' => 0])->first();

    if (!$request) {
        throw new Exception('Request for ' . $position->title . ' ' . $level->title . ' not found');
    } else {
        if (empty($data->interviewer_id) && $request->requestor_id !== 'AUTO') {
            if (isset($request->requestor_id)) {
                $data->interviewer_id = $request->requestor_id;
            }
        }

        if (empty($data->reviewer_id) && $request->requestor_id !== 'AUTO') {
            if (isset($request->requestor_id)) {
                $data->reviewer_id  = $request->requestor_id;
            }
        }
    }
}

//Hủy chức năng cập nhật step và trạng thái CV
if (isset($data->step)) {
    unset($data->step);
}

if (isset($data->status)) {
    unset($data->status);
}

if (isset($data->description)) {
    $data->description = substr($data->description, 0, 5000);
}
