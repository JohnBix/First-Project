<?php
  include '../functions.php';
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  $message = [];
  $action = ($_GET['action'] == 'create')?'Ajouter':'Modifier';
  $id_avalaible = ($_GET['action'] == 'edit')?'disabled':'';
  $id = (isset($_GET['id'])&&($_GET['action'] != 'create'))?$_GET['id']:'';
  $avion = [
    'no_avion' => '',
    'type'=>'',
    'nb_place'=>'',
  ];

  // actions
  if(!empty($_POST)){
    switch ($_GET['action']) {
      /* create */
      case 'create':
        if(!id_existe_deja($_POST['no_avion'],'avion','no_avion')){
          $qInsertAvion = $dbh->prepare("INSERT INTO avion(`no_avion`, `type`, `nb_place`) VALUES(?,?,?)");
          $qInsertAvion->execute(array($_POST['no_avion'],$_POST['type'],$_POST['nb_place']));
          // create automatically place
          for ($i=0; $i < $_POST['nb_place'] ; $i++) {
            $qInsertPlace = $dbh->prepare("INSERT INTO place(`no_avion`,`no_place`, `occupation`) VALUES(?,?,?)");
            $qInsertPlace->execute(array($_POST['no_avion'],str_replace(' ','_',$_POST['no_avion']).'_P'.$i,'P'.$i));
          }
          $op = true;
        }else{
          $op = false;
        }
        break;

      /* edit */
      case 'edit':
        $query = "UPDATE avion SET `type`='".$_POST['type']."', `nb_place`='".$_POST['nb_place']."' WHERE `no_avion`='".$_GET['id']."'";
        $qUpdate = $dbh->prepare($query);
        $qUpdate->execute();
        $op = true;
        break;
      default:
        break;
    }

    // message
    if(empty($_POST['no_avion']) || empty($_POST['type']) || empty($_POST['nb_place'])){
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
    $qAvion=$dbh->query("SELECT * FROM avion WHERE no_avion='".$_GET['id']."'");
    $gAvion = $qAvion->fetchAll(PDO::FETCH_ASSOC);
    $avion = [
      'no_avion' => $gAvion[0]['no_avion'],
      'type'=> $gAvion[0]['type'],
      'nb_place'=> $gAvion[0]['nb_place'],
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
          <li><a href="list.php">Liste des avions</a></li>
          <li class="active"><a href="form.php?action=create">Ajouter un avion</a></li>
        </ul>
      </div>
    </nav>
    <?php if($message) : ?>
      <div class="message <?php print $message['type'] ?>">
        <?php print $message['content']; ?>
      </div>
    <?php endif; ?>
    <div class="container">
    <h2> <?php print $action; ?> avion <?php print $id; ?></h2>
    <form action="form.php?action=<?php print $_GET['action']; ?>&id=<?php print isset($_GET['id'])?$_GET['id']:''; ?>" method="post">
    <!-- no_avion -->
    <div class="form-group">
      <label for="edit-no_avion">ID de l'avion: </label>
      <input class="form-control" type="text" name="no_avion" id="edit-no_avion" value="<?php print $avion['no_avion']; ?>" <?php print $id_avalaible; ?> ><br>
      <?php if($id_avalaible == "disabled"): ?>
      <input class="form-control" type="hidden" name="no_avion" value="<?php print $avion['no_avion']; ?>">
      <?php endif; ?>
    </div>
    <!-- Type -->
    <div class="form-group">
      <label for="edit-type">Type de l'avion: </label>
      <input class="form-control" type="text" name="type" id="edit-type" value="<?php print $avion['type']; ?>"><br>
    </div>
    <!-- nb place -->
    <div class="form-group">
      <label for="edit-nb_place">Nombre de place: </label>
      <input class="form-control" type="text" name="nb_place" id="edit-nb_place" value="<?php print $avion['nb_place']; ?>"><br>
    </div>
    <input type="submit" value="<?php print $action; ?>">
    </form>
    </div>
  </body>

</html>
