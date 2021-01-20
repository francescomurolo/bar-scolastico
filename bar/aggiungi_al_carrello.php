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

  $id=$_GET['id'];
  $quantita=$_POST[$id];

  if(isset($_SESSION['carrello'][$id])){

      $_SESSION['carrello'][$id]['quantity']+=$quantita;
      header('Location:crea_ordine.php');

  }else{
      include "connessione_DB.php";

      $sql="SELECT * FROM prodotti WHERE ID=$id";
      $sql_ris=mysqli_query($conn, $sql);

      if(mysqli_num_rows($sql_ris)!=0){
          $row_s=mysqli_fetch_array($sql_ris);

          $_SESSION['carrello'][$row_s['ID']]=array("quantity" => $quantita,"price" => $row_s['prezzo']);
          header('Location:crea_ordine.php');

      }else{

          header('Location:crea_ordine.php?err=1');

      }

  }
?>
