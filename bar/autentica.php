<?php
//sessione inizializzata
session_start();

//accedi al DB  per l'autenticazione
include "connessione_DB.php";

$username=mysqli_real_escape_string($conn, $_POST['email']);
$password=md5($_POST['password']);

if ($username=="" || $password==""){
	header('location:index.php?err=1');
} else {
	//salvo l'username nel vettore di sessione
	$_SESSION['id']= $username;

	//prepara la query per controllare se l'utente è loggato
	$query_titolari = "SELECT * FROM titolari, account WHERE titolari.IDlogin=account.IDlogin AND email='$username' AND password='$password'";

	//esegui la query
	$ris_t = mysqli_query($conn, $query_titolari) or die("Errore query 1!");
	$nro_rec_t = mysqli_num_rows($ris_t);

	//se non estratto nessun record
	if(!$nro_rec_t){
		$query_studenti = 	"SELECT * ".
												"FROM studenti,account ".
												"WHERE studenti.IDlogin=account.IDlogin AND studenti.email='$username' AND account.password='$password'";

		$ris_s = mysqli_query($conn, $query_studenti) or die("Errore query 2!");
		$nro_rec_s = mysqli_num_rows($ris_s);

		if(!$nro_rec_s){
		//header('Location:index.php?err=2');
		}else{
			//se esiste un record con questo account
			$_SESSION['autenticato'] = 1;

			//memorizza username in un COOKIE per prossimi accessi
			$NGIORNI = 1;
			setcookie ("username", $username, time()+60*60*24*$NGIORNI);

			mysqli_close($conn);   //chiudi connessione col DBMS

			header('Location:crea_ordine.php');
		}
	}else{
		//se esiste un record con questo account
		$_SESSION['autenticato'] = 1;

		//memorizza username in un COOKIE per prossimi accessi
		$NGIORNI = 1;
		setcookie ("username",$username,time()+60*60*24*$NGIORNI);

		mysqli_close($conn);   //chiudi connessione col DBMS

		header('Location:gestione.php');
	}
}

?>
