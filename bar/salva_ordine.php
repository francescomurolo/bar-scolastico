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

$luogo=$_POST['luogo_consegna'];
$date=date("Y-m-d H:i:s");
$totale=(float)substr($_POST['totale_ordine'], 3);
$messaggio=null;
$mail_mittente = $_SESSION['id'];
$studente = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM studenti,classi WHERE studenti.ID_classe=classi.ID AND studenti.email='$mail_mittente' "));
$nome_mittente = ucfirst($studente['nome']);
$mail_oggetto = "Ordine ".$studente['anno'].strtoupper($studente['sezione']);

$titolare = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM titolari "));
//$mail_destinatario = $titolare['email'];
$mail_destinatario = 'francescomurolo05@gmail.com';

$mail_headers = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
$mail_headers .= "Reply-To: " .  $mail_mittente . "\r\n";
$mail_headers .= "Content-type: text/html; charset=UTF-8\r\n";
$mail_headers .= "X-Mailer: PHP/" . phpversion();

$sql="SELECT * FROM prodotti WHERE prodotti.ID IN (";
      foreach($_SESSION['carrello'] as $id => $value) {
          $sql.=$id.",";
      }

      $sql=substr($sql, 0, -1).") ORDER BY nome ASC";

$query=mysqli_query($conn, $sql);
while($prodotto=mysqli_fetch_array($query)){
  $quantita=$_SESSION['carrello'][$prodotto['ID']]['quantity'];
  $messaggio.=$prodotto['nome']." x ".$quantita. "<br/>";
}

$messaggio.="<hr/><strong>Luogo consegna: </strong>".$luogo."<br/><strong>Totale: </strong>€".number_format($totale,2);

if(mail($mail_destinatario, $mail_oggetto, $messaggio, $mail_headers)){
  echo "fatto";
  $query_o="INSERT INTO bar.ordini (luogo_consegna, data_ora_ordine, totale, ID_studente) VALUES ('$luogo', '$date', '$totale', '$mail_mittente') ";

  $ris = mysqli_query($conn, $query_o) or die("Errore query 1!");

  $query_s="SELECT ordini.ID FROM ordini WHERE ordini.data_ora_ordine='$date' AND ordini.ID_studente='$mail_mittente' ";

  $ris_q= mysqli_query($conn, $query_s) or die("Errore query 2!");
  $id_ordine=mysqli_fetch_array($ris_q);
  $ordine=$id_ordine['ID'];

  $sql="SELECT * FROM prodotti WHERE prodotti.ID IN (";
        foreach($_SESSION['carrello'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY nome ASC";
  $query=mysqli_query($conn, $sql);
  while($prodotto=mysqli_fetch_array($query)){
    $quantita=$_SESSION['carrello'][$prodotto['ID']]['quantity'];
    $id_p=$prodotto['ID'];
    $sql2="INSERT INTO bar.dettaglio_ordini (ID_ordine, ID_prodotto, quantita) VALUES ('$ordine', '$id_p', '$quantita')";
    mysqli_query($conn, $sql2) or die("Errore query 3!");
  }

  mysqli_close($conn);

  header('Location:avviso_ordine.php');
}else
  echo "errore";
  //header('Location:riepilogo.php?err=1');
?>
