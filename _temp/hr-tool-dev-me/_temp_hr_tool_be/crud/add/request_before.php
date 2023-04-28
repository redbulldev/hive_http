<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request, [
    'requestor_id' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    'position_id' => v::digit()->notEmpty(),
    'company_id' => v::digit()->notEmpty(),
    'company_id' => v::digit()->notEmpty(),
    'levels' => v::arrayType()->notEmpty(),
    'target' => v::digit()->between(1, 127)->notEmpty(),
    'day' => v::digit()->length(1, 2)->notEmpty(),
    'month' => v::digit()->length(1, 2)->notEmpty(),
    'year' => v::digit()->length(4, 4)->min(date('Y'))->notEmpty(),
    'template_id' => v::digit()->notEmpty(),
]);

// ngày tháng năm -> phải nằm trong khoảng 2->90 ngày (khoản thời gian job có hiệu lực)
$current = strtotime($data->year . '-' . $data->month . '-' . $data->day . ' 23:59:59');

$data->date = date('Y-m-d', $current);

$setting = DB::table('setting')->where('id', 1)->first();

if ($setting) {
    $minalllow = time() + $setting->daybefore * 24 * 60 * 60;

    $maxalllow = time() + $setting->dayafter * 24 * 60 * 60;

    if ($current <= time()) {
        throw new Exception('The selected time must be greater than the current time');
    }

    if ($current < $minalllow) {
        throw new Exception('Time must be selected at least ' . $setting->daybefore . ' days');
    }

    if ($current > $maxalllow) {
        throw new Exception('Time must be selected no more than ' . $setting->dayafter . ' days');
    }
} else {
    throw new Exception('Please update setting before create request');
}

if (isset($data->decision_id)) {
    throwError($container, $request, [
        'decision_id' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace()
    ]);

    if (!DB::table('users')->where('username', $data->decision_id)->where('isdelete', 0)->count()) {
        throw new Exception('Decision not exist');
    }
}

if (!DB::table('users')->where('username', $data->requestor_id)->where('isdelete', 0)->count()) {
    throw new Exception('Requestor not exist');
}

if (!DB::table('positions')->where('id', $data->position_id)->where('isdelete', 0)->count()) {
    throw new Exception('Position not exist');
}

if (!DB::table('company')->where('id', $data->company_id)->where('isdelete', 0)->count()) {
    throw new Exception('Company not exist');
}


if (!DB::table('template_request')->where('id', $data->template_id)->where('isdelete', 0)->count()) {
    throw new Exception('Template not exist');
}

if (isset($data->levels)) {
    foreach ($data->levels as $level) {
        if (!DB::table('level')->where('id', $level->id)->where('isdelete', 0)->count()) {
            throw new Exception('Level not exist');
        }
    }
}

$data->status = 0;

if (isset($data->description)) {
    $data->description = substr($data->description, 0, 5000);
}

$data->deadline = $data->date;
