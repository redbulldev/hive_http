<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->leftJoin('positions', 'positions.id', '=', 'request.position_id')
    ->leftJoin('positions as department', 'department.id', '=', 'positions.parent_id')
    ->leftJoin('type_work', 'type_work.id', '=', 'request.typework_id')
    ->leftJoin('company', 'company.id', '=', 'request.company_id');

if (!empty($params['level_id'])) {
    $idrole = explode('-', $params['level_id']);

    $obj->where(function ($query) use ($idrole) {
        foreach ($idrole as $id) {
            $k1 = '"' . $id . '"';
            $k2 = ': ' . $id . ',';
            $k3 = ':' . $id . ',';
            $k4 = ':' . $id . ' ,';
            $query->orWhere('request.levels', 'LIKE', "%$k1%")
                ->orWhere('request.levels', 'LIKE', "%$k2%")
                ->orWhere('request.levels', 'LIKE', "%$k3%")
                ->orWhere('request.levels', 'LIKE', "%$k4%");
        }
    });
}

if (!empty($params['cv']) && $params['cv'] === 'add') {
    $date = date('Y-m');
    $obj->where('request.date', '>=', $date . '-0');
    $obj->whereIn('request.status', [2, 4]);
}

if (empty($permission->request->all)) {
    $obj->where(function ($query) use ($user) {
        $query->orWhere('request.author_id', $user->username);
        $query->orWhere('request.requestor_id', $user->username);
        $query->orWhere('request.decision_id', $user->username);
        $query->orWhere('request.assignee_id', $user->username);
    });
}

$moreselect = ['positions.title AS position_title', 'department.title AS department_title', 'type_work.title AS typework_title', 'company.name AS company_name'];
