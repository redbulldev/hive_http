<?php
use Illuminate\Database\Capsule\Manager as DB;

// die($name); //cv
$obj = DB::table($name);

require './crud/all_where.php';   


// $objRequest = DB::table('request');
// data summary
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
// SUM(step) AS target, 

// task - Đơn ứng tuyển/qua phỏng vấn:
// $test = $obj->selectRaw('
//     SUM(target) AS target
// ');

// $countCv = $obj->where('step', 1)->count();

// $countStep = $obj->count();
// echo "<pre>";
// print_r($test ) ;
// echo "</pre>";


// die($response->withJson($obj->get()));

// die('ok');


// Số lượng cần tuyển:
// − Tổng số lượng cần tuyển (= tổng trong kế hoạch)
// Số lượng CV:
// − Tổng số cv (= tổng số CV được tạo)
// Tổng hợp điểm:
// − Số lượng CV của ứng viên actual onboard nhân với điểm tương ứng từng <vị trí + trình độ>
// (điểm tương ứng từng vị trí trình độ ở Bảng điểm quy đổi)
// Ứng viên đã tuyển:
// − = tổng ứng viên actual onboard
// Tỉ lệ offer thành công:
// − = offer thành công/offer (pass offer/pass pre offer)



// Biểu đồ Đơn ứng tuyển/qua phỏng vấn:
// − Thống kê theo vị trí
// − Hiển thị giá trị ở đầu cột
// − Số lượng cv trong tạo mới/Số lượng cv pass phỏng vấn
// − Sắp xếp theo số lượng CV giảm dần



// Bảng xếp hạng số lượng nhân sự đã tuyển:
// − Thống kê theo vị trí
// − Sắp xếp theo số lượng nhân sự onboard từ cao đến thấp
// − Chỉ hiện số lượng vị trí đủ độ dài bảng (phụ thuộc vào thực tế dev), không scroll





// *Bảng xếp hạng số lượng đã tuyển //
// data - department //
$colors = ['#32E875','#FBB13C','#FF5D73','#8A84E2','#A3F4FF','#3495eb','#9e0211','#ad4731','#066917','#ded750','#f707c7'];

$department = ['labels'=>[],'values'=>[],'colors'=>[]]; 

$item = DB::table('positions')->where(['isdelete'=>0,'status'=>1, 'parent_id'=>0])->get(); 

$all = DB::table('positions')->where(['isdelete'=>0,'status'=>1])->get(); 

// die($response->withJson($item));
// get all position of phongban
$data = [];
$count = 0;
foreach($item as $key => $value) {
    foreach($all as $index => $l) {
        if($l->parent_id == $value->id){
            $department['labels'][] = $l->title;   
            $data[$count] = $l->id; 
            $count++;
        }
    }
}
// print_r($data);
// die();
// die( $response->withJson($data));


$test=[];

// $values = DB::table('request')->where(['isdelete'=>0])->get(); 
// foreach($values as $key => $value) {
    foreach ($data as $key  => $id) {
        // if($id == $value->position_id){
        //    $d = $value->author_id;
        //    die($d);
        // }
        $value = DB::table('request')->where(['isdelete'=>0])->where('request.position_id' , $id)->selectRaw('SUM(target) AS target')->first();
// die($value->target);
        $test[$key]  = $value->target;
    }
// }
die($response->withJson($test));

// $obj1 = clone  $obj;
// $obj1->where(function($query) use ($data){
//     foreach ($data as $key  => $id) {
//         // echo $id;
//         $c = $query->where('request.position_id' , $id)->selectRaw('SUM(target) AS target')->first();
// print_r($c->target);
//         $test['values'][] = $c->target;
//     }
// });

// print_r($department['values'][] );


// $valueAndColor = DB::table('positions')->where(['isdelete'=>0,'status'=>1])->where('parent_id', '!=', 0)->get(); 

// foreach($valueAndColor as $key => $k)
// {
//     $obj1 = clone  $obj; //request

//     $res = $obj1->where('positions.parent_id',$k->id)->selectRaw('SUM(target) AS target')->first();
//     // die($res->target);

//     // $department['labels'][] = $res->title;

//     $department['values'][] = $res->target; //yêu cầu (số lượng)
//     $department['colors'][] = !empty($colors[$key]) ? $colors[$key]:'#000';
// }
die( $response->withJson($department));
// print_r($department);
die();


// các bước 
// + get phòng ban
// + get số lượng yêu cầu 
// + get colors 