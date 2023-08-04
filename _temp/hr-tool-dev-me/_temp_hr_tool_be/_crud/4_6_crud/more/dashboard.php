<?php
use Illuminate\Database\Capsule\Manager as DB;
$obj->whereIn('request.status', [2, 4])->where('request.isdelete',0);
if(!empty($params['level_id']))
{
    $idrole= explode('-',$params['level_id']);
    $obj->where(function($query) use ($idrole){
        foreach ($idrole as $id) {
            $k1='"'.$id.'"';
            $k2=': '.$id.',';
            $k3=':'.$id.',';
            $k4=':'.$id.' ,';
            $query->orWhere('request.levels' , 'LIKE', "%$k1%")
            ->orWhere('request.levels' , 'LIKE', "%$k2%")
            ->orWhere('request.levels' , 'LIKE', "%$k3%")
            ->orWhere('request.levels' , 'LIKE', "%$k4%");
        }
    });
}
if (!empty($params['from']) && !empty($params['to'])) {
    $from = $params['from'];
    $to = $params['to'];
    $obj->where('date', '>=', $from)->where('date', '<=', $to);
}
if (!empty($params['requestor'])) {
    $obj->where(function ($query) use ($user) {
        $query->orWhere('request.requestor_id', $user->username);
    });
}
$obj->join('positions', function ($join) {
    $join->on('positions.id', '=', 'request.position_id');
    $join->where(['positions.status'=>1, 'positions.isdelete'=>0]);
});
$obj->join('positions as parent', function ($join) {
    $join->on('parent.id', '=', 'positions.parent_id');
    $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
});
$moreselect= ['positions.title as positions_title', 'parent.title as department_title'];
