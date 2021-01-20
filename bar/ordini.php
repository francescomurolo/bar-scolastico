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

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco ordini</title>
    <!--link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body data-spy="scroll" data-target="#myScrollspy" data-offset="1">
  <nav class="navbar navbar-dark navbar-expand-md sticky-top" style="background-color:#1fb7ac;">
    <div class="container-fluid">
      <div class="navbar-brand" style="font-size:34px; color:white;"><strong>Elenco ordini</strong></div>
      <div class="navbar-header">
          <a href="http://www.itismaglie.it/" target="_blank" style="margin-left:14px;margin-right:14px;"><img src="assets/img/itis_logo.png" style="width:34px;"></a>
      </div>
        <ul class="nav navbar-nav navbar-right ml-auto">
          <li><a href="logout.php"><button class="btn btn-primary" type="button" style="background-color:rgb(31,183,172);">Esci</button></a></li>
        </ul>
    </div>
  </nav>

  <div class="container-fluid">
      <div class="row" style="text-align:center">
        <div class="col-md-1">
          <div style="padding-top:10px"><a class="btn btn-primary" role="button" href="crea_ordine.php" style="background-color:rgb(255,15,0);"><strong>Indietro</strong></a></div>
        </div>
        <div class="col-md-10">
          <?php
          //apri canale di comunicazione con il DBMS
          include "connessione_DB.php";
          $id=$_SESSION['id'];
          //preparo la query
          $query_o =   "SELECT * ".
                      "FROM ordini,studenti ".
                      "WHERE ordini.ID_studente=studenti.email AND ordini.ID_studente='$id' ".
                      "ORDER BY ordini.ID DESC ";

          //esegui la query e salva in memoria la tabella risultato
          $tab_ris_o = mysqli_query($conn, $query_o) or die("Errore query !");
          $nro_rec_o = mysqli_num_rows($tab_ris_o);   //ricava il numero di tuple estratte

          if($nro_rec_o){
          ?>
          <div class="table-responsive" style="padding-top:10px;">
              <table class="table" style="text-align:center">
                <thead>
                  <tr>
                      <th>Numero ordine</th>
                      <th>Data ordine</th>
                      <th>Totale ordine</th>
                      <th></th>
                  </tr>
                </thead>
                <tbody>
              <?php
              //stampa degli ordini
              for($i=0; $i<$nro_rec_o; $i++) {
                  $ordine= mysqli_fetch_array($tab_ris_o);
                  $id_ordine=$ordine['ID'];
                  ?>
                  <tr>
                    <td><?php echo $ordine['ID']?></td>
                    <td><?php echo date('d-m-Y',strtotime(substr($ordine['data_ora_ordine'],0,10))); ?></td>
                    <td>€<?php echo $ordine['totale']?></td>
                  </td>
                  <td style="vertical-align:middle;">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal<?php echo $ordine['ID']?>" style="background-color:#1fb7ac">
                      <i class="icon ion-information-circled"></i>
                    </button>
                  </td>
                    <?php
                    $query_co = "SELECT * ".
                              "FROM ordini,dettaglio_ordini,prodotti ".
                              "WHERE ordini.ID='$id_ordine' AND ordini.ID=dettaglio_ordini.ID_ordine AND dettaglio_ordini.ID_prodotto=prodotti.ID ";

                    $tab_ris_co = mysqli_query($conn, $query_co) or die("Errore query !");
                    $nro_rec_co = mysqli_num_rows($tab_ris_co);
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="modal<?php echo $ordine['ID']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Numero ordine: <?php echo $ordine['ID']?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <?php
                            //stampa dei prodotti
                            for($c=0; $c<$nro_rec_co; $c++) {
                                $prodotto= mysqli_fetch_array($tab_ris_co);
                            ?>
                                <p style="text-align:left"><?php echo $prodotto['nome'].' x '.$prodotto['quantita'];?></p>
                            <?php } ?>
                          </div>
                          <div class="modal-footer">
                            <span><font style="color:rgb(255,0,0);font-weight:bold;">Luogo consegna: <?php echo $ordine['luogo_consegna'];?></font></span>
                          </div>
                        </div>
                      </div>
                    </div>

                  </tr>
                <?php } ?>
              </table>
          </div>
        <?php }else{?>
          <h1 style="text-align:center; padding-top:5%; ">Nessun ordine!</h1>
        <?php } ?>
        </div>
        <div class="col-md-1"></div>
      </div>
  </div>
  <!--script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  <script src="assets/js/plus_minus.js"></script>
</body>

</html>
