<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Página destinada para apresentados resultados das buscas feitas na API. -->
    <meta name="APS" content="">
    <link rel="icon" href="images/icon.png">

    <title>Hardware Finder</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <?php
  		if(!@($conexao=pg_connect ("host=localhost dbname=aps port=5432 user=postgres password=1234"))) {
		   print "Não foi possível estabelecer uma conexão com o banco de dados.";
		}
		$buscar = $_GET['buscar'];
		
		for ($i = 1; $i <= 2; $i++) {
          if($i == 1){
            $conteudo = file_get_contents("http://127.0.0.1:5000/".str_replace(" ","%20",$buscar));
            
            $dados = json_decode($conteudo, False); 
			
			$a = 0;

            foreach($dados as $chave => $valor)
            { 

			$array[$a] = [
				"Descricao" => $valor->{'Descricao'},
				"Valor" => floatval(str_replace("R$","", str_replace(",",".",str_replace(".", "", $valor->{'Valor'})))),
				"LinkFoto" => $valor->{'LinkFoto'},
				"LinkProduto" => $valor->{'LinkProduto'},
			];	
			$a++;

            }
          }
          elseif($i==2){
            $conteudo =	file_get_contents("http://127.0.0.1:5000/kabum/".str_replace(" ","%20",$buscar));
            $i = 3;
            
            while ($i <= sizeof(explode("nome",$conteudo))) { 			
				$array[$a] = [
					"Descricao" => str_replace("'","", explode("\"", explode("nome",$conteudo)[$i])[2]),
					"Valor" => floatval(explode(",", explode("preco_desconto\":",$conteudo)[$i/3])[0]),
					"LinkFoto" => explode("\"", explode("img",$conteudo)[$i])[2],
					"LinkProduto" => "https://www.kabum.com.br/".explode("\"", explode("link_descricao",$conteudo)[$i/3])[2],
				];	
				$a++;
              $i = $i + 3;			
            }
          }			  
        }
		
		function array_sort($array, $on, $order=SORT_ASC)
		{
			$new_array = array();
			$sortable_array = array();

			if (count($array) > 0) {
				foreach ($array as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $k2 => $v2) {
							if ($k2 == $on) {
								$sortable_array[$k] = $v2;
							}
						}
					} else {
						$sortable_array[$k] = $v;
					}
				}

				switch ($order) {
					case SORT_ASC:
						asort($sortable_array);
					break;
					case SORT_DESC:
						arsort($sortable_array);
					break;
				}

				foreach ($sortable_array as $k => $v) {
					$new_array[$k] = $array[$k];
				}
			}

			return $new_array;
		}
  ?>
  <body>
    <nav class="navbar navbar-dark bg-dark" style="margin-top: -50px; background: linear-gradient(0deg, rgba(0,0,50,0.0) 35%, rgba(0,0,80,0.5) 50%, rgba(0,0,150,0.001) 100% ); backdrop-filter: blur(10px);">
      <div class="container">
          <a href="index.php"><img style="max-width: 350px; vertical-align: middle;" src="images/logo.png" ></a>
          <form class="form-group d-flex align-items-center">
            <input class="col-xs-3 form-control m-1 container-md" type="text" placeholder="Buscar" name="buscar" aria-label="Search">
			<input hidden type="text" id="inputBusca" class="form-control align-self-md-center" placeholder="relevante" name="ordena" value="relevante">
            <input type="submit" class="btn btn-primary m-2"></input>
          </form>
        </div>
      </div>
    </nav>

    <div class="container-busca">
		<div class="pt-3" ><h3>Resultados da Busca:</h3></div>
	  	<div class="row justify-content-left ">
			<div class="btn-group" style="width: 250px;">
				<select name="select" onchange="javascript:mostraAlerta(this);" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<option id="bt-ordenar" >Selecione...</option>
					<option class="dropdown-item" value="valor1">Preço Crescente</option>
					<option class="dropdown-item" value="valor2">Preço Decrescente</option>
					<option class="dropdown-item" value="valor3">Mais Relevantes</option>
					<option class="dropdown-item" value="valor4">A-Z</option>
					<div class="dropdown-divider"></div>
					<option class="dropdown-item" value="valor5">Z-A</option>
				</select>
			</div>
		</div>
    </div>

    <div class="container">
      <div class="row justify-content-center">
		<?php
		
		try {
			$ordena = $_GET['ordena'];
		} catch (Throwable $e) {
			echo $e;
		}		
		 
		function exibir($array, $conexao){
			foreach($array as $chave => $valor){ 
			?>
			<div class="col-md-4 shadow-sm border m-1 pricer align-items-left pt-3" border="1">
			  <img class="imageView mx-auto d-block" src=<?php echo $valor['LinkFoto'];?> alt="HTML5 Icon" style="width:150px;height:150px;">
			  <dl style="font-weight: 600;">Descrição:</dl><p class="primary" style="height: 20px; padding-bottom: 20px"><?php echo str_replace("'","", $valor['Descricao']); pg_query($conexao, "SELECT inserirDadosValor('".rtrim(ltrim(str_replace("'","",$valor['Descricao'])))."', '".$valor['Valor']."');");?></p>
					<p style="padding-top: 35px;"><dl style="font-weight: 600;">Preço:</dl><p class="fontPrice"><?php echo "R$ ".number_format($valor['Valor'], 2, ',', '.');?></p></p>
					<a href='historicoProduto.php?recebe=<?php echo rtrim(ltrim(str_replace("'","", $valor['Descricao'])))."&link=".$valor['LinkProduto'];?>'><img src="images/grafico.png" style="height: 60px; margin-bottom: 3px;" alt="Histórico de Preços" title="Histórico de Preços"></a>
			  <a target="_blank" style="margin-bottom: 0px;" href=<?php echo $valor['LinkProduto'];?>><button class="btn btn-primary" style="margin-bottom:10px; position: relative; width:100%;">Ir na loja</button></a>
			</div>
			<?php 
			}
		}
		
		if($ordena == "crescente"){
			exibir(array_sort($array, 'Valor', SORT_ASC), $conexao);
			$texto = "Preço Crescente";
		}
		elseif($ordena == "decrescente"){
			exibir(array_sort($array, 'Valor', SORT_DESC), $conexao);
			$texto = "Preço Decrescente";
		}
		elseif($ordena == "az"){
			exibir(array_sort($array, 'Descricao', SORT_ASC), $conexao);
			$texto = "A-Z";
		}	
		elseif($ordena == "za"){
			exibir(array_sort($array, 'Descricao', SORT_DESC), $conexao);
			$texto = "Z-A";
		}			
		elseif($ordena == "relevante"){
			exibir($array, $conexao);
			$texto = "Mais Relevantes";
		}		
		
		echo "<script>
		
			var text = '".$texto."';
			var select = document.querySelector('select');
			for (var i = 0; i < select.options.length; i++) {
				if (select.options[i].text === text) {
					select.selectedIndex = i;
					break;
				}
			}

			</script>";	
		
		?>
		
		<script type="text/javascript">
			function mostraAlerta(elemento)
			{
				if(elemento.value == 'valor1'){
					document.location = window.location.href.split("&")[0]+"&ordena=crescente";
					}
				else if(elemento.value == 'valor2'){
					document.location = window.location.href.split("&")[0]+"&ordena=decrescente";
					
				}
				else if(elemento.value == 'valor3'){
					document.location = window.location.href.split("&")[0]+"&ordena=relevante";
					
				}				
				else if(elemento.value == 'valor4'){
					document.location = window.location.href.split("&")[0]+"&ordena=az";
					
				}
				else if(elemento.value == 'valor5'){
					document.location = window.location.href.split("&")[0]+"&ordena=za";
					
				}
				
			}
		</script>



			 
      </div> <!--/row-->        
      <footer class="panel-footer">
        <p>&copy; 2021 APS, SA.</p>
      </footer>
    </div> <!-- /container -->
    <!-- Footer -->
    <footer class="bg-dark text-center text-white">
      
      <div class="container p-4">      
        <div class="text-center p-3">
          © 2021 Copyright:
          <a class="text-white" href="#">APS LVWW</a>
        </div>
      </div>
      
    </footer>
    <!-- Footer -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
