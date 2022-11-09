<?php
// require './shared/where.php';
if (file_exists(__DIR__ . '/more/' . $file . '.php'))
    require(__DIR__ . '/more/' . $file . '.php');

// die($file); //dashboard

if (isset($params['orderby'])) {
    $arrmtob = explode('__', $params['orderby']);
    foreach ($arrmtob as $ob) {
        $arrob = explode('-', $ob);
        if (count($arrob) == 2){
            //$obj->orderBy($name . '.' . $arrob[0], $arrob[1]);
            $obj->orderBy($arrob[0], $arrob[1]);
        }
    }
} else if (isset($orderby)) {
    foreach ($orderby as $ob) {
        $obj->orderBy($ob['name'], $ob['type']);
    }
}else $obj->orderBy($name . '.'.$columnorb, 'DESC');

if ($key != '') {
    if(empty($listKeySearch))
        $obj = findby($name, $obj, $key);
    else{
        $obj = findby($name, $obj, $key, $listKeySearch);
    }
}
if ($daterange != '') {
    $arrdate = explode('-', $daterange);

    if (count($arrdate) == 2) {
        $begin = $arrdate[0];
        $end = $arrdate[1];
        if ($begin > 0 && $end > 0) {
            $obj->where($name . '.datecreate', '>=', $begin)->where($name . '.datecreate', '<=', $end);
        }
    }
    
}