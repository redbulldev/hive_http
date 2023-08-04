<?php

use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as DB;

$arrStatus = [
    'review_hr' => ['title' => 'HR Review', 'current' => CURRENT_REVIEW_HR, 'step' => STEP_REVIEW_HR, 'old' => 'New'],

    'review_physiognomy1' => ['title' => 'Physiognomy 1', 'current' => CURRENT_REVIEW_PHYSIOGNOMY1, 'step' => STEP_REVIEW_PHYSIOGNOMY1, 'old' => 'HR Review'],

    'review_cv' => ['title' => 'CV Review', 'current' => CURRENT_REVIEW_CV, 'step' => STEP_REVIEW_CV, 'old' => 'Physiognomy 1'],

    'review_physiognomy2' => ['title' => 'Physiognomy 2', 'current' => CURRENT_REVIEW_PHYSIOGNOMY2, 'step' => STEP_REVIEW_PHYSIOGNOMY2, 'old' => 'CV Review'],

    'to_interview' => ['title' => 'Pre Interview', 'current' => CURRENT_TO_INTERVIEW, 'step' => STEP_TO_INTERVIEW, 'old' => 'Physiognomy 2'],

    'interview_hr' => ['title' => 'HR Interview', 'current' => CURRENT_INTERVIEW_TECH_HR, 'step' => STEP_INTERVIEW_TECH_HR, 'old' => 'Pre Interview'],

    'interview_tech' => ['title' => 'Tech Interview', 'current' => CURRENT_INTERVIEW_TECH_HR, 'step' => STEP_INTERVIEW_TECH_HR, 'old' => 'Pre Interview'],

    'cv_preoffer' => ['title' => 'Pre Offer', 'current' => CURRENT_CV_PREOFFER, 'step' => STEP_CV_PREOFFER, 'old' => 'Hr and Tech Interview'],

    'cv_offer' => ['title' => 'Offer', 'current' => CURRENT_CV_OFFER, 'step' => STEP_CV_OFFER, 'old' => 'Pre Offer'],

    'cv_onboard' => ['title' => 'OnBoard', 'current' => CURRENT_CV_ONBOARD, 'step' => STEP_CV_ONBOARD, 'old' => 'Offer'],

    'cv_probation' => ['title' => 'Probation', 'current' => CURRENT_CV_PROBATION, 'step' => STEP_CV_PROBATION, 'old' => 'OnBoard'],
];

$cv = DB::table('cv')->where('id', trim($data->cv_id))->where('isdelete', 0)->first();

if ($name === 'cv_probation' && empty($data->todate) && $data->status == 2) {
    throw new Exception('Todate is required');
}

if ($name === 'cv_onboard' && empty($data->onboard)  && $data->status == 2) {
    throw new Exception('Onboard date is required');
}

if (isset($data->onboard)) {
    if ($name !== 'cv_onboard' && $data->status == 2) {
        $onboard = strtotime($data->onboard . ' ' . date('H:i:s'));
    }
} else {
    $data->onboard = null;
}

throwError($container, $request,  [
    'cv_id' => v::digit()->notEmpty(),
]);

if (!$cv) {
    throw new Exception('CV not exist');
}

$obj = $arrStatus[$name];

$where['cv_id'] = trim($data->cv_id);

if ($name === 'review_physiognomy1') {
    $where['issecond'] = 0;

    $data->issecond = 0;

    $name = 'review_physiognomy';
} else if ($name === 'review_physiognomy2') {
    $where['issecond'] = 1;

    $data->issecond = 1;

    $name = 'review_physiognomy';
}

if (isset($data->notes)) {
    $data->notes = substr($data->notes, 0, 5000);
}

if (isset($data->reason)) {
    $data->reason = substr($data->reason, 0, 5000);
}

if (isset($data->experience)) {
    $data->experience = substr($data->experience, 0, 5000);
}

if (isset($data->language)) {
    $data->language = substr($data->language, 0, 5000);
}

if (isset($data->expertise)) {
    $data->expertise = substr($data->expertise, 0, 5000);
}

if (isset($data->character_note)) {
    $data->character_note = substr($data->character_note, 0, 5000);
}

if (isset($data->knowledge)) {
    $data->knowledge = substr($data->knowledge, 0, 5000);
}

if (isset($data->self_appraisal)) {
    $data->self_appraisal = substr($data->self_appraisal, 0, 5000);
}

if (isset($data->career_direction)) {
    $data->career_direction = substr($data->career_direction, 0, 5000);
}

if (isset($data->questions)) {
    $data->questions = substr($data->questions, 0, 5000);
}

$newdata = removecolumn($name, $data);

$olddata = DB::table($name)->where($where)->first();

$description = '';

