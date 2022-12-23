<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj = DB::table($name);

require './crud/all_where.php';

// $res = clone $ketqua;
$res = clone $statisticCv;

function getPosition($value)
{
    $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}

function getPointOfPosition($pos, $level)
{
    $data = DB::table('level_positions')->where('position_id', $pos)->where('level_id', $level)->latest('id')->first();

    if (!empty($data)) {
        return $data->point;
    }

    return false;
}

// summary //
$temp_list_cv_pass = [];

$interview_cv = 0;

foreach ($res->all() as $key => $value) {
    if (($value->step == 6 && $value->status == 2) || $value->step > 6 && !empty(getPosition($value->position_id))) {
        $index = getPosition($value->position_id);

        $temp_list_cv_pass[$index] = 0;
    }

    if (($value->step == 5 && $value->status == 2) || $value->step > 5  && !empty(getPosition($value->position_id))) {
        $interview_cv++;
    }
}

$list_cv_pass = [];

foreach ($temp_list_cv_pass as $key => $value) {
    $list_cv_pass[$key] = $value;
}

foreach ($res->all() as $key => $value) {
    if (($value->step == 6 && $value->status == 2) || $value->step > 6 && !empty(getPosition($value->position_id))) {
        $index = getPosition($value->position_id);

        array_key_exists($index, $list_cv_pass) ?  $list_cv_pass[$index]++ : 0;
    }
}

$obj_cv = clone $obj;

