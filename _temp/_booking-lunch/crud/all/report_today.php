<?php

use Illuminate\Database\Capsule\Manager as DB;

$disableLimit = true;
if (!empty($params['store_id'])) {
    $store = explode('-', $params['store_id']);
}
$type='date';
$limit = 7;
if (!empty($params['type'])) {
    if($params['type']=='month') {
        $type = "DATE_FORMAT(date, '%m/%Y') ";
        $limit = 12;
    } else  if ($params['type'] == 'week') {
        $type = "DATE_FORMAT(date, '%v/%Y') ";
        $limit = 12;
    }
}


$objthisday = DB::table('history')->select([DB::raw('count(*) as total'), DB::raw('SUM(price) as price')])->where('booked', 1)
    ->where('date', '=', date('Y-m-d', time()));
if (!empty($store) && count($store)) {
    $objthisday->whereIn('store_id', $store);
}
$moreresults['thisday'] = $objthisday->get()->toArray();


$objthisweek = DB::table('history')->select([DB::raw('count(*) as total'), DB::raw('SUM(price) as price')])->where('booked', 1)
    ->where('date', '>=', date('Y-m-d', strtotime('monday this week')))->where('date', '<=', date('Y-m-d', strtotime('sunday this week')));

if (!empty($store) && count($store)) {
    $objthisweek->whereIn('store_id', $store);
}

$moreresults['thisweek'] = $objthisweek->get()->toArray();

$from = time();
$to = time();
if (!empty($params['from']) && !empty($params['to'])) {
    $from = strtotime($params['from']);
    $to = strtotime($params['to']);
}

$objdata = DB::table('history')->select([DB::raw($type." as date"), DB::raw('IF(booked=0, "Không đặt", menu) as menu'), 'menu_id', 'booked', DB::raw('count(*) as total'), DB::raw('SUM(price) as price')],)
    ->groupBy(DB::raw($type), 'menu', 'menu_id', 'booked');
    
if (!empty($params['from']) && !empty($params['to'])) {
    $objdata->where('date', '>=', date('Y-m-d', $from))->where('date', '<=', date('Y-m-d', $to));
} else {
    $from = $to - 7 * 24 * 60 * 60;
    $fromdata = $to - 0 * 24 * 60 * 60;
    $objdata->where('date', '>=', date('Y-m-d', $fromdata))->where('date', '<=', date('Y-m-d'));
}

if (!empty($store) && count($store)) {
    $objdata->whereIn('store_id', $store);
}
$moreresults['data'] = $objdata->orderBy('date', 'DESC')->get()->toArray();

$moreresults['total'] = count($moreresults['data']);



$objweeks = DB::table('history')->select([DB::raw($type . " as date"), DB::raw('count(*) as total'), DB::raw('SUM(price) as price')])->groupBy(DB::raw($type))->where(['booked' => 1]);
if (!empty($params['from']) && !empty($params['to'])) {
    $objweeks->where('date', '>=', date('Y-m-d', $from))->where('date', '<=', date('Y-m-d', $to));
}else{
    $objweeks->where('date', '<=', date('Y-m-d'));
}
if (!empty($store) && count($store)) {
    $objweeks->whereIn('store_id', $store);
}
$chart = $objweeks->orderBy('date', 'DESC')->limit($limit)->get()->toArray();
//krsort($chart);
$moreresults['chart'] = $chart;
