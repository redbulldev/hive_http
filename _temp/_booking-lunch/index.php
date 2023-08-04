<?php
require(__DIR__.'/initiate.php');
use Slim\App;
use Anddye\Validation\Validator;
use Respect\Validation\Validator as v;

$config = ['settings' => ['displayErrorDetails' => true]];
$app = new App($config);
$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/'.$conf['folder_upload'];
$container['validationService'] = function () {
    return new Validator();
};

// $container['phpErrorHandler'] = function () {
//     return function ($request, $response, $exception)  {
//         $results = ['status' => 'error', 'message' => 'Something went wrong:', 'code' => 'notfound','error'=>$exception->getMessage()];
//         return $response->withJson($results,500);
//     };
// };

$container['notFoundHandler'] = function () {
    return function ($request, $response, $exception)  {
        $results = ['status' => 'error', 'message' => 'API not found', 'code' => 'notfound'];
        return $response->withJson($results,404);
    };
};

// $container['notAllowedHandler'] = function () {
//     return function ($request, $response, $exception)  {
//         $results = ['status' => 'error', 'message' => 'Method not allowed', 'code' => 'notallow'];
//         return $response->withJson($results,405);
//     };
// };
// Register Smarty View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Smarty('templates', [
        'cacheDir' => 'cache',
        'compileDir' => 'compile'
    ]);
    // Add Slim specific plugins
    $smartyPlugins = new \Slim\Views\SmartyPlugins($c['router'], $c['request']->getUri());
    $view->registerPlugin('function', 'path_for', [$smartyPlugins, 'pathFor']);
    $view->registerPlugin('function', 'base_url', [$smartyPlugins, 'baseUrl']);

    return $view;
};
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response;
});
$container['upload_directory'] = __DIR__ . '/uploads';


$app->get('/scan', function ($request, $response, $args) {
    $data = scan();
    $results = ['status' => 'ok', 'message' => 'no', 'data'=> $data];
    return $response->withJson($results, intval(200));
});

$app->get('/', function ($request, $response, $args) {
    $data=[
        'title'=>'HR TOOL API',
        'description'=>'',
    ];
    return $this->view->render($response, 'index.html', $data);
});
// API Hiển thị danh sách nhiều dòng dữ liệu
$app->get('/v1/{name}', function ($request, $response, $args) use ($conf,$container) {
    $httpStatus = 200;
    $name = str_replace('-','_',$args['name']);
    if ($name != '' && file_exists(__DIR__ . '/method/all/' . $name . '.php')) {
        require('method/all/' . $name . '.php');
    } else require('crud/all.php');
    return $response->withJson($results,intval($httpStatus));
});
// API hiển thị 1 bản ghi dữ liệu với ID truyền vào
$app->get('/v1/{name}/{id}', function ($request, $response, $args)  use ($conf,$container) {
    $httpStatus = 200;
    $name = str_replace('-','_',$args['name']);
    if ($name != '' && file_exists(__DIR__ . '/method/one/' . $name . '.php')) {
        require('method/one/' . $name . '.php');
    } else require('crud/one.php');
    return $response->withJson($results,intval($httpStatus));
});
//Thêm mới dữ liệu hoặc API nhận post data
$app->post('/v1/{name}', function ($request, $response, $args) use ($conf,$container) {
    $httpStatus = 200;
    $name = str_replace('-','_',$args['name']);
    if ($name != '' && file_exists(__DIR__ . '/method/post/' . $name . '.php')) {
        require('method/post/' . $name . '.php');
    } else require('crud/add.php');
    return $response->withJson($results,intval($httpStatus));
});

//Sửa dữ liệu
$app->put('/v1/{name}/{id}', function ($request, $response, $args)   use ($conf,$container) {
    
    $httpStatus = 200;
    $name = str_replace('-','_',$args['name']);
    if ($name != '' && file_exists(__DIR__ . '/method/put/' . $name . '.php')) {
        require('method/put/' . $name . '.php');
    } else require('crud/edit.php');
    return $response->withJson($results,intval($httpStatus));
});
//Xóa dữ liệu
$app->delete('/v1/{name}/{id}', function ($request, $response, $args)   use ($conf) {
    $httpStatus = 200;
    $name = str_replace('-','_',$args['name']);
    if ($name != '' && file_exists(__DIR__ . '/method/delete/' . $name . '.php')) {
        require('method/delete/' . $name . '.php');
    } else require('crud/delete.php');
    return $response->withJson($results,intval($httpStatus));
});
//Xóa dữ liệu
$app->delete('/v1/{name}', function ($request, $response, $args)   use ($conf) {
    $httpStatus = 200;
    $name = str_replace('-','_',$args['name']);
    if ($name != '' && file_exists(__DIR__ . '/method/delete/' . $name . '-all.php')) {
        require('method/delete/' . $name . '-all.php');
    } else require('crud/delete-all.php');
    return $response->withJson($results,intval($httpStatus));
});
$app->run();
