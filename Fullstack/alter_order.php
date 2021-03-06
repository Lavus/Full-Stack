<?php
    include("conexao.php");
    $query = "SELECT produtos.id_produto, produtos.nome_produto, produtos.preco_unitario_produto, produtos.multiplo_produto FROM produtos ORDER by produtos.id_produto";
    $result = $mysqli->query($query);        
    $produto = $result->fetch_all(MYSQLI_ASSOC);

    $sql = "SELECT pedido.id_pedido,pedido.id_produto_pedido,clientes.nome_cliente,produtos.nome_produto,pedido.preco_unitario_pedido,pedido.quantidade_pedido,pedido.tempo_pedido FROM pedido,clientes,produtos WHERE clientes.id_cliente = pedido.id_cliente_pedido and pedido.id_produto_pedido = produtos.id_produto and pedido.id_pedido = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_GET['pedido']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id_pedido,$id_produto_pedido,$nome_cliente,$nome_produto,$preco_unitario_pedido,$quantidade_pedido,$tempo_pedido);
    $stmt->fetch();
    $stmt->close();
    $set = '';
    if ($_GET['produto'] != $id_produto_pedido){
        $set = "pedido.id_produto_pedido = '".$_GET['produto']."'";
    }
    //~ echo ($set);
    if ($_GET['preco'] != $preco_unitario_pedido){
        if ($set != ''){
            $preco = sprintf("%.2f", $_GET['preco']);
            $set=$set.", pedido.preco_unitario_pedido = '".$preco."'";
        }else{
            $set = "pedido.preco_unitario_pedido = '".$_GET['preco']."'";
        }
    }
    //~ echo ($set);
    if ($_GET['quantidade'] != $quantidade_pedido){
        if ($set != ''){
            $set=$set.", pedido.quantidade_pedido = '".$_GET['quantidade']."'";
        }else{
            $set = "pedido.quantidade_pedido = '".$_GET['quantidade']."'";
        }
    }
    //~ echo ($set);
    if ($set != ''){
        $sql = "UPDATE pedido SET ".$set." WHERE pedido.id_pedido = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $_GET['pedido']);
        $stmt->execute();
        $stmt->close();
    }
    echo "<tr>";
        echo "<td data-label='ID do pedido'>" . $id_pedido. "</td>";
        echo "<td data-label='Nome do cliente'>" . $nome_cliente. "</td>";
        echo "<td data-label='Nome do produto'>" . $produto[$_GET['produto']-1]['nome_produto']. "</td>";
        echo "<td data-label='Preço unitario do pedido'>" . $_GET['preco']. "</td>";
        if ($_GET['preco'] > $produto[$_GET['produto']-1]['preco_unitario_produto']){
            echo "<td data-label='Rentabilidade do pedido'>Ôtima</td>";
        }elseif($_GET['preco'] >= $produto[$_GET['produto']-1]['preco_unitario_produto']*0.9){
            echo "<td data-label='Rentabilidade do pedido'>Boa</td>";
        }else{
            echo "<td data-label='Rentabilidade do pedido'>Ruim? Como?</td>";
        }
        echo "<td data-label='Quantidade de produtos pedido'>" . $_GET['quantidade']. "</td>";
        echo "<td data-label='Preço total do pedido' id='total_price".$id_pedido."'>" . $_GET['quantidade']*$_GET['preco']. "</td>";
        echo "<td data-label='Tempo de realização do pedido'>" . $tempo_pedido. "</td>";
        echo "<td data-label='Alterar o pedido'><input id='".$id_pedido."' type='button' onclick='alter_table(this.id)' value='Alterar pedido'></td>";
        echo "<td data-label='Deletar o pedido'><input id='".$id_pedido."' type='button' onclick='exclude_order(this.id)' value='Excluir pedido'></td>";
    echo "</tr>";
?> 
