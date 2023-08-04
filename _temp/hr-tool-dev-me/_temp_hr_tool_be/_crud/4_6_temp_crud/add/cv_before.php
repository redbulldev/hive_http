
<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'request_id' =>  v::digit()->notEmpty(),
    'assignee_id' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    'fullname' => v::length(3, 200)->notEmpty(),
]);

if (!empty($data->email)) {
    throwError($container, $request,  [
        'email' => v::email()->length(6, 200)->notEmpty(),
    ]);

    if (DB::table('cv')->where('email', $data->email)->where('isdelete', 0)->count()) {
        $alertmore = 'Email already exists';
    }
}

if (!empty($data->mobile)) {
    if (!preg_match("/^\+?[0-9]{10,13}$/", $data->mobile)) {
        throw new Exception('Mobile must be valid');
    }

    if (DB::table('cv')->where('mobile', $data->mobile)->where('isdelete', 0)->count()) {
        $alertmore = 'Mobile already exists';
    }
}

if (!empty($data->source_id)) {
    if (!DB::table('source')->where('id', $data->source_id)->where('isdelete', 0)->count()) {
        throw new Exception('Source not exist');
    }
}

if (!empty($data->reviewer_id)) {
    if (!DB::table('users')->where('username', $data->reviewer_id)->where('isdelete', 0)->count()) {
        throw new Exception('Reviewer not exist');
    }
}

if (!empty($data->interviewer_id)) {
    if (!DB::table('users')->where('username', $data->interviewer_id)->where('isdelete', 0)->count()) {
        throw new Exception('Interviewer not exist');
    }
}

if (!empty($data->assignee_id)) {
    if (!DB::table('users')->where('username', $data->assignee_id)->where('isdelete', 0)->count()) {
        throw new Exception('Assignee not exist');
    }
}

$request = DB::table('request')->where([
    'request.id' => $data->request_id,
    'status' => 2
])->where('request.isdelete', 0)->first();

if (empty($data->reviewer_id)) {
    unset($data->reviewer_id);
}

if (empty($data->interviewer_id)) {
    unset($data->interviewer_id);
}

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

//Luôn set step và trạng thái CV vs giá trị mặc định khi thêm mới CV
$data->step = 1;

$data->status = 1;

if (!empty($data->description)) {
    $data->description = substr($data->description, 0, 5000);
}