$obj_cv->selectRaw('
    GROUP_CONCAT(cv.position_id) AS list_cv_new, 
    GROUP_CONCAT(cv.step,\'\') AS list_cv_pass, 
    GROUP_CONCAT(positions.title,\'\') as labels
');

$summary = $obj_cv->first();

$newlabel = [];

$newlist_cv_pass = [];

$newlist_cv_new = [];

$labels = explode(',', $summary->labels);

foreach ($labels as $key => $label) {
    if ($label) {
        $newlabel[$label] = $label;

        $newlist_cv_new[$label] = (!empty($newlist_cv_new[$label]) ? $newlist_cv_new[$label] : 0) + 1;

        $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $list_cv_pass[$label] : 0);
    }
}

arsort($newlist_cv_new);

$temp_cv_pass = [];

$temp_newlabel = [];

foreach ($newlist_cv_new as $key => $new) {
    $temp_cv_pass[$key] = $new;

    $temp_newlabel[$key] = 1;
}

foreach ($newlist_cv_pass as $key => $value) {
    foreach ($temp_cv_pass as $index => $pass) {
        if ($key == $index) {
            $temp_cv_pass[$index] = $value;
        }
    }
}

$summary->labels = implode(',', array_keys($temp_newlabel));

$summary->list_cv_new = implode(',', array_values($newlist_cv_new));

$summary->list_cv_pass = implode(',', array_values($temp_cv_pass));

$summary->interview_cv =  !empty($interview_cv) ? $interview_cv : 0;

$summary->pass_cv = !empty($newlist_cv_pass) ? (int)array_sum($newlist_cv_pass) : 0;

$summary->total_cv = !empty($newlist_cv_new) ? (int)array_sum($newlist_cv_new) : 0;

// department //
$department = ['labels' => [], 'values' => []];

$temp_department_lables = [];

$temp_department_values = [];

foreach ($res->all() as $key => $value) {
    if (($value->step > 8 && $value->status == 2) || $value->step > 9 && !empty(getPosition($value->position_id))) {
        $index = getPosition($value->position_id);

        $temp_department_lables[$value->position_id] = $index;

        $temp_department_values[$value->position_id] = 0;
    }
}

$level_position_point_ids = [];

foreach ($res->all() as $key => $value) {
    if (($value->step == 9 && $value->status == 2) || $value->step > 9 && !empty(getPosition($value->position_id))) {
        $temp_department_values[$value->position_id]++;
    }

    if ($value->step > 8) {
        $level_position_point_ids[$value->id . '-' . $value->position_id . '-' . $value->level_id] = array($value->position_id, $value->level_id);
    }
}

arsort($temp_department_values);

foreach ($temp_department_values as $index => $value) {
    $department['values'][$index] = $value;
}

foreach ($temp_department_values as $key => $value) {
    foreach ($temp_department_lables as $index => $lable) {
        if ($index == $key) {
            $department['labels'][$index] = $lable;
        }
    }
}

$summary->onboard_cv = (int)array_sum($department['values']);

// point //
$count_point = 0;

foreach ($level_position_point_ids as $key => $pos) {
    $point = getPointOfPosition($pos[0], $pos[1]);

    if (!empty($point)) {
        $count_point += $point;
    }
}

// target //
$obj_plan = DB::table('plan');

if (!empty($params['from']) && !empty($params['to'])) {
    $obj_plan->groupBy('year')->groupBy('month')->whereIn('status', [2, 4])
        ->selectRaw("SUM(target) AS target");

    if (!empty($params['from']) && !empty($params['to'])) {
        $from = substr($params['from'], 0, 7) . '-01';

        $to = substr($params['to'], 0, 7) . '-31';

        $obj_plan->where('date', '>=', $from)->where('date', '<=', $to);
    }
}

$target_cv = 0;

foreach ($obj_plan->get() as $key => $value) {
    $target_cv += $value->target;
}

$summary->target = !empty($target_cv) ? $target_cv : 0;

// report //
$report_ids = [];

foreach ($res->all() as $key => $value) {
    $report_ids[$value->id] =  $value->id;
}

$str_report_ids = implode(",", $report_ids);

$report = DB::select(
    DB::raw("
        SELECT 
            cv.id,
            positions.id as position_id,
            level.id as level_id,
            cv.request_id as cv_request_id,
            cv.assignee_id,
            (
                SELECT GROUP_CONCAT(cv.fullname) FROM cv WHERE (step = 8 AND cv.status = 2) OR step > 8 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id  AND cv.level_id = level.id
            ) as employees,
            cv.datecreate,
            parent.id as pos_parent_id,
            request.target,      
            (
                -- SELECT request.deadline FROM request WHERE request.id = cv.request_id AND cv.isdelete = 0 AND request.status = 2 AND parent.id = cv_parent_id AND cv.position_id = request.position_id  LIMIT 1
                -- thỏa mãn y/c: request, department_title, positions_title

                SELECT 
                    -- GROUP_CONCAT(request.id, request.deadline)
                    request.deadline
                FROM cv 
                    left join positions on positions.id = cv.position_id
                    left join positions as parent on parent.id = positions.parent_id
                    left join request on request.id = cv.request_id               
                WHERE request.id = cv_request_id AND cv.isdelete = 0 AND request.isdelete = 0 AND request.status = 2 AND parent.id = pos_parent_id AND cv.position_id = request.position_id ORDER BY request.id DESC LIMIT 1
            ) as deadline,     
            parent.title as department_title, 
            positions.title as positions_title, 
            level.title as level_title,
            (
                SELECT count(step) FROM cv WHERE step >= 0 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as total_cv,
            (
                SELECT count(step) FROM cv WHERE (step = 5 AND cv.status = 2) OR step > 5 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as interview_cv,
            (
                SELECT count(step) FROM cv WHERE (step = 6 AND cv.status = 2) OR step > 6 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as pass_cv,
            (
                SELECT count(step) FROM cv WHERE (step = 7 AND cv.status = 2) OR step > 7 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as offer_cv,
            (
                SELECT count(step) FROM cv WHERE (step = 8 AND cv.status = 2) OR step > 8 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as offer_success,
            (
                SELECT count(step) FROM cv WHERE (step = 9 AND cv.status = 2) OR step > 9 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as onboard_cv,
            (
                SELECT count(step) FROM cv WHERE step > 9 AND cv.status = 0 AND cv.isdelete = 0 GROUP BY cv.position_id, cv.level_id HAVING cv.position_id = positions.id AND cv.level_id = level.id
            ) as fail_job
        FROM cv 
            left join positions on positions.id = cv.position_id
            left join positions as parent on parent.id = positions.parent_id
            left join request on request.id = cv.request_id
            left join level on level.id = cv.level_id
            left join source on source.id = cv.source_id
        WHERE cv.id In ($str_report_ids)
        GROUP BY cv.position_id, cv.level_id   
    ")
);

$page = $request->getQueryParam('page', 1);

$limit = $request->getQueryParam('limit', 10);

$totalCount = count($report);

$collection = collect($report);

$offer_cv = 0;

$offer_success = 0;

$fail_job = 0;

foreach ($collection as $key => $value) {
    if (!empty($value->offer_cv)) {
        $offer_cv += $value->offer_cv;
    }

    if (!empty($value->offer_success)) {
        $offer_success += $value->offer_success;
    }

    if (!empty($value->fail_job)) {
        $fail_job += $value->fail_job;
    }
}

$summary->offer_cv = !empty($offer_cv) ? $offer_cv : 0;

$summary->offer_success = !empty($offer_success) ? $offer_success : 0;

$summary->fail_job = !empty($fail_job) ? $fail_job : 0;

$paginator = new \Illuminate\Pagination\LengthAwarePaginator($collection->forPage($page, $limit), $totalCount, $limit, $page);

$results = [
    'status' => 'success',
    'point' => $count_point,
    'summary' => $summary,
    'department' => $department,
    'data' => $paginator ? $paginator->all() : null,
    'total' => $totalCount,
    'time' => time()
];
