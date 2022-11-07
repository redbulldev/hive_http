<?php

use Illuminate\Database\Capsule\Manager as DB;

if(!empty($params['level_id']))
{
    $idrole= explode('-',$params['level_id']);
    $obj->where(function($query) use ($idrole,$name){
        foreach ($idrole as $id) {
            $k1='"'.$id.'"';
            $k2=': '.$id.',';
            $k3=':'.$id.',';
            $k4=':'.$id.' ,';
            $query->orWhere($name.'.levels' , 'LIKE', "%$k1%")
            ->orWhere($name.'.levels' , 'LIKE', "%$k2%")
            ->orWhere($name.'.levels' , 'LIKE', "%$k3%")
            ->orWhere($name.'.levels' , 'LIKE', "%$k4%");
        }
    });
}

if (empty($params['month']) || empty($params['year'])) {
    $obj->groupBy('year')->groupBy('month')->whereIn('status', [2,4])
        ->selectRaw("year,month,SUM(target) AS target,SUM(total_cv) AS total_cv, SUM(interview_cv) AS interview_cv, SUM(pass_cv) AS pass_cv, SUM(offer_cv) AS offer_cv, SUM(offer_success) AS offer_success, SUM(onboard_cv) AS onboard_cv, SUM(fail_job) AS fail_job");
    if (!empty($params['from']) && !empty($params['to'])) {
        $from = $params['from'] . '-01';
        $to = $params['to'] . '-31';
        $obj->where('date', '>=', $from)->where('date', '<=', $to);
    }
    if (empty($permission->plan->all)) {
        $obj->where(function ($query) use ($user,$name) {
            $query->orWhere($name.'.author_id', $user->username);
            $query->orWhere($name.'.requestor_id', $user->username);
            $query->orWhere($name.'.decision_id', $user->username);
            $query->orWhere($name.'.assignee_id', $user->username);
        });
    }
    $orderby = [['name' => 'year', 'type' => 'DESC'], ['name' => 'month', 'type' => 'DESC']];
} else {
    $obj->whereIn('plan.status', [2, 4]);
    $obj->leftJoin('positions', 'positions.id', '=', $name.'.position_id')
        ->leftJoin('positions as department', 'department.id', '=', 'positions.parent_id')
        ->leftJoin('type_work', 'type_work.id', '=',  $name.'.typework_id');

    if (empty($permission->plan->all)) {
        $obj->where(function ($query) use ($user,$name) {
            $query->orWhere($name.'.author_id', $user->username);
            $query->orWhere($name.'.requestor_id', $user->username);
            $query->orWhere($name.'.decision_id', $user->username);
            $query->orWhere($name.'.assignee_id', $user->username);
        });
    }

    $moreselect = ['positions.title AS position_title', 'department.title AS department_title', 'type_work.title AS typework_title'];
}