<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
    ->leftJoin('level', 'level.id', '=', 'cv.level_id')
    ->leftJoin('source', 'source.id', '=', 'cv.source_id');

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

$moreselect = ['positions.title AS position_title', 'level.title AS level_title', 'source.title AS source_title'];
