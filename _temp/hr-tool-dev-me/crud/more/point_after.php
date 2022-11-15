<?php

use Illuminate\Database\Capsule\Manager as DB;



// $getLevels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();
// // die($response->withJson($levels->get()));
// $level_positions = DB::table('level_positions as lp')->where('isdelete', 0)->get();


// $lables = [];

// $levels = [];

// $values = [];

// $allPositions = $obj->get(); 


// foreach ($allPositions as $key => $value) {
// 	$lables[$key] = $value->title;
// }

// function dataposition($value){
// // print_r($value);die();

// 	$check = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();
// // die($check);
// 	if($check){
// 				// die('ok');

// 		return $check->id;
// 	}

// 	return false;
// }
// // foreach ($allPositions as $key => $value) {
// // 	$lables[$key] = $value->title;
// 	foreach ($getLevels as $index => $v) {
// 		$check = false;

// 			 // print_r($level_positions[$3]->point);
// 			 // die();
// 		$levels[$v->id] = $v->title;
// 		for($i=0;$i<count($level_positions);$i++) {
// 			if ($level_positions[$i]->level_id == $v->id && $level_positions[$i]->position_id == dataposition($level_positions[$i]->position_id)) {//$value->id
// 			 // print_r($level_positions[$i]->level_id);
// 			 	$values[$level_positions[$i]->level_id ] = $level_positions[$i]->point;
// 				// die('ok');
// 				$check=true;


// 				$data_summaries = [array_flip($lables)];
// 				foreach($data_summaries as $test) {
// 						foreach($test as $key => $value)
// 						{
// 							// print_r($value);die();
// 							if($level_positions[$i]->position_id == $value){
// 								$data_summaries[$key] = array($levels, $values);
// 							}
// 						}
// 					}
// 				}
		
// 		}
		
// 		if($check ==false){
// 			$values[$v->id] = 1;
// 				$data_summaries = [array_flip($lables)];

// 			foreach($data_summaries as $test) {
// 						foreach($test as $key => $value)
// 						{
// 							// print_r($value);die();
// 							// if($level_positions[$i]->position_id == $value){
// 								$data_summaries[$key] = array($levels, $values);
// 							// }
// 						}
// 					}
// 			}
// 	}
// // }
// // die();

// // $data_summaries = [array_flip($lables)];

// // foreach($data_summaries as $test) {
// // 	foreach($test as $key => $value)
// // 	{
// // 		$data_summaries[$key] = array($levels, $values);
// // 	}
// // }

// // $data_summaries = [array_flip($lables)];

// // foreach($data_summaries as $test) {
// // 	foreach($test as $key => $v)
// // 	{
// // 		// $data_summaries[$key] = array($levels, $values);
// // 		foreach ($levels as $index => $l) {
// // 			$check = false;
// // 			foreach ($values as $i => $k) {
// // 				if($index == $i){
// // 					$values[$index] == $k;
// // 					$check=true;
// // 				}
// // 			}
// // 			if($check ==false){
// // 				$values[$index] = 1;
// // 			}
// // 		}
// // 		// $data_summaries[$key] = array($levels, $values);

// // 	}
// // }




// // $data_summaries = [array_flip($lables)];

// // foreach($data_summaries as $test) {
// // 	foreach($test as $key => $value)
// // 	{
// // 		$data_summaries[$key] = array($levels, $values);
// // 	}
// // }
// die($response->withJson($data_summaries));











$all_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$all_level_positions = DB::table('level_positions as lp')->where('isdelete', 0)->get();


$results = [
    'status' => 'success',
    'positions' =>  $ketqua->all(),
    'levels' => $all_levels,
    'levelpositions' => $all_level_positions,
    // 'department' => $department,
    // 'data' => $ketqua ? $ketqua->all() : null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];