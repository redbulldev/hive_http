<?php

use Illuminate\Database\Capsule\Manager as DB;

echo 'AUTO SEND MAIL' . "\n";
if (!empty($conf)) {
    $obj = DB::table('email_history')->where('sent', 0)->where(DB::raw('(datecreate+delay*60)'), '<=', time())->limit(10);
    //echo $obj->toSql();
    $allmail = $obj->get();
    foreach ($allmail as $mail) {
        $emailto = $mail->email;
        if (!empty($conf['emailtest'])) {
            $emailto = $conf['emailtest'];
        }
        echo '<pre>';
        echo '<h2>' . $emailto . '</h2>';
        print_r($mail);
        echo '</pre>';
        //Gửi mail
        $datasend = [
            "origin" => "Emitter",
            "subject" => $mail->title,
            "content" => $mail->content,
            "to" => [$emailto],
        ];
        if (!empty($mail->cc)) {
            $datasend['cc'] = @json_decode($mail->cc);
        }
        if (!empty($mail->reply)) {
            $datasend['reply'] = ['address' => $mail->reply];
        }
        echo '<pre>';
        print_r($datasend);
        echo '</pre>';
        sendQueue($datasend, 'mail');
        DB::table('email_history')->where('id', $mail->id)->update(['sent' => 1]);
    }
} else {
    echo '<br/> SETTING NOT FOUND';
}
die();