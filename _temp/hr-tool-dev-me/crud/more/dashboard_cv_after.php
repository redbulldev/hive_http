<?php
use Illuminate\Database\Capsule\Manager as DB;

// die($name); //cv
$obj = DB::table($name);

require './crud/all_where.php';   


////////////////////////////////////////////////////////////////////////
// *Bảng xếp hạng số lượng đã tuyển //
// data - department //
$department = ['labels'=>[],'values'=>[]]; 

$dataPositionId = $obj->get();
// die($response->withJson($dataPositionId));

$allPositions = DB::table('positions')->where(['isdelete'=>0,'status'=>1])->get(); 

$positionId = [];

foreach($dataPositionId as $key => $value) {
    foreach($allPositions as $index => $l) {
        if($l->id == $value->position_id){
            $department['labels'][$l->id] = $l->title;   
            $positionId[$index] = $l->id;
        }
    }
}

// $allCvs = DB::table('cv')->select(DB::raw('count(*), position_id'))->where('isdelete', 0)->groupBy('position_id')->get();
// die($response->withJson($positionId));

foreach($positionId as $key => $k)
{
    $obj1 = clone  $obj; //request

    // $res = $obj1->where('request.position_id',$k)->selectRaw('count(cv.step) AS total_cv')->where('cv.step', '>', 5)->first();
    $res = $obj1->where('cv.position_id',$k)->selectRaw('count(cv.step) AS total_cv')->where('cv.step', '>', 8)->first();//->groupBy('cv.position_id')
// die($response->withJson($res));

    $department['values'][$k] = $res->total_cv; //yêu cầu (số lượng)
}
// die($response->withJson($dataPositionId));
// // print_r($department);
// die();

/////////////////////////////////////////
// Tổng điểm
$all_level_positions = DB::table('level_positions')->where(['isdelete'=>0])->where('position_id', '!=', 0)->get(); 

$count_point = 0;
foreach ($all_level_positions as $key => $value) {
    foreach($positionId as $index => $k) {
        if ($value->position_id == $k) {
            // echo $value->id.';' ;
            $count_point += $value->point;
        }
    }
}
// print_r($positionId);
// echo $count_point;
// die();










//////////////////////////////////////////////////////////////////////
$obj_step = clone $obj;

function getPosition($value)
{
    $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}

$list_step = [];
foreach ($obj_step->get() as $key => $value) {
    // echo $value->step.';';
    if($value->step > 5){
        if (!empty(getPosition($value->position_id))) {
            $list_step[getPosition($value->position_id)] = $value->step;
        }    
    }
}
// die($response->withJson($obj_step->get()));
die($response->withJson($list_step));





