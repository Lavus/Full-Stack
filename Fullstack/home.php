<?php
    include("conexao.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
          $("form").submit(function(ev){
            ev.preventDefault();
            alert("Submitted");
            var dados = $('#cadastro_pedido').serialize();
            alert(dados);
            
            var xhttp;    
            if (dados == "") {
                document.getElementById("txtHint").innerHTML = "";
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtHint").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "cadastro_pedido.php?"+dados, true);
            xhttp.send();
            
            
            
            
          });
        });
        </script>
        <title>Full Stack</title>
    </head>
    <body>
        <table style="width:100%;">
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Age</th>
            </tr>
            <tr>
                <td>Jill</td>
                <td>Smith</td>
                <td>50</td>
            </tr>
            <tr style="visibility:collapse;">
                <td>Eve</td>
                <td>Jackson</td>
                <td>94</td>
            </tr>
        </table> 
        <form id="cadastro_pedido" action="" method="GET">
            <select name='cliente'>
                <?php
                    $query = "SELECT clientes.id_cliente, clientes.nome_cliente FROM clientes ORDER by clientes.id_cliente";
                    $result = $mysqli->query($query);        
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        printf ("<option value='%d'>%s</option>", $row["id_cliente"], $row["nome_cliente"]);
                    }
                    
                ?>
            </select> 
            <select name='produto'>
                <?php
                    $query = "SELECT produtos.id_produto, produtos.nome_produto, produtos.preco_unitario_produto, produtos.multiplo_produto FROM produtos ORDER by produtos.id_produto";
                    $result = $mysqli->query($query);      
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        printf ("<option value='%d'>%s</option>", $row["id_produto"], $row["nome_produto"]);
                    }
                    
                ?>
            </select> 
            Preço Unitario:
            <input type="text" name="preco" value="Mickey">
            Quantidade:
            <input type="text" name="quantidade" value="Mouse">
            <input type="submit" value="Submit">
        </form> 
        <div id="txtHint">Customer info will be listed here...</div>
    </body>
</html>
