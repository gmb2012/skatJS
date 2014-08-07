<?php
date_default_timezone_set('Europe/Berlin');

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// config
$dbConfig = new stdClass();
$dbConfig->uri = 'mongodb://localhost:27017';
$dbConfig->db = 'skatJS';

$app->config('dbConfig', $dbConfig);

// enable json output
$app->response()->header('Content-Type', 'application/json');

/* PLAYERS */
// get all players
$app->get('/players', function () use ($app) {
    echo json_encode(\SkatJS\Util\JsonHelper::toJsonList((new \SkatJS\Repository\PlayerRepository($app->config('dbConfig')))->findAll()), JSON_PRETTY_PRINT);
});

// add new player
$app->post('/players/new', function () use ($app) {
    try {
        $player = new \SkatJS\Model\Player(new \SkatJS\Repository\PlayerRepository($app->config('dbConfig')));
        $player->fromJson(json_decode($app->request->getBody()));
        $player->save();
        echo json_encode($player->toJson(), JSON_PRETTY_PRINT);
    } catch(\SkatJS\Exception\DuplicateException $e) {
        $app->response->setStatus(409);
        $error = new stdClass();
        $error->message = "Player already exists";
        echo json_encode($error, JSON_PRETTY_PRINT);
    }
});

/* MATCHES */
// get match by id
$app->get('/matches/:id', function ($id) use ($app) {
    echo json_encode((new \SkatJS\Repository\MatchRepository($app->config('dbConfig')))->findById($id)->toJson(), JSON_PRETTY_PRINT);
});

// get all matches
$app->get('/matches', function () use ($app) {
    echo json_encode(\SkatJS\Util\JsonHelper::toJsonList((new \SkatJS\Repository\MatchRepository($app->config('dbConfig')))->findAllOldestFirst()), JSON_PRETTY_PRINT);
});

// add new match
$app->post('/matches/new', function () use ($app) {
    $match = new \SkatJS\Model\Match(new \SkatJS\Repository\MatchRepository($app->config('dbConfig')));
    $match->fromJson(json_decode($app->request->getBody()));

    $match->save();
    echo json_encode($match->toJson(), JSON_PRETTY_PRINT);
});

/* GAMES */
// add new game
$app->post('/games/new', function () use ($app) {
    $game = new \SkatJS\Model\Game(new \SkatJS\Repository\GameRepository($app->config('dbConfig')));
    $game->fromJson(json_decode($app->request->getBody()));

    $game->save();
    echo json_encode($game->toJson(), JSON_PRETTY_PRINT);
});

$app->get('/games/byMatch/:matchId', function ($matchId) use ($app) {
    echo json_encode(\SkatJS\Util\JsonHelper::toJsonList((new \SkatJS\Repository\GameRepository($app->config('dbConfig')))->findByMatchId($matchId)), JSON_PRETTY_PRINT);
});

$app->run();
