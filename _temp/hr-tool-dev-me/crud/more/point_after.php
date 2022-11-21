<?php

use Illuminate\Database\Capsule\Manager as DB;

$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$lables = [];

$levels = [];

$get_positions = $obj->get(); 

function checkPosition($value){
	$check = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if (!empty($check))
	{
		return $check->id;
	}

	return false;
}

function getPosition($value){
	$position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if (!empty($position))
	{
		return $position->title;
	}

	return false;
}

function checkLevel($value){
	$check = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if (!empty($check)){
		return $check->id;
	}

	return false;
}

function getLevel($value)
{
	$level = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

	if (!empty($level))
	{
		return $level->title;
	}

	return false;
}

function checkLevelPosition($value)
{
	$check = DB::table('level_positions')->where('position_id', $value)->where([ 'isdelete' => 0])->where('position_id', '!=', 0)->first();

	if (!empty($check))
	{
		return $check->position_id;
	}

	return false;
}

foreach ($get_positions as $key => $value) 
{
	$lables[$value->id] = $value->title;
}

foreach ($get_levels as $index => $v) 
{
	$levels[$v->id] = $v->title;
}

$point_positions =[[[]]];

for($i=0;$i<count($level_positions);$i++) 
{
	if ($level_positions[$i]->level_id == checkLevel($level_positions[$i]->level_id) && $level_positions[$i]->position_id == checkPosition($level_positions[$i]->position_id)) 
	{
		$point_positions[getPosition($level_positions[$i]->position_id)][getLevel($level_positions[$i]->level_id)] = $level_positions[$i]->point;
	}
}

foreach($lables as $key => $lable)
{
	if(empty($point_positions[$lable]))
	{
		foreach($levels as $index => $level){
			if(empty($point_positions[$lable][$level]))
			{
				$point_positions[$lable][$level]= 1;
			}
		}
	}
	if(!empty($point_positions[$lable]))
	{
		foreach($levels as $index => $level){
			if(empty($point_positions[$lable][$level]))
			{
				$point_positions[$lable][$level] = 1;						
			}
		}
	}
}

// die($response->withJson($point_positions));


$results = [
    'status' => 'success',
    'data' => $point_positions ? $point_positions : null,
    'total' => $point_positions ? count($point_positions) : null,
    'time' => time(),
];





