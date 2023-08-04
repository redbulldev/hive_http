<?php

use Respect\Validation\Validator as v;

throwError($container, $request, [
    'hr_notes' => v::length(2, 5000)->notEmpty(),
]);

if (!empty($data->hr_notes)) {
    $data->notes = $data->hr_notes;
}

$data->status = $data->hr_status;

if ($data->hr_status === 0) {
    $data->status = $data->hr_status;
} else {
    $data->status = !empty($data->hr_status) ? $data->hr_status : 0;
}

require('cv_review_interview.php');
