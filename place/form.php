<?php
  include '../functions.php';
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  $message = [];
  $action = ($_GET['action'] == 'create')?'Ajouter':'Modifier';
  $id_avalaible = ($_GET['action'] == 'edit')?'disabled':'';
  $id = (isset($_GET['id'])&&($_GET['action'] != 'create'))?$_GET['id']:'';
  $place = [
    'no_place' => '',
    'occupation'=>'',
  ];

  // actions
  if(!empty($_POST)){
    switch ($_GET['action']) {
      /* create */
      case 'create':
        if(!id_existe_deja($_POST['no_place'],'place','no_place')){
          $qInsert = $dbh->prepare("INSERT INTO place(`no_avion`,`no_place`, `occupation`) VALUES(?,?,?)");
          $qInsert->execute(array($_GET['avion'],$_POST['no_place'],$_POST['occupation']));
          $op = true;
        }else{
          $op = false;
        }
        break;

      /* edit */
      case 'edit':
        $query = "UPDATE place SET `occupation`='".$_POST['occupation']."' WHERE `no_place`='".$_GET['id']."'";
        $qUpdate = $dbh->prepare($query);
        $qUpdate->execute();
        $op = true;
        break;
      default:
        break;
    }

    // message
    if(empty($_POST['no_place'])){
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
    $qplace=$dbh->query("SELECT * FROM place WHERE no_place='".$_GET['id']."'");
    $gplace = $qplace->fetchAll(PDO::FETCH_ASSOC);
    $place = [
      'no_place' => $gplace[0]['no_place'],
      'occupation'=> $gplace[0]['occupation'],
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
          <li><a href="list.php?avion=<?php print $_GET['avion'] ?>">Liste des places de cet avion</a></li>
          <li class="active"><a href="#">Modifier Place</a></li>
        </ul>
      </div>
    </nav>
    <?php if($message) : ?>
      <div class="message <?php print $message['type'] ?>">
        <?php print $message['content']; ?>
      </div>
    <?php endif; ?>
    <div class="container">
    <h2> <?php print $action; ?> place <?php print $id; ?></h2>
    <form action="form.php?action=<?php print $_GET['action']; ?>&id=<?php print isset($_GET['id'])?$_GET['id']:''; ?>&avion=<?php print $_GET['avion']; ?>" method="post">
    <!-- no_place -->
    <div class="form-group">
      <label for="edit-no_place">ID de la place: </label>
      <input class="form-control" type="text" name="no_place" id="edit-no_place" value="<?php print $place['no_place']; ?>" <?php print $id_avalaible; ?> ><br>
      <?php if($id_avalaible == "disabled"): ?>
      <input class="form-control" type="hidden" name="no_place" value="<?php print $place['no_place']; ?>">
      <?php endif; ?>
    </div>
    <!-- occupation -->
    <div class="form-group">
      <label for="edit-occupation">Libelle de la place: </label>
      <input class="form-control" type="text" name="occupation" id="edit-occupation" value="<?php print $place['occupation']; ?>"><br>
    </div>
      <input type="submit" value="<?php print $action; ?>">
    </form>
    </div>
  </body>

</html>
