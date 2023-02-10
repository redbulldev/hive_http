<?php
require_once('shared/PHPMailer/Exception.php');
require_once('shared/PHPMailer/PHPMailer.php');
require_once('shared/PHPMailer/SMTP.php');
use Tuupola\Base62;
use \Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager as DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('./define.php');

//Slim 
function sendmail()
{
	$mail = new PHPMailer();                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 1;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;                               
		$mail->Username = 'lymo0501@gmail.com'; 
		$mail->Password = 'xoybzvgrshxgsmqm';
		$mail->CharSet = 'UTF-8'; 
		//Recipients
		$mail->setFrom('lymo0501@gmail.com', 'Hivetech');
		$mail->addAddress('namng050185@gmail.com', 'Joe User');     // Add a recipient
		// $mail->addAddress('ellen@example.com');               // Name is optional
		// $mail->addReplyTo('info@example.com', 'Information');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('bcc@example.com');

		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Here is the subject';
		$mail->Body    = 'This is the HTML message body in bold!';
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if($mail->send())
		echo 'Message has been sent';
		else
		{
			echo ($mail->ErrorInfo);
		}
		
	} catch (Exception $e) {
		echo 'Message could not be sent. Mailer Error: ', $e->getMessage();
	}
}

function udCVInterview($cv)
{
    $hr=DB::table('interview_hr')->where('cv_id',$cv->id)->first();
    $tech=DB::table('interview_tech')->where('cv_id',$cv->id)->first();
    $status=-1;
    if($hr && $tech)
    {
        if($hr->status==2 && $tech->status==2)
        {
            $status=2;
        }else if($hr->status==3)
        {
            $status=3;
        }else if($hr->status==0 || $tech->status==0)
        {
            $status=0;
        }else $status=1;
    }else if($hr)
    {
        if($hr->status==2)
        {
            $status=1;
        }else $status=$hr->status;
    }else if($tech)
    {
        if($tech->status==2)
        {
            $status=1;
        }else $status=$tech->status;
    }
    return $status;
}

function cvStep($cv) {
	$step=0;
	$datacv=['step'=>$step,'status'=>2];
	$arrcheck = [
		['name'=>'review_hr','current'=>CURRENT_REVIEW_HR],
		['name'=>'review_physiognomy','current'=>CURRENT_REVIEW_PHYSIOGNOMY1,'where'=>['issecond'=>0]],
		['name'=>'review_cv','current'=>CURRENT_REVIEW_CV],
		['name'=>'review_physiognomy','current'=>CURRENT_REVIEW_PHYSIOGNOMY2,'where'=>['issecond'=>1]],
		['name'=>'cv','current'=>CURRENT_TO_INTERVIEW],
		['name'=>['interview_tech','interview_hr'],'current'=>CURRENT_INTERVIEW_TECH_HR],
		['name'=>'cv_preoffer','current'=>CURRENT_CV_PREOFFER],
		['name'=>'cv_offer','current'=>CURRENT_CV_OFFER],
		['name'=>'cv_onboard','current'=>CURRENT_CV_ONBOARD],
		['name'=>'cv_probation','current'=>CURRENT_CV_PROBATION],
	];
	foreach ($arrcheck as $item)
	{
		if(is_array($item['name']))
		{
			$status = udCVInterview($cv);
			if($status>-1)
			{
				$datacv=['status'=>$status,'step'=>$item['current']];
				if($status!=2)break;
			}
		}else{
			$query = DB::table($item['name'])->where($item['name']!='cv'?'cv_id':'id',$cv->id);
			if(!empty($item['where']))$query->where($item['where']);
			$obj = $query->first();
			if($obj)
			{
				if($item['name']!='cv')
				{
					$datacv['step'] = $item['current'];
					$datacv['status']= $obj->status;
					if($obj->status!=2)break;
				}else if($obj->appoint_date){
					$datacv['step'] = $item['current'];
					$datacv['status']= 2;
				}
			}
		}
	}
	return $datacv;
}

