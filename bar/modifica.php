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
?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<title>Modifica prodotto</title>
	<!--link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="assets/fonts/ionicons.min.css">
	<link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
	<link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
	<link rel="stylesheet" href="assets/css/styles.css">
	<script language="javascript" type="text/javascript">
	function controllo(){
		if(modifica.txtnome.value == "" || modifica.txtprezzo.value == "" ){
			alert("Compilare tutti i campi!!");
		}
		else
			document.modifica.submit();
	}

	function azzera_img(){
	document.modifica.foto.value='';
	}

	</script>
</head>

<body>
	<div class="container">
		<div class="row" style="text-align:center;">
			<div class="col-md-12">
				<div style="padding-top:30px">
					<h1 class="display-4" style="color:red"><strong>BAR</strong></h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form action="modifica_php.php?id=<?php echo $_GET['id'] ?>" method="post" name="modifica" enctype="multipart/form-data">
					<div class="table-responsive">
						<table class="table borderless">
							<?php
							    $id=$_GET['id'];
								  //apro canale di comunicazione con il DBMS
									include "connessione_DB.php";

									//scrivo la query
									$query_p=	"SELECT * ".
										   		"FROM prodotti,categorie ".
										   		"WHERE prodotti.ID_categoria=categorie.ID_categoria AND ID='$id'";


									$tab_ris_p=mysqli_query($conn, $query_p) or die ("errore");//esegue la query e salva la tabella ris in memoria
									$riga=mysqli_fetch_array($tab_ris_p);

									$query_c=	"SELECT * ".
														"FROM categorie ";


									$tab_ris_c=mysqli_query($conn, $query_c) or die ("errore");//esegue la query e salva la tabella ris in memoria
									$nro_rec_c= mysqli_num_rows($tab_ris_c);

							?>
								<tr>
								<td><strong>Nome </strong></td>
								<td><input name="txtnome" type="text" value="<?php echo $riga['nome']?>"/></td>
								</tr>
								<tr>
								<td><strong>Prezzo </strong></td>
								<td><input name="txtprezzo" type="text" value="<?php echo $riga['prezzo']?>"/></td>
								</tr>
								<tr>
									<td><strong>Categoria </strong></td>
									<td>
										<select name="tendina_categorie" style="text-transform:capitalize;">
									<?php

									for($i=0; $i<$nro_rec_c; $i++) {
										$riga2= mysqli_fetch_array($tab_ris_c);
										$categoria = $riga2['titolo'];
										$id=$riga2['ID_categoria'];
									  $selected = "";
										if($categoria==$riga["titolo"]) $selected = "selected";

										echo "<option $selected value=\"$id\">$categoria</option>\n";
									}

									?>
										</select></td>
							</tr>
							<tr>
								<td><strong>Immagine attuale: </strong></td>
								<td><img height="15" width="15" alt="Annulla" src="assets/img/null.png" onclick="azzera_img();"/> <input name="foto" type="text" readonly value="<?php echo $riga['immagine']?>"/> <input name="txtimmagine" type="file"  accept=".jpg, .jpeg, .png"/></td>
							</tr>
							<tr>
								<td><strong>Disponibile: </strong></td>
								<td>
									<input name="disponibile" type="radio" value="1" id="disponibile_si"/>Sì
									<input name="disponibile" type="radio" value="0" id="disponibile_no"/>No
									<?php
											if($riga['disponibile'] == 1){
												echo "<script>document.modifica.disponibile_si.checked=true</script>";
											}else{
												echo "<script>document.modifica.disponibile_no.checked=true</script>";
											}
									?>
								</td>
							</tr>
							<tr>
								<td><strong>In produzione: </strong></td>
								<td>
									<input name="produzione" type="radio" value="1" id="in_produzione_si"/>Sì
									<input name="produzione" type="radio" value="0" id="in_produzione_no"/>No
									<?php
											if($riga['in_produzione'] == 1){
												echo "<script>document.modifica.in_produzione_si.checked=true</script>";
											}else{
												echo "<script>document.modifica.in_produzione_no.checked=true</script>";
											}
									?>
								</td>
							</tr>
						</table>
					</div>
					<?php
						mysqli_close($conn);//chiudo la connessione con il DBMS
					?>
					<div style="text-align:center">
						<a class="btn btn-primary" role="button" href="gestione.php" style="background-color:rgb(255,15,0);"><strong>Indietro</strong></a>
						<button type="button" name="btnSubmit" value="Modifica" class="btn btn-primary" style="background-color:rgb(20,200,0)"; onclick="javascript:controllo()">Modifica</button>
					</div>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>

  <?php
  if(isset($_GET['err'])){
      //salvataggio della variabile err definita nell'url
      $codice_errore = $_GET['err'];
      //fase di controllo dell'errore
      switch($codice_errore){
        case 1:   echo "<script>window.alert('File già esistente sul server. Rinominarlo e riprovare!')</script>";
                  break;

        case 2:   echo "<script>window.alert('Errore nel caricamento dell\'immagine!')</script>";
                  break;
      }
  }
  ?>
<!--script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>
</html>
