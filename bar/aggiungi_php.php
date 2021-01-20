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

  include "connessione_DB.php";
  $file = $_FILES['txtimmagine']['name'];
  $nome = ucfirst(strtolower($_POST['txtnome']));
  $prezzo = $_POST['txtprezzo'];
  $categoria=$_POST['tendina_categorie'];

  $redirect='Location:gestione.php';
  $controllo=0;
  do {
  if (is_uploaded_file($_FILES['txtimmagine']['tmp_name'])) {
    // Verifico che sul sul server non esista già un file con lo stesso nome
    // In alternativa potrei dare io un nome che sia funzione della data e dell'ora
    if (file_exists('assets/img/'.$_FILES['txtimmagine']['name'])) {
      $redirect='Location:aggiungi.php?err=1';
      $controllo=1;
      break;
    }
    // Sposto il file nella cartella da me desiderata
    if (!move_uploaded_file($_FILES['txtimmagine']['tmp_name'], 'assets/img/'.$_FILES['txtimmagine']['name'])) {
      $redirect='Location:aggiungi.php?err=2';
      $controllo=1;
      break;
    }
 	}
} while (false);

if($controllo==0){
  $query = "INSERT INTO prodotti (nome, prezzo, disponibile,ID_categoria, immagine) ".
         "VALUES ('$nome','$prezzo','1','$categoria','$file')";

  $ris = mysqli_query($conn, $query) or die("Errore query !");
  $nro_rec = mysqli_num_rows($ris);
}

mysqli_close($conn);   //chiudi connessione col DBMS

header($redirect);
?>
