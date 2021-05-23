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
	
	$recebe = $_GET['recebe'];
	$link = $_GET['link'];
	
	
	$result = pg_query($conexao, "SELECT * FROM preco_produtos WHERE nomeproduto = '".rtrim($recebe)."' ORDER BY data_preco");		
	?>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);
	   
	window.addEventListener('resize', function(event){
		document.location.reload(true);
	});
	 
    function drawChart() {
	  var data = new google.visualization.DataTable();
      data.addColumn('string', 'Data');
      data.addColumn('number', 'Produto');


	  
      data.addRows([
		<?php
		while ($row = pg_fetch_assoc($result)) {
			$date = new DateTime($row['data_preco']); 
			$dataString = strval($date->format('d/m/Y'));
			
		?>
		[<?php echo '\''.$dataString.'\''?>,  <?php print_r(floatval($row['preco'])); ?>],
		
		<?php
		}
		?>	
      ]);


	var options = {
        chart: {
          title: 'Histórico de Preços de '+<?php echo '\''.rtrim($recebe).'\''?>,
          
        }
      };

      var chart = new google.charts.Line(document.getElementById('linechart_material'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
  </script>
  
  	<style>
		#linechart_material {
		width:100%;
		height:80%;
		}
	</style>
	
	<script>
  function voltar() {
      window.history.back();
    }
	function resize () {
    // change dimensions if necessary
    chart.draw(data, options);
	}
	if (window.addEventListener) {
		window.addEventListener('resize', resize);
	}
	else {
		window.attachEvent('onresize', resize);		
	}	

	</script>
	
  <body>
    <nav class="navbar navbar-dark bg-dark" style="margin-top: -50px; background: linear-gradient(0deg, rgba(0,0,50,0.0) 35%, rgba(0,0,80,0.5) 50%, rgba(0,0,150,0.001) 100% ); backdrop-filter: blur(10px);">
        <div class="container-busca">
            <a href="index.php"><img style="max-width: 350px; vertical-align: middle;" src="images/logo.png" ></a>
            <form class="form-group d-flex align-items-center">
              
            </form>
          </div>
        </div>
      </nav>
      
		<div id="linechart_material" style="width: 100%; height: 100% ;"></div>
		  <div style="margin-left: 10%;">
		    <a href=<?php echo $link; ?>><button class="btn btn-primary" style="margin-bottom:10px; position: relative; width:250px;">Ir na loja</button></a>
        <button onclick="voltar()" class="btn btn-primary" style="margin-bottom:10px; position: relative; width:250px;">Voltar</button>
      </div>
  
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