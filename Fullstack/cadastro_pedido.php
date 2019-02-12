<?php
include("conexao.php");

$sql = "INSERT INTO `pedido` (`id_produto_pedido`, `id_cliente_pedido`, `preco_unitario_pedido`, `quantidade_pedido`) VALUES (?, ?, ?, ?);";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iidi", $_GET['cliente'],$_GET['produto'],$_GET['preco'],$_GET['quantidade']);
$stmt->execute();
$stmt->close();

echo "<table>";
echo "<tr>";
echo "<th>CustomerID</th>";
echo "<th>CompanyName</th>";
echo "</tr>";
echo "</table>";
?> 
