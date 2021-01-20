<!DOCTYPE html>
<?php
	//inizializzazione della sessione
	session_start();

	//imposto un flag "autenticato" per proteggere l'area riservata
	if(!isset($_SESSION['autenticato']))
		$_SESSION['autenticato'] = 0;
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"-->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
		<link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
    		<div class="login-clean" style="width:100%;height:100%;position:fixed;top:0;left:0;">
        <form method="post" action="autentica.php">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-contact-outline" style="color:#1fb7ac;"></i></div>
            <div class="form-group"><input class="form-control" type="email" name="email" placeholder="E-mail" /></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password"/></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit" style="background-color:#1fb7ac;">Accedi</button></div>
            <?php
          	if(isset($_GET['err'])){
								//salvataggio della variabile err definita nell'url
                $codice_errore = $_GET['err'];
								//fase di controllo dell'errore
                switch($codice_errore){
									//Codice d'errore = 1 --> l'utente non ha inserito le sue credenziali d'accesso
              		case 1:echo "<div class='alert alert-danger alert-dismissible fade show' style='text-align:right'>".
                            "<button type='button' class='close' data-dismiss='alert'>&times;</button>".
                            "<strong><font size='2'>Credenziali obbligatorie!</font></strong>".
                            "</div>";
                            break;
									//Codice d'errore = 2 --> l'utente ha inserito delle credenziali d'accesso errate
                  case 2:echo "<div class='alert alert-danger alert-dismissible fade show' style='text-align:right'>".
                            "<button type='button' class='close' data-dismiss='alert'>&times;</button>".
                            "<strong><font size='2'>Credenziali errate!</font></strong>".
                            "</div>";
                            break;
									//Codice d'errore = 5 --> l'utente ha tentato di accedere all'area riservata senza effettuare l'accesso
                  case 5:echo "<div class='alert alert-danger alert-dismissible fade show' style='text-align:right'>".
                            "<button type='button' class='close' data-dismiss='alert'>&times;</button>".
                            "<strong><font size='2'>Effettua l'accesso!</font></strong>".
                            "</div>";
                            break;
              	}
            }
          	?>
        </form>
    </div>
			</div>
		</div>
	</div>

    <!--script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script-->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>
