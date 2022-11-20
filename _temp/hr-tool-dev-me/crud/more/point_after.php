<?php

use Illuminate\Database\Capsule\Manager as DB;

$getLevels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where('isdelete', 0)->get();

$max_id =  DB::table('level')->max('id');

$lables = [];

$levels = [];

$values = ['values'=>[]];

$allPositions = $obj->get(); 

function checkPosition($value){
	$check = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if($check){
		return $check->id;
	}

	return false;
}
function getPosition($value){
	$check = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if($check){
		return $check->title;
	}

	return false;
}
function checkLevel($value){
	$check = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if($check){
		return $check->id;
	}

	return false;
}

function getLevel($value){
	$check = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if($check){
		return $check->title;
	}

	return false;
}

function checkLevelPosition($value){
	$check = DB::table('level_positions')->where('position_id', $value)->where([ 'isdelete' => 0])->first();

	if($check){
		return $check->position_id;
	}

	return false;
}

$count =0;

$temp_position_ids = [];

$lable_exists = [];

$lable_not_exists = [];

foreach ($allPositions as $key => $value) {
	$lables[$value->id] = $value->title;

	if($value->id == checkLevelPosition($value->id)){
		$temp_position_ids[$count] = checkLevelPosition($value->id);

		$lable_exists[$value->id] = $value->title;

		$count++;
	} else {
		$lable_not_exists[$value->id] = $value->title;
	}
}

// die($response->withJson($lable_exists));
// die($response->withJson($lable_not_exists));

$count_level = 0;
foreach ($getLevels as $index => $v) {
	$levels[$v->id] = $v->title;

	$count_level++;

	$level_ids[$index] = $v->id;
}

foreach ($level_positions as $index => $v) {
	$level_positions_ids[$index] = $v->id;
}
// print_r($level_positions_ids);die();


$count_point_exist = 0;

$l = ['intern', 'fresher', 'senior'];
$v = [1,5,2];
$tempp = array(
	'positions' => array(
		'levels' => array(),
		'values' => array(),
	)
);	

// $position =[[[]]];
// $test2=['lables'=>[]];
// $test3=['values'=>[]];
// $d=[];
for($i=0;$i<count($level_positions);$i++) {
	if ($level_positions[$i]->level_id == checkLevel($level_positions[$i]->level_id) && $level_positions[$i]->position_id == checkPosition($level_positions[$i]->position_id)) {
		$values['values'][$level_positions[$i]->id] = $level_positions[$i]->point;

		$tempp['positions'][][]=  getPosition($level_positions[$i]->position_id);

		$tempp['positions']['levels'][]=  getLevel($level_positions[$i]->level_id);
		$tempp['positions']['values'][] = $level_positions[$i]->point;
		// foreach($tempp['levels'] as $key => $level) {
		// 	$tempp['levels'][$key] =  getLevel($level_positions[$i]->level_id);
		// }

		// foreach($tempp['values'] as $key => $value){
		// 	$tempp['values'][$key] = $level_positions[$i]->point;
		// }

		// $position[getPosition($level_positions[$i]->position_id)][getLevel($level_positions[$i]->level_id)][$level_positions[$i]->level_id] = $level_positions[$i]->point;
		// $test2['lables'][] = getLevel($level_positions[$i]->level_id);
		// $test3['values'][] =  $level_positions[$i]->point;
	}
}
die($response->withJson($tempp));

// foreach($position as $p => $level) {
// 	foreach($level as $l => $point){
// 		foreach($point as $p => $id){
// 			foreach ($level_ids as $i => $l) {
// 				if($l != $p){
// 					$point[$i] = 1;
// 					array_push($point, 1);
// 				}
// 			}
// 		}
// 	}
// }
// die();
// $test_temp = array_merge( $test2, $test3);
// foreach($tempp['levels'] as $p => $level) {
// 	print_r($level);
// }
// die();

//
$summary_exists = [array_flip($lable_exists)];

// foreach ($level_positions_ids as $index => $v) {
foreach ($level_ids as $index => $v) {
	if(empty($values[$v])){
		$values[$v] = 1;
	}
}
// ksort($values);

foreach($summary_exists as $item) {
	foreach($item as $key => $value)
	{					
		$summary_exists[$key] = array($levels, $values);	
	}
}

// die($response->withJson($summary_exists));






// //
$value_not_exists = [];

for ($i=0; $i < $count_level; $i++) { 
	$value_not_exists[$i] = 1;
}

$summary_not_exists = [array_flip($lable_not_exists)];

foreach($summary_not_exists as $test) {
	foreach($test as $key => $value)
	{					
		$summary_not_exists[$key] = array($levels, $value_not_exists);
	}
}

$summaries = array_merge($summary_not_exists, $summary_exists);

$results = [
    'status' => 'success',
    'summary_exists' => $summaries,
    // 'summary_not_exists' => $summary_not_exists,
    // 'data' => $ketqua ? $ketqua->all() : null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];







