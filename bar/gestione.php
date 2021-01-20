<!DOCTYPE html>
<?php
/*proteggi area riservata:
  fai in modo che la presente non vada memorizzata nella cache del computer client
  costringendo il browser a richiederla al server web
  e quindi a rieseguirla (per l'impostazione del flag che controlla l'accesso "autenticato")*/
  header("Cache-Control: no-store, no-cache, must-revalidate");

  session_start();
  $autenticato=$_SESSION['autenticato'];

  if (!$autenticato) {
	  header("location:index.php?err=5");
	  exit();
  }
//funzione che imposta una immagine di default se nel database non è presente
function ctrl_img($img){
	if($img==null)
		return $img="no-photo.png";
	else
		return $img;
}
?>
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<title>Gestione</title>
	<!--link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="assets/fonts/ionicons.min.css">
	<link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
	<link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
	<link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
	<div class="container-fluid">
		<div class="row" style="text-align:center;">
			<div class="col-md-12">
        <div style="text-align:right;padding-top:10px">
          <a href="logout.php"><button class="btn btn-primary" type="button" style="background-color:rgb(255,15,0);">Esci</button></a>
        </div>
				<div>
					<h1 class="display-4" style="color:red"><strong>BAR</strong></h1>
				</div>
				<div style="padding-top:30px">
					<a class="btn btn-primary" role="submit" href="aggiungi.php" style="background-color:#FF8100"><strong>Aggiungi prodotto</strong></a>
				</div>
				<hr/>
			</div>
		</div>
		<div class="row" style="text-align:center;">
			<div class="col-md-12">
				<div class="table-responsive">
					<?php

					//apro canale di comunicazione con il DBMS
					  include "connessione_DB.php";

						$query= "SELECT * ".
										"FROM prodotti, categorie ".
										"WHERE prodotti.ID_categoria=categorie.ID_categoria ";

						//echo $query;

						$tab_ris = mysqli_query($conn, $query) or die("Errore query !");
						$nro_rec = mysqli_num_rows($tab_ris);

						if($nro_rec==0){
						   echo"<p align='center'><b><font  color=#FF0000#>Nessun prodotto !!!</font></b></p><br/>";
						 }
					?>
					<table class="table" style="text-align:center">
								<thead style="background:#FFE118">
									<tr>
											<th>ID</th>
											<th>Nome</th>
											<th>Prezzo</th>
											<th>Categorie</th>
											<th>Disponibilità</th>
											<th>In produzione</th>
											<th>Immagine</th>
											<th>Gestione</th>
									</tr>
								</thead>
					<?php
						if($nro_rec!=0){
						   for($i=0;$i<$nro_rec;$i++){
					           $riga=mysqli_fetch_array($tab_ris);
										 $img=ctrl_img($riga['immagine']);
					?>
					    <tr>
								<td style="vertical-align:middle"><?php echo $riga['ID']?></td>
								<td style="vertical-align:middle;text-transform:capitalize;"><?php echo $riga['nome']?></td>
								<td style="vertical-align:middle">€<?php echo $riga['prezzo']?></td>
								<td style="vertical-align:middle;text-transform:capitalize;"><?php echo $riga['titolo']?></td>
								<td style="vertical-align:middle"><?php if($riga['disponibile']) echo "Si"; else echo "No";?></td>
								<td style="vertical-align:middle"><?php if($riga['in_produzione']) echo "Si"; else echo "No";?></td>
								<td style="vertical-align:middle"><img class="m-auto" src="assets/img/<?php echo $img;?>" style="max-width:100%;max-height:100%;height:70px;"></td>
								<td style="vertical-align:middle">
									<a href="modifica.php?id=<?php echo $riga['ID'] ?>" data-toggle="tooltip" data-placement="bottom" title="Modifica"><img src="assets/img/edit.png" height="20" width="20"></a>
								</td>
					    </tr>

					 <?php

						   }
						mysqli_close($conn);//chiudo la connessione con il DBMS
						}

					?>
					</table>
			</div>
		</div>
	</div>
</div>
<!--script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script>$(document).ready(function(){$('[data-toggle="tooltip"]').tooltip();});</script>
</body>
</html>
