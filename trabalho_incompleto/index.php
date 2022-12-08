<?php
session_start();

require 'vendor/autoload.php';
require 'kernel.php';

$router = new AltoRouter();

$router->addRoutes([
    ['GET', '/', 'App#index'],
    ['GET|POST', '/entrar', 'App#entrar'],
    ['GET|POST', '/cadastro', 'App#cadastro'],
    ['GET', '/sair', 'Usuario#sair'],
    ['GET', '/estacionamento', 'Estacionamento#index'],
    ['GET|POST', '/estacionamento/[i:id]', 'Estacionamento#detalhar'],
    ['GET', '/usuario', 'Usuario#index'],
    ['GET', '/usuario/carteira', 'Usuario#carteira'],
    ['GET', '/usuario/informacao', 'Usuario#informacao'],
    ['GET', '/usuario/historico', 'Usuario#historico'],
    ['GET', '/usuario/adm', 'Usuario#adm'],
    ['GET', '/usuario/carteiraAdm', 'Usuario#carteiraAdm'],          
    ['GET', '/usuario/estacionamentoCadastrado', 'Usuario#estacionamentoCadastrado'],
    ['GET', '/usuario/reserva', 'Usuario#reserva'],
    ['GET', '/usuario/reservaConfirmada', 'Usuario#reservaConfirmada'],
    ['GET', '/usuario/reservaPendente', 'Usuario#reservaPendente'],
    ['GET', '/usuario/reservaRecusada', 'Usuario#reservaRecusada'],
    ['GET', '/usuario/editarUsuario', 'Usuario#editarUsuario']
]);

$match = $router->match();

list( $controller, $action ) = explode( '#', $match['target'] );

if ( is_callable(array($controller, $action)) ) {

    $obj = new $controller();
    call_user_func_array(array($obj,$action), array($match['params']));

} else if ($match['target']==''){
    echo 'Error: no route was matched'; 

} else {
    echo 'Error: can not call '.$controller.'#'.$action; 

}