$datacv = [
    'step' => $cv->step,
    'status' => (isset($newdata['status'])) ? $newdata['status'] : -1
];

if (!$olddata) {
    if ($cv->step < $obj['current']) {
        if (is_array($obj['step'])) {
            //Kiểm tra bước CV có phù hợp với lần Review không
            if (!in_array($cv->step, $obj['step'])) {
                throw new Exception('Can\'t create ' . $obj['title'] . '. Please create ' . $obj['old'] . ' before');
            }
        } else {
            //Kiểm tra bước CV có phù hợp với lần Review không
            if ($cv->step != $obj['step']) {
                throw new Exception('Can\'t create ' . $obj['title'] . '. Please create ' . $obj['old'] . ' before');
            }

            //Kiểm tra satus CV hiện tại phải đang là pass mới cho phép tiếp tục
            if ($cv->status !== 2) {
                throw new Exception('Can\'t create ' . $obj['title'] . '. Status for ' . $obj['old'] . ' not pass');
            }

            if ($datacv['status'] === 2) {
                $datacv['step'] = $cv->step + 1;
            }
        }
    }

    //Thêm mới
    $id = DB::table($name)->insertGetId($newdata);

    $idlog = historySave($user->username, 'insert', $name, $id);

    $description = $user->username . ' thêm mới thông tin ' . $obj['title'];

    if ($name === 'interview_hr' || $name === 'interview_tech') {
        $datacv = ['status' => udCVInterview($cv), 'step' => 6];
    }

    if ($datacv['step'] == $obj['current'] && $obj['current'] < 10 && $datacv['status'] == 2) {
        $datacv['step'] = $obj['current'] + 1;

        $datacv['status'] = 1;
    }

    if ($datacv['status'] <= 10) {
        DB::table('cv')->where('id', $cv->id)->update($datacv);
    }
} else {
    //Sửa
    unset($newdata['datecreate']);

    DB::table($name)->where($where)->update($newdata);

    $idlog = historySave($user->username, 'update', $name, $olddata->id, $olddata);

    $description = $user->username . ' cập nhật thông tin ' . $obj['title'];

    if ($datacv['status'] != 2) {
        $datacv['step'] = $obj['current'];
    } else if ($datacv['status'] == 2) {
        $datacv = cvStep($cv);

        if ($datacv['step'] == $obj['current'] && $obj['current'] < 10  && $datacv['status'] == 2) {
            $datacv['step'] = $obj['current'] + 1;

            $datacv['status'] = 1;
        }
    }

    if ($name === 'interview_hr' || $name === 'interview_tech') {
        $datacv['status'] = udCVInterview($cv);
    }

    if ($datacv['status'] <= 10) {
        DB::table('cv')->where('id', $cv->id)->update($datacv);
    }
}

if (isset($data->onboard) && $name === 'cv_onboard') {
    DB::table('cv')->where('id', $cv->id)->update(['onboard' => $data->onboard]);
}

if ($name === 'to_interview') {
    $newdata2 = [
        'appoint_type' => !empty($data->appoint_type) ? $data->appoint_type : 0,
        'appoint_date' => !empty($data->appoint_date) ? $data->appoint_date : null,
        'appoint_place' => !empty($data->appoint_place) ? $data->appoint_place : '',
        'appoint_link' => !empty($data->appoint_link) ? $data->appoint_link : '',
    ];

    if (!empty($data->status) && $data->status == 2 && $cv->step == 5) {
        $newdata2['step'] = 6;

        $newdata2['status'] = 1;
    }

    if (!empty($data->interviewer_id) && !empty($data->appoint_date)) {
        $newdata2['interviewer_id'] = $data->interviewer_id;

        if ($conf['debug'] === false && $data->status == 2) {
            sendMessage($data->interviewer_id, 'Bạn có lịch phỏng vấn ứng viên ' . $cv->fullname . ' vào ngày ' . date('H:i d/m/Y', $data->appoint_date));
        }
    }

    DB::table('cv')->where('id', $cv->id)->update($newdata2);
} else if ($name == 'review_physiognomy' && $data->status == 2 && $cv->status <= 3 && !empty($cv->reviewer_id) && $conf['debug'] === false) {
    sendMessage($cv->reviewer_id, 'Bạn hãy vào Review ứng viên ' . $cv->fullname . ' tại đường link : https://hrm.ossigroup.net/cv/' . $cv->id);
}

DB::table('cv_history')->insertGetId([
    'cv_id' => trim($data->cv_id),
    'author_id' => $user->username,
    'description' => $description,
    'datecreate' => time(),
    'idlog' => $idlog
]);

if (isset($cv->request_id)) {
    updateReport($cv->request_id);
}

createMail($data->cv_id, DB::table($name)->where($where)->first(), $obj['current'], $data->status);
