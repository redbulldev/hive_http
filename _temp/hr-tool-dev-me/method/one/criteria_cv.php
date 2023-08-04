<?php

use Illuminate\Database\Capsule\Manager as DB;

$id = $args['id'];

$table = DB::table('cv');

$criteria =
    $table->where(function ($query) use ($id) {
        $query->where('cv.id', $id);
        $query->where('cv.isdelete', 0);
    })
    ->leftJoin('request', 'request.id', '=', 'cv.request_id')->where(function ($query) {
        $query->where('request.isdelete', 0);
    })
    ->leftJoin('criteria_request', 'criteria_request.id_request', '=', 'request.id')

    // false
    // ->leftJoin('criteria', 'criteria.id', '=', 'criteria_request.id_criteria')->where(function ($query) {
    //     // $query->where(['reviews.status' => 1, 'reviews.isdelete' => 0]);
    // })
    // ->leftJoin('criteria_review', 'criteria_review.id_criteria', '=', 'criteria.id')
    // ->leftJoin('reviews', 'reviews.id', '=', 'criteria_review.id_review')->where(function ($query) {
    //     $query->where('reviews.status', 1);
    //     $query->where('reviews.isdelete', 0);
    // })

    // true
    ->leftJoin('criteria', 'criteria.id', '=', 'criteria_request.id_criteria')->leftJoin('criteria_review', function ($join) {
        $join->on('criteria_review.id_criteria', '=', 'criteria.id');
        $join->where(['criteria.status' => 1, 'criteria.isdelete' => 0]);
    })->leftJoin('reviews', 'reviews.id', '=', 'criteria_review.id_review')->where(function ($query) {
        // $query->where(['reviews.status' => 1, 'reviews.isdelete' => 0]);
    })
    ->get([
        'cv.id AS id_cv',
        'request.id AS id_request',
        'criteria.id AS id_criteria',
        'criteria.name AS criteria_name',
        'reviews.review AS review',
        'criteria_request.id as id', 'criteria_request.range'
    ]);

$results = [
    'status' => 'success',
    'data' => $criteria ? $criteria : null,
    'total' => count($criteria),
    'time' => time()
];

// cv -> request -> criteria_request -> criteria -> criteria_review -> reviews
