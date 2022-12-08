<?php
// $twig ESTÁ VINDO O KERNEL

class App {
    public static function index() {
        global $twig;

        if($_SESSION['usuario']){
          header("Location: /estacionamento");
        }
      
        echo $twig->render(
          'app/index.html'
        );
    }

  
  public static function entrar() {
        global $twig;

        if($_SESSION['usuario']){
          header("Location: /usuario");
        }

        $retorno = false;
    
        if(isset($_POST['login'])){
          $retorno = self::logar_usuario();
        }
      
        echo $twig->render(
          'app/entrar.html',
          ["notificacao" => $retorno]
        );
    }

  public static function cadastro() {
        global $twig;

        if($_SESSION['usuario']){
          header("Location: /usuario");
        }

        $retorno = false;

        if(count($_POST) > 0){
          $retorno = self::cadastrar_usuario();
        }
      
        echo $twig->render(
          'app/cadastro.html',
          ["notificacao" => $retorno]
        );
    }

  function logar_usuario() {
        global $db;

        $params = $_POST;
        $notificacao = false; 

    if(isset($params['login'], 
            $params['senha']) && 
            !empty($params['login']) && 
            !empty($params['senha']))
    { 
      $login = $params['login'];
      $senha = $params['senha'];
      
      $query = $db->query("SELECT u.rowid as id, u.*, cart.*, ct.* 
                           FROM usuarios AS u 
                           INNER JOIN contatos_usuarios AS ct
                           ON ct.rowid = u.id_contato_usuario
                           INNER JOIN carteira AS cart
                           ON cart.rowid = u.id_carteira
                           WHERE login = ? 
                           AND senha = ?");
      $query->execute([$login, $senha]);
      $infouso = $query->fetch();

      if($infouso){
        $_SESSION['usuario'] = $infouso;
        $notificacao = "Carregando...";
        header('Refresh:0');
      } else {
        $notificacao = "Informação incorreta ou usuário não existe.";
      }
    
    } else{
      $notificacao = "Digite todos os campos!";
    } 
    
    return $notificacao;
  }

  function cadastrar_usuario() {
        global $db;

        $params = $_POST;
        $notificacao = false;
    
        if(isset($params['login'], 
                 $params['senha'], 
                 $params['senha_repetida']) && 
           !empty($params['login']) &&
           !empty($params['senha']) &&
           !empty($params['senha_repetida'])){
          if($params['senha'] === $params['senha_repetida']){
            $login = $params['login'];
            $senha = $params['senha'];
            
            $query = $db->prepare("SELECT * FROM usuarios WHERE login = ?");  
            $query->execute([ $login ]);
            $row = $query->fetch();
            
            if(!$row){
              $carteira = $db->prepare("INSERT INTO carteira VALUES(?)");
              $carteira->execute([0.0]);
              $idCarteira = $db->lastInsertId();
              
              $contatos = $db->prepare("INSERT INTO contatos_usuarios VALUES(?,?,?)");
              $contatos->execute(['', '', '']);
              $idContato = $db->lastInsertId();
              
              $usuario = $db->prepare("INSERT INTO usuarios(id_carteira, id_contato_usuario, nome, cpf, status, tipo, login, senha) VALUES(?,?,?,?,?,?,?,?)");
              $insert = $usuario->execute([$idCarteira, $idContato, $login, '', 1, 1, $login, $senha]);

             if($insert){
               $notificacao = "Usuario criado com sucesso! Faça login.";
             } else {
               $notificacao = "Algo deu errado!";
             }
            } else {
              $notificacao = "Esse usuário já existe.";
            }
          } else {
            $notificacao = "Senhas não são iguais.";
          }
        } else {
          $notificacao = "Preencha todos os campos.";
          }
    
        return $notificacao;
    }
}