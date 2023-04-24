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

$results = [
    'status' => 'success',
    'data' => count($criteria) ? $criteria : null,
    'total' => !empty($criteria) ? count($criteria) : null,
    'time' => time(),
];

/*
$criteria = $obj->get();

$criteria_elements =  DB::table('criteria_elements')->get();

$arr_criteria = [];

$arr_criteria_elements = [];

foreach ($criteria as $key => $value) {
    $arr_criteria[$key] = $value;
}

for ($i=0; $i < count($criteria); $i++) { 
    for ($j=0; $j < count($criteria_elements); $j++) { 
        if($arr_criteria[$i]->id ===  $criteria_elements[$j]->id_criteria){
            $arr_criteria[$i]->{$j} = $criteria_elements[$j];
        }
    }
   
}

$results = [
    'status' => 'success',
    'data' => count($arr_criteria) ? $arr_criteria : null,
    'total' => !empty($arr_criteria) ? count($arr_criteria) : null,
    'time' => time(),
];
*/
