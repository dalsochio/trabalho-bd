<?php
require_once 'crud.php';
$p = new item("controle", "localhost", "root", "");
// instanciar uma classe Instanciar uma classe é criar um novo objeto do mesmo tipo dessa classe
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
  <h1>{ CONTROLE DE PEDIDOS }</h1>
    <?php
    $dados = $p->buscarPedidoCliente();
    if (count($dados) > 0) { // Tem items cadastrados no banco 
      foreach ($dados as $d){ ?>
        <table>
           <tr> 
           <th id="titulo">Pedido:</th>
           <?php echo "<td>" .$d['num_pedido'] . "</td>";?>
           <th id="titulo" >Cliente:</th>
           <?php  echo "<td>" .$d['nom_cliente'] . "</td>"; ?>
           <?php $valorTotal = 0 ;?>
           <td>
          <a id="incluir" href="cadastro_de_items.php?inc=<?php echo $d['num_pedido'] ?>">Incluir Items</a>
          <a id="excluir" href="index.php?excP=<?php echo $d['num_pedido'] ?>">Excluir Pedido</a>
        </td><?php
              echo "</tr>"; ?>
        <!-- ////////////------------------------------ subgrid -->
        <tr>
          <td  colspan='5'>
            <table id='subgrid'>
              <thead>
                <tr id="titulo">
                  <th>Item</th>
                  <th>Quantidade</th>
                  <th>Preço</th>
                  <th>Total</th>
                </tr>
              </thead>
              <?php  $item = $p->buscarItem_pedido($d['num_pedido']);?>
        <!-- Realizar a função para buscar os itens do pedido, passando a var. $dados[$i]['num_pedido'] -->
              <tbody>
            <?php  
          if(count($dados) > 0){
            foreach ($item as $i){  
       echo "<tr>";
       echo "<td>" . $i['den_item'] . "</td>";
       echo "<td>" . number_format($i['qtd_solicitada']) . "</td>";
       echo "<td>" ."R$". number_format($i['pre_unitario'],2,",",".") . "</td>"; 
       $total = $i['qtd_solicitada'] * $i['pre_unitario'];
       echo "<td>" ."R$".number_format($total,2,",","."). "</td>";
       $valorTotal +=  $total;
       ?> 
       <td>
           <a id="incluir" href="cadastro_de_items.php?cod=<?php echo $d['num_pedido']?> &update=<?php echo $i['num_seq_item']?>">Modificar</a>
           <a id="excluir" href="index.php?excI=<?php echo $i['num_seq_item']?>">Excluir</a>
         </td> <?php echo "</tr>";
        
          } 
        ?>  
          <th id="titulo">Total:</th>
          <td><?php echo "R$". " ".number_format($valorTotal,2,",",".");?></td>

          <?php
            } else { // BANCO ESTA VAZIO ?>
              <div class="alert">
                  <h4>Ainda não há items cadastrados!!</h4>
              </div>
       <?php }
         }
    } 
      else { // BANCO ESTA VAZIO ?>
             <div class="alert">
                  <h4>Ainda não há pedidos cadastrados!!</h4>
             </div>
   <?php } ?>
    </tr>
  </table>
  </table>
  </table>
  </table>
  
  <a id="cadastro" href="cadastro_de_pedidos.php">Incluir Pedido</a>
</body>
</html>
<?php
if (isset($_GET['excP'])) {
  $pedido = addslashes($_GET['excP']);
  $p->excluirPedido($pedido);
  echo "<script>location.href='index.php';</script>";
} else 
      if (isset($_GET['excI'])){
         $id_item = addslashes($_GET['excI']);
         $p->excluirItem($id_item);
         echo "<script>location.href='index.php';</script>";
}
?>