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
var backup_inner = "";
var backup_inner_id = "";
//~ alert( produto[6]['preco_unitario_produto'] );
</script>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
          $('#cadastro_pedido').submit(function(ev){
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
            alert("aaa");
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
            <select name='cliente' required="required" onchange="showCustomer(this.value)">
                <option value="">Selecione um cliente:</option>
                <?php
                    for ($contador = 0; $contador < count($cliente); $contador++) {
                        printf ("<option value='%d'>%s</option>", $cliente[$contador]['id_cliente'], $cliente[$contador]['nome_cliente']);
                    }
                ?>
            </select> 
            <select id='product' name='produto' required="required" onchange="showprice(this.value,'')">
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
            <input id="price" required="required" type="number" step=0.01 name="preco" min="0.01" value="0.01" onkeyup="showrentability(this.value,'')" onchange="showrentability(this.value,'')">
            Quantidade:
            <input type="number" step=1 id="amount" required="required" min="1" name="quantidade" value="1">
            <input type="submit" value="Submit">
        </form> 
        <div id="txtHint">Customer info will be listed here...</div>
        <script>
            function alter_table(int) {
                if ((backup_inner != "") && (backup_inner_id != "")){
                    document.getElementById(backup_inner_id).innerHTML = backup_inner;
                }
                var id = "txt"+int;
                backup_inner_id = id;
                backup_inner = document.getElementById(id).innerHTML;
                var xhttp;
                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById(id).innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "change_table.php?pedido="+int, true);
                xhttp.send();
            }
            function alter_order(int) {
                $(document).ready(function(){
                  $('#altera_pedido').submit(function(ev){
                    ev.preventDefault();
                    var id = "txt"+int;
                    var xhttp;    
                    xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById(id).innerHTML = this.responseText;
                            backup_inner = "";
                            backup_inner_id = "";
                        }
                    };
                    var pedido = "pedido="+int;
                    var produto = "produto="+document.getElementById('product'+int).value;
                    var preco = "preco="+document.getElementById('price'+int).value;
                    var quantidade = "quantidade="+document.getElementById('amount'+int).value;
                    alert("alter_order.php?"+pedido+"&"+produto+"&"+preco+"&"+quantidade);
                    xhttp.open("GET", "alter_order.php?"+pedido+"&"+produto+"&"+preco+"&"+quantidade, true);
                    xhttp.send();
                  });
                });
                if (confirm('Tem certeza que quer alterar este pedido?')) {
                    alert("yes");
                } else {
                    alert("no");
                    return false;
                }
            }
            function exclude_order(int) {
                if (confirm('Tem certeza que quer excluir este pedido?')) {
                    alert("yes");
                } else {
                    alert("no");
                }
            }
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
                        backup_inner = "";
                        backup_inner_id = "";
                    }
                };
                xhttp.open("GET", "get_pedido.php?cliente="+int, true);
                xhttp.send();
            }
            function showprice(int,txt) {
                int = int-1
                //~ alert(txt);
                //~ alert(produto[int]['preco_unitario_produto']);
                //~ alert($('#price').val());
                document.getElementById("price"+txt).value = (produto[int]['preco_unitario_produto']*1.01);
                document.getElementById("amount"+txt).value = (produto[int]['multiplo_produto']);
                document.getElementById("amount"+txt).step = (produto[int]['multiplo_produto']);
                document.getElementById("amount"+txt).min = (produto[int]['multiplo_produto']);
                showrentability(document.getElementById("price"+txt).value,txt)
            }
            function showrentability(str,txt) {
                //~ alert(txt);
                if (str.length == 0) {
                    document.getElementById("rentabilidade"+txt).innerHTML = "Rentabilidade do pedido serás mostrado aqui ...";
                    return;
                }
                else {
                    //~ alert (str);
                    var index_produto = (document.getElementById("product"+txt).value)-1;
                    var preco = produto[index_produto]['preco_unitario_produto'];
                    //~ alert (preco);
                    if ( parseFloat(str) > parseFloat(preco) ){
                        document.getElementById("rentabilidade"+txt).innerHTML = "Ôtima";
                        //~ alert("otimo");
                    }
                    else{
                        if ( parseFloat(str) >= parseFloat(preco*0.9) ){
                            document.getElementById("rentabilidade"+txt).innerHTML = "Boa";
                            //~ alert("bom");
                        }
                        else{
                            document.getElementById("rentabilidade"+txt).innerHTML = "Ruim";
                            alert("ruim");
                        }
                    }
                }
            }
        </script>
    </body>
</html>
