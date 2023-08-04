<?php

use Illuminate\Database\Capsule\Manager as DB;

use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'id_cv' =>  v::digit()->notEmpty(),
]);

if (!empty($data->id_cv)) {
    if (!DB::table('cv')->where('id', $data->id_cv)->where('isdelete', 0)->count()) {
        throw new Exception('CV not exist');
    }
}

// với criteria là array
$exist_review = [];

$empty_review = [];

$id_criterias = [];

$id_reviews = [];

$id_empty_reviews = [];

foreach ($data->criterias as $key => $reviews) {
    if (!empty($reviews[2]) && !empty($reviews[3])) {
        !empty($reviews[0]) ? array_push($exist_review, $reviews[0]) : '';

        array_push($id_criterias, $reviews[0]);

        if (!empty($reviews[1])) {
            !empty($reviews[1]) ? array_push($id_reviews, $reviews[1]) : '';
        }

        if (empty($reviews[1])) {
            !empty($reviews[0]) ? array_push($id_empty_reviews, $reviews[0]) : '';
        }
    }
}

if (!empty($id_criterias)) {
    foreach ($id_criterias as $key => $value) {
        if (!DB::table('criteria')->whereIn('id', [$value])->where('isdelete', 0)->count()) {
            throw new Exception('Criteria not exist');
        }
    }
}

// exist range and review thì mới thêm sửa trong csdl
if (!empty($id_reviews)) {
    foreach ($id_reviews as $key => $value) {
        $check_review = DB::table('reviews')->where('id', $value)->first();

        if (!empty($check_review)) {
            foreach ($data->criterias as $key => $reviews) {
                if ($value === $reviews[1]) {
                    DB::table('criteria_review')->where('id_review', $value)->update([
                        'range' => !empty($reviews[2]) ? trim($reviews[2]) : null
                    ]);

                    DB::table('reviews')->where('id', $value)->update([
                        'review' => !empty($reviews[3]) ? trim($reviews[3]) : null,
                        'datemodified' => time()
                    ]);
                }
            }
        }
    }
}

if (!empty($id_empty_reviews)) {
    foreach ($id_empty_reviews as $key => $value) {
        foreach ($data->criterias as $key => $reviews) {
            if ($value === $reviews[0]) {
                $insertGetId = DB::table('reviews')->insertGetId([
                    'id_cv' => $data->id_cv,
                    'author_id' => $user->username,
                    'review' => !empty($reviews[3]) ? trim($reviews[3]) : null,
                    'datecreate' => time(),
                    'datemodified' => time()
                ]);

                if ($insertGetId) {
                    DB::table('criteria_review')->insertGetId([
                        'id_criteria' => $value,
                        'id_review' => $insertGetId,
                        'range' => !empty($reviews[2]) ? trim($reviews[2]) : null
                    ]);
                }
            }
        }
    }
}
// die('ok');


/*
// với criteria là json
$exist_review = [];

$empty_review = [];

$id_criterias = [];

$id_reviews = [];

// die('ok');
foreach ($data->criterias as $key => $reviews) {
    if (!empty($reviews->range) && !empty($reviews->review)) {
        !empty($reviews->criteria_id) ? array_push($exist_review, $reviews->criteria_id) : '';

        array_push($id_criterias, $reviews->criteria_id);

        if (!empty($reviews->review_id)) {
            !empty($reviews->review_id) ? array_push($id_reviews, $reviews->review_id) : '';
        }

        if (empty($reviews->review_id)) {
            !empty($reviews->criteria_id) ? array_push($id_empty_reviews, $reviews->criteria_id) : '';
        }
    }
}

if (!empty($id_criterias)) {
    foreach ($id_criterias as $key => $value) {
        if (!DB::table('criteria')->whereIn('id', [$value])->where('isdelete', 0)->count()) {
            throw new Exception('Criteria not exist');
        }
    }
}

// exist range and review thì mới thêm sửa trong csdl
if (!empty($id_reviews)) {
    foreach ($id_reviews as $key => $value) {
        $check_review = DB::table('reviews')->where('id', $value)->first();

        if (!empty($check_review)) {
            foreach ($data->criterias as $key => $reviews) {
                if ($value === $reviews->review_id) {
                    DB::table('criteria_review')->where('id_review', $value)->update([
                        'range' => !empty($reviews->range) ? trim($reviews->range) : null
                    ]);

                    DB::table('reviews')->where('id', $value)->update([
                        'review' => !empty($reviews->review) ? trim($reviews->review) : null,
                        'datemodified' => time()
                    ]);
                }
            }
        }
    }
}

if (!empty($id_empty_reviews)) {
    foreach ($id_empty_reviews as $key => $value) {
        foreach ($data->criterias as $key => $reviews) {
            if ($value === $reviews->criteria_id) {
                $insertGetId = DB::table('reviews')->insertGetId([
                    'id_cv' => $data->id_cv,
                    'author_id' => $user->username,
                    'review' => !empty($reviews->review) ? trim($reviews->review) : null,
                    'datecreate' => time(),
                    'datemodified' => time()
                ]);

                if ($insertGetId) {
                    DB::table('criteria_review')->insertGetId([
                        'id_criteria' => $value,
                        'id_review' => $insertGetId,
                        'range' => !empty($reviews->range) ? trim($reviews->range) : null
                    ]);
                }
            }
        }
    }
}
*/













// exist range or exist review thì mới thêm sửa trong csdl //
/*
foreach ($data->criterias as $key => $reviews) {
    !empty($reviews[1]) ? array_push($exist_review, $reviews[1]) : array_push($empty_review, $reviews[0]);
}

if(!empty($exist_review)) {
    foreach ($exist_review as $key => $value) {
       $check_criteria_review = DB::table('criteria_review')->where('id_review', $value)->first();

       if(!empty($check)){
            foreach ($data->criterias as $key => $reviews) {
                if($value=== $reviews[1]){
                    DB::table('criteria_review')->where('id_review', $check->id_review)->update([
                        'range' => !empty($reviews[2]) ? trim($reviews[2]) : null
                    ]);

                    DB::table('reviews')->where('id', $check->id_review)->update([
                        'review' => !empty($reviews[3]) ? trim($reviews[3]) : null
                    ]);
                }
            }
       }
    }
}

if(!empty($empty_review)) {
    foreach ($empty_review as $key => $value) {
        foreach ($data->criterias as $key => $reviews) {
            if($value === $reviews[0]) {
                if(!empty($reviews[3])) {
                    $insertGetId = DB::table('reviews')->insertGetId([
                        'review' => !empty($reviews[3]) ? trim($reviews[3]) : null
                    ]);

                    if($insertGetId){                    
                        DB::table('criteria_review')->insertGetId([
                            'id_criteria' => $reviews[0],
                            'id_review' => $insertGetId,
                            'range' => !empty($reviews[2]) ? trim($reviews[2]) : null
                        ]);
                    }
                }              
            }
        }
    }
}
*/



// // "criterias": [
//       ["id_criteria", 'id_review', range, 'review...'],
// ]



// "id_cv": 13,
// "criterias": [
//       ["1", '', 30, 'review...'],
//       ["2", '', 37, 'review...'],
//       ["5", '4', 65, '']
//       ["5", '', null, '']
//   ]

// jwt,
// cookie,
// header