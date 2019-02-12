<?php
    include("conexao.php");
?>
<!DOCTYPE html>
<html>
<style>
table,th,td {
  border : 1px solid black;
  border-collapse: collapse;
}
th,td {
  padding: 5px;
}
</style>
<body>

<h1>The XMLHttpRequest Object</h1>

<form action=""> 
<select name="customers" onchange="showCustomer(this.value)">
<option value="">Select a customer:</option>
<?php
    $query = "SELECT clientes.id_cliente, clientes.nome_cliente FROM clientes ORDER by clientes.id_cliente";
    $result = $mysqli->query($query);        
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        printf ("<option value='%d'>%s</option>", $row["id_cliente"], $row["nome_cliente"]);
    }
    
?>
</select>
</form>
<br>
<div id="txtHint">Customer info will be listed here...</div>

<script>
function showCustomer(int) {
  var xhttp;    
  if (int == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("txtHint").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "getcustomer.php?cliente="+int, true);
  xhttp.send();
}
</script>

</body>
</html>
