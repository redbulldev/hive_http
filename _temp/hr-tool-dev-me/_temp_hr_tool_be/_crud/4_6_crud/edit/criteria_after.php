<?php

use Illuminate\Database\Capsule\Manager as DB;

require(__DIR__ . '/../condition/criteria_condition.php');

function countEriteriaElementsOldOrNew($criteria_elements, $check)
{
    $count_old = 0;

    $count_new = 0;

    $count = count((array)$criteria_elements);

    for ($i = 0; $i < $count; $i++) {
        if (!empty($criteria_elements->{$i}->id_criteria)) {
            $count_old++;
        }
    }

    if ($count_old && $check === 'old') {
        return $count_old;
    }

    if ($check === 'new') {
        $count_all = count((array)$criteria_elements);

        if ($count_old) {
            if ($count_all != $count_old) {
                if ($count_all > $count_old) {
                    $count_new = $count_all - $count_old;
                }
            }

            if ($count_new) {
                return $count_new;
            }
        } else if ($count_all) {
            return $count_all;
        }
    }

    return false;
}

function checkExsitItemNewWithOld($data, $exsit)
{
    $list_new = criteriaElementIsNewAndOld($data, 'new');

    $list_old = criteriaElementIsNewAndOld($data, 'old');

    $name_exsit = [];

    $index_exsit = [];

    if ($list_new && $list_old) {
        for ($i = 0; $i < count($list_new); $i++) {
            for ($j = 0; $j < count($list_old); $j++) {
                $check =  strcmp($list_old[$i], $list_new[$j]);

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
}

function getExsitOnItemOld($data, $exsit)
{
    $list_old = criteriaElementIsNewAndOld($data, 'old');

    $name_exsit = [];

    $index_exsit = [];

    $duplicate_values = array_count_values($list_old);

    foreach ($duplicate_values as $key => $value) {
        if ($duplicate_values[$key] > 1) {
            array_push($name_exsit, $key);

            array_push($index_exsit, $duplicate_values[$key]);
        }
    }

    if (count($name_exsit) && $exsit === 'name') {
        return array_unique($name_exsit);
    }

    if (count($index_exsit) && $exsit === 'index') {
        return array_unique($index_exsit);
    }

    return false;
}

function callToGetExsitOfElement($data, $exsit)
{
    if (checkExsitItemNewWithOld($data, $exsit)) {
        return checkExsitItemNewWithOld($data, $exsit);
    } else if (countEriteriaElementsOldOrNew($data->criteria_elements, 'old')) {
        return getExsitOnItemOld($data, $exsit);
    }

    return false;
}

function updateCriteriaElements($data)
{
    for ($i = 0; $i < count((array)$data->criteria_elements); $i++) {
        if (!empty($data->criteria_elements->{$i}->id_element) && !empty($data->criteria_elements->{$i}->id_criteria)) {
            DB::table('criteria_elements')
                ->where('id', $data->criteria_elements->{$i}->id_element)
                ->where('id_criteria', $data->criteria_elements->{$i}->id_criteria)
                ->update([
                    'name' => $data->criteria_elements->{$i}->name
                ]);
        }
    }

    return;
}

function insertOrUpdateCriteriaElements($data, $id)
{
    $new = countEriteriaElementsOldOrNew($data->criteria_elements, 'new');

    $old = countEriteriaElementsOldOrNew($data->criteria_elements, 'old');

    if ($new && $old) {
        updateCriteriaElements($data);

        insertCriteriaElements($data, $id);
    } else if ($old) {
        updateCriteriaElements($data);
    } else if ($new) {
        insertCriteriaElements($data, $id);
    }

    return;
}

$count_of_null = 0;

$count_of_exsit = 0;

function isErrorNull($name_null)
{
    $index_list = array_values($name_null);

    $index_of_name_null_str = '';

    foreach ($index_list as $key => $value) {
        $index_of_name_null_str .= ', ' . $value;
    }

    return $index_of_name_null_str;
}

$is_exsit_error = 0;

$name_str = '';

$index_str = '';

if (!empty($data->criteria_elements)) {
    $name_null = checkEmptyOfElement($data);

    $count_criteria_elements = count((array)$data->criteria_elements);

    $name_exsit = callToGetExsitOfElement($data, 'name');

    $index_exsit = callToGetExsitOfElement($data, 'index');

    if ($name_null) {
        $count_of_null++;

        $is_exsit_error++;

        $index_of_name_null_str = isErrorNull($name_null);
    }

    if ($name_exsit && $index_exsit) {
        $name_list = array_values($name_exsit);

        $index_list = array_values($index_exsit);

        foreach ($name_list as $key => $value) {
            $name_str .= ', ' . $value;
        }

        foreach ($index_list as $key => $value) {
            $index_str .= ', ' . $value;
        }

        $count_of_exsit++;

        $is_exsit_error++;
    } else {
        $count_new = countEriteriaElementsOldOrNew($data->criteria_elements, 'new');

        if ($count_new) {
            $name_exsit = getExsitOnItemNew($data, 'name');

            $index_exsit = getExsitOnItemNew($data, 'index');

            if ($name_exsit && $index_exsit) {
                $name_list = array_values($name_exsit);

                $index_list = array_values($index_exsit);

                foreach ($name_list as $key => $value) {
                    $name_str .= ', ' . $value;
                }

                foreach ($index_list as $key => $value) {
                    $index_str .= ', ' . $value;
                }

                $count_of_exsit++;

                $is_exsit_error++;
            }
        }
    }

    if ($is_exsit_error === 0) {
        insertOrUpdateCriteriaElements($data, $id);
    }
}

if ($count_of_exsit || $count_of_null) {
    if ($count_of_exsit) {
        $results = [
            'status' => 'false',
            'same_value' => ltrim($name_str, ","),
            'index' => ltrim($index_str, ","),
            'data' => 'Same value!',
            'time' => time()
        ];

        echo json_encode($results);

        die();
    }

    if ($count_of_null) {
        $results = [
            'status' => 'false',
            'index' => ltrim($index_of_name_null_str, ","),
            'data' => 'Value is empty!',
            'time' => time()
        ];

        echo json_encode($results);

        die();
    }
}
