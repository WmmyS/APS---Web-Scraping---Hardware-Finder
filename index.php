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

    <!-- Estilo personalizado para estapágina. -->
    <link href="css/jumbotron.css" rel="stylesheet">

    <script src="js/ie-emulation-modes-warning.js"></script>

  </head>
  <body style="width: 100%; height: 100%; padding-bottom:0px; padding-top:0px; background-image: url('images/pc.jpg'); background-size: 100% 100%; background-repeat: no-repeat;">
    <div style="margin-top: 0%; " class="frontPage align-middle"> 
      <form style="padding-top:15%; padding-bottom:30%; border: none; background: linear-gradient(0deg, rgba(30,30,30,0.5) 35%, rgba(0,0,0,0.1) 70%, rgba(150,150,150,0.0) 100% ); backdrop-filter: blur(10px);" class= "form-control form-control-lg frontPage" action="mostraProdutos.php?ordena=relevante">
        <div class="form-label-group justify-content-center align-self-center align-middle " >
          <div class="text-center mb-4">
            <a class="justify-content-center align-self-center" href="index.php"><img class="img-fluid noCopy" style="max-width: 100%; position:relative" src="images/logo.png" ></a>
          </div>
          <input type="text" id="inputBusca" class="form-control align-self-md-center" placeholder="Busca de Hardware" name="buscar" required="" autofocus="">
		  <input hidden type="text" id="inputBusca" class="form-control align-self-md-center" placeholder="relevante" name="ordena" text="relevante" value="relevante">
          <div class="text-center mb-4" style="margin-top:20px;">
            <button style="width: 150px;" class="btn-lg btn-primary m-2" type="submit">Buscar</button>
          </div>
        </div>
      </form>
    </div>

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