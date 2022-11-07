<?php

use Respect\Validation\Validator as v;

$name= 'email_history';
throwError($container, $request, [
    'cv_id' => v::digit()->notEmpty(),
    'email_id' => v::digit()->notEmpty(),
    'email' => v::email()->length(6, 200)->notEmpty(),
    'title' => v::length(3, 200)->notEmpty()
]);

