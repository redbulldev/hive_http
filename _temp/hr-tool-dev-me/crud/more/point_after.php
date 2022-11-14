<?php

use Illuminate\Database\Capsule\Manager as DB;



$getLevels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

// die($response->withJson($levels->get()));


$lables = [];

$levels = [];

$values = [];

$allPositions = $obj->get(); 

foreach ($allPositions as $key => $value) {
	$lables[$key] = $value->title;
	foreach ($getLevels as $index => $v) {
		$levels[$index] = $v->title;
		$values[$index] = $v->point;
	}
}

// $summaries = array(
// 		'positions' => array($lables),
// 		'levels' => array($levels),
// 		'values' => array($values)

// );

// $positionTitles = array(
// 		'positions' => array($lables),
// );

$data_summaries = [array_flip($lables)];

foreach($data_summaries as $test) {
	foreach($test as $key => $value)
	{
		$data_summaries[$key] = array($levels, $values);
	}
}

// die($response->withJson($data_summaries));


// foreach ($summaries['lables']['position'] as $value) {
// 	echo $value;
// }

// foreach($summaries as $book) {
// 	echo "<br>";
// 	foreach($book['positions'] as $key => $value)
// 	{
// 		echo $value. "<br>";
// 	}
// }
// die();
// die($response->withJson($summaries));

// echo count($levels);
// die();



$results = [
    'status' => 'success',
    'summary' => $data_summaries,
    // 'department' => $department,
    'data' => $ketqua ? $ketqua->all() : null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];