<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

require('cv_review_interview.php');

checkNoteIsEmpty($data->notes, $data->status);
