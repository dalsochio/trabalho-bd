<?php 
class item{
  private $pdo;
  //CONEXÃO COM BANCO DE DADOS 
  public function __construct($dbname, $host, $user, $senha)
  {
     try{
      $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host, $user, $senha);
     } catch (PDOException $e){
      echo "Erro com banco de dados:".$e->getMessage(); // CASO OCORRA UM ERRO NO BANCO DE DADOS ELE RETORNA O ERRO COM O CODIGO DO MESMO.
      exit();
     } catch(Exception $e){
       echo "Erro generico:".$e->getMessage();//CASO OCORRA UM ERRO EM OUTRA PARTE QUE NÃO SEJA O BANCO DE DADOS, ELE RETORNA ESTE COM O CÓDIGO DO ERRO.
       exit();
     }
  }

  // FUNÇÃO PARA BUSCAR OS DADOS E COLOCAR NA TABELA REFERENTE AO PEDIDO JUNTO A TABELA CLIENTE
  public function buscarPedidoCliente(){
    $res = array();
    $cmd = $this->pdo->query("SELECT * FROM pedido JOIN cliente ON  pedido.cod_cliente = cliente.cod_cliente ORDER BY num_pedido;"); 
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  // FUNÇÃO PARA BUSCAR OS DADOS E COLOCAR NA TABELA REFERENTE AO ITEM_PEDIDO
  public function buscarItem_pedido($num){
    $res = array();
    $cmd = $this->pdo->prepare("SELECT * FROM item JOIN item_pedido ON item_pedido.cod_item = item.cod_item WHERE num_pedido = :num_pedido");
    $cmd->bindValue(":num_pedido", $num);
    $cmd->execute();
    $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

// FUNÇÃO PARA BUSCAR O CLIENTE PARA QUE POSSA POPULAR NOS OPTIONS DO CADASTRO DE PEDIDOS
public function buscarCliente(){
  $res = array();
  $cmd = $this->pdo->query("SELECT cod_cliente, nom_cliente FROM cliente");
  $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
  return $res;
}

// FUNÇÃO PARA BUSCAR OS ITEMS E COLOCAR NO SELECT OPTION
public function buscarItem(){
  $res = array();
  $cmd = $this->pdo->query("SELECT cod_item, den_item FROM item");
  $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
  return $res;
}

// DELETA ITEM_PEDIDO DO BANCO 
public function excluirItem($id){
  $cmd = $this->pdo->query("DELETE FROM item_pedido WHERE num_seq_item = $id");
 // $cmd->bindValue(":id", $id);
  $cmd->execute();
}

// DELETA PEDIDO DO BANCO
public function excluirPedido($pedido){
  $cmd = $this->pdo->query("DELETE FROM pedido WHERE num_pedido = $pedido");
  //$cmd->bindValue(":pedido", $pedido);
  $cmd->execute();
}

// CADASTRA PEDIDO NO BANCO 
public function cadastroPedido($cod_cliente){
  $cmd = $this->pdo->query("SELECT num_pedido FROM pedido ORDER BY num_pedido DESC LIMIT 1");
  $res = $cmd->fetch(); 
  $ultimoPedidoMaisUm = $res['num_pedido'] + 1; // AUTO INCREMENT
  $cmd = $this->pdo->prepare("INSERT INTO pedido(num_pedido, cod_cliente) VALUES (:num_pedido, :cod_cliente) ");
  $cmd->bindValue(":num_pedido", $ultimoPedidoMaisUm );
  $cmd->bindValue(":cod_cliente" , $cod_cliente);
  $cmd->execute();
 }

// CADASTRA ITEM_PEDIDO NO BANCO
 public function cadastroItems($num_pedido, $cod_item, $qtd_solicitada, $pre_unitario){
  $cmd = $this->pdo->prepare("SELECT num_seq_item AS prox_num_seq_item FROM item_pedido WHERE num_pedido = :num_pedido ORDER BY num_seq_item DESC LIMIT 1");
  $cmd->bindValue(":num_pedido", $num_pedido );
  $cmd->execute();
  $res = $cmd->fetch();
  $cmd = $this->pdo->prepare("INSERT INTO item_pedido(num_pedido, num_seq_item, cod_item, qtd_solicitada, pre_unitario) VALUES (:num_pedido, :num_seq_item , :cod_item, :qtd_solicitada, :pre_unitario) ");
  $cmd->bindValue(":num_pedido", $num_pedido );
  $cmd->bindValue(":num_seq_item", $res['prox_num_seq_item'] + 1 ?? 1 );
  $cmd->bindValue(":cod_item", $cod_item);
  $cmd->bindValue(":qtd_solicitada", $qtd_solicitada);
  $cmd->bindValue(":pre_unitario", $pre_unitario);
  $cmd->execute();
 }
         
 public function buscarDadosItems($num_pedido, $num_seq_item){
    $res = array(); // CASO NÃO VENHA NADA DO BANCO ELE RETORNARA UM ARRAY VAZIO
    $cmd = $this->pdo->prepare("SELECT * FROM item_pedido WHERE num_pedido = :num_pedido AND num_seq_item = :num_seq_item");
    $cmd->bindValue(":num_pedido", $num_pedido);
    $cmd->bindValue(":num_seq_item", $num_seq_item);
    $cmd->execute();
    $res = $cmd->fetchAll();
    return $res;     
}

//--------------A BAIXO DESSA LINHA NÃO ESTÃ FUNCIONANDO NO MOMENTO--------------
 

// ATUALIZAR ITEMS NO BANCO DE DADOS
public function atulizarItems($cod_item, $qtd_solicitada, $pre_unitario, $num_seq_item, $num_pedido){
  $cmd = $this->pdo->prepare("UPDATE item_pedido SET cod_item = :cod_item, qtd_solicitada = :qtd_solicitada, pre_unitario= :pre_unitario  WHERE num_seq_item= :num_seq_item AND num_pedido = :num_pedido");
  $cmd->bindValue(":cod_item" , $cod_item );
  $cmd->bindValue(":qtd_solicitada", $qtd_solicitada);
  $cmd->bindValue(":pre_unitario", $pre_unitario);
  $cmd->bindValue(":num_seq_item", $num_seq_item);
  $cmd->bindValue(":num_pedido", $num_pedido);
  $cmd->execute();
}







    





























































//------------------------------------LEGENDA------------------------------------
/* 
SQL INJECTION:  é um tipo de ameaça de segurança que se aproveita de falhas em sistemas que trabalham com bases de dados,
 realizando ataques com comandos SQL; onde o atacante consegue inserir uma instrução SQL personalizada 
 e indevida através da entrada de dados de uma aplicação, como formulários ou URL de uma aplicação online.

*REALIZAR CONSULTAS NO BANCO:
->PREPARE:  é útil em evitar ataques de SQL injection.
 Passamos os parametros depois substituimos.
->QUERY: Passamos os paramentros diretamente, não precisa fazer substituição.

*SUBSTITUI PARÂMETROS:
->BINVALUE: Aceita váriavies e funçãoes.
->BINDPARAM: ACAeita apenas váriaveis, não aceita que passe valores diretamente.

*INCLUDE E REQUIRE É A FORMA COMO O ERRO É TRATADO
-> REQUIRE: Produz um erro E_COMPILETE_ERROR, 
encerando a execução. 
-> INCLUDE: Apenas produz um warning que pode ser abafado com um @. 

*MÉTODOS DE RETORNO DAS CONSULTAS
->FETCH: Usado quando é retornado apenas um registro.
->FETCHALL: Utilizado quando vem mais de um registro do banco de dados.
Para economizar memória se utiliza o PDO::FETCH_ASSOC

EXECUTE: Permite executar um script ou uma função PHP.

*CONCEITOS DE POO:
->$THIS: Utilizado para fazer referência ao próprio objeto.
ADDSLASHES: Usada para retornar uma string com barras invertidas antes de caracteres que precisam ser citados no banco de dados
->_CONSTRUT: O método construtor de uma classe serve para executar algum comportamento, 
como atribuição de valor, execução de método, etc logo no momento em que uma instancia da mesma for criada.

*SQL:
->MAX: Retorna o maximo da tabela.
->COALESCE: Avalia seus parâmetros em ordem e retorna o primeiro que não seja NULL.
*/

}
?>