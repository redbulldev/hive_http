<?php

use Illuminate\Database\Capsule\Manager as DB;

$criteria = DB::select(
    DB::raw("
        SELECT 
            q.*, 
            GROUP_CONCAT(a.name ORDER BY q.id) AS elements_name
        FROM 
            criteria q
        LEFT JOIN 
            criteria_elements a ON q.id = a.id_criteria
        WHERE 
            a.isdelete = 0 
            AND 
            q.isdelete = 0
        GROUP BY 
            q.name
        ORDER BY 
            q.id DESC
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
