<?php
// $twig ESTÁ VINDO O KERNEL

class Estacionamento {
  
  private static $usuario = false;

   function __construct() {
      if($_SESSION['usuario']){
        self::$usuario = $_SESSION['usuario'];
      }

    }
  
  public static function index() {
        global $twig;

        $locais = self::listarTodos();
    
        echo $twig->render(
          'app/estacionamento/index.html', 
          ["estacionamentos" => $locais]
        );
    }

  public static function detalhar($params) {
        global $twig;

        $notificacao = false;
    
        $local = self::listar($params['id']);

        if(isset($_POST['reservar'])){
          $notificacao = self::reservarEstacionamento($local);
        }

      
        echo $twig->render(
          'app/estacionamento/reservar.html',
          ["estacionamento" => $local,
           "reservou" => self::verificarReserva($local)]
        );
    }
  function verificarReserva($local) {
     global $db;
     $idUsuario = self::$usuario['id'];

     $query = $db->prepare("SELECT rowid FROM locacoes WHERE id_usuario = ? AND id_local = ?");      
     $query->execute([ $idUsuario, $local['id'] ]);
     $row = $query->fetch();

     if($row){
       return true;
     }
     
    return false;
  }

  function reservarEstacionamento($local){
    global $db;

    $idUsuario = self::$usuario['id'];

    $reservou = self::verificarReserva($local);
    $notificacao = false;

    if(!$reservou){
      $locacao = $db->prepare("INSERT INTO locacoes(id_usuario, id_local, valor, status) VALUES(?,?,?,?)");
      $insert = $locacao->execute([$idUsuario, $local['id'], floatval($local['preco']), 0]);
             if($insert){
               $notificacao = "Vaga reservada em $local[nome].";
             } else {
               $notificacao = "Algo deu errado!";
             }
    } else {
      $query = $db->prepare("DELETE FROM locacoes WHERE id_usuario = ? AND id_local = ?");      

      if($query->execute([ $idUsuario, $local['id'] ])){
        $notificacao = "Vaga cancelada em $local[nome].";
      } else {
        $notificacao = "Algo deu errado!";
      }
    }
  
    return $notificacao;    
  }

  function listarTodos() {
    global $db;

    $sql = "SELECT * FROM listarLocais;";
    $query = $db->query($sql);
    
    return $query;
    
  }

  function listar(int $id) {
    global $db;

    $query = $db->prepare("
              SELECT l.rowid as id,
                     nome,
                     vagas, 
                     extras, 
                     e.logradouro || ', ' || e.numero AS endereco,
                     preco
              FROM locais AS l
              INNER JOIN enderecos AS e
              ON e.rowid = l.id_endereco
              WHERE l.rowid = ?
            ");
    $query->execute([ $id ]);
    $row = $query->fetch();
    return $row;
    
  }
  
}