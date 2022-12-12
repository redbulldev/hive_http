<?php
use Illuminate\Database\Capsule\Manager as DB;

$obj = DB::table($name);
require './crud/all_where.php';   


/////////////////// data - summary ///////////////////////////////////////////////////////
$obj_step = clone $obj->get();
$temp_list_cv_pass = [];
$newlist_cv_pass = [];
$newlist_cv_new = [];
function getPosition($value)
{
    $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}
// die($response->withJson($obj_step));

foreach ($ketqua->all() as $key => $value) {
    if ($value->step > 5 && $value->status == 2) {
        $index = getPosition($value->position_id);

        $temp_list_cv_pass[$index] = 0;
    }

    // $newlist_cv_pass[$key] = $value->positions_title;
}

$list_cv_pass = [];

foreach ($temp_list_cv_pass as $key => $value) {
    $list_cv_pass[$key] = $value;
}
foreach ($ketqua->all() as $key => $value) {
    if ($value->step > 5 && !empty(getPosition($value->position_id)) && $value->status == 2) {
        $index = getPosition($value->position_id);

        array_key_exists($index, $list_cv_pass) ?  $list_cv_pass[$index]++ : 0;
    }
}

// die($response->withJson($list_cv_pass));

$obj->selectRaw('
    GROUP_CONCAT(cv.position_id) AS list_cv_new, 
    GROUP_CONCAT(cv.step,\'\') AS list_cv_pass, 
    GROUP_CONCAT(positions.title,\'\') as labels
');

$summary=$obj->first();
// die($response->withJson($obj->get()));

// die($response->withJson($summary));

$newlabel = [];

$labels = explode(',', $summary->labels);

foreach($labels as $key=>$label){
    if($label){
        $newlabel[$label]= $label;
        $newlist_cv_new[$label] = (!empty($newlist_cv_new[$label]) ? $newlist_cv_new[$label] : 0) + 1;
        // $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $newlist_cv_pass[$label] + 1 : 0);
        $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $list_cv_pass[$label] : 0);
    }
}

$summary->labels = implode(',', array_keys($newlabel));

$summary->list_cv_new = implode(',', array_values($newlist_cv_new));
$summary->list_cv_pass = implode(',', array_values($newlist_cv_pass));
// die($response->withJson($summary));



//////////////////// *Bảng xếp hạng số lượng đã tuyển ////////////////////////////////////////////////////////
// data - department //
$department = ['labels' => [], 'values' => []];

$temp_department = [];

$get_position_ids = $obj->get();

$get_positions = DB::table('positions')->where(['isdelete' => 0, 'status' => 1])->get();

$position_ids = [];

foreach ($ketqua->all() as $key => $value) {
    // foreach ($get_positions as $index => $l) {
        // if ($l->id == $value->position_id) {
        if (!empty(getPosition($value->position_id)) && $value->status == 2) {
            $index = getPosition($value->position_id);
            $temp_department[$index] = $index;

            $position_ids[$value->position_id] = $value->position_id;
        }
    // }
}

foreach ($temp_department as $index => $value) {
    $department['labels'][] = $value;
}

// $position_request = clone $obj;

// $check_positions = $position_request->whereIn('cv.position_id', $position_ids)->where(['cv.isdelete' => 0])->get();

$temp_values = [];

foreach ($ketqua->all() as $key => $value) {
    if ($value->step > 8 && $value->status == 2) {
        $temp_values[$value->position_id] = 0;
    }
}

$position_point_ids = [];

foreach ($ketqua->all() as $key => $value) {
    if ($value->step > 8 && $value->status == 2) {
        $temp_values[$value->position_id]++;
    }

    if (!array_key_exists($value->position_id, $temp_values)) {
        $temp_values[$value->position_id] = 0;
    }

    $position_point_ids[$value->position_id] = $value->position_id;
}

foreach ($temp_values as $key => $value) {
    $department['values'][] = $value;
}
// die($response->withJson($position_point_ids));

////////////////// Tổng điểm/////////////////////////
$all_level_positions = DB::table('level_positions')->where(['isdelete' => 0])->where('position_id', '!=', 0)->whereIn('position_id', $position_point_ids)->get();

$count_point = 0;

foreach ($all_level_positions as $key => $value) {
    $count_point += $value->point;
}

////////////////// Số lượng cần tuyển/////////////////////////
$obj_plan = DB::table('plan');

if (!empty($params['from']) && !empty($params['to'])) {
    $obj_plan->groupBy('year')->groupBy('month')->whereIn('status', [2,4])
        ->selectRaw("SUM(target) AS target");
   
    if (!empty($params['from']) && !empty($params['to'])) {
        $from = substr($params['from'], 0, 7) . '-01';

        $to = substr($params['to'], 0, 7) . '-31';

        // $from = $params['from'];
        // $to = $params['to'];
    // die($to);

        $obj_plan->where('date', '>=', $from)->where('date', '<=', $to);
    }
} 

// $total_cv = $obj_plan->sum("target");
$total_cv = 0;

foreach ($obj_plan->get() as $key => $value) {
    $total_cv += $value->target;
}
$summary->total_cv = !empty($total_cv) ? $total_cv : 0;

// die($response->withJson($total_cv));


////////////////// Số lượng cần tuyển/////////////////////////
// $obj_request = DB::table($name);
$obj_request = DB::table('request');

$position_request_ids =[];
foreach ($ketqua->all() as $key => $value) {
   $position_request_ids[$value->position_id] = $value->position_id;
}
$obj_request->whereIn('position_id', $position_request_ids)->where([ 'request.isdelete' => 0]);
// die($response->withJson($position_request_ids));

// $obj_request->join('request', function ($join) {
//     $join->on('request.position_id', '=', 'cv.position_id');
//     $join->where([ 'request.isdelete' => 0]);
// });
$obj_request->join('positions', function ($join) {
    $join->on('positions.id', '=', 'request.position_id');
    $join->where(['positions.status' => 1, 'positions.isdelete' => 0]);
});

$obj_request->join('positions as parent', function ($join) {
    $join->on('parent.id', '=', 'positions.parent_id');
    $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
});
$obj_request->selectRaw('positions.title AS position_title, parent.title AS parent_title, request.*');
if (!empty($params['from']) && !empty($params['to'])) {
    $from = $params['from'];
    $to = $params['to'];
    $obj_request->where('date', '>=', $from)->where('date', '<=', $to);
}
if (!empty($params['assignee_id'])) {
    $assignee_id = explode('-', $params['assignee_id']);

    $obj_request->where(function ($query) use ($assignee_id) {
        foreach ($assignee_id as $key  => $id) {
            $query->orWhere('request.assignee_id', 'LIKE', "%$id%");
        }
    });
}
if (!empty($params['department_id'])) {
    $department_id = explode('-', $params['department_id']);

    $obj_request->where(function ($query) use ($department_id) {
        foreach ($department_id as $id) {
            $query->orWhere('positions.parent_id', 'LIKE', "%$id%");
        }
    });
}

// die($response->withJson($obj_request->get()));


$results = [
    'status' => 'success',
    'point' => $count_point,
    'summary' => $summary,
    'department' => $department,
    'data' => $obj_request ? $obj_request->get() : null,
    'total' => $obj_request ? $obj_request->count() : null,
    'time' => time(),
];































///////////////////////////////////////////////////////////////////////////
// use Illuminate\Database\Capsule\Manager as DB;

// error_reporting(E_ALL ^ E_NOTICE);

// $obj = DB::table($name);

// require './crud/all_where.php';

// $department = ['labels' => [], 'values' => []];

// $temp_department = [];

// $get_position_ids = $obj->get();

// $get_positions = DB::table('positions')->where(['isdelete' => 0, 'status' => 1])->get();

// $position_ids = [];

// foreach ($get_position_ids as $key => $value) {
//     foreach ($get_positions as $index => $l) {
//         if ($l->id == $value->position_id) {
//             $temp_department[$index] = $l->title;

//             $position_ids[$index] = $l->id;
//         }
//     }
// }

// foreach ($temp_department as $index => $value) {
//     $department['labels'][] = $value;
// }

// $position_request = clone $obj;

// $check_positions = $position_request->whereIn('cv.position_id', $position_ids)->where(['cv.isdelete' => 0])->get();

// $temp_values = [];

// foreach ($check_positions as $key => $value) {
//     if ($value->step > 8 && $value->status == 2) {
//         $temp_values[$value->position_id] = 0;
//     }
// }

// foreach ($check_positions as $key => $value) {
//     if ($value->step > 8 && $value->status == 2) {
//         $temp_values[$value->position_id]++;
//     }

//     if (!array_key_exists($value->position_id, $temp_values)) {
//         $temp_values[$value->position_id] = 0;
//     }
// }

// foreach ($temp_values as $key => $value) {
//     $department['values'][] = $value;
// }

// $all_level_positions = DB::table('level_positions')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

// $count_point = 0;

// foreach ($all_level_positions as $key => $value) {
//     foreach ($position_ids as $index => $k) {
//         if ($value->position_id == $k) {
//             $count_point += $value->point;
//         }
//     }
// }

// function getPosition($value)
// {
//     $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

//     if (!empty($position)) {
//         return $position->title;
//     }

//     return false;
// }

// $obj_step = clone $obj->get();

// $temp_list_cv_pass = [];

// foreach ($obj_step as $key => $value) {
//     if ($value->step > 5 && !empty(getPosition($value->position_id)) && $value->status == 2) {
//         $index = getPosition($value->position_id);

//         $temp_list_cv_pass[$index] = 0;
//     }
// }

// $list_cv_pass = [];

// foreach ($temp_list_cv_pass as $key => $value) {
//     $list_cv_pass[$key] = $value;
// }

// foreach ($obj_step as $key => $value) {
//     if ($value->step > 5 && !empty(getPosition($value->position_id)) && $value->status == 2) {
//         $index = getPosition($value->position_id);

//         array_key_exists($index, $list_cv_pass) ?  $list_cv_pass[$index]++ : 0;
//     }
// }

// $obj->selectRaw(' 
//     GROUP_CONCAT(cv.position_id) AS list_cv_new, 
//     GROUP_CONCAT(cv.step,\'\') AS list_cv_pass, 
//     SUM(target) AS target, 
//     SUM(total_cv) AS total_cv, 
//     SUM(interview_cv) AS interview_cv, 
//     SUM(pass_cv) AS pass_cv, 
//     SUM(offer_cv) AS offer_cv, 
//     SUM(offer_success) AS offer_success, 
//     SUM(onboard_cv) AS onboard_cv, 
//     SUM(fail_job) AS fail_job,
//     GROUP_CONCAT(target) as list_target, 
//     GROUP_CONCAT(total_cv) as list_total, 
//     GROUP_CONCAT(onboard_cv) as list_onboard,
//     GROUP_CONCAT(positions.title,\'\') as labels
// ');

// $summary = $obj->first();

// $list_target = explode(',', $summary->list_target);

// $list_total = explode(',', $summary->list_total);

// $list_onboard = explode(',', $summary->list_onboard);

// $labels = explode(',', $summary->labels);

// $newlabel = [];

// $newlist_target = [];

// $newlist_cv_pass = [];

// $newlist_cv_new = [];

// $newlist_total = [];

// $newlist_onboard = [];

// $newlist_cv_pass = [];

// foreach ($labels as $key => $label) {
//     if ($label) {
//         $newlist_cv_pass[$label] = 0;       
//     }
// }

// foreach ($labels as $key => $label) {
//     if ($label) {
//         $newlabel[$label] = $label;

//         $newlist_target[$label] = (!empty($newlist_target[$label]) ? $newlist_target[$label] : 0) + $list_target[$key];

//         $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $newlist_cv_pass[$label] + 1 : 0);

//         $newlist_cv_new[$label] = (!empty($newlist_cv_new[$label]) ? $newlist_cv_new[$label] : 0) + 1;

//         $newlist_total[$label] = (!empty($newlist_total[$label]) ? $newlist_total[$label] : 0) + $list_total[$key];

//         $newlist_onboard[$label] = (!empty($newlist_onboard[$label]) ? $newlist_onboard[$label] : 0) + $list_onboard[$key];
//     }
// }

// foreach ($newlist_cv_pass as $key => $item) {
//     foreach ($list_cv_pass as $index => $value) {
//         if ($key == $index && $value != 1) {
//             $newlist_cv_pass[$key] = $newlist_cv_pass[$key] - $list_cv_pass[$index];
//         }
//     }
// }

// $summary->labels = implode(',', array_keys($newlabel));

// $summary->list_target = implode(',', array_values($newlist_target));

// $summary->list_cv_pass = implode(',', array_values($newlist_cv_pass));

// $summary->list_cv_new = implode(',', array_values($newlist_cv_new));

// $summary->list_total = implode(',', array_values($newlist_total));

// $summary->list_onboard = implode(',', array_values($newlist_onboard));

// $results = [
//     'status' => 'success',
//     'point' => $count_point,
//     'summary' => $summary,
//     'department' => $department,
//     'data' => $ketqua ? $ketqua->all() : null,
//     'total' => $ketqua ? $ketqua->count() : null,
//     'time' => time(),
// ];
