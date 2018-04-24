<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
include 'DB/DB.php';
include 'Classes/Clip.php';

$settings = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$app = new \Slim\App($settings);

$container = $app->getContainer();
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('views');
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.html');
});

$app->post('/show-clips', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    $query = $body['query'];
    $clips = Clip::getClips($query);

    return $response->withJSON(
           ['clips' => $clips],
           200,
           JSON_UNESCAPED_UNICODE
    );
});

$app->get('/show-clip/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $clip = Clip::getOne($id);

    return $response->withJSON(
           ['clip' => $clip],
           200,
           JSON_UNESCAPED_UNICODE
    );
});

$app->run();