<?php

use Illuminate\Database\Capsule\Manager as DB;

if(!empty($_GET['day']))$day = $_GET['day'];else $day=0;
$time = time() - $day * 24 * 60 * 60;
$date = date('Y-m-d', $time);

echo 'Day '.date('N', $time) . '/Week -> ' . $date.'<br/>';

if (date('N', $time) < 6) {
    $users = DB::table('users')->where(['isdelete' => 0, 'status' => 1])->where('lastdate', '<', $date)->limit(200)->get();
    foreach ($users as $user) {
        echo $user->username.' - '.$user->fullname.' :';
        if ($user && $user->office_id) {

            $menu_id = $user->menu_id;
            if (empty($menu_id)) {
                $office = DB::table('offices')->where('id', $user->office_id)->first();
                if ($office) {
                    $menu_id = $office->menu_id;
                }
            }
            if (!empty($menu_id)) {

                $menu = DB::table('menus')->where('id', $menu_id)->first();
                if ($menu) {
                    $store = DB::table('offices_menus')->where(['menu_id' => $menu_id, 'office_id' => $user->office_id])->first();

                    if ($store) {
                        $addhis = [
                            'username' => $user->username,
                            'date' => $date,
                            'menu' => $user->default_lunch ? $menu->title:null,
                            'price' => $user->default_lunch ? $store->price:null,
                            'store_id' => $user->default_lunch ? $store->store_id:null,
                            'menu_id' => $user->default_lunch ? $menu_id:null,
                            'office_id' => $user->office_id,
                            'booked' => $user->default_lunch,
                            'ate' => $user->default_lunch,
                            'notes' => $user->default_lunch ? $user->notes : null,
                            'datemodified' => date('Y-m-d H:i:s')
                        ];
                        try {
                            DB::table('history')->insert($addhis);
                        } catch (Exception $e) {
                        }
                        $allhis = DB::table('history')->where(['username' => $user->username])->limit(5)->orderBy('date', 'DESC')->get()->toArray();
                        $historyData = ['history' => json_encode($allhis), 'lastdate' => $date];
                        DB::table('users')->where(['username' => $user->username])->update($historyData);
                        echo '<pre>';
                        print_r($addhis);
                        print_r($historyData);
                        echo '</pre>';
                    }else echo "Khong thay quan an<br/>";
                }else echo "Khong thay mon an<br/>";
            }else echo "Khong thay ID mon an<br/>";
        }else echo "Khong thay Van phong<br/>";
    }
    header('Content-Type:text/html; charset=UTF-8');
?>
    <script type="text/javascript">
        setTimeout(function() {
            //window.location.href = '/v1/cron/1?day=<?php echo $day + 1 ?>'
        }, 500)
    </script>
<?php
    die();
}
