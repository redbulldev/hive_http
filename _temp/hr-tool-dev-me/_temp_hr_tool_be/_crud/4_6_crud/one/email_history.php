<?php

use Illuminate\Database\Capsule\Manager as DB;

$one =  DB::table('email_history')->where('id', $id)->first();
if ($one) {
    $one->cc = @json_decode($one->cc);
    $cv=  DB::table('cv')->where('id',$one->cv_id)->first();
    if($cv){
        $one->fullname = $cv->fullname;
    }
    $mail =  DB::table('email')->where('id', $one->email_id)->first();
    if ($mail) {
        $one->email_title = $mail->title;
    }
}
