<?php

use Illuminate\Database\Capsule\Manager as DB;

error_reporting(E_ALL ^ E_NOTICE);

try {
    require './shared/getToken.php';

    $body = $request->getParsedBody();

    $request_id = (int)$body['request_id'];

    if (empty($request_id)) {
        @json_decode(throw new Exception('Invalid data!'));
    }

    if (!empty($request_id)) {
        if (!DB::table('request')->where(['id' => $request_id])->where('isdelete', 0)->count()) {
            @json_decode(throw new Exception('Request not exist'));
        }
    }

    $uploadedFiles = $request->getUploadedFiles();

    if (count($uploadedFiles) > 0) {
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

                    if (empty($item['fullname']) || empty($item['position']) || empty($item['level'])) {
                        if (empty($item['fullname'])) {
                            $listError[] = ['row' => $index, 'fullname' => null, 'message' => 'Invalid column: fullname!'];
                        }

                        if (empty($item['position'])) {
                            $listError[] = ['row' => $index, 'fullname' => !empty($item['fullname']) ? $item['fullname'] : null, 'message' => 'Invalid column: position!'];
                        }

                        if (empty($item['level'])) {
                            $listError[] = ['row' => $index, 'fullname' => !empty($item['fullname']) ? $item['fullname'] : null, 'message' => 'Invalid column: level!'];
                        }
                    } else {
                        //Kiểm tra department
                        if (!empty($item['department'])) {
                            $department = DB::table('positions')->where(['title' => trim($item['department']), 'parent_id' => 0, 'status' => 1, 'isdelete' => 0])->first();

                            if (!$department) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Department not found'];

                                continue;
                            }
                        }

                        //Kiểm tra position
                        if (!empty($item['position'])) {
                            $position = DB::table('positions')->where(['title' => trim($item['position']), 'parent_id' => $department->id, 'status' => 1, 'isdelete' => 0])->first();

                            if (!$position) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Position not found'];

                                continue;
                            }
                        }

                        //Kiểm tra level
                        if (!empty($item['level'])) {
                            $level = DB::table('level')->where(['title' => trim($item['level']), 'status' => 1, 'isdelete' => 0])->first();

                            if (!$level) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Level not found'];

                                continue;
                            }
                        }

                        //Kiểm tra source
                        if (!empty($item['source'])) {
                            $source = DB::table('source')->where(['title' => trim($item['source']), 'status' => 1, 'isdelete' => 0])->first();

                            if (!$source) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Source not found'];

                                continue;
                            }
                        }

                        //Kiểm tra interviewer
                        if (!empty($item['interviewer'])) {
                            $interviewer = DB::table('users')->where(['username' => trim($item['interviewer']), 'status' => 1, 'isdelete' => 0])->first();

                            if (!$interviewer) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Interviewer not found'];

                                continue;
                            }
                        }

                        //Kiểm tra reviewer
                        if (!empty($item['reviewer'])) {
                            $reviewer = DB::table('users')->where(['username' => trim($item['reviewer']), 'status' => 1, 'isdelete' => 0])->first();

                            if (!$reviewer) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Reviewer not found'];

                                continue;
                            }
                        }

                        //Kiểm tra assignee
                        if (!empty($item['assignee'])) {
                            $assignee = DB::table('users')->where(['username' => trim($item['assignee']), 'status' => 1, 'isdelete' => 0])->first();

                            if (!$assignee) {
                                $listError[] = ['row' => $index, 'fullname' => $item['fullname'], 'message' => 'Assignee not found'];

                                continue;
                            }
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

                        $item['author_id'] = 'hungnv1';

                        if (!empty($position->id)) {
                            $item['position_id'] = $position->id;
                        }

                        if (!empty($level->id)) {
                            $item['level_id'] = $level->id;
                        }

                        $item['request_id'] = $request_id;

                        $item['source_id'] = !empty($source->id) ? $source->id : null;

                        $item['interviewer_id'] = !empty($interviewer->username) ? $interviewer->username : null;

                        $item['reviewer_id'] = !empty($reviewer->username) ? $reviewer->username : null;

                        $item['assignee_id'] = !empty($assignee->username) ? $assignee->username : null;

                        $item['images'] = !empty($item['images']) ? json_encode(explode(',', $item['images'])) : null;

                        if (!empty($item['onboard'])) {
                            $dob = explode('/', $item['onboard']);

                            if (count($dob) == 3) {
                                $item['onboard'] = $dob[2] . '-' . $dob[1] . '-' . $dob[0];
                            } else {
                                unset($item['onboard']);
                            }
                        }

                        if (!empty($item['birthday'])) {
                            $dbd = explode('/', $item['birthday']);

                            if (count($dbd) == 3) {
                                $item['birthday'] = $dbd[2] . '-' . $dbd[1] . '-' . $dbd[0];
                            } else {
                                unset($item['birthday']);
                            }
                        }

                        $numberOk++;

                        $item['datecreate'] = time();

                        $item['datemodified'] = time();

                        $item['step'] = 0;

                        $item['status'] = 2;

                        $item['isdelete'] = 0;

                        $newCv = removecolumn('cv', $item);

                        $cvId = DB::table('cv')->insertGetId($newCv);

                        DB::table('cv_history')->insert(['author_id' => 'hungnv1', 'cv_id' => $cvId, 'description' => 'CV được import từ Excel', 'datecreate' => time()]);
                        
                        if ($index == 101) {
                            break;
                        }
                    }
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
