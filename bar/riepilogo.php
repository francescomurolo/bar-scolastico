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

  //verifico dell'invio del modulo
  if(isset($_POST['submit'])){

      foreach($_POST['quantity'] as $key => $val) {
          //se 0 elimino il prodotto dalla sessione
          if($val==0) {
              unset($_SESSION['carrello'][$key]);
          //se non è 0 imposto la nuova quantità
          }else{
              $_SESSION['carrello'][$key]['quantity']=$val;
          }
      }

  }

  //controllo della presenza dell'immagine
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello</title>
    <!--link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <script>
    //disabilita bottone
    /*function lock(button){
        button.disabled=true;
    }*/

    //abilita bottone
    function unlock(button){
        button.disabled=false;
    }
    </script>
</head>

<body style="position:relative;" data-spy="scroll" data-target="#myScrollspy" data-offset="1">
  <nav class="navbar navbar-dark navbar-expand-md sticky-top" style="background-color:#1fb7ac;">
    <div class="container-fluid">
      <div class="navbar-brand" style="font-size:34px; color:white;"><strong>Carrello</strong></div>
      <div class="navbar-header">
          <a href="http://www.itismaglie.it/" target="_blank" style="margin-left:14px;margin-right:14px;"><img src="assets/img/itis_logo.png" style="width:34px;"></a>
      </div>
        <ul class="nav navbar-nav navbar-right ml-auto">
          <li><a href="logout.php"><button class="btn btn-primary" type="button" style="background-color:rgb(31,183,172);">Esci</button></a></li>
        </ul>
    </div>
  </nav>

  <div class="container">
<?php if(isset($_SESSION['carrello']) && count($_SESSION['carrello'])!=0){ ?>
      <div class="row" style="text-align:center;">
        <div class="col-md-12">
          <form method="post" action="riepilogo.php">
            <div class="table-responsive" style="padding-top:70px;">
                <table class="table" style="text-align:center">
                    <thead>
                      <tr>
                          <th></th>
                          <th>Nome</th>
                          <th>Quantità</th>
                          <th>Prezzo unitario</th>
                          <th>Subtotale</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                          //connessione al db
                          include "connessione_DB.php";
                          //stampo il contenuto del carrello
                          $sql="SELECT * FROM prodotti WHERE prodotti.ID IN (";
                                foreach($_SESSION['carrello'] as $id => $value) {
                                    $sql.=$id.",";
                                }

                                $sql=substr($sql, 0, -1).") ORDER BY nome ASC";
                                $query=mysqli_query($conn, $sql);
                                $totale=0;
                                while($prodotto=mysqli_fetch_array($query)){
                                $img=ctrl_img($prodotto['immagine']);
                                $subtotale=number_format($_SESSION['carrello'][$prodotto['ID']]['quantity']*$prodotto['prezzo'],2);
                                $totale+=$subtotale;
                      ?>
                        <tr>
                            <td style="vertical-align:middle;"><img class="m-auto" src="assets/img/<?php echo $img ?>" style="max-width:100%;max-height:100%;height:70px;"></td>
                            <td style="vertical-align:middle;"><?php echo $prodotto['nome'] ?></td>
                            <td style="vertical-align:middle;width:174px;">
                              <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-danger btn-number"  data-type="minus" data-field="quantity[<?php echo $prodotto['ID'] ?>]">-</button>
                                </span>
                                <input type="text" name="quantity[<?php echo $prodotto['ID'] ?>]" class="form-control input-number" readonly value="<?php echo $_SESSION['carrello'][$prodotto['ID']]['quantity'] ?>" min="0" max="20" style="text-align:center;" onchange="if(value!=<?php echo $_SESSION['carrello'][$prodotto['ID']]['quantity'] ?>) unlock(submit);"/>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="quantity[<?php echo $prodotto['ID'] ?>]">+</button>
                                </span>
                              </div>
                            </td>
                            <td style="vertical-align:middle;"><font style="text-align:center;color:rgb(255,0,0);font-weight:bold;">€<?php echo $prodotto['prezzo'] ?></font></td>
                            <td style="vertical-align:middle;"><font style="text-align:center;color:rgb(255,0,0);font-weight:bold;">€<?php echo $subtotale ?></font></td>
                        </tr>
                        <?php

                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div style="text-align:center;padding-top:5%;">
                <button class="btn btn-primary" type="submit" name="submit" style="background-color:rgb(0,76,130);margin-left:40px;" disabled><strong>Aggiorna carrello</strong></button>
            </div>
          </form>
        </div>
      </div>

      <div class="row" style="text-align:center;">
        <div class="col-md-12">
          <form method="post" action="salva_ordine.php">
            <div style="text-align:center;padding-top:5%;">
              <p class="d-inline-block">Totale:&nbsp;</p><input type="text" value="€<?php echo number_format($totale,2) ?>" readonly style="width:6%;text-align:center;" name="totale_ordine"/>
              <a class="btn btn-primary" role="button" href="crea_ordine.php" style="background-color:rgb(255,15,0);margin-left:40px;"><strong>Indietro</strong></a>
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#quantityModal" style="background-color:rgb(20,200,0);margin-left:40px;"><strong>Termina ordine</strong></button>

              <!-- Modal -->
              <div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Dove consegnare l'ordine?</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <select style="font-size:20px;" name="luogo_consegna">
                        <option value="Classe" selected="">Classe</option>
                        <option value="Palestra succ. 1">Palestra succ. 1</option>
                        <option value="Lab. piano terra">Laboratorio piano terra</option>
                        <option value="Lab. piano 1">Laboratorio primo piano</option>
                        <option value="Lab. piano 2">Laboratorio secondo piano</option>
                        <option value="Lab. piano 3">Laboratorio terzo piano</option>
                      </select>
                    </div>
                    <div class="modal-footer">
                      <form method="post" action="salva.php">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color:rgb(255,15,0);">Annulla</button>
                        <button type="submit" class="btn btn-primary" style="background-color:rgb(20,200,0);">Conferma</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
<?php }else{ ?>
      <div class="row" style="text-align:center;">
        <div class="col-md-12">
          <h1 style="text-align:center; padding-top:5%; ">Carrello vuoto!</h1>
          <div style="text-align:center; padding-top:5%;">
              <a class="btn btn-primary" role="button" href="crea_ordine.php" style="background-color:rgb(255,15,0);"><strong>Indietro</strong></a>
          </div>
        </div>
      </div>
<?php } ?>

  </div>

  <?php
  if(isset($_GET['err'])){
      $codice_errore = $_GET['err'];

      switch($codice_errore){
        case 1:echo "<script>window.alert('Ordine non inviato!');</script>";
                  break;
      }
  }
  ?>
  <!--script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  <script src="assets/js/plus_minus.js"></script>
</body>

</html>
