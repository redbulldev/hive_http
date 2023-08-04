<?php

use Illuminate\Database\Capsule\Manager as DB;

function getTable($name, $id)
{
    $status = DB::table($name)->where('id', $id)->first();

    if ($status) {
        return $status;
    }

    return null;
}

function getTableAndSelect($name, $id, $select)
{
    $status = DB::table($name)->where('cv_id', $id)->select($select)->first();

    if ($status) {
        return $status;
    }

    return null;
}

$one->position = getTable('positions', $one->position_id);

$one->request = getTable('request', $one->request_id);

$one->level = getTable('level', $one->level_id);

$review_hr = getTableAndSelect('review_hr', $id, ['salary_want', 'onboard', 'social_link']);

$interview_hr = getTableAndSelect('interview_hr', $id, ['salary_want', 'onboard', 'notes']);

if (!empty($interview_hr)) {
    $one->hr_notes = !empty($interview_hr->notes) ? $interview_hr->notes : '';

    $interview_tech = getTableAndSelect('interview_tech', $id, ['notes', 'salary_suggested']);

    if ($interview_tech) {
        $one->tech_notes = !empty($interview_tech->notes) ? $interview_tech->notes : '';

        $tech_salary_suggested = !empty($interview_tech->salary_suggested) ? $interview_tech->salary_suggested : '';
    } else {
        $one->tech_notes = '';

        $tech_salary_suggested = '';
    }

    $interview_hr_salary_want = !empty($interview_hr->salary_want) ? $interview_hr->salary_want : '';

    $interview_hr_onboard = !empty($interview_hr->onboard) ? $interview_hr->onboard : '';

    $hr_salary_want = $review_hr_onboard = '';

    if (!empty($review_hr)) {
        $hr_salary_want = !empty($review_hr->salary_want) ? $review_hr->salary_want : '';

        $review_hr_onboard = !empty($review_hr->onboard) ? $review_hr->onboard : '';
    }

    $one->more_select = [
        'salary_want' => !empty($interview_hr_salary_want) ? $interview_hr_salary_want : $hr_salary_want,
        'salary_suggested' => $tech_salary_suggested,
        'onboard' => !empty($interview_hr_onboard) ? $interview_hr_onboard : $review_hr_onboard
    ];
} else if (!empty($review_hr)) {
    $one->more_select = [
        'salary_want' => !empty($review_hr->salary_want) ? $review_hr->salary_want : '',
        'onboard' => !empty($review_hr->onboard) ? $review_hr->onboard : ''
    ];
} else {
    $one->more_select = '';
}

$one->social_link = !empty($review_hr->social_link) ? $review_hr->social_link : '';

$one->favorite = false;

if (empty($one->favorite)) {
    $check_favorite = DB::table('cv_favorite')
        ->where('cv_favorite.cv_id', $id)
        ->where('cv_favorite.status', 1)
        ->select('cv_favorite.id AS favorite')
        ->first();

    if ($check_favorite) {
        $one->favorite = true;
    }
}

if (!empty($one->last_level_id)) {
    $one->last_level = DB::table('level')->where('id', $one->last_level_id)->first();
} else {
    $one->last_level = '';
}