function checkRole($permission, $name, $action, $id = null, $username = null)
{
	require('./config.php');
	$convert = [
		'cv_history.view' => 'cv.view',
		'permission.view' => 'role.view',
		'setting.view' => 'general.view',
		'setting.edit' => 'general.edit',
		'cv_offer.view' => 'cv.decision',
		'cv_offer.add' => 'cv.decision',
		'cv_onboard.view' => 'cv.decision',
		'cv_onboard.add' => 'cv.decision',
		'cv_preoffer.view' => 'cv.decision',
		'cv_preoffer.add' => 'cv.decision',
		'cv_probation.view' => 'cv.decision',
		'cv_probation.add' => 'cv.decision',
		'interview_hr.view' => 'cv.decision',
		'interview_hr.add' => 'cv.decision',
		'interview_tech.view' => 'cv.decision',
		'interview_tech.add' => 'cv.decision',
		'review_cv.view' => 'cv.decision',
		'review_cv.add' => 'cv.decision',
		'review_hr.view' => 'cv.decision',
		'review_hr.add' => 'cv.decision',
		'review_physiognomy1.view' => 'cv.decision',
		'review_physiognomy1.add' => 'cv.decision',
		'review_physiognomy2.view' => 'cv.decision',
		'review_physiognomy2.add' => 'cv.decision',
		'to_interview.view' => 'cv.decision',
		'to_interview.add' => 'cv.decision'
	];
	$haveall = ['request', 'cv', 'positions'];
	if (!empty($convert[$name . '.' . $action])) {
		$value = explode('.', $convert[$name . '.' . $action]);
		$module = $value[0];
		$action = $value[1];
	} else {
		$module = $name;
	}
	if (empty($permission) || empty($permission->$module) || empty($permission->$module->$action)) {
		//Nếu không có quyền truyền vào
		throw new Exception('Cannot access ' . $action . ' ' . $name);
	} else if (empty($permission->$module->all) && $id != null && $username != null && in_array($module, $haveall)) {
		//Nếu ko có quyền all thì kiểm tra ID
		$count = 0;
		switch ($module) {
			case 'request':
				$count=DB::table($module)->where(function($query) use ($username){
					$query->orWhere('author_id',$username)
						->orWhere('requestor_id',$username)
						->orWhere('decision_id',$username)
						->orWhere('assignee_id',$username);
				})->whereIn('id',$id)->count();
				break;
			case 'cv':
				$count=DB::table($module)->where(function($query) use ($username){
					$query->orWhere('author_id',$username)
						->orWhere('interviewer_id',$username)
						->orWhere('reviewer_id',$username)
						->orWhere('assignee_id', $username);
				})->whereIn('id',$id)->count();
				break;
			case 'positions':
				$count=DB::table($module)->whereIn('id',$id)
				->join('positions_requester','positions_requester.position_id', '=', 'positions.id')
				->where('positions_requester.user_id',$username)->count();
				break;
		}
		if($count != count($id))
		{
			throw new Exception('Cannot access ' . $action . ' ' . $name.' with some id');
		}
	}
}

function buildPublicKey(string $key)
{
	return "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
}

function postUrl($link, $data)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $link,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => http_build_query($data),
		CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
	));
	$response = curl_exec($curl);
	$json = @json_decode($response);
	return $json;
}

function updateReport($request_id)
{
    $emp = DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where('step','>', CURRENT_CV_ONBOARD)->selectRaw('GROUP_CONCAT(fullname) as title')->first();
    DB::table('request')->where('isdelete',0)->where('id',$request_id)->update([
        'total_cv'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->count(),
		'interview_cv'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where(function($query){
			$query->orWhere('step','>',CURRENT_INTERVIEW_TECH_HR);
			$query->orWhere(function($query){
				$query->where('step', CURRENT_INTERVIEW_TECH_HR)->where('status','<',3);
			});
		})->count(),
        'pass_cv'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where('step', '>', CURRENT_INTERVIEW_TECH_HR)->count(),
        'offer_cv'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where('step','>', CURRENT_CV_PREOFFER)->count(),
        'offer_success'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where('step', '>', CURRENT_CV_OFFER)->count(),
        'onboard_cv'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where('step','>', CURRENT_CV_ONBOARD)->count(),
        'fail_job'=>DB::table('cv')->where('isdelete',0)->where('request_id',$request_id)->where('step','>', CURRENT_CV_ONBOARD)->where('status', 0)->count(),
        'employees'=>json_encode(explode(',',isset($emp->title)?$emp->title:''))
    ]);
}
function colExist($table,$col)
{
	$check = DB::select('SHOW COLUMNS FROM `'.$table.'` LIKE \''.$col.'\';');
	return $check?true:false;
}

