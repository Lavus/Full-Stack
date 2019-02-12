<?php
include("conexao.php");

$sql = "SELECT pedido.id_pedido,pedido.id_produto_pedido,pedido.id_cliente_pedido,pedido.preco_unitario_pedido,pedido.quantidade_pedido,pedido.tempo_pedido FROM pedido WHERE pedido.id_cliente_pedido = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET['cliente']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id_pedido,$id_produto_pedido,$id_cliente_pedido,$preco_unitario_pedido,$quantidade_pedido,$tempo_pedido);

echo "<table style='width:100%;'>";
echo "<tr>";
echo "<th>ID do pedido</th>";
echo "<th>ID do produto</th>";
echo "<th>ID do cliente</th>";
echo "<th>Preço unitario do pedido</th>";
echo "<th>Quantidade de produtos pedido</th>";
echo "<th>Tempo de realização do pedido</th>";
echo "</tr>";
while($stmt->fetch()){
    echo "<tr>";
        echo "<td>" . $id_pedido. "</td>";
        echo "<td>" . $id_produto_pedido. "</td>";
        echo "<td>" . $id_cliente_pedido. "</td>";
        echo "<td>" . $preco_unitario_pedido. "</td>";
        echo "<td>" . $quantidade_pedido. "</td>";
        echo "<td>" . $tempo_pedido. "</td>";
    echo "</tr>";
}
echo "</table>";
$stmt->close();
?> 
