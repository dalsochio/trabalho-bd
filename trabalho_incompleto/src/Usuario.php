<?php
// $twig ESTÁ VINDO O KERNEL

class Usuario {
    private static $usuario = false;
  
    function __construct() {
      if(!$_SESSION['usuario']){
          header("Location: /");
        }

      self::$usuario = $_SESSION['usuario'];
    }

    function render(string $template, array $p = []){
      global $twig;
      $params = array_merge(["usuario" => self::$usuario], []);
  
      echo $twig->render($template, $params);
    }
  
    public static function index() {
        global $twig;
      
        self::render(
          'app/usuario/index.html'
        );
    }

    public static function sair() {
      unset($_SESSION['usuario']);
      header("Refresh:0");
    }

    public static function carteira() {
        global $twig;
      
        self::render(
          'app/usuario/carteira.html' 
        );
    }
  

   public static function informacao() {
        global $twig;
      
        self::render(
          'app/usuario/informacao.html' 
        );
    }

  public static function editarUsuario() {
        global $twig;

      /* $params = $_POST;
       $notificacao = false;

       if(isset
         ($params['nome'], 
          $params['cpf'], 
          $params['email'], 
          $params['telefone'], 
          $params['veiculo']) 
          !empty($params['nome']) && 
          !empty($params['cpf']) && 
          !empty($params['email']) && 
          !empty($params['telefone']) && 
          !empty($params['veiculo']))
         $nome = $params['nome'];
         $cpf = $params['cpf'];
         $email = $params['email'];
         $telefone = $params['telefone'];
         $veiculo = $params['veiculo'];
         
      $query = $db->query = "UPDATE usuarios 
                             SET nome = ?, 
                                 cpf = ?, 
                                 email = ?, 
                                 telefone = ?, 
                                 veiculo = ? "
      $query->execute([$nome, $cpf, $email, $telefone, $veiculo]);
      $editUse =  $query->fetchAll();

    if($editUse){
      
    }





      */
    
        self::render(
          'app/usuario/editarUsuario.html' 
        );
    }

  public static function historico() {
        global $twig;
      
        self::render(
          'app/usuario/historico.html' 
        );
    }

  public static function adm() {
        global $twig;
      
        self::render(
          'app/usuario/adm.html' 
        );
    }

  public static function carteiraAdm() {
        global $twig;
      
        self::render(
          'app/usuario/carteiraAdm.html' 
        );
    }
  public static function estacionamentoCadastrado() {
        global $twig;
      
        self::render(
          'app/usuario/estacionamentoCadastrado.html' 
        );
    }

   public static function cadastroVeiculo() {
        global $twig;
      
        self::render(
          'app/usuario/cadastroVeiculo.html' 
        );
    }

  public static function reserva() {
        global $twig;
      
        self::render(
          'app/usuario/reserva.html' 
        );
    }

  public static function reservaConfirmada() {
        global $twig;
      
        self::render(
          'app/usuario/reservaConfirmada.html' 
        );
    }

   public static function reservaPendente() {
        global $twig;
      
        self::render(
          'app/usuario/reservaPendente.html' 
        );
    }

  public static function reservaRecusada() {
        global $twig;
      
        self::render(
          'app/usuario/reservaRecusada.html' 
        );
    }

  
}