function  removecolumn($table, $alldata)
{
	$allcolumn = DB::select('SHOW FULL COLUMNS FROM ' . $table);
	$newdata = [];
	if ($allcolumn && is_array($allcolumn))
		foreach ($allcolumn as $col)
			foreach ($alldata as $key => $val)
				if ($key == $col->Field) {
					if (is_array($val) || is_object($val)) $newdata[$key] = json_encode($val);
					else {
						if (strpos($col->Type, 'int') > -1) {
							$newdata[$key] = preg_replace("/[^0-9]/", "", $val);
							if ($newdata[$key] == '') $newdata[$key] = null;
						} else if ($key == 'password') {
							if ($val != '') {
								$newdata[$key] = md5($val);
							}
						}else if (strpos($col->Type, 'date') > -1 && !$val)  {
							$newdata[$key] = null;
						} else $newdata[$key] = trim($val);
					}
				}
	return $newdata;
}


function objectToArray($data)
{
	if (is_object($data)) {
		$data = get_object_vars($data);
	}

	if (is_array($data)) {
		return array_map('objectToArray', $data);
	}

	return $data;
}
function metaToObject($arrmeta = [], $cols = [])
{
	$object = [];
	if (count($cols) > 0)
		foreach ($cols as $col) $object[$col] = '';
	foreach ($arrmeta as $key => $meta) {
		$data = @json_decode($meta->metavalue);
		if (json_last_error() === JSON_ERROR_NONE) {
			if (is_array($data)) $object[$meta->metakey] = objectToArray($data);
			if (is_object($data)) $object[$meta->metakey] = objectToArray($data);
			else $object[$meta->metakey] = $data;
		} else  $object[$meta->metakey] = $meta->metavalue;
	}
	return $object;
}
function getMetaObject($type, $id, $arr = [])
{
	$obj = DB::table($type . '_meta')->where($type . '_id', $id);
	if (count($arr) > 0) {
		$obj = $obj->where(function ($query) use ($arr) {
			foreach ($arr as $item)
				$query->orWhere('metakey', $item);
		});
	}
	$meta = metaToObject($obj->get(), $arr);
	return $meta;
}

function vi_to_en($str, $sep = ' ')
{
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);
	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	$str = preg_replace('/[^ A-Za-z0-9\-]/', '', $str);
	$str = str_replace(" ", $sep, $str);
	return $str;
}

function findby($table, $obj, $k, $nowcolumns = [])
{
	$k = trim($k);
	$k1 = trim(vi_to_en($k));
	$k2 = trim(vi_to_en($k, '-'));
	$allcolumn = DB::select('SHOW FULL COLUMNS FROM ' . $table);
	$obj->where(function ($query) use ($allcolumn, $table, $k, $k1, $k2, $nowcolumns) {
		if (count($nowcolumns) == 0) {
			foreach ($allcolumn as $col) {
				if ((strpos($col->Type, 'varchar') > -1 || strpos($col->Type, 'text') > -1) && $col->Field != 'password') {
					$query->orWhere($table . '.' . $col->Field, 'LIKE', "%$k%")->orWhere($table . '.' . $col->Field, 'LIKE', "%$k1%")->orWhere($table . '.' . $col->Field, 'LIKE', "%$k2%");
				} else {
					//if (is_numeric($k)) $query->orWhere($table . '.' . $col->Field, $k);
				}
			}
		} else {
			foreach ($nowcolumns as $col) {
				$query->orWhere($col, 'LIKE', "%$k%")->orWhere($col, 'LIKE', "%$k1%")->orWhere($col, 'LIKE', "%$k2%");
			}
		}
	});
	return $obj;
}



