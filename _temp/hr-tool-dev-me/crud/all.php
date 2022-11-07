<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException;
$httpStatus= 200;

// get - table name
$name = str_replace('-', '_', $args['name']);
// die($name);

if (in_array($name,$conf['block'])) {
    $httpStatus= 201;
    $results = ['status' => 'error', 'message' => 'API is Block', 'code' => 'block'];
} else {
    
    try {
        require('./shared/getToken.php');
         if (!$ignoreUrl)checkRole($permission, $name, 'view');
        function filterparam($table, $params)
        {
            $allcolumn = DB::select('SHOW FULL COLUMNS FROM ' . $table);
            $newparam = [];
            foreach ($allcolumn as $col)
                foreach ($params as $key => $val)
                    if ($key == $col->Field) {
                        if (strpos($col->Type, 'int') > -1)
                        {
                            $arval=explode('-',$val);
                            if(count($arval)==1)
                            {
                                $newparam['notlike'][$table . '.' . $key] = $val;
                            }else{
                                $newparam['in'][$table . '.' . $key] = $arval;
                            }
                        }
                        else{
                            $newparam['like'][$table . '.' . $key] = $val;
                        }
                    }
            return $newparam;
        }
        try {
            $hearkey = $request->getHeader('key');
            $hearsearch = $request->getHeader('search');
            $asselect = $request->getHeader('asselect');

            if ($hearsearch && isset($hearsearch[0])) $moresearch = json_decode(str_replace('\"', '"', $hearsearch[0]), true);
            if ($asselect && isset($asselect[0])) $asselect = json_decode($asselect[0]);
            $page = $request->getQueryParam('page', 1);
            $limit = $request->getQueryParam('limit', 10);
            if($limit==0)$limit = 1000;
            $key = $request->getQueryParam('key', $request->getQueryParam('keyword', ''));
            $daterange = $request->getQueryParam('daterange', '');
            if (!is_numeric($page) || $page < 1) $page = 1;
            //if (!is_numeric($limit) || $limit < 1) $limit = 30;
            $params = $request->getQueryParams();
            unset($params['limit']);
            unset($params['page']);
            unset($params['key']);
            unset($params['keyword']);
            unset($params['daterange']);
        
            $file = $name;
            //  die($file); //dashboard
            if (file_exists(__DIR__ . '/all/' . $file . '_before.php')) require(__DIR__ . '/all/' . $file . '_before.php');

            // die($results); //null
            if (!isset($results)) {
                if (file_exists(__DIR__ . '/all/' . $file . '.php')) {
                    require(__DIR__ . '/all/' . $file . '.php');
                    if (isset($obj)) {
                        if (!isset($results))
                            if (!isset($moreselect)) $ketqua = $obj->paginate($limit, ['*'], 'page', $page);
                            else $ketqua = $obj->paginate($limit, $moreselect, 'page', $page);
                            if (file_exists(__DIR__ . '/all/' . $file . '_after.php')) require(__DIR__ . '/all/' . $file . '_after.php');
                    } else  $ketqua = null;
                } else {
                    // nếu params không tương ứng với một table trong csdl thì sẽ call trong /crud/more

                    $where = filterparam($name, $params);
                    $obj = DB::table($name);
                    // die($name); table request
                    
                    if($name==='users')$columnorb='datecreate';else $columnorb='id';

                    require 'all_where.php';

                    if (colExist($name, 'isdelete')) {
                        $obj->where($name.'.isdelete', 0);
                    }
                    
                    //echo $obj->toSql();die(); explain sql
                    if (isset($select)) $moreselect = $select;
                    else if ($asselect && is_array($asselect)) $moreselect = $asselect;
                    else if (isset($moreselect)) $moreselect = array_merge($moreselect, [$name . '.*']);
                    else $moreselect =  [$name . '.*'];
                    // die($moreselect); Array
                    // die($disableLimit); 1
                    if(empty($disableLimit))
                    {
                        if ($hearkey && isset($hearkey[0])) {
                            $arrkey = explode(',', $hearkey[0]);
                            if (count($arrkey) > 0) $ketqua = $obj->paginate($limit, $arrkey, 'page', $page);
                            else $ketqua = $obj->paginate($limit, $moreselect, 'page', $page);
                        } else $ketqua = $obj->paginate($limit, $moreselect, 'page', $page);
                    }else{ 
                        // drive intro
                        $ketqua = $obj->select($moreselect); //table request
                    }
                    
                    // die($file); dashboard
                    if (file_exists(__DIR__ . '/more/' . $file . '_after' . '.php')){
                        require(__DIR__ . '/more/' . $file . '_after' . '.php');
                    }
                }
            }
            if(empty($disableLimit))
            {
                if (!isset($results)) $results = [
                    'status' => 'success',
                    'data' => $ketqua ? $ketqua->items() : null,
                    'time' => time(),
                    //'obj' => $obj->toSql(),
                    'total' => $ketqua ? $ketqua->total() : null,
                    'page' => $ketqua ? $ketqua->currentPage() : null,
                    'totalpage' => $ketqua ? $ketqua->lastPage() : null
                ];
            }else{
                if (!isset($results)) {
                    $results = [
                        'status' => 'success',
                        'obj' => $obj->toSql(),
                        'data' => $ketqua ? $ketqua->get() : null,
                        'total' => $ketqua ? $ketqua->count() : null,
                        'time' => time(),
                    ];
                }
            }
        }catch (QueryException $e) {
            throw new Exception(json_encode([
                "message"=>'Error connection',
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