<?php
use Illuminate\Database\Capsule\Manager as DB;


// lọc data in table request //

// die($params['level_id']);
$obj->whereIn('request.status', [2, 4])->where('request.isdelete',0);

// die($params['level_id']);
//start - chức năng "Bộ lọc thông tin:"

 //   $idrole= explode('-',$params['level_id']);
 //    // print_r($idrole);
 // die($response->withJson($idrole));
    
 //    die();
if(!empty($params['level_id']))
{
    $idrole= explode('-',$params['level_id']);
    print_r($idrole);
    
   
    // $obj->where(function($query) use ($idrole){
    //     foreach ($idrole as $id) {         
    //         $k1='"'.$id.'"';             
    //         $k2=': '.$id.','; 
    //         $k3=':'.$id.','; 
    //         $k4=':'.$id.' ,'; 
    //         $query->orWhere('request.levels' , 'LIKE', "%$k1%")
    //         ->orWhere('request.levels' , 'LIKE', "%$k2%")
    //         ->orWhere('request.levels' , 'LIKE', "%$k3%")
    //         ->orWhere('request.levels' , 'LIKE', "%$k4%");
    //     }
    // });
}

// die();

if (!empty($params['from']) && !empty($params['to'])) {
    $from = $params['from'];
    $to = $params['to'];
    $obj->where('date', '>=', $from)->where('date', '<=', $to);
}
// die($params['requestor']);
if (!empty($params['requestor'])) {
    $obj->where(function ($query) use ($user) {
        $query->orWhere('request.requestor_id', $user->username);
    });
}

// map -> position_id (lấy ra vị trí)
// die($params['position_id']);
$obj->join('positions', function ($join) {
    $join->on('positions.id', '=', 'request.position_id');
    $join->where(['positions.status'=>1, 'positions.isdelete'=>0]);
});

// map -> parent_id (lấy ra phòng ban)
$obj->join('positions as parent', function ($join) {
    $join->on('parent.id', '=', 'positions.parent_id');
    $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
});

// thêm column vào struct data[]
$moreselect= ['positions.title as positions_title', 'parent.title as department_title'];

// print_r();
 // die($response->withJson($obj->get()));