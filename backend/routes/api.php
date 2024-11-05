<?php

use App\RouterAPI;

$router = new RouterAPI();


$router->addRoute('GET', '/api/albums', 'AlbumController@index');
$router->addRoute('POST', '/api/albums', 'AlbumController@store');
$router->addRoute('PUT', '/api/albums/{id}', 'AlbumController@update');
$router->addRoute('DELETE', '/api/albums/{id}', 'AlbumController@destroy');

$router->addRoute('POST', '/api/register', 'AuthController@register');
$router->addRoute('POST', '/api/login', 'AuthController@login');
$router->addRoute('GET', '/api/auth/check','AuthController@check');
$router->addRoute('POST', '/api/logout','AuthController@logout');


$router->addRoute('GET', '/api/albums/{id}/images', 'ImageController@index');
$router->addRoute('POST', '/api/albums/{id}/images', 'ImageController@store');


$router->addRoute('GET', '/api/users', 'UserController@index');

$router->addRoute('GET', '/api/uploads/{id}', 'UploadController@getImage');




$router->dispatch();