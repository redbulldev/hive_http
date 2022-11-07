<?php
use Illuminate\Database\Capsule\Manager as DB;

// die($name); request
$obj = DB::table($name);

require './crud/all_where.php';   

$obj->selectRaw('
    SUM(target) AS target,
    SUM(total_cv) AS total_cv, 
    SUM(interview_cv) AS interview_cv, 
    SUM(pass_cv) AS pass_cv, 
    SUM(offer_cv) AS offer_cv, 
    SUM(offer_success) AS offer_success, 
    SUM(onboard_cv) AS onboard_cv, 
    SUM(fail_job) AS fail_job,
    GROUP_CONCAT(target) as list_target, 
    GROUP_CONCAT(total_cv) as list_total, 
    GROUP_CONCAT(onboard_cv) as list_onboard,
    GROUP_CONCAT(positions.title,\'\') as labels
');


// data - Summary //
// print_r($obj);
// die('ok');
$summary=$obj->first();
// print_r($summary);
// die('ok');

$list_target = explode(',',$summary->list_target);
$list_total = explode(',',$summary->list_total);
$list_onboard = explode(',',$summary->list_onboard);
$labels = explode(',',$summary->labels);
$newlabel=[];
$newlist_target=[];
$newlist_total=[];
$newlist_onboard=[];

foreach($labels as $key=>$label)
{
    if($label)
    {
        $newlabel[$label]= $label;
        $newlist_target[$label]= (!empty($newlist_target[$label])?$newlist_target[$label]:0) + $list_target[$key];
        $newlist_total[$label]= (!empty($newlist_total[$label])?$newlist_total[$label]:0) + $list_total[$key];
        $newlist_onboard[$label]= (!empty($newlist_onboard[$label])?$newlist_onboard[$label]:0) + $list_onboard[$key];
    }
}

$summary->labels = implode(',',array_keys($newlabel));
$summary->list_target =  implode(',',array_values($newlist_target));
$summary->list_total =  implode(',',array_values($newlist_total));
$summary->list_onboard =  implode(',',array_values($newlist_onboard));


// data - department //
$colors = ['#32E875','#FBB13C','#FF5D73','#8A84E2','#A3F4FF','#3495eb','#9e0211','#ad4731','#066917','#ded750','#f707c7'];

$department=['labels'=>[],'values'=>[],'colors'=>[]];

$all = DB::table('positions')->where(['isdelete'=>0,'status'=>1,'parent_id'=>0])->get();

// $obj1 = clone  $obj;
// print_r($obj1);
// die('ok');

foreach($all as $key=>$item)
{
    $obj1 = clone  $obj; //table request

    // lấy ra phòng ban và đếm số lượng
    // nếu 'positions.parent_id' = $item->id ở table positions thì đếm sum target ở table request
    $kq = $obj1->where('positions.parent_id',$item->id)->selectRaw('SUM(target) AS target')->first();
    
    $department['labels'][] = $item->title;
    $department['values'][] = $kq->target;
    $department['colors'][] = !empty($colors[$key])?$colors[$key]:'#000';
}

// return data
$results = [
    'status' => 'success',
    'summary' => $summary,
    'department' => $department,
    // variable $ketqua from table request intro file all.php
    'data' => $ketqua ? $ketqua->get() : null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];
//