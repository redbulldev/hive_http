<?php

use Illuminate\Database\Capsule\Manager as DB;

$check_favorite = '';

if (!empty($params['favorite'])) {
    $check_favorite = $params['favorite'];
}

if ($check_favorite === '1') {
    $obj->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
        ->leftJoin('level', 'level.id', '=', 'cv.level_id')
        ->leftJoin('source', 'source.id', '=', 'cv.source_id')
        ->join('cv_favorite', 'cv_favorite.cv_id', '=', 'cv.id')->where('cv_favorite.status', 1);
} else {
    $obj->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
        ->leftJoin('level', 'level.id', '=', 'cv.level_id')
        ->leftJoin('source', 'source.id', '=', 'cv.source_id')
        ->leftJoin('cv_favorite', 'cv_favorite.cv_id', '=', 'cv.id');
};

if (!empty($params['company_id'])) {
    $company_ids = explode('-', $params['company_id']);

    $obj->leftJoin('request', 'request.id', '=', 'cv.request_id')->where(function($query) use ($company_ids){
        foreach ($company_ids as $id) {
            $query->orWhere('request.company_id', $id);
        }
    });
}

if (!empty($params['progress'])) {
    $idprogress = explode('-', $params['progress']);

    if (count($idprogress)) {
        $obj->where(function ($query) use ($idprogress) {
            $query->orWhere(function ($query) use ($idprogress) {
                $query->whereIn('cv.step', [0, 1, 2, 4, 5, 8, 9])->whereIn('cv.assignee_id', $idprogress);
            });

            $query->orWhere(function ($query) use ($idprogress) {
                $query->whereIn('cv.step', [3])->whereIn('cv.reviewer_id', $idprogress);
            });

            $query->orWhere(function ($query) use ($idprogress) {
                $query->whereIn('cv.step', [5])->whereIn('cv.interviewer_id', $idprogress);
            });
            
            $query->orWhere(function ($query) use ($idprogress) {
                $query->whereIn('cv.step', [6, 7])->whereIn('cv.chairman_id', $idprogress);
            });
        });
    }
}

if (empty($permission->cv->all)) {
    $obj->where(function ($query) use ($user) {
        $query->orWhere('cv.author_id', $user->username)
            ->orWhere('cv.interviewer_id', $user->username)
            ->orWhere('cv.reviewer_id', $user->username)
            ->orWhere('cv.assignee_id', $user->username);
    });
}

if (!empty($params['company_id'])) {
    $moreselect = ['positions.title AS position_title', 'level.title AS level_title', 'source.title AS source_title', 'cv_favorite.status AS favorite', 'request.company_id'];
} else {
    $moreselect = ['positions.title AS position_title', 'level.title AS level_title', 'source.title AS source_title', 'cv_favorite.status AS favorite'];
}
