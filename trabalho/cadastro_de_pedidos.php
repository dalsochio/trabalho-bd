<?php
require_once 'crud.php';
$p = new item("controle", "localhost", "root", "");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Controle de Pedidos</title>
</head>
<body>
  <?php
  if (isset($_POST['cliente']) && !empty($_POST['cliente'])) {
    $cod_cliente = addslashes($_POST['cliente']);
   if (!$p->cadastroPedido($cod_cliente)) {
     echo "<script>location.href='index.php';</script>";
    }
  } else { ?>
  <div class="alert">
          <h4>Escolha o cliente, por favor !!</h4>
  </div>
 <?php }
  ?>
  <form action="" method="POST">
    <h2>CADASTRO DE PEDIDOS</h2>
    <label for="">Informe Cliente:</label>
    <select name="cliente">
      <?php
      $dados = $p->buscarCliente();
      if (count($dados) > 0) { // Tem items cadastrados no banco 
        foreach ($dados as $key => $value) {
          echo "<option value='{$value['cod_cliente']}'>" . $value['nom_cliente'] . "</option>";
        }
      }
      ?>
    </select>
    <input type="submit" value="Cadastrar">
  </form>
    <a id="voltar" href="index.php">Cancelar</a>
</body>
</html>

