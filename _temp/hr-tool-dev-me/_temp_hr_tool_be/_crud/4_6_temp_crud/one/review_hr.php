<?php

use Illuminate\Database\Capsule\Manager as DB;

$params = $request->getQueryParams();

$id = $args['id'];

if (!empty($params['checklist'])) {
    if ($params['checklist'] === '1') {
        $one = DB::table('review_hr')
            ->where('review_hr.cv_id', $id)
            ->leftJoin('cv', 'review_hr.cv_id', '=', 'cv.id')
            ->select(
                'review_hr.*',
                'cv.checklist'
            )
            ->first();
    }
} else {
    $one =  DB::table($name)->where('cv_id', $id)->first();
}
