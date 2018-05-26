<?php
/**
 * @license   MIT
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午1:16
 */


use Courser\App;
use Knight\Middleware\Cors;
use Knight\Middleware\Auth;
use Psr\Http\Message\RequestInterface;
use Hayrick\Http\Response;
use Ben\Config;
use Knight\Middleware\NotFound;

$app = new App();
$cors = new Cors();
$app->add($cors);
$app->add(new \Knight\Middleware\Multipart());

$app->get('/posts', [Knight\Controller\Article::class, 'posts']);
$app->get('/posts/:id', [Knight\Controller\Article::class, 'detail']);
$app->get('/posts/:id/comments', [Knight\Controller\Article::class, 'comments']);
$app->post('/posts/:id/comments', [Knight\Controller\Comment::class , 'add']);
$app->get('category', [Knight\Controller\Category::class , 'list']);
$app->post('/login', [Knight\Controller\Auth::class, 'login']);
$app->get('/article', [Knight\Controller\Admin::class, 'article']);
// $app->post('/photos', [Knight\Controller\Photo::class, 'create']);
$app->get('/albums', [Knight\Controller\Album::class, 'list']);
$app->get('/albums/:albumId/photos', [Knight\Controller\Album::class, 'photos']);
$app->group('/admin', function (App $app) {
    $auth = new Auth(Config::get('jwt'), 'knight');
    $app->add($auth);
    $app->post('/photos', [Knight\Controller\Photo::class, 'create']);
    $app->get('/survey', [Knight\Controller\Admin::class, 'survey']);
    $app->get('/article', [Knight\Controller\Admin::class, 'article']);
    $app->get('/article/:id', [Knight\Controller\Admin::class, 'detail']);
    $app->delete('/article/:id', [Knight\Controller\Admin::class, 'drop']);
    $app->put('/article/:id', [Knight\Controller\Admin::class, 'edit']);
    $app->post('/article', [Knight\Controller\Admin::class, 'create']);
    $app->get('/comments', [Knight\Controller\Admin::class, 'comments']);
    $app->post('/category', [Knight\Controller\Category::class, 'create']);
    $app->delete('/category/:id', [Knight\Controller\Category::class, 'drop']);
    $app->get('/all/albums', [Knight\Controller\Album::class, 'all']);
    $app->post('/albums', [Knight\Controller\Album::class, 'create']);

});

$app->add(new NotFound());
$app->setReporter(function(RequestInterface $request, Throwable $err) {
    $response = new Response();
    $response = $response->json([
        'error' => $err->getMessage(),
        'file' => $err->getFile(),
        'line' => $err->getLine(),
    ])->withStatus(500);

    return $response;
});


return $app;




