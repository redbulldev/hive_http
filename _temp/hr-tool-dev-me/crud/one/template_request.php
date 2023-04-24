<?php 

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

// echo $id;die();

// $one = DB::table('template_request')
//         ->leftJoin('template_criteria', 'template_criteria.id_template', '=', 'template_request.id')
//         ->leftJoin('criteria', 'template_criteria.id_criteria', '=', 'criteria.id')
//         ->where('template_request.id', $id)
//         ->get();

// $query = DB::table('template_request')
//         ->leftJoin('template_criteria', 'template_criteria.id_template', '=', 'template_request.id', function ($join) {
//             $join->on('criteria', 'criteria.id', '=', 'template_criteria.id__criteria');
//         })->get();

// $one =$query;

// $list  = DB::table('template_request')->where('id', $id);

// $list->leftJoin('template_criteria', function($join){
//     $join->on('template_criteria.id_template', '=', 'template_request.id', function($join){
//         $join->leftJoin('criteria', function($join){
//             $join->on('template_criteria.id_criteria', '=', 'criteria.id')->select("*")->get();
//         });
//     });
// });

// foreach ($list as $key => $item) {
//     foreach ($item as $index => $value) {
//         echo $value->name;
//         print_r($value->name);
//         // echo $
//     }
// }
// print_r($list);

// $one =$list;

// $obj->join('positions', function ($join) {
//     $join->on('positions.id', '=', 'request.position_id');
//     $join->where(['positions.status'=>1, 'positions.isdelete'=>0]);
// });

// print_r($obj);die();




/////////////////////////////////////////////////////////
// template_request
//     criteria
//         template_criteria

$template_request = DB::table('template_request')
    ->where('id', $id)
    ->first();

$template_criteria = DB::table('template_criteria')
    ->where('id_template', $template_request->id)
    ->get();

$arr_template_request = [];
$arr_template_criteria = [];
$arr_res = [];
array_push($arr_template_request, $template_request);
array_push($arr_res, $template_request);

$arr_template = [];

$criteria = DB::table('criteria')
    ->select('criteria.*')->get();

$criteria_elements = DB::table('criteria_elements')
    ->select('criteria_elements.*')->get();

foreach ($template_criteria as $key => $i) {
    if($arr_template_request[0]->id === $i->id_template){
        foreach ($criteria as $key => $j) {
            if($i->id_criteria === $j->id){
                $arr_template_request['templates'][$key] = $criteria[$key];     
                $arr_template[$key] = $criteria[$key];  
            }        
        }
    }
}

foreach ($arr_template_request as $key => $item) {
    foreach ($item as $index => $value) {
        foreach ($criteria_elements as $index => $l) {
            if($l->id_criteria === $value->id){
                $arr_template_request['templates']['elements'][$index] = $criteria_elements[$index];
            }
        }
    } 
}

$count = 0;
$arr_criteria = [];
$arr_criteria_elements = [];

foreach ($arr_template_request['templates'] as $index => $value) {
    if(is_int($value->id)){
        $arr_criteria[$count] = $value;
        $count++;
    }
}

$count = 0;

foreach ($arr_template_request['templates']['elements'] as $index => $value) {
    $arr_criteria_elements[$count] = $value;
    $count++;
}
for ($i=0; $i < count($arr_criteria); $i++) { 
    for ($j=0; $j < count($arr_criteria_elements); $j++) { 
        if($arr_criteria[$i]->id ===  $arr_criteria_elements[$j]->id_criteria){
            $arr_criteria[$i]->{$j} = $arr_criteria_elements[$j];
        }
    }
}

$arr_res['criteria'] = $arr_criteria;

$one = $arr_res;

// die();