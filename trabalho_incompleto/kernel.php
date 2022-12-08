<?php

// PADRÃO DE CONFIGURAÇÃO DO TWIG (serviço de templates)
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);


// TENTATIVA DE CONEXÃO DO BANCO DE DADOS
// Retorno da variavel $db com a conexão.
try {
    $db = new PDO("sqlite:database.sqlite");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
    die();
}


// FACILITADOR DE include_once FAZENDO COM QUE TODOS
// OS CONTROLLERS
foreach (glob("src/*.php") as $filename)
{
    include_once $filename;
}