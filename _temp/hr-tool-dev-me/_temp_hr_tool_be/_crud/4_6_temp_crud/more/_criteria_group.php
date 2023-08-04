<?php

use Illuminate\Database\Capsule\Manager as DB;

$criteria = DB::select(
    DB::raw("
        SELECT 
            criteria_group.*, 
            GROUP_CONCAT(criteria.name ORDER BY criteria_group.id) AS criteria_name
        FROM 
            criteria_group
        LEFT JOIN 
            criteria_has_group ON criteria_group.id = criteria_has_group.id_criteria_group
        LEFT JOIN 
            criteria ON criteria.id = criteria_has_group.id_criteria
        WHERE 
            criteria_group.isdelete = 0
        GROUP BY 
            criteria_group.name
        ORDER BY 
            criteria_group.id DESC
    ")
);

$collection = collect($criteria);

$totalCount = count($collection);

$paginator = new \Illuminate\Pagination\LengthAwarePaginator($collection->forPage($page, $limit), $totalCount, $limit, $page);

$results = [
    'status' => 'success',
    'data' => $paginator ? array_values($paginator->all()) : null,
    'total' => !empty($criteria) ? count($criteria) : null,
    'time' => time(),
];
