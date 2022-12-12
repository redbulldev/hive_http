<?php

if(isset($where) && isset($obj)) 
{
    if(isset($where['notlike'])){
        // position_id=675&step=0
    $obj->where($where['notlike']);
    // print_r($where['notlike']);
    // die('ok');
    }
    if(isset($where['in'])){
       
        // print_r($where['like']);
        // die('ok');
        foreach($where['in'] as $column=>$value)
        {
            $obj->whereIn($column,$value);
        }
    }
    if(isset($where['like'])){
      //assignee_i
        // print_r($where['like']);
        // die('ok');
        
        foreach($where['like'] as $column=>$value)
        {
            // die($value);
            $arrkey = explode('-',$value);
            // if(count($arrkey)==1)$obj->where($column, 'like', '%'.$value.'%');
            // else  $obj->where(function($query) use ($name,$column,$arrkey){
            //     foreach($arrkey as $key)
            //     $query->orWhere($column, 'like', '%'.$key.'%');
            // });
       
            if(count($arrkey)==1){
            //      print_r($column);

            // die($arrkey);
            $obj->where($column, 'like', $value);
            }
            else  $obj->where(function($query) use ($name,$column,$arrkey){
                foreach($arrkey as $key)
                $query->orWhere($column, 'like', $key);
            });
        }
    }
}

