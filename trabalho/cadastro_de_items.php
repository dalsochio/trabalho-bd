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
    if(isset($_GET['cod'])){ // BUSCAR DADOS DOS ITEMS PARA COLOCAR NOS INPUTS
      $item_update = addslashes($_GET['update']);
      $ped_update = addslashes($_GET['cod']);
      $res = $p->buscarDadosItems($ped_update, $item_update);
    } 
  ?>
  <?php 
  if(isset($_POST['qtd']) ){ // SIGNIFICA QUE A PESSOA CLICOU NO BOTÃO PARA CADASTRAR OU EDITAR
   //--------------------------- EDITAR ---------------------------

   if(isset($_GET['update']) && !empty($_GET['update'])){
    $cod_item = addslashes($_POST['item']);
    $qtd_solicitada = addslashes($_POST['qtd']);
    $pre_unitario = addslashes($_POST['pre']);
    $num_seq_item = addslashes($_GET['update']);
    $num_pedido = addslashes($_GET['cod']);
    if( !empty($num_pedido) && !empty($num_seq_item)  && !empty($pre_unitario) && !empty($qtd_solicitada) && !empty($cod_item)){
        // CADASTRAR 
        if(!$p-> atulizarItems($cod_item, $qtd_solicitada, $pre_unitario, $num_seq_item, $num_pedido)){
          header("location: index.php");
        }
    } else { ?>
        <div class="alert">
          <h4>Ao atulizar os dados preencha todos os campos, por favor</h4>
      </div>
   <?php }
    //--------------------------- CADASTRAR ---------------------------
    } else {
      $num_pedido = addslashes($_GET['inc']);
      $cod_item = addslashes($_POST['item']);
      $qtd_solicitada = addslashes($_POST['qtd']);
      $pre_unitario = addslashes($_POST['pre']);
      if( !empty($num_pedido) && !empty($cod_item) && !empty($qtd_solicitada) && !empty($pre_unitario)){
          // CADASTRAR 
          if(!$p->cadastroItems($num_pedido, $cod_item, $qtd_solicitada, $pre_unitario)){
            echo "<script>location.href='index.php';</script>";
          }
      } else { ?>
      <div class="alert">
          <h4>Preencha todos os campos, por favor</h4>
      </div>
    <?php  } 
    } 
  }
  ?>
<form action="" method="POST">
  <h2> CADASTRO DE ITEMS</h2>
  <label for="">Informe Item:</label>
 <select name="item" id="">
  <?php $dados = $p->buscarItem();
  foreach($dados as $key => $value): ?>

    <option value="<?php echo $value['cod_item']?>" 
      <?php if( isset($res[0]['cod_item']) && $res[0]['cod_item'] == $value['cod_item']) echo "selected"; ?> ><?php echo $value['den_item']?>
    </option>
  <?php endforeach; ?>
 </select>
  <label for="">Informe a Quantidade:</label>
  <input type="number" name="qtd" 
  value="<?php if(isset($res)){echo number_format($res[0]['qtd_solicitada'],2,".","");}?>" >
  <label for="">Informe o Preço:</label>
  <input type="number" name="pre" 
  value="<?php if(isset($res)){echo number_format($res[0]['pre_unitario'],2,".","");}?>" >
  <input type="submit" 
  value="<?php if(isset($res)){echo "Atualizar";} else {echo "Cadastrar";}?>">
</form>
    <a id="voltar" href="index.php">Cancelar</a>
</body>
</html>
