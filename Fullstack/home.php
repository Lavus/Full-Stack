<?php
    include("conexao.php");
    $query = "SELECT clientes.id_cliente, clientes.nome_cliente FROM clientes ORDER by clientes.id_cliente";
    $result = $mysqli->query($query);        
    $cliente = $result->fetch_all(MYSQLI_ASSOC);
    $query = "SELECT produtos.id_produto, produtos.nome_produto, produtos.preco_unitario_produto, produtos.multiplo_produto FROM produtos ORDER by produtos.id_produto";
    $result = $mysqli->query($query);        
    $produto = $result->fetch_all(MYSQLI_ASSOC);
?>
<script type="text/javascript">
var produto = <?php echo json_encode($produto) ?>;
//~ alert( produto[6]['preco_unitario_produto'] );
</script>
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
                <th style="width:33%;">Firstname</th>
                <th style="width:34%;">Lastname</th>
                <th style="width:33%;">Age</th>
            </tr>
            <tr>
                <td>Jill</td>
                <td>Rentabilidade</td>
                <td>50</td>
            </tr>
            <tr>
                <td style="visibility:collapse;">Eve</td>
                <td><div id="rentabilidade">Rentabilidade do pedido serás mostrado aqui ...</div></td>
                <td style="visibility:collapse;">94</td>
            </tr>
        </table> 
        <form id="cadastro_pedido" action="" method="GET">
            <select name='cliente' onchange="showCustomer(this.value)">
                <option value="">Selecione um cliente:</option>
                <?php
                    for ($contador = 0; $contador < count($cliente); $contador++) {
                        printf ("<option value='%d'>%s</option>", $cliente[$contador]['id_cliente'], $cliente[$contador]['nome_cliente']);
                    }
                ?>
            </select> 
            <select id='product' name='produto' onchange="showprice(this.value)">
                <option value="">Selecione um produto:</option>
                <?php
                    $contador = 0;
                    while( $contador < count($produto)){
                        printf ("<option value='%d'>%s</option>", $produto[$contador]["id_produto"], $produto[$contador]["nome_produto"]);
                        $contador++;
                    }
                ?>
            </select> 
            Preço Unitario:
            <input id="price" type="number" step=0.01 name="preco" value="" onkeyup="showrentability(this.value)" onchange="showrentability(this.value)">
            Quantidade:
            <input type="number" name="quantidade" value="">
            <input type="submit" value="Submit">
        </form> 
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
                xhttp.open("GET", "get_pedido.php?cliente="+int, true);
                xhttp.send();
            }
            function showprice(int) {
                int = int-1
                //~ alert(produto[int]['preco_unitario_produto']);
                //~ alert($('#price').val());
                document.getElementById("price").value = (produto[int]['preco_unitario_produto']*1.01);
                showrentability(document.getElementById("price").value)
            }
            function showrentability(str) {
                if (str.length == 0) {
                    document.getElementById("rentabilidade").innerHTML = "Rentabilidade do pedido serás mostrado aqui ...";
                    return;
                }
                else {
                    //~ alert (str);
                    var index_produto = (document.getElementById("product").value)-1;
                    var preco = produto[index_produto]['preco_unitario_produto'];
                    //~ alert (preco);
                    if ( parseFloat(str) > parseFloat(preco) ){
                        document.getElementById("rentabilidade").innerHTML = "Rentabilidade ôtima";
                        //~ alert("otimo");
                    }
                    else{
                        if ( parseFloat(str) >= parseFloat(preco*0.9) ){
                            document.getElementById("rentabilidade").innerHTML = "Rentabilidade boa";
                            //~ alert("bom");
                        }
                        else{
                            document.getElementById("rentabilidade").innerHTML = "Rentabilidade ruim";
                            //~ alert("ruim");
                        }
                    }
                }
            }
        </script>
    </body>
</html>
