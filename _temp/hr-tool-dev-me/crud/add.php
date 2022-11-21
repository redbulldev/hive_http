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
        if (!$ignoreUrl) checkRole($permission, $name, 'add');
        try {
            $id = 0;
            $datas = json_decode($request->getBody());
            function itemsAdd($request,$container,$name,$data,$id,$user, $conf){
                if ($data) {
                    $data->datecreate = time();
                    $data->datemodified = time();
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
                        // die('kk');
                        $id = DB::table($name)->insertGetId($newdata);
                        if (isset($newdata['alias'])) {
                            $check = DB::table($name)->where(['alias' => $newdata['alias']])->where('id', '!=', $id)->first();
                            if ($check)  DB::table($name)->where('id', $id)->update(['alias' => $newdata['alias'] . '-' . $id]);
                        }
                        if($name==='users' && !empty($newdata['username']))$id=$newdata['username'];
                        $results = ['status' => 'success', 'id' => $id, 'time' => time()];
                       if(!empty($loginid)) $idlog =historySave($login_id, 'insert', $name, $id);
                    } else $results = ['status' => 'error', 'message' => 'Data not found', 'code' => 'datanotfound', 'class' => 'add'];
                }
                if (file_exists(__DIR__ . '/add/' . $name . '_after.php')) require(__DIR__ . '/add/' . $name . '_after.php');
                if(!isset($results)) $results = ['status' => 'success', 'time' => time()];
                if(!empty($alertmore)) $results['more'] = $alertmore;
                return $results;
            }

            if(is_array($datas)) {

                if (file_exists(__DIR__ . '/add/' . $name . '_before_all.php')) require(__DIR__ . '/add/' . $name . '_before_all.php');
                if(isset($results)) return $results;
                foreach($datas as $data) {
                    echo 'add';die($data->id);
                    if(isset($data->id))unset($data->id);
                    $id=itemsAdd($request,$container,$name,$data,$id,$user, $conf);
                }
                $results = ['status' => 'success','time' => time()];
            }else{
                    // echo $id;die($id);
                $results = itemsAdd($request,$container,$name,$datas,$id,$user, $conf);
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