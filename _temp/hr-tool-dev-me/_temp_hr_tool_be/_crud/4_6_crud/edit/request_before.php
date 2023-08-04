<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

if (isset($data->target)) {
    throwError($container, $request, [
        'target' => v::digit()->between(1, 127)->notEmpty(),
    ]);
}

if (isset($data->month) || isset($data->year)) {
    throwError($container, $request, [
        'month' => v::digit()->length(1, 2)->notEmpty(),
        'year' => v::digit()->length(4, 4)->min(date('Y'))->notEmpty()
    ]);

    $old = DB::table('request')->where(['id' => $id, 'isdelete' => 0])->first();

    if ($old) {
        $arr = explode('-',  $old->date);

        $current = strtotime($data->year . '-' . $data->month . '-' . ($old->day > 0 ? $old->day : (!empty($arr[2]) ? $arr[2] : 30)) . ' 23:59:59');

        $data->date = date('Y-m-d', $current);
    }

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
}

if (isset($data->requestor_id)) {
    throwError($container, $request, [
        'requestor_id' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace()
    ]);

    if (!DB::table('users')->where('username', $data->requestor_id)->where('isdelete', 0)->count()) {
        throw new Exception('Requestor not exist');
    }
}

if (isset($data->decision_id)) {
    throwError($container, $request, [
        'decision_id' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace()
    ]);

    if (!DB::table('users')->where('username', $data->decision_id)->where('isdelete', 0)->count()) {
        throw new Exception('Decision not exist');
    }
}

if (isset($data->position_id)) {
    throwError($container, $request, [
        'position_id' => v::digit()->notEmpty()
    ]);

    if (!DB::table('positions')->where('id', $data->position_id)->where('isdelete', 0)->count()) {
        throw new Exception('Position not exist');
    }
}

if (isset($data->company_id)) {
    throwError($container, $request, [
        'company_id' => v::digit()->notEmpty()
    ]);

    if (!DB::table('company')->where('id', $data->company_id)->where('isdelete', 0)->count()) {
        throw new Exception('Company not exist');
    }
}

if (isset($data->levels)) {
    throwError($container, $request, [
        'levels' => v::arrayType()->notEmpty(),
    ]);

    foreach ($data->levels as $level) {
        if (!DB::table('level')->where('id', $level->id)->where('isdelete', 0)->count()) {
            throw new Exception('Level not exist');
        }
    }
}

if (isset($data->description)) {
    $data->description = substr($data->description, 0, 5000);
}