function sendQueue($message, $queue = '')
{
	require(__DIR__ . '/config.php');
	$queue = $queue ? $queue : $conf['queuename'];
	$connection = new AMQPStreamConnection($conf['queueserver'], $conf['queueport'], $conf['queueuser'],  $conf['queuepass']);
	$channel = $connection->channel();
	$channel->exchange_declare($queue, 'fanout', true, true, false);
	$msg = new AMQPMessage(json_encode($message));
	$channel->basic_publish($msg,  $queue);
	$channel->close();
	$connection->close();
}


function historySave($loginid, $action, $table, $id, $olddata = '', $newdata = '')
{
	if(!empty($loginid))
	{
		if (($action == 'insert' || $action == 'update') && $newdata == '')
			try {
				if ($table === 'users') $columnpri = 'username';
				else $columnpri = 'id';
				$newdata = DB::table($table)->where($columnpri, $id)->first();
			} catch (Exception $ex) {
			}

		$strolddata = @json_encode($olddata);
		$strnewdata = @json_encode($newdata);
		$data = [
			'user_id' => $loginid,
			'tablename' => $table,
			'table_id' => $id,
			'action' => $action,
			'olddata' => $strolddata,
			'newdata' => $strnewdata,
			'datecreate' => time()
		];
		return DB::table('logs')->insertGetId($data);
	}return null;
}

function throwError($container, $request, $array)
{
	$validation = $container['validationService']->validate($request, $array);
	if (!$validation->hasPassed()) {
		foreach ($validation->getErrors() as $errors) {
			foreach ($errors as $error) {
				throw new Exception($error);
			}
		}
	}
}

//Gửi tin nhắn Rocket chat
function sendMessage($user,$msg)
{
	$domain= 'https://chat.ossigroup.net/';
	//1. Login để tạo token: https://chat.ossigroup.net/api/v1/login
	$login= post($domain.'api/v1/login',["username"=>'techqr',"password"=>'123!@#qwer']);
	if($login && $login->status=='success')
	{
		//2. Tạo Direct message: https://chat.ossigroup.net/api/v1/im.create (Gửi Token)
		$userId = $login->data->userId;
		$authToken = $login->data->authToken;
		$create= post($domain.'api/v1/im.create',["username"=>$user],[ 'X-Auth-Token: '.$authToken, 'X-User-Id: '.$userId]);
		if($create && !empty($create->room))
		{
			$rid=$create->room->rid;
			if(!empty($rid))
			{
				//3. Gửi tin nhắn: https://chat.ossigroup.net/api/v1/chat.sendMessage (Gửi Token)
				$data=["message"=>["rid"=>$rid,"msg"=>$msg]];
				$send = post($domain.'api/v1/chat.sendMessage',$data,[ 'X-Auth-Token: '.$authToken, 'X-User-Id: '.$userId]);
			}
		}
	}
}

function post($link, $data,$header=[])
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => $link,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>json_encode($data),
	CURLOPT_HTTPHEADER => array_merge(['Content-Type: application/json'],$header)));
	$response = curl_exec($curl);
	curl_close($curl);
	$json = @json_decode($response);
	return $json;
}


function login($username, $userpass){
	ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);
	$baseDN = 'CN=Users,DC=i,DC=hivetech,DC=vn';
	$adminDN = "CN=Ldap Access,OU=SpecialUsers,DC=i,DC=hivetech,DC=vn"; //this is the admin distinguishedName
	$adminPswd = "umqOP3f7MPC3oez";
	$ldap_conn = ldap_connect('ldap://192.168.3.199:389'); //I'm using LDAPS here

	if (!$ldap_conn) {
		throw new Exception("Couldn't connect to LDAP service");
	} 

	//The first step is to bind the administrator so that we can search user info
	$ldapBindAdmin = ldap_bind($ldap_conn, $adminDN, $adminPswd);
	if ($ldapBindAdmin) {
		$filter = '(sAMAccountName=' . $username . ')';
		$attributes = array("name", "telephonenumber", "mail", "samaccountname");
		$result = ldap_search($ldap_conn, $baseDN, $filter, $attributes);
		$entries = ldap_get_entries($ldap_conn, $result);
		$userDN = !empty($entries[0]["name"][0])? $entries[0]["name"][0]:'';
		$dn = !empty($entries[0]["dn"]) ? $entries[0]["dn"] : '';
		if(!empty($dn))
		{
			//Okay, we're in! But now we need bind the user now that we have the user's DN
			$ldapBindUser = @ldap_bind($ldap_conn, $dn, $userpass);

			if ($ldapBindUser) {
				$email = !empty($entries[0]["mail"][0]) ? $entries[0]["mail"][0] : ''; 
				ldap_unbind($ldap_conn); // Clean up after ourselves.
				return ['fullname'=> $userDN, 'email'=>$email];
			} else {
				throw new Exception (ldap_error($ldap_conn));
			}
		}
		throw new Exception("User not found");
	} else {
		throw new Exception (ldap_error($ldap_conn));
	}
}
function createToken($exp, $secret, $data)

