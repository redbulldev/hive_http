<?php

use Illuminate\Database\Capsule\Manager as DB;

require(__DIR__ . '/../condition/criteria_condition.php');

// add criteria on request
if (!empty($data->id_criteria_group)) {
    if (!DB::table('criteria_group')->where('id', $data->id_criteria_group)->where('isdelete', 0)->count()) {
        throw new Exception('Criteria not exist');
    }

    $pradd = ['id_criteria_group' => $data->id_criteria_group, 'id_criteria' => $id];

    DB::table('criteria_has_group')->insert($pradd);
}


$count_of_null = 0;

$count_of_exsit = 0;

$name_str = '';

$index_str = '';

$index_of_name_null_str = '';

if (!empty($data->criteria_elements)) {
    $name_null = checkEmptyOfElement($data);

    if ($name_null) {
        $count_of_null++;

        $index_list = array_values($name_null);

        foreach ($index_list as $key => $value) {
            $index_of_name_null_str .= ', ' . $value;
        }
    } else {
        $name_exsit = getExsitOnItemNew($data, 'name');

        $index_exsit = getExsitOnItemNew($data, 'index');

        if ($name_exsit || $index_exsit) {
            $name_list = array_values($name_exsit);

            $index_list = array_values($index_exsit);

            foreach ($name_list as $key => $value) {
                $name_str .= ', ' . $value;
            }

            foreach ($index_list as $key => $value) {
                $index_str .= ', ' . $value;
            }

            $count_of_exsit++;
        } else {
            insertCriteriaElements($data, $id);
        }
    }
}

if ($count_of_exsit || $count_of_null) {
    if ($count_of_exsit) {
        $results = [
            'status' => 'false',
            'same_value' => ltrim($name_str, ","),
            'index' => ltrim($index_str, ","),
            'data' => 'same value!',
            'time' => time()
        ];

        echo json_encode($results);

        die();
    }

    if ($count_of_null) {
        $results = [
            'status' => 'false',
            'index' => $index_of_name_null_str,
            'data' => 'Value is empty!',
            'time' => time()
        ];

        echo json_encode($results);

        die();
    }
}
