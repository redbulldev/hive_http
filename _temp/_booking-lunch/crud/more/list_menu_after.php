<?php
if($ketqua && $ketqua->count()==0){
    throw new Exception('Văn phòng của bạn chưa có thực đơn!');
}