<?php

use Illuminate\Database\Capsule\Manager as DB;

function checkEmptyOfElement($data)
{
    $keys = [];

    for ($i = 0; $i < count((array)$data->criteria_elements); $i++) {
        if (empty($data->criteria_elements->{$i}->name)) {
            array_push($keys, $i);
        }
    }

    if (count($keys)) {
        return $keys;
    }

    return false;
}

function criteriaElementIsNewAndOld($data, $exsit)
{
    $arr_name_new = [];

    $arr_name_old = [];

    for ($i = 0; $i < count((array)$data->criteria_elements); $i++) {
        if (empty($data->criteria_elements->{$i}->id_criteria) && $exsit === 'new') {
            array_push($arr_name_new, trim($data->criteria_elements->{$i}->name));
        }

        if (!empty($data->criteria_elements->{$i}->id_criteria) && $exsit === 'old') {
            array_push($arr_name_old, trim($data->criteria_elements->{$i}->name));
        }
    }

    if (count($arr_name_new) && $exsit === 'new') {
        return $arr_name_new;
    }

    if (count($arr_name_old) && $exsit === 'old') {
        return $arr_name_old;
    }

    return false;
}


function getExsitOnItemNew($data, $exsit)
{
    $list_new = criteriaElementIsNewAndOld($data, 'new');

    $name_exsit = [];

    $index_exsit = [];

    if ($list_new) {
        for ($i = 1; $i < count($list_new); $i++) {
            for ($j = 0; $j < $i; $j++) {
                $check =  strcmp($list_new[$i], $list_new[$j]);

                if ($check === 0 && $exsit === 'name') {
                    array_push($name_exsit, $list_new[$j]);
                }

                if ($check === 0 && $exsit === 'index') {
                    array_push($index_exsit, $i);
                }
            }
        }
    }

    if (count($name_exsit) && $exsit === 'name') {
        return array_unique($name_exsit);
    }

    if (count($index_exsit) && $exsit === 'index') {
        return array_unique($index_exsit);
    }

    return false;

    /*
        $name_exsit = [];

        $duplicate_values = array_count_values($array);

        for ($i = 0; $i < count(array_count_values($array)); $i++) {
            if($duplicate_values[$i] > 1){
                array_push($name_exsit, $duplicate_values[$i]);
            }

        }
    */
}

function insertCriteriaElements($data, $id)
{
    for ($i = 0; $i < count((array)$data->criteria_elements); $i++) {
        if (empty($data->criteria_elements->{$i}->id_element) && empty($data->criteria_elements->{$i}->id_criteria)) {
            DB::table('criteria_elements')->insert([
                'name' => $data->criteria_elements->{$i}->name,
                'id_criteria' => $id
            ]);
        }
    }

    return;
}