// $objRequest = DB::table('request');
// data summary
// $test = $obj->where('step', 5)->get();
// die($test);
$obj->selectRaw(' 
    GROUP_CONCAT(cv.step,\'\') AS steps, 
    SUM(target) AS target, 
    SUM(total_cv) AS total_cv, 
    SUM(interview_cv) AS interview_cv, 
    SUM(pass_cv) AS pass_cv, 
    SUM(offer_cv) AS offer_cv, 
    SUM(offer_success) AS offer_success, 
    SUM(onboard_cv) AS onboard_cv, 
    SUM(fail_job) AS fail_job,
    GROUP_CONCAT(target) as list_target, 
    GROUP_CONCAT(pass_cv) as list_pass, 
    GROUP_CONCAT(total_cv) as list_total, 
    GROUP_CONCAT(onboard_cv) as list_onboard,
    GROUP_CONCAT(positions.title,\'\') as labels
');//->where('cv.step', '>', 5);


// $check_step = $obj->whereRaw('cv.step', '>', 5)->get();

$summary=$obj->first();
// die($response->withJson($summary));

$list_target = explode(',',$summary->list_target);

$list_pass = explode(',',$summary->list_pass);

// $list_step = explode(',',$summary->steps);

$list_total = explode(',',$summary->list_total);

$list_onboard = explode(',',$summary->list_onboard);

$labels = explode(',',$summary->labels);

$newlabel=[];

$newlist_target=[];

$newlist_pass=[];

$newlist_total=[];

$newlist_onboard=[];


$newlist_step=[];
// foreach($list_step as $key=>$value)
// {
//     if($value>5){
//         $newlist_step[$value] = (!empty($newlist_step[$value])?$newlist_step[$value]:0) + 1;
//         echo $newlist_step[$value].';';
//     }
// }
// die();

foreach($labels as $key=>$label)
{
    // echo $key.';';
    if($label)
    {
        $newlabel[$label]= $label;
        // echo $newlabel[$label].';';

        $newlist_target[$label]= (!empty($newlist_target[$label])?$newlist_target[$label]:0) + $list_target[$key];
        // echo $newlist_target[$label].';'; //10;20;6;106;116;122;222;232;4;2;6;12;1;
        // echo $list_target[$key].';';    //10;10;6;100;10;6;100;10;4;2;4;6;1;

        $newlist_pass[$label]= (!empty($newlist_pass[$label])?$newlist_pass[$label]:0) + $list_pass[$key];
        // echo $newlist_pass[$label].';';
        // echo $list_pass[$key];


        // $newlist_step[$label] = (!empty($list_step[$label]) ?$newlist_step[$label]+1:0);
        if(!empty($list_step[$label]) && $list_step[$label] > 5){
            // echo $label.';';
            echo  $list_step[$label].';';
            // $newlist_step[$label]++;
        }

        // if(!empty($list_step[$label])){
        //     $newlist_step[$label] +=  1;
        //     // echo $list_step[$label].';';
        // }
       
          

        $newlist_total[$label] = (!empty($newlist_total[$label])?$newlist_total[$label]:0) + $list_total[$key];
        // echo $newlist_total[$label].';';
        // echo $list_total[$key].';';

        $newlist_onboard[$label]= (!empty($newlist_onboard[$label])?$newlist_onboard[$label]:0) + $list_onboard[$key];
        // echo $newlist_onboard[$label].';';
        // echo $list_onboard[$key].';';
    }
}
die();
$summary->labels = implode(',',array_keys($newlabel));

$summary->list_target =  implode(',',array_values($newlist_target));

$summary->list_pass =  implode(',',array_values($newlist_pass));

$summary->steps =  implode(',',array_values($newlist_step));

$summary->list_total =  implode(',',array_values($newlist_total));

$summary->list_onboard =  implode(',',array_values($newlist_onboard));


// $cv_news = DB::table('cv')->where(['isdelete'=>0,'step'=>1])->where('request.position_id', 'position_id')->first(); 
// // $ts = $obj->where('position_id', 'cv.position_id')
// print_r($cv_news);
// die();

// die($response->withJson($obj->get()));

// die('ok');

//////////////////////////
// SUM(target) AS target,  //Vị trí cần tuyển(Vị trí)
// SUM(total_cv) AS total_cv, //Đơn ứng tuyển(Đơn)
// SUM(interview_cv) AS interview_cv, //CV được tham gia phỏng vấn;
// SUM(pass_cv) AS pass_cv, //Số lượng CV pass phỏng vấn;
// SUM(offer_cv) AS offer_cv, //số lượng cv tham gia offer
// SUM(offer_success) AS offer_success, //offer thành công/
// SUM(onboard_cv) AS onboard_cv, //Ứng viên đã tuyển(Ứng viên)
// SUM(fail_job) AS fail_job, //Số lượng Fail thử việc;
// GROUP_CONCAT(target) as list_target, //ds Yêu cầu
// GROUP_CONCAT(total_cv) as list_total, //ds Đã tuyển
// GROUP_CONCAT(onboard_cv) as list_onboard, // giôngs với list_total
// GROUP_CONCAT(positions.title,\'\') as labels //phòng ban


// Số lượng cần tuyển:
// − Tổng số lượng cần tuyển (= tổng trong kế hoạch)

// Số lượng CV:
// − Tổng số cv (= tổng số CV được tạo) //table cv column status=1(cv mới)

// Ứng viên đã tuyển:
// − = tổng ứng viên actual onboard

// Tỉ lệ offer thành công:
// − = offer thành công/offer (pass offer/pass pre offer)

// Tổng hợp điểm:
// − Số lượng CV của ứng viên actual onboard nhân với điểm tương ứng từng <vị trí + trình độ>
// (điểm tương ứng từng vị trí trình độ ở Bảng điểm quy đổi)

// Biểu đồ Đơn ứng tuyển/qua phỏng vấn:
// − Thống kê theo vị trí
// − Hiển thị giá trị ở đầu cột
// − Số lượng cv trong tạo mới/Số lượng cv pass phỏng vấn
// − Sắp xếp theo số lượng CV giảm dần



// Bảng xếp hạng số lượng nhân sự đã tuyển:
// − Thống kê theo vị trí
// − Sắp xếp theo số lượng nhân sự onboard từ cao đến thấp
// − Chỉ hiện số lượng vị trí đủ độ dài bảng (phụ thuộc vào thực tế dev), không scroll








$results = [
    'status' => 'success',
    'point' => $count_point,
    'summary' => $summary,
    'department' => $department,
    'data' => $ketqua ? $ketqua->all() : null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];
//


// các bước 
// + get phòng ban
// + get số lượng yêu cầu 
