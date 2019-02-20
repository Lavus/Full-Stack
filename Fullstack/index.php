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
var order_id = "";
var type_change = "";
//~ alert( produto[6]['preco_unitario_produto'] );
</script>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body {font-family: Arial, Helvetica, sans-serif;}
			.pagination {
			  display: inline-block;
			  margin: 5px;
			}
			.pagination a {
			  color: black;
			  float: left;
			  padding: 8px 16px;
			  text-decoration: none;
			}

			.pagination a.active {
			  background-color: #4CAF50;
			  color: white;
			  border-radius: 5px;
			}

			.pagination a:hover:not(.active) {
			  background-color: #ddd;
			  border-radius: 5px;
			}
            /* The Modal (background) */
            .modal {
			  text-align:center;
              display: none; /* Hidden by default */
              position: fixed; /* Stay in place */
              z-index: 1; /* Sit on top */
              padding-top: 100px; /* Location of the box */
              left: 0;
              top: 0;
              width: 100%; /* Full width */
              height: 100%; /* Full height */
              overflow: auto; /* Enable scroll if needed */
              background-color: rgb(0,0,0); /* Fallback color */
              background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* Modal Content */
            .modal-content {
              position: relative;
              background-color: #fefefe;
              margin: auto;
              padding: 0;
              border: 1px solid #888;
              width: 50%;
              box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
              -webkit-animation-name: animatetop;
              -webkit-animation-duration: 0.4s;
              animation-name: animatetop;
              animation-duration: 0.4s
            }

            /* Add Animation */
            @-webkit-keyframes animatetop {
              from {top:-300px; opacity:0} 
              to {top:0; opacity:1}
            }

            @keyframes animatetop {
              from {top:-300px; opacity:0}
              to {top:0; opacity:1}
            }

            /* The Close Button */
            .close {
              color: white;
              float: right;
              font-size: 28px;
              font-weight: bold;
            }

            .close:hover,
            .close:focus {
              color: #000;
              text-decoration: none;
              cursor: pointer;
            }

            .modal-header {
              padding: 2px 16px;
              background-color: #5cb85c;
              color: white;
            }

            .modal-body {padding: 2px 16px;}

            .modal-footer {
              padding: 2px 16px;
              background-color: #5cb85c;
              color: white;
            }
            .modal-footer input {
              margin: 10px;
			  background-color: #e7e7e7; /* Gray */
			  border: none;
			  color: black;
			  padding: 15px 32px;
			  text-align: center;
			  text-decoration: none;
			  display: inline-block;
			  border-radius: 12px;
			  border: 2px solid #e7e7e7;
			  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
			  font-size: 16px;
            }
        </style>

        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <style>
            table, td, th {
                border: 1px solid black;
            }
            td {
                text-align:center;
            }
        </style>
        <script>
        $(document).ready(function(){
          $('#cadastro_pedido').submit(function(ev){
            //~ alert(document.getElementById("rentabilidade").style.backgroundColor);
			if (verify_profitability("") == false){
				return false;
			}
            ev.preventDefault();
            //~ alert("Submitted");
            var dados = $('#cadastro_pedido').serialize();
            //~ alert(dados);
            
            var xhttp;    
            if (dados == "") {
                return;
            }
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    showCustomer(document.getElementById("cliente").value,0)
					document.getElementById("modal_title_check").innerHTML = "Cadastro Pedido";
					document.getElementById("modal_body_check").innerHTML = "<p>O cadastro do pedido foi realizado com sucesso</p><p>obrigado por utilizar nossos serviços.</p>";
					document.getElementById("modal_footer_check").style.visibility = "hidden";
					modal.style.display = "block";
                }
            };
            xhttp.open("GET", "cadastro_pedido.php?"+dados, true);
            xhttp.send();
          });
          return false;
        });
        </script>
        <title>Full Stack</title>
    </head>
    <body>
        <form id="cadastro_pedido" action="" method="GET">
            <table style="width:100%;">
                <tr>
                    <th>Nome do cliente</th>
                    <th>Nome do produto</th>
                    <th>Preço unitario</th>
                    <th>Rentabilidade do pedido</th>
                    <th>Quantidade de produtos</th>
                    <th>Concluir pedido</th>
                </tr>
                <tr>
                    <td>                    
                        <select id="cliente" name='cliente' required="required" onchange="showCustomer(this.value,0)">
                            <option value="">Selecione um cliente:</option>
                            <?php
                                for ($contador = 0; $contador < count($cliente); $contador++) {
                                    printf ("<option value='%d'>%s</option>", $cliente[$contador]['id_cliente'], $cliente[$contador]['nome_cliente']);
                                }
                            ?>
                        </select> 
                    </td>
                    <td>
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
                    </td>
                    <td>
                        <input id="price" required="required" type="number" step=0.01 name="preco" min="0.01" value="0.01" onkeyup="showrentability(this.value,'')" onchange="showrentability(this.value,'')">
                    </td>
                    <td>
                        <div id="rentabilidade">
                            Rentabilidade ...
                        </div>
                    </td>
                    <td>
                        <input type="number" step=1 id="amount" required="required" min="1" name="quantidade" value="1">
                    </td>
                    <td>
                        <input type="submit" value="Submit">
                    </td>
                </tr>
            </table> 
        </form> 
        <H1>preço total do pedido</H1>
        <!-- The Modal -->
        <div id="myModal" class="modal">

          <!-- Modal content -->
          <div class="modal-content">
            <div class="modal-header">
              <span class="close">&times;</span>
              <h2 id="modal_title_check">Modal Header</h2>
            </div>
            <div class="modal-body" id="modal_body_check">
              <p>Some text in the Modal Body</p>
              <p>Some other text...</p>
            </div>
            <div class="modal-footer" id="modal_footer_check">
              <h3>Modal Footer</h3>
            </div>
          </div>

        </div>
		<a href='#' style="visibility:hidden"></a>
        <script>
        // Get the modal
        var modal = document.getElementById('myModal');
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          close_modal()
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            close_modal()
          }
        }
        </script>

        <form id='altera_pedido'>
            <div id="txtHint">Informações dos pedidos do cliente selecionado serão mostradas aqui ...</div>
            <input type="submit" style="visibility:hidden" value="Submit">
        </form>
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
                order_id = int;
                type_change = "alter";
                document.getElementById("modal_title_check").innerHTML = "Alteração de pedido";
                document.getElementById("modal_body_check").innerHTML = "<p>Tem certeza que quer alterar este pedido?</p><p></p>";
				document.getElementById("modal_footer_check").style.visibility = "visible";
                document.getElementById("modal_footer_check").innerHTML = "<input type='button' value='Sim' onclick='confirmed()'><input type='button' value='Não' onclick='close_modal()'>";
                modal.style.display = "block";
            }
            function verify_profitability(int){
				if (document.getElementById("rentabilidade"+int).style.backgroundColor == 'red'){
					document.getElementById("modal_title_check").innerHTML = "Rentabilidade Ruim";
					document.getElementById("modal_body_check").innerHTML = "<p>Não é permitido atualizar pedidos se a rentabilidade estiver RUIM</p><p>por favor aumente o preço se deseja atualizar o pedido</p>";
					document.getElementById("modal_footer_check").style.visibility = "hidden";
					modal.style.display = "block";
					type_change = "verify";
					order_id = "price"+int;
					return false;
                }else{
					return true;
				}
			}
            function confirmed_alter_order(int){
                if (verify_profitability(int) == false){
					return false;
                }
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
				xhttp.open("GET", "alter_order.php?"+pedido+"&"+produto+"&"+preco+"&"+quantidade, true);
				xhttp.send();
				close_modal()
			}

            function confirmed() {
				if (type_change == "exclude"){
					confirmed_exclude_order(order_id)
				}else{
					 if (type_change == "alter"){
						 confirmed_alter_order(order_id)
					 }else{
						return false;
					 }
				}
			}
			
            function close_modal() {
				if (type_change == "verify"){
					document.getElementById(order_id).focus();
				}
				order_id = "";
                type_change = "";
				modal.style.display = "none";
			}
			
            function get_page(int) {
				var page_id = int.substr(10);
				//~ alert (page_id);
				//~ alert(document.getElementById("pagination"+page_id).className);
				if ((document.getElementById("pagination"+page_id).className == "disabled") || (document.getElementById("pagination"+page_id).className == "active")) {
					return false;
				}
				if (page_id == "next"){
					//~ alert (page_id);
					var id = document.getElementsByClassName("active")[0].id; 
					//~ alert (id);
					id = id.substr(10);
					//~ alert (id);
					id = parseInt(id) + 1;
					//~ alert (id);
					page_id = id;
					//~ alert (page_id);
				}else{
					if (page_id == "prev"){
						//~ alert (page_id);
						var id = document.getElementsByClassName("active")[0].id;
						//~ alert (id);
						id = id.substr(10);
						id = parseInt(id) - 1;
						page_id = id;
					}
				}
				//~ alert (page_id);
				page_id = (page_id - 1)*10
				//~ alert (page_id);
			    showCustomer(document.getElementById("cliente").value,page_id)
			}
            function exclude_order(int) {
                order_id = int;
                type_change = "exclude";
				document.getElementById("modal_title_check").innerHTML = "Exclusão de pedido";
				document.getElementById("modal_body_check").innerHTML = "<p>Tem certeza que quer excluir este pedido?</p><p></p>";
				document.getElementById("modal_footer_check").style.visibility = "visible";
				document.getElementById("modal_footer_check").innerHTML = "<input type='button' value='Sim' onclick='confirmed()'><input type='button' value='Não' onclick='close_modal()'>";
                modal.style.display = "block";
			}
			function confirmed_exclude_order(int){
				var id = "txt"+int;
				var xhttp;    
				xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById(id).style.visibility = "collapse";
						document.getElementById("modal_title_check").innerHTML = "Exclusão de pedido";
						document.getElementById("modal_body_check").innerHTML = "<p>"+this.responseText+"</p>";
						document.getElementById("modal_footer_check").style.visibility = "hidden";
						modal.style.display = "block";
						backup_inner = "";
						backup_inner_id = "";
					}
				};
				var pedido = "pedido="+int;
				xhttp.open("GET", "exclude_order.php?"+pedido, true);
				xhttp.send();
				close_modal()
            }
            function showCustomer(int,int_offset) {
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
                xhttp.open("GET", "get_pedido.php?cliente="+int+"&offset="+int_offset, true);
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
                        document.getElementById("rentabilidade"+txt).style.backgroundColor = "";
                        //~ alert("otimo");
                    }
                    else{
                        if ( parseFloat(str) >= parseFloat(preco*0.9) ){
                            document.getElementById("rentabilidade"+txt).innerHTML = "Boa";
                            document.getElementById("rentabilidade"+txt).style.backgroundColor = "";
                            //~ alert("bom");
                        }
                        else{
                            document.getElementById("rentabilidade"+txt).innerHTML = "Ruim";
                            document.getElementById("rentabilidade"+txt).style.backgroundColor = "red";
                            //~ alert(document.getElementById("rentabilidade"+txt).style.backgroundColor);
                            //~ alert("ruim");
                        }
                    }
                }
            }
            $("#altera_pedido").submit(function(e){
                e.preventDefault();
                return false;
            });
        </script>
    </body>
</html>
