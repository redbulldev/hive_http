<?php

use Illuminate\Database\Capsule\Manager as DB;

$parent_id =  $data->template_id;

$template_request = DB::table('template_request')
    ->where('id', trim($parent_id))
    ->where('isdelete', 0)
    ->first();

if(!empty($template_request)){
    $status = DB::table('template_request')->insertGetId([
        "author_id" => $user->username,
        "id_criteria_group" => $template_request->id_criteria_group,
        "name" => $id.'-'.$template_request->name,
        "parent_id" => $template_request->id
    ]);

    DB::table('request')->where('id', $id)->update(['template_id' => $status]); 

    if(!empty($status)){
        $criterias = DB::table('template_request')
            ->leftJoin('template_criteria', 'template_criteria.id_template', 'template_request.id')
            ->leftJoin('criteria', 'criteria.id', 'template_criteria.id_criteria')
            ->leftJoin('criteria_elements', 'criteria.id', 'criteria_elements.id_criteria')
            ->where('template_request.id', trim($parent_id))
            ->where('template_request.isdelete', 0)
            ->distinct()
            ->get(['template_request.id as id_template', 'criteria.id as id_criteria']);

        if (!empty($criterias) && count($criterias) > 0) {
            foreach ($criterias as $value) {
                $pradd = [
                    'id_template' => $status, 
                    'id_criteria' => $value->id_criteria
                ];

                DB::table('template_criteria')->insert($pradd);
            }
        }
    }
}

/*
{
    "id": 1,
    "author_id": "hungnv1",
    "id_criteria_group": 1,
    "name": "test-ssss",
    "parent_id": 0
}
*/