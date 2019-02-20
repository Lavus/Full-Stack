<?php
include("conexao.php");
$sql = "SELECT count(`pedido`.`id_pedido`) FROM `pedido` WHERE pedido.id_cliente_pedido = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['cliente']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($count_id_pedido);
$stmt->fetch();
$stmt->close();

$sql = "SELECT pedido.id_pedido,clientes.nome_cliente,produtos.nome_produto,pedido.preco_unitario_pedido,pedido.quantidade_pedido,pedido.tempo_pedido,produtos.preco_unitario_produto FROM pedido,clientes,produtos WHERE clientes.id_cliente = pedido.id_cliente_pedido and pedido.id_produto_pedido = produtos.id_produto and pedido.id_cliente_pedido = ? ORDER BY pedido.id_pedido limit 10 offset ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $_GET['cliente'], $_GET['offset']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_pedido,$nome_cliente,$nome_produto,$preco_unitario_pedido,$quantidade_pedido,$tempo_pedido,$preco_unitario_produto);

echo "<table style='width:100%;'>";
echo "<tr>";
echo "<th>ID do pedido</th>";
echo "<th>Nome do cliente</th>";
echo "<th>Nome do produto</th>";
echo "<th>Preço unitario do pedido</th>";
echo "<th>Rentabilidade do pedido</th>";
echo "<th>Quantidade de produtos pedido</th>";
echo "<th>Tempo de realização do pedido</th>";
echo "<th>Alterar o pedido</th>";
echo "<th>Deletar o pedido</th>";
echo "</tr>";
while($stmt->fetch()){
    echo("<tbody id='txt" . $id_pedido. "'>");
        echo "<tr>";
            echo "<td>" . $id_pedido. "</td>";
            echo "<td>" . $nome_cliente. "</td>";
            echo "<td>" . $nome_produto. "</td>";
            echo "<td>" . $preco_unitario_pedido. "</td>";
            if ($preco_unitario_pedido > $preco_unitario_produto){
                echo "<td>Ôtima</td>";
            }elseif($preco_unitario_pedido >= $preco_unitario_produto*0.9){
                echo "<td>Boa</td>";
            }else{
                echo "<td>Ruim? Como?</td>";
            }
            echo "<td>" . $quantidade_pedido. "</td>";
            echo "<td>" . $tempo_pedido. "</td>";
            echo "<td><input id='".$id_pedido."' type='button' onclick='alter_table(this.id)' value='Alterar pedido'></td>";
            echo "<td><input id='".$id_pedido."' type='button' onclick='exclude_order(this.id)' value='Excluir pedido'></td>";
        echo "</tr>";
    echo("</tbody>");
}
echo "</table>";
$pages = ceil($count_id_pedido/10);
if ($_GET['offset'] == 0){
	$active = 1;
}else{
	$active = (($_GET['offset']/10)+1);
}
echo"<div style='text-align: center;'>";
echo "<div class='pagination'>";
	if ($active == 1){
		echo "<a href='#' id='paginationprev' onclick='get_page(this.id);return false;' class='disabled'>&laquo;</a>";
	}else{
		echo "<a href='#' id='paginationprev' onclick='get_page(this.id);return false;'>&laquo;</a>";
	}
	for ($contador = 1; $contador <= $pages; $contador++) {
		if ($contador == $active){
			echo "<a href='#' id='pagination".$contador."' onclick='get_page(this.id);return false;' class='active'>".$contador."</a>";
		}else{
			echo "<a href='#' id='pagination".$contador."' onclick='get_page(this.id);return false;'>".$contador."</a>";
		}
	}
	if ($active == $pages){
		echo "<a href='#' id='paginationnext' onclick='get_page(this.id);return false;' class='disabled'>&raquo;</a>";
	}else{
		echo "<a href='#' id='paginationnext' onclick='get_page(this.id);return false;'>&raquo;</a>";
	}
echo "</div>";
echo "</div>";
$stmt->close();
?> 
