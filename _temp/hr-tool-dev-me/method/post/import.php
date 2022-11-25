<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
use Illuminate\Database\QueryException;

// die('ok');

    // $uploadedFiles = $request->getUploadedFiles();

    // $t = count($uploadedFiles) ;

    // if($t){
    //     echo 'exist';
    // }else{
    //     echo 'not exist';
    // }
    // die();

// 
// // $request_id = $request->getBody(); // false
// // $request_id = $request->getParsedBody(); // true
// // $request_id = $request->getParsedBody('request_id'); // true
// $request_id = $request->getParam('request_id'); // true
// // $request_id = json_decode($request->getBody());
// // $request_id = $request->getQueryParams('request_id'); //false

// // echo $request_id;
// print_r($request_id);
// die('t');


// throwError($container, $request, [
//     'request_id' => v::length(1, 8)->notEmpty()->noWhitespace(),
// ]);



// or
$body = json_decode($request->getBody());

$request_id = $body->request_id;
// echo $request_id;

print_r($request_id);

if (empty($request_id)) {
    throw new Exception('Invalid data!');
}

// if (!DB::table('request')->where(['id' => trim($request_id)])->where('isdelete', 0)->count()) 
// {
//     throw new Exception('Request not exist');
// }

die();

try {
    require('./shared/getToken.php');

    $uploadedFiles = $request->getUploadedFiles();

    // print_r($uploadedFiles);    die();
    // die($response->withJson($uploadedFiles));

    if (count($uploadedFiles) > 0) {
        // die('ok');
        foreach ($uploadedFiles as $file) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->file);
            $sheet = $spreadsheet->getActiveSheet(0);
            $list = $sheet->getRowIterator();
            $collection = $sheet->getColumnDimensions();
            $indexKey = [];
            $numberOk = 0;
            $listError = [];
            foreach ($list as $index => $row) {
                if ($index > 1) {
                    $item = [];
                    foreach ($collection as $key => $val) {
                        $item[$indexKey[$key]] = $sheet->getCell($key . $index)->getValue();
                    }
                    //Kiểm tra department
                    $department = DB::table('positions')->where(['title' => trim($item['department']), 'parent_id' => 0, 'status' => 1, 'isdelete' => 0])->first();
                    if (!$department) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Department not found'];
                        continue;
                    }
                    // echo $department->id;
                    // die($department->id);
                    //Kiểm tra position
                    $position = DB::table('positions')->where(['title' => trim($item['position']), 'parent_id' => $department->id, 'status' => 1, 'isdelete' => 0])->first();
                    if (!$position) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Position not found'];
                        continue;
                    }

                    //Kiểm tra level
                    $level = DB::table('level')->where(['title' => trim($item['level']), 'status' => 1, 'isdelete' => 0])->first();
                    if (!$level) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Level not found'];
                        continue;
                    }
                    //Tìm kiếm Request mới Nhất
                    // $requests = DB::table('request')->select('request.*')->join('request_level', 'request_level.request_id', '=', 'request.id')->get();
                    // $requestors = DB::table('request')->where(['isdelete' => 0])->whereIn('status', [2,4])->get();

                    // $items = [];

                    // foreach($requestors as $key => $item){
                    //     // echo $key;
                    //      // print_r($item->target);
                    //      // echo $item->deadline;
                    //     $items[$item->id] =  
                    //     // [ 'Requestor: '. $item->requestor_id .'- Number:' .$item->target. '- Deadline:' .$item->deadline];
                    //     [
                    //         $item->requestor_id,
                    //         $item->target,
                    //         $item->deadline 
                    //     ];
                        
                    //     // $items['requestor']['author'] = $item->requestor_id;
                    //     // $items['requestor']['tagrget'] = $item->target;
                    //     // $items['requestor']['deadline'] = $item->deadline;                      
                    // }
                    // // die();

                    // // die($response->withJson($requests));
                    // die($response->withJson($items));



                    // $request = DB::table('request')->select('request.*')->join('request_level', 'request_level.request_id', '=', 'request.id')->where(['position_id' => $position->id, 'level_id' => $level->id, 'status' => 2, 'request.isdelete' => 0])->first();
                    // if (!$request) {
                    //     $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Request not found'];
                    //     continue;
                    // }


                    //Kiểm tra source
                    $source = DB::table('source')->where(['title' => trim($item['source']), 'status' => 1, 'isdelete' => 0])->first();
                    if (!$source) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Source not found'];
                        continue;
                    }
                    //Kiểm tra interviewer
                    $interviewer = DB::table('users')->where(['username' => trim($item['interviewer']), 'status' => 1, 'isdelete' => 0])->first();
                    if (!$interviewer) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Interviewer not found'];
                        continue;
                    }
                    //Kiểm tra reviewer
                    $reviewer = DB::table('users')->where(['username' => trim($item['reviewer']), 'status' => 1, 'isdelete' => 0])->first();
                    if (!$reviewer) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Reviewer not found'];
                        continue;
                    }
                    //Kiểm tra assignee
                    $assignee = DB::table('users')->where(['username' => trim($item['assignee']), 'status' => 1, 'isdelete' => 0])->first();
                    if (!$assignee) {
                        $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Assignee not found'];
                        continue;
                    }
                    if (!empty($item['gender'])) {
                        if (strtolower($item['gender']) == 'nam') {
                            $item['gender'] = 1;
                        } else {
                            $item['gender'] = 0;
                        }
                    } else {
                        unset($item['gender']);
                    }
                    unset($item['department']);
                    unset($item['position']);
                    unset($item['level']);
                    unset($item['source']);
                    unset($item['interviewer']);
                    unset($item['reviewer']);
                    unset($item['assignee']);
                    $item['author_id'] = $user->username;
                    $item['position_id'] = $position->id;
                    // $item['request_id'] = $request->id;
                    $item['request_id'] = $request_id;
                    $item['level_id'] = $level->id;
                    $item['source_id'] = $source->id;
                    $item['interviewer_id'] = $interviewer->username;
                    $item['reviewer_id'] = $reviewer->username;
                    $item['assignee_id'] = $assignee->username;
                    $item['images'] = json_encode(explode(',', $item['images']));
                    $dob = explode('/', $item['onboard']);
                    if (count($dob) == 3) {
                        $item['onboard'] = $dob[2] . '-' . $dob[1] . '-' . $dob[0];
                    } else unset($item['onboard']);

                    $dbd = explode('/', $item['birthday']);
                    if (count($dbd) == 3) {
                        $item['birthday'] = $dbd[2] . '-' . $dbd[1] . '-' . $dbd[0];
                    } else unset($item['birthday']);
                    $numberOk++;
                    $item['datecreate'] = time();
                    $item['datemodified'] = time();
                    $item['step'] = 0;
                    $item['status'] = 2;
                    $item['isdelete'] = 0;
                    $newdata = removecolumn('cv', $item);

    // die($response->withJson($newdata));

                    // $id = DB::table('cv')->insertGetId($newdata);
                    // DB::table('cv_history')->insert(['author_id'=>$user->username,'cv_id'=>$id,'description'=>'CV được import từ Excel','datecreate'=>time()]);

                    // if ($index == 101) break;    //line > 100
                } else {
                    foreach ($collection as $key => $val) {
                        $indexKey[$key] = $sheet->getCell($key . $index)->getValue();
                    }
                }
            }
            $results = ['status' => 'success', 'data' => $numberOk, 'error' => $listError, 'time' => time()];
        }
    }
} catch (Exception $e) {
    $obj = @json_decode($e->getMessage());
    if (is_object($obj)) {
        $httpStatus = $obj->status;
        $results = ['status' => 'error', 'message' => $obj->message, 'code' => $obj->code];
        if (!empty($obj->more)) {
            $results['more'] = $obj->more;
        }
    } else {
        $httpStatus = 201;
        $results = ['status' => 'error', 'message' => $e->getMessage(), 'code' => 'fatalerror'];
    }
}
