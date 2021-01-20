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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Crea ordine</title>
    <!--link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
    <link rel="stylesheet" href="assets/css/styles.css">


    <script>
    //disabilita bottone
    function lock(button){
        button.disabled=true;
    }

    //abilita bottone
    function unlock(button){
        button.disabled=false;
    }
    </script>
</head>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="1">
    <nav class="navbar navbar-dark navbar-expand-md sticky-top" style="background-color:#1fb7ac;">
        <div class="container-fluid">
          <div class="navbar-brand" style="font-size:34px; color:white;"><strong>Crea il tuo ordine</strong></div>
          <div class="navbar-header">
              <a href="https://www.iissmatteimaglie.edu.it/" target="_blank" style="margin-left:14px;margin-right:14px;"><img src="assets/img/itis_logo.png" style="width:34px;"></a>
          </div>
            <ul class="nav navbar-nav navbar-right ml-auto">
              <li><a href="ordini.php"><button class="btn btn-primary" type="button" data-toggle="tooltip" data-placement="bottom" title="Visualizza i tuoi ordini" style="background-color:rgb(31,183,172);margin-right:6px">Ordini <?php if(isset($_GET['ordine'])){ echo "<span class='badge badge-light'>1</span>";} ?></button></a></li>
              <li><a href="logout.php"><button class="btn btn-primary" type="button" style="background-color:rgb(31,183,172);">Esci</button></a></li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row" style="text-align:center;">
          <?php
          //apri canale di comunicazione con il DBMS
          include "connessione_DB.php";
          //preparo la query per salvare tutte le categorie da stampare
          $query_categorie =  "SELECT DISTINCT categorie.titolo ".
                              "FROM prodotti, categorie ".
                              "WHERE prodotti.ID_categoria=categorie.ID_categoria ";
          //creo un vettore per salvare tutte le categorie dei prodotti
          $cat=array();

          //esegui la query e salva in memoria la tabella risultato
          $tab_ris_c = mysqli_query($conn, $query_categorie) or die("Errore query !");
          $nro_rec_c = mysqli_num_rows($tab_ris_c);   //ricava il numero di tuple estratte
          ?>

          <div class="col-md-2">
            <ul class="nav nav-pills flex-column nav-justified" id="myScrollspy" style="position:fixed;background-color:#eeeeee;margin:36px;font-size:19px;">
              <?php for($i=0; $i<$nro_rec_c; $i++) {
                        $categoria= mysqli_fetch_array($tab_ris_c);?>
                        <li class="nav-item"><a class="nav-link" href="#<?php echo $categoria['titolo']?>" style="text-align:left; text-transform:capitalize"><?php echo $categoria['titolo']?></a></li>
              <?php
                        $cat[$i]=$categoria['titolo'];
                    }?>
            </ul>
          </div>

          <div class="col-md-8">
          <?php
              for($a=0; $a<count($cat); $a++) {
                //preparo la query che permette di stampare i prodotti differiti per categoria
                $query_prodotti = "SELECT * ".
                                  "FROM prodotti, categorie ".
                                  "WHERE prodotti.ID_categoria=categorie.ID_categoria AND categorie.titolo LIKE '$cat[$a]' AND prodotti.in_produzione='1' ";

                      //esegui la query e salva in memoria la tabella risultato
                      $tab_ris_p = mysqli_query($conn, $query_prodotti) or die("Errore query !");
                      $nro_rec_p = mysqli_num_rows($tab_ris_p);

                      ?>
                      <div id="<?php echo $cat[$a]?>" style="padding-top:70px">
                          <h2 style="color:rgb(31,183,172);text-align:center;text-transform:uppercase;"><?php echo $cat[$a]?></h2>
                      </div>
                      <div class="table-responsive d-inline" style="max-width:100%;max-height:100%;width:60%;align-content:center;margin-left:20%;">
                          <table class="table" style="text-align:center">
                              <tbody>

                      <?php
                      //stampa dei prodotti
                      for($c=0; $c<$nro_rec_p; $c++) {
                          $prodotto = mysqli_fetch_array($tab_ris_p);
                          $img = ctrl_img($prodotto['immagine']);
                          ?>
                          <tr>
                            <form method="post" action="aggiungi_al_carrello.php?id=<?php echo $prodotto['ID']?>" name="form_<?php echo $prodotto['ID']?>">
                              <td style="vertical-align:middle;text-align:center"><img class="m-auto" src="assets/img/<?php echo $img ?>" style="max-width:100%;max-height:100%;height:70px;"></td>
                              <td style="vertical-align:middle;width:200px;"><?php echo $prodotto['nome']?></td>
                              <td style="vertical-align:middle;color:rgb(255,15,0);"><font style="text-align:center;color:rgb(255,0,0);font-weight:bold;">€<?php echo $prodotto['prezzo']?></font></td>
                              <td style="vertical-align:middle;width:174px;">
                                <?php if($prodotto['disponibile']==1){ ?>
                                <div class="input-group">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="<?php echo $prodotto['ID']?>"disabled>-</button>
                                  </span>
                                  <input type="text" name="<?php echo $prodotto['ID']?>" class="form-control input-number" readonly value="0" min="0" max="20" style="text-align:center;" onchange="if(value>0) unlock(invio_dati_<?php echo $prodotto['ID']?>); else lock(invio_dati_<?php echo $prodotto['ID']?>);">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="<?php echo $prodotto['ID']?>">+</button>
                                  </span>
                                </div>
                              <?php }else{?>
                                Prodotto non disponibile!
                              <?php }?>

                              </td>
                              <td style="vertical-align:middle;">
                                <button type="submit" name="invio_dati_<?php echo $prodotto['ID']?>" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Aggiungi al carrello" style="background-color:#1fb7ac" disabled>
                                  <i class="icon ion-ios-cart"></i>
                                </button>
                              </td>
                            </form>
                          </tr>
                      <?php }?>
                        </tbody>
                        </table>
                        </div>
              <?php  }?>
              </div>
              <div class="col-md-2">
                <div style="position:fixed;text-align:left;margin-top:41px;">
                <?php
                    //verifico se il carrello di sessione è impostato
                    if(isset($_SESSION['carrello']) && count($_SESSION['carrello'])!=0){
                        //seleziono solo i prodotti che sono nella sessione
                        $sql="SELECT * FROM prodotti WHERE ID IN (";

                        foreach($_SESSION['carrello'] as $id => $value) {
                            $sql.=$id.",";
                        }

                        $sql=substr($sql, 0, -1).") ORDER BY nome ASC";
                        $query=mysqli_query($conn, $sql);
                        while($row=mysqli_fetch_array($query)){

                        ?>
                            <p><font size="2"><?php echo $row['nome'] ?> x <?php echo $_SESSION['carrello'][$row['ID']]['quantity'] ?></font></p>
                        <?php

                        }
                        ?>
                        <a class="btn btn-primary" role="submit" href="riepilogo.php" style="background-color:rgb(255,15,0);"><strong>Visualizza carrello</strong></a>
                        <?php
                    }
                ?>
                </div>
              </div>
        </div>
    </div>

    <?php
    if(isset($_GET['err'])){
        $codice_errore = $_GET['err'];

        switch($codice_errore){
          case 1:echo "<script>window.alert('ID prodotto invalido!');</script>";
                    break;
        }
    }
    ?>

    <!--script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="assets/js/plus_minus.js"></script>
    <script>$(document).ready(function(){$('[data-toggle="tooltip"]').tooltip();});</script>
</body>

</html>
