<?php
if(isset($where) && isset($obj)) 
{
    if(isset($where['notlike']))$obj->where($where['notlike']);
    if(isset($where['in'])){
        foreach($where['in'] as $column=>$value)
        {
            $obj->whereIn($column,$value);
        }
    }
    if(isset($where['like'])){
        foreach($where['like'] as $column=>$value)
        {
            $arrkey = explode('-',$value);
            // if(count($arrkey)==1)$obj->where($column, 'like', '%'.$value.'%');
            // else  $obj->where(function($query) use ($name,$column,$arrkey){
            //     foreach($arrkey as $key)
            //     $query->orWhere($column, 'like', '%'.$key.'%');
            // });
            if(count($arrkey)==1)$obj->where($column, 'like', $value);
            else  $obj->where(function($query) use ($name,$column,$arrkey){
                foreach($arrkey as $key)
                $query->orWhere($column, 'like', $key);
            });
        }
    }
}