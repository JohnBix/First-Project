<?php
  include '../functions.php';
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  $message = [];
  $action = ($_GET['action'] == 'create')?'Ajouter':'Modifier';
  $id_avalaible = ($_GET['action'] == 'edit')?'disabled':'';
  $id = (isset($_GET['id'])&&($_GET['action'] != 'create'))?$_GET['id']:'';
  $reservation = [
    'no_reservation' => '',
    'no_vol'=>'',
    'no_place'=>'',
    'date_reservation'=>'',
    'nom_voyageur'=>'',
  ];

  // actions
  if(!empty($_POST)){
    switch ($_GET['action']) {
      /* create */
      case 'create':
        if(!id_existe_deja($_POST['no_reservation'],'reservation','no_reservation')){
          $qInsert = $dbh->prepare("INSERT INTO reservation(`no_reservation`,`no_vol`,`no_place`,`date_reservation`,`nom_voyageur`) VALUES(?,?,?,?,?)");
          $qInsert->execute(array($_POST['no_reservation'],$_POST['no_vol'],$_POST['no_place'],$_POST['date_reservation'],$_POST['nom_voyageur']));
          $op = true;
        }else{
          $op = false;
        }
        break;

      /* edit */
      case 'edit':
        $date = str_replace("T"," ",$_POST['date_reservation']);
        $query = "UPDATE reservation SET `date_reservation`='".$date."',`nom_voyageur`='".$_POST['nom_voyageur']."' WHERE `no_reservation`='".$_GET['id']."'";
        $qUpdate = $dbh->prepare($query);
        $qUpdate->execute();
        $op = true;
        break;
      default:
        break;
    }

    // message
    if(empty($_POST['no_reservation']) || empty($_POST['date_reservation']) || empty($_POST['nom_voyageur'])){
      $message = array(
        'type' => 'echec',
        'content' => "Veuillez bien remplir les champs."
      );
    }elseif($op){
      $message = array(
        'type' => 'reussi',
        'content' => "L'opération a bien été enregistrée."
      );
    }else{
      $message = array(
        'type' => 'echec',
        'content' => "Le ID existe déjà."
      );
    }

  }

  // fill form
  if(($_GET['action'] == 'edit')&&(isset($_GET['id']))){
    $qreservation=$dbh->query("SELECT * FROM reservation WHERE no_reservation='".$_GET['id']."'");
    $greservation = $qreservation->fetchAll(PDO::FETCH_ASSOC);
    $reservation = [
      'no_reservation' => $greservation[0]['no_reservation'],
      'date_reservation'=> str_replace(" ","T",$greservation[0]['date_reservation']),
      'nom_voyageur' => $greservation[0]['nom_voyageur'],
    ];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!--<link rel="stylesheet" href="../style/style.css">-->
    <link rel="stylesheet" href="../style/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="../style/ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../style/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
    /*.form-field-item {
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .form-field-item.part {
      border-top: 1px solid #e6e4df;
    } */

    /* messages style*/
    .message{
      padding: 10px;
      margin-top: 10px;
    }

    .reussi{
      border-color: #c6f9bd;
      border-style: solid;
      background: #dcffdc;
      color: #0b3801;
    }

    .echec{
      border-color: #f5a7b5;
      border-style: solid;
      background: #fbebef;
      color: #980606;
    }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-align-justify"></span></a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="../index.php">Acceuil</a></li>
          <li><a href="list.php?vol=<?php print $_GET['vol'] ?>&avion=<?php print $_GET['avion'] ?>">Liste des places et reservations</a></li>
          <li class="active"><a href="#">Ajouter reservation</a></li>
        </ul>
      </div>
    </nav>

    <?php if($message) : ?>
      <div class="message <?php print $message['type'] ?>">
        <?php print $message['content']; ?>
      </div>
    <?php endif; ?>
    <div class="container"
    <h2> <?php print $action; ?> reservation <?php print $id; ?></h2>
    <form action="form.php?action=<?php print $_GET['action']; ?>&id=<?php print isset($_GET['id'])?$_GET['id']:''; ?>&vol=<?php print $_GET['vol'] ?>&avion=<?php print $_GET['avion'] ?>&place=<?php print $_GET['place']; ?>" method="post">
      <!-- no_reservation -->
      <div class="form-group">
        <label for="edit-no_reservation">ID de la reservation: </label>
        <input class="form-control" type="text" name="no_reservation" id="edit-no_reservation" value="<?php print $reservation['no_reservation']; ?>" <?php print $id_avalaible; ?> ><br>
        <?php if($id_avalaible == "disabled"): ?>
        <input class="form-control" type="hidden" name="no_reservation" value="<?php print $reservation['no_reservation']; ?>">
        <?php endif; ?>
      </div>
      <!-- no_vol -->
      <input class="form-control" type="hidden" name="no_vol" value="<?php print $_GET['vol']; ?>">
      <!-- no_place -->
      <input class="form-control" type="hidden" name="no_place" value="<?php print $_GET['place']; ?>">
      <!-- date de reservation -->
      <div class="form-group">
        <label for="edit-date_reservation">Date de reservation: </label>
        <input class="form-control" type="datetime-local" step=1 name="date_reservation" id="edit-date_reservation" value="<?php print $reservation['date_reservation']; ?>"><br>
      </div>
      <!-- Nom voyageur -->
      <div class="form-group">
        <label for="edit-nom_voyageur">Nom du voyageur: </label>
        <input class="form-control" type="text" name="nom_voyageur" id="edit-nom_voyageur" value="<?php print $reservation['nom_voyageur']; ?>"><br>
      </div>
      <input type="submit" value="<?php print $action; ?>">
    </form>
    </div>
  </body>

</html>
