<?php
/**
 * @license   MIT
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午1:16
 */

define('APP_ROOT', dirname(dirname(__FILE__)));
require APP_ROOT . '/vendor/autoload.php';
use Courser\App;
use Courser\Server\SwooleServer;
use Knight\Middleware\Cors;
use Knight\Middleware\Auth;
use Hayrick\Http\Request;
use Hayrick\Http\Response;
use Ben\Config;

Config::load(APP_ROOT . '/api/config');
$app = new App();
$cors = new Cors();

$app->used($cors);

$app->get('/posts', [Knight\Controller\Article::class, 'posts']);
$app->get('/posts/:id', [Knight\Controller\Article::class, 'detail']);
$app->get('/posts/:id/comments', [Knight\Controller\Article::class, 'comments']);
$app->post('/posts/:id/comments', [Knight\Controller\Comment::class , 'add']);
$app->get('category', [Knight\Controller\Category::class , 'list']);
$app->post('/login', [Knight\Controller\Auth::class, 'login']);
$app->group('/admin', function () {
    $auth = new Auth(Config::get('jwt'), 'knight');
    $this->used($auth);
    $this->get('/survey', [Knight\Controller\Admin::class, 'survey']);
    $this->get('/article', [Knight\Controller\Article::class, 'article']);
    $this->get('/article/:id', [Knight\Controller\Admin::class, 'detail']);
    $this->delete('/article/:id', [Knight\Controller\Admin::class, 'drop']);
    $this->put('/article/:id', [Knight\Controller\Admin::class, 'edit']);
    $this->post('/article', [Knight\Controller\Admin::class, 'create']);
    $this->get('/comments', [Knight\Controller\Admin::class, 'comments']);
    $this->post('/category', [Knight\Controller\Category::class, 'create']);
    $this->delete('/category/:id', [Knight\Controller\Category::class, 'drop']);
});

$app->used(function (Request $req, Closure $next) {
    $response = $next($req);
    if (!$response instanceof Response) {
        $response = new Response();
    }

    return $response->withStatus(404)->json(['message' => 'Not Found']);
});

$app->setReporter(function ($err) {
    var_dump($err);
    $res->withStatus(500)->json([
        'message' => 'server error',
        'code' => $err->getMessage(),
    ]);
});

$server = new SwooleServer($app);
$setting = Config::get('server');
$server->bind($setting['host'], $setting['port']);
$server->setting($setting);
$server->start();
