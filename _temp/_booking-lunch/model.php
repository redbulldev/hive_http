<?php

use Tuupola\Base62;
use \Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager as DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


function checkRole($permission, $name, $action, $id = null, $username = null)
{
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
	$interview_cv = 4;
	$pass_cv = 6;
	$offer = 8;
	$onboard_cv = 9;
	$emp = DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where('step', '>=', $onboard_cv)->selectRaw('GROUP_CONCAT(fullname) as title')->first();
	DB::table('request')->where('isdelete', 0)->where('id', $request_id)->update([
		'total_cv' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->count(),
		'interview_cv' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where(function ($query) use ($interview_cv) {
			$query->orWhere(function ($query) use ($interview_cv) {
				$query->where('step', $interview_cv)
					->whereIn('status', [2, 4]);
			});
			$query->orWhere('step', '>', $interview_cv);
		})->count(),
		'pass_cv' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where(function ($query) use ($pass_cv) {
			$query->orWhere(function ($query) use ($pass_cv) {
				$query->where('step', $pass_cv)
					->whereIn('status', [2, 4]);
			});
			$query->orWhere('step', '>', $pass_cv);
		})->count(),
		'offer_cv' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where('step', '>=', $offer)->count(),
		'offer_success' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where(function ($query) use ($offer) {
			$query->orWhere(function ($query) use ($offer) {
				$query->where('step', $offer)
					->whereIn('status', [2, 4]);
			});
			$query->orWhere('step', '>', $offer);
		})->count(),
		'onboard_cv' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where('step', '>=', $onboard_cv)->count(),
		'fail_job' => DB::table('cv')->where('isdelete', 0)->where('request_id', $request_id)->where('step', '>', $onboard_cv)->where('status', 0)->count(),
		'employees' => json_encode(explode(',', isset($emp->title) ? $emp->title : ''))
	]);
}
function colExist($table, $col)
{
	$check = DB::select('SHOW COLUMNS FROM `' . $table . '` LIKE \'' . $col . '\';');
	return $check ? true : false;
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
						} else if (strpos($col->Type, 'date') > -1 && !$val) {
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
	$k2 = trim(vi_to_en($k, '_'));
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
	$channel->exchange_declare($queue, 'fanout', true, false, false);
	$msg = new AMQPMessage(json_encode($message));
	$channel->basic_publish($msg,  $queue);
	$channel->close();
	$connection->close();
}


function historySave($loginid, $action, $table, $id, $olddata = '', $newdata = '')
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
		'username' => $loginid,
		'tablename' => $table,
		'table_id' => $id,
		'action' => $action,
		'olddata' => $strolddata,
		'newdata' => $strnewdata,
		'datecreate' => date('Y-m-d H:i:s')
	];
	return DB::table('logs')->insertGetId($data);
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
function sendMessage($user, $msg)
{
	$domain = 'https://chat.ossigroup.net/';
	//1. Login để tạo token: https://chat.ossigroup.net/api/v1/login
	$login = post($domain . 'api/v1/login', ["username" => 'techqr', "password" => '123!@#qwer']);
	if ($login && $login->status == 'success') {
		//2. Tạo Direct message: https://chat.ossigroup.net/api/v1/im.create (Gửi Token)
		$userId = $login->data->userId;
		$authToken = $login->data->authToken;
		echo $userId . ' --- ' . $authToken . "\n";
		$create = post($domain . 'api/v1/im.create', ["username" => $user], ['X-Auth-Token: ' . $authToken, 'X-User-Id: ' . $userId]);
		if ($create && !empty($create->room)) {
			$rid = $create->room->rid;
			echo $rid;
			if (!empty($rid)) {
				//3. Gửi tin nhắn: https://chat.ossigroup.net/api/v1/chat.sendMessage (Gửi Token)
				$data = ["message" => ["rid" => $rid, "msg" => $msg]];
				$send = post($domain . 'api/v1/chat.sendMessage', $data, ['X-Auth-Token: ' . $authToken, 'X-User-Id: ' . $userId]);
			}
		}
	}
}

function post($link, $data, $header = [])
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
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array_merge(['Content-Type: application/json'], $header)
	));
	$response = curl_exec($curl);
	curl_close($curl);
	$json = @json_decode($response);
	return $json;
}


function login($username, $userpass)
{
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
		$userDN = !empty($entries[0]["name"][0]) ? $entries[0]["name"][0] : '';
		$dn = !empty($entries[0]["dn"]) ? $entries[0]["dn"] : '';
		if (!empty($dn)) {
			//Okay, we're in! But now we need bind the user now that we have the user's DN
			$ldapBindUser = @ldap_bind($ldap_conn, $dn, $userpass);

			if ($ldapBindUser) {
				$email =
					!empty($entries[0]["mail"][0]) ? $entries[0]["mail"][0] : '';
				ldap_unbind($ldap_conn); // Clean up after ourselves.
				return ['fullname' => $userDN, 'email' => $email];
			} else {
				throw new Exception(ldap_error($ldap_conn));
			}
		}
		throw new Exception("User not found");
	} else {
		throw new Exception(ldap_error($ldap_conn));
	}
}

function scan()
{
	$ad_users = [];
	$adminDN = "CN=Ldap Access,OU=SpecialUsers,DC=i,DC=hivetech,DC=vn"; //this is the admin distinguishedName
	$adminPswd = "umqOP3f7MPC3oez";
	$ldap_conn = ldap_connect('ldap://192.168.3.199:389'); //I'm using LDAPS here

	if (!$ldap_conn) {
		throw new Exception("Couldn't connect to LDAP service");
	}
	if (TRUE === ldap_bind($ldap_conn, $adminDN, $adminPswd)) {
		$ldap_base_dn = 'CN=Users,DC=i,DC=hivetech,DC=vn';
		$search_filter = '(&(objectClass=user)(samaccountname=*))';
		$attributes = array();
		$attributes[] = 'givenname';
		$attributes[] = 'mail';
		$attributes[] = 'samaccountname';
		$attributes[] = 'sn';
		$attributes[] = 'description';
		$attributes[] = 'userAccountControl';
		$result = ldap_search($ldap_conn, $ldap_base_dn, $search_filter, $attributes);
		if (FALSE !== $result) {
			$entries = ldap_get_entries($ldap_conn, $result);
			for ($x = 0; $x < $entries['count']; $x++) {
				if (!empty($entries[$x]['samaccountname'][0]) && isset($entries[$x]['description'][0])) {
					$description = strtolower(trim($entries[$x]['description'][0]));
					if ($description === 0 || $description === '0') {
						$username = strtolower(trim($entries[$x]['samaccountname'][0]));
						$email = !empty($entries[$x]['mail'][0]) ? strtolower(trim($entries[$x]['mail'][0])) : '';
						$fullname = (!empty($entries[$x]['sn'][0]) ? trim($entries[$x]['sn'][0]) : '') . ' ' . (!empty($entries[$x]['givenname'][0]) ? trim($entries[$x]['givenname'][0]) : '');
						try {
							DB::table('users')->insert([
								'username' => $username,
								'uid' => $username,
								'email' => $email,
								'fullname' => $fullname,
								'datecreate' => date('Y-m-d H:i:s'),
								'datemodified' => date('Y-m-d H:i:s'),
								'lastdate' => '1970-01-01',
								'status' => 1,
								'isdelete' => 0
							]);
						} catch (Exception $e) {
						}
						$ad_users[strtoupper(trim($entries[$x]['samaccountname'][0]))] = $entries[$x];
					}
				}
			}
		}
		ldap_unbind($ldap_conn); // Clean up after ourselves.
	}
	return $ad_users;
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
