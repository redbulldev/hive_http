<?php
use Illuminate\Database\Capsule\Manager as DB;

// die($name); //cv
$obj = DB::table($name);

require './crud/all_where.php';   


$objRequest = DB::table('request');
// data summary
// $obj->selectRaw('
//     SUM(step) AS target, 
//     SUM(total_cv) AS total_cv, 
//     SUM(interview_cv) AS interview_cv, 
//     SUM(pass_cv) AS pass_cv, 
//     SUM(offer_cv) AS offer_cv, 
//     SUM(offer_success) AS offer_success, 
//     SUM(onboard_cv) AS onboard_cv, 
//     SUM(fail_job) AS fail_job,
//     GROUP_CONCAT(target) as list_target, 
//     GROUP_CONCAT(total_cv) as list_total, 
//     GROUP_CONCAT(onboard_cv) as list_onboard,
//     GROUP_CONCAT(positions.title,\'\') as labels
// ');
// SUM(step) AS target, 

// task - Đơn ứng tuyển/qua phỏng vấn:
$test = $obj->where('step', 1)->where('status', 1)->selectRaw('
    SUM(step) AS target, 
    SUM(status) AS total_cv, 
');

// $countCv = $obj->where('step', 1)->count();

// $countStep = $obj->count();
echo "<pre>";
print_r($test ) ;
echo "</pre>";

die();


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



//     1   id Primary  int         No  None    ID level;21 AUTO_INCREMENT  Change Change   Drop Drop   
//     2   author_id Index varchar(150)    utf8mb4_unicode_ci      No  None    ID người tạo;namng      Change Change   Drop Drop   
//     3   request_id Index    int         No  None    ID request;23       Change Change   Drop Drop   
//     4   interviewer_id Index    varchar(150)    utf8mb4_unicode_ci      Yes NULL    Người phỏng vấn;huynhnv     Change Change   Drop Drop   
//     5   reviewer_id Index   varchar(150)    utf8mb4_unicode_ci      Yes NULL    Người Review CV;thangpm     Change Change   Drop Drop   
//     6   assignee_id varchar(150)    utf8mb4_unicode_ci      Yes NULL    Người xử lý;thanglv     Change Change   Drop Drop   
//     7   chairman_id varchar(50) utf8mb4_unicode_ci      Yes lent    Người xem nhân tướng và pre offer;lent      Change Change   Drop Drop   
//     8   position_id Index   int         Yes NULL    ID vị trí;123       Change Change   Drop Drop   
//     9   level_id Index  int         Yes NULL    ID level;4      Change Change   Drop Drop   
//     10  fullname    varchar(200)    utf8mb4_unicode_ci      No  None    Họ và tên; Nguyễn Xuân Khoát        Change Change   Drop Drop   
//     11  email   varchar(250)    utf8mb4_unicode_ci      Yes NULL    Email;xuankhoat@gmail.com       Change Change   Drop Drop   
//     12  mobile  varchar(15) utf8mb4_unicode_ci      Yes NULL    Số di động;09124578963      Change Change   Drop Drop   
//     13  birthday    date            Yes NULL    Ngày sinh;1988-10-27        Change Change   Drop Drop   
//     14  address varchar(250)    utf8mb4_unicode_ci      Yes NULL    Địa chỉ;123 Xuân Thủy, Cầu giấy, Hà Nội     Change Change   Drop Drop   
//     15  gender  tinyint         Yes NULL    Giới tính:0 Nữ, 1 Nam;1     Change Change   Drop Drop   
//     16  linkcv  varchar(250)    utf8mb4_unicode_ci      Yes NULL    Đường dẫn CV;https://download.com/cv.pdf        Change Change   Drop Drop   
//     17  images  json            Yes NULL    Danh sách hình ảnh;["http://...jpg","http://...png"]        Change Change   Drop Drop   
//     18  source_id   int         Yes NULL    Nguồn; TopCV        Change Change   Drop Drop   
//     19  appoint_type    tinyint         Yes NULL    Loại hình phỏng vấn:0 Offline, 1 Online;0       Change Change   Drop Drop   
//     20  description text    utf8mb4_unicode_ci      Yes NULL    Mô tả;Lorem ipsum dolor sit amet...     Change Change   Drop Drop   
//     21  appoint_date    int         Yes NULL    Ngày phỏng vấn;1622504033       Change Change   Drop Drop   
//     22  appoint_place   varchar(250)    utf8mb4_unicode_ci      Yes NULL    Nơi phỏng vấn;Phòng họp lớn     Change Change   Drop Drop   
//     23  appoint_link    varchar(2100)   utf8mb4_unicode_ci      Yes NULL    Link phỏng vấn online;https://meet.ossigroup.net/interview      Change Change   Drop Drop   
//     24  salary  int         Yes NULL    Lương vào làm (VND);40000000        Change Change   Drop Drop   
//     25  checklist   varchar(2100)   utf8mb4_unicode_ci      Yes NULL    Link checklist;http://link.checklist.com        Change Change   Drop Drop   
//     26  onboard date            Yes NULL    Ngày onboard;2022-05-15     Change Change   Drop Drop   
//     27  datecreate  int         No  None    Ngày tạo;1622504033     Change Change   Drop Drop   
//     28  datemodified    int         No  None    Ngày sửa cuối;1622505033        Change Change   Drop Drop   
//     29  step    tinyint         No  0   Bước CV: 0: new, 1: Hr review,2: nhân tướng 1, 3: CV review,4: To interview,5: Interview,6: Nhân tướng 2,7: Pre offer,8: Offer,9: OnBoard,10: Thử việc;9        Change Change   Drop Drop   
//     30  status  tinyint(1)          No  2   Trạng thái:0 Fail, 1 pedding, 2 Pass;0      Change Change   Drop Drop   
//     31  isdelete    tinyint         No  0           Change Change   Drop Drop   








// *Bảng xếp hạng số lượng đã tuyển //
// data - department //
$colors = ['#32E875','#FBB13C','#FF5D73','#8A84E2','#A3F4FF','#3495eb','#9e0211','#ad4731','#066917','#ded750','#f707c7'];

$department = ['labels'=>[],'values'=>[],'colors'=>[]]; 

$all = DB::table('positions')->where(['isdelete'=>0,'status'=>1,'parent_id'=>1])->get();

foreach($all as $key => $item)
{
    $obj1 = clone  $objRequest; //request

    $res = $obj1->where('position_id',$item->id)->selectRaw('SUM(onboard_cv) AS total_onboard_cv')->first();
    // print_r($res);

    $department['labels'][] = $item->title;
    $department['values'][] = $res->onboard_cv; //yêu cầu (số lượng)
    $department['colors'][] = !empty($colors[$key]) ? $colors[$key]:'#000';
}

print_r($department);
die();


// các bước 
// + get phòng ban
// + get số lượng yêu cầu 
// + get colors 