<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(E_ALL ^ E_DEPRECATED);
require 'vendor/autoload.php';
require('config.php');
require('model.php');
use Illuminate\Database\Capsule\Manager as DB;

$db = new DB;
$db->addConnection([
    "driver" => "mysql",
    "host" => $conf['dbhost'],
    "database" => $conf['dbname'],
    "username" => $conf['dbuser'],
    "password" => $conf['dbpass'],
    "charset" => 'UTF8'
]);

$db->setAsGlobal();
$db->bootEloquent();