{

	$localData = clone $data;

	if (isset($localData->roles)) unset($localData->roles);

	$now = new DateTime();

	$future = new DateTime("+" . $exp);

	$payload = [

		"iat" => $now->getTimeStamp(),

		"exp" => $future->getTimeStamp(),

		"jti" => (new Base62)->encode(random_bytes(16)),

		"sub" => $localData

	];

	$token = JWT::encode($payload, $secret, "HS256");

	return $token;
}
function calcutateAge($dateOfBirth)
{
	$diff = date_diff(date_create($dateOfBirth), date_create(date("Y-m-d")));
	return $diff->format('%y');
}
function replaceEmail($content,$cv, $data){
	$position =  DB::table('positions')->where('id', $cv->position_id)->first();
	$level =  DB::table('level')->where('id', $cv->level_id)->first();
	$levelofffer =  DB::table('level')->where('id', !empty($data->level_id)? $data->level_id:0)->first();
	//$request =  DB::table('request')->where('id', $cv->request_id)->first();
	$content = str_replace('&lt;fullname&gt;', $cv->fullname, $content);
	$content = str_replace('&lt;position_title&gt;', !empty($position->title) ? $position->title : '', $content);
	$content = str_replace('&lt;level_title&gt;', !empty($level->title) ? $level->title : '', $content);
	$content = str_replace('&lt;mobile&gt;', $cv->mobile, $content);
	$content = str_replace('&lt;interviewer&gt;', $cv->interviewer_id, $content);
	$content = str_replace('&lt;age&gt;', calcutateAge($cv->birthday), $content);
	$content = str_replace('&lt;appoint_date&gt;', $cv->appoint_date, $content);
	$content = str_replace('&lt;appoint_place&gt;', $cv->appoint_place, $content);
	$content = str_replace('&lt;appoint_link&gt;', $cv->appoint_link, $content);
	$content = str_replace('&lt;offer_level_title&gt;', !empty($levelofffer->title) ? $levelofffer->title : '', $content);
	$content = str_replace('&lt;offer_onboard&gt;', !empty($data->onboard) ? $data->onboard : '', $content);
	$content = str_replace('&lt;onboard_onboard&gt;', !empty($data->onboard) ? $data->onboard : '', $content);
	return $content;
}
function createMail($cv_id, $data, $step, $status)
{
	$cv= DB::table('cv')->where('id',$cv_id)->first();
	if($cv)
	{
		$checktemp= DB::table('email')->where(['cv_step'=>$step, 'cv_status' => $status,'isdelete'=>0,'status'=>1, 'isauto'=>1])->first();
		if($checktemp)
		{
			replaceEmail($checktemp->content, $cv, $data);
			$checkhis = DB::table('email_history')->where(['cv_id'=>$cv_id,'cv_step' => $step, 'cv_status' => $status])->first();
			if (!$checkhis && !empty($cv->email)) {
			
				DB::table('email_history')->insert([
					'author_id'=>'AUTO',
					'email_id' => $checktemp->id,
					'cv_id' => $cv_id,
					'cc' => $checktemp->cc,
					'reply' => $checktemp->reply,
					'title' => $checktemp->title,
					'content' => replaceEmail($checktemp->content,$cv, $data),
					'cv_step' => $checktemp->cv_step,
					'cv_status' => $checktemp->cv_status,
					'delay' => $checktemp->delay,
					'datecreate' => time(),
					'sent' => 0,
					'email' => $cv->email,
				]);
			}
		}
	}
}