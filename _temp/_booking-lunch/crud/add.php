<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException;
$httpStatus= 200;
$name = str_replace('-', '_', $args['name']);
if (in_array($name,$conf['block'])) {
    $httpStatus= 201;
    $results = ['status' => 'error', 'message' => 'API is Block', 'code' => 'block'];
} else {
    try {
        require('./shared/getToken.php');
        checkRole($permission, $name, 'add');
        try {
            $id = 0;
            $datas = json_decode($request->getBody());
            function itemsAdd($request,$container,$name,$data,$id,$user){
                if ($data) {
                    $data->datecreate = date('Y-m-d H:i:s');
                    $data->datemodified = date('Y-m-d H:i:s');
                    if($user)$data->author_id = $user->username;
                    $data->ismobile = 1;
                    if (isset($data->title)) $data->alias = vi_to_en($data->title, '-');
                }
                if (file_exists(__DIR__ . '/add/' . $name . '_before.php')) require(__DIR__ . '/add/' . $name . '_before.php');
                if(isset($results)) return $results;
                if (file_exists(__DIR__ . '/add/' . $name . '.php')) require(__DIR__ . '/add/' . $name . '.php');
                else {
                    if ($data) $newdata = removecolumn($name, $data);
                    else $newdata = [];
                    if (count($newdata) > 0) {
                        $id = DB::table($name)->insertGetId($newdata);
                        if (isset($newdata['alias'])) {
                            $check = DB::table($name)->where(['alias' => $newdata['alias']])->where('id', '!=', $id)->first();
                            if ($check)  DB::table($name)->where('id', $id)->update(['alias' => $newdata['alias'] . '-' . $id]);
                        }
                        if($name==='users' && !empty($newdata['username']))$id=$newdata['username'];
                        $results = ['status' => 'success', 'id' => $id, 'time' => date('Y-m-d H:i:s')];
                        $idlog =historySave($data->author_id, 'insert', $name, $id);
                    } else $results = ['status' => 'error', 'message' => 'Data not found', 'code' => 'datanotfound', 'class' => 'add'];
                }
                if (file_exists(__DIR__ . '/add/' . $name . '_after.php')) require(__DIR__ . '/add/' . $name . '_after.php');
                if(!isset($results)) $results = ['status' => 'success', 'time' => date('Y-m-d H:i:s')];
                if(!empty($alertmore)) $results['more'] = $alertmore;
                return $results;
            }

            if(is_array($datas)) {
                if (file_exists(__DIR__ . '/add/' . $name . '_before_all.php')) require(__DIR__ . '/add/' . $name . '_before_all.php');
                if(isset($results)) return $results;
                foreach($datas as $data) {
                    if(isset($data->id))unset($data->id);
                    $id=itemsAdd($request,$container,$name,$data,$id,$user);
                }
                $results = ['status' => 'success','time' => date('Y-m-d H:i:s')];
            }else{
                $results = itemsAdd($request,$container,$name,$datas,$id,$user);
            }
        }catch (QueryException $e) {
            throw new Exception(json_encode([
                "message"=>'Insert error',
                "status"=> 201,
                "code" => "dberror",
                "more" => $e->getMessage()
            ]));
        }
    } catch (Exception $e) {
        $obj=@json_decode($e->getMessage());
        if(is_object($obj))
        {
            $httpStatus= $obj->status;
            $results = ['status' => 'error', 'message' => $obj->message, 'code' => $obj->code];
            if(!empty($obj->more))
            {
                $results['more'] = $obj->more;
            }
        }else{
            $httpStatus= 201;
            $results = ['status' => 'error', 'message' => $e->getMessage(), 'code' => 'fatalerror'];
        }
    }
}