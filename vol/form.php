<?php
  include '../functions.php';
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  $message = [];
  $action = ($_GET['action'] == 'create')?'Ajouter':'Modifier';
  $id_avalaible = ($_GET['action'] == 'edit')?'disabled':'';
  $id = (isset($_GET['id'])&&($_GET['action'] != 'create'))?$_GET['id']:'';
  $vol = [
    'no_vol' => '',
    'heure_depart'=>'',
    'heure_arrive'=>'',
    'ville_depart'=>'',
    'ville_arrive'=>'',
    'frais'=>'',
    'avion'=>'',
  ];

  // actions
  if(!empty($_POST)){
    switch ($_GET['action']) {
      /* create */
      case 'create':
        if(!id_existe_deja($_POST['no_vol'],'vol','no_vol')){
          $qInsert = $dbh->prepare("INSERT INTO vol(`no_vol`, `heure_depart`, `heure_arrive`, `ville_depart`, `ville_arrive`, `frais`, `no_avion`) VALUES(?,?,?,?,?,?,?)");
          $qInsert->execute(array($_POST['no_vol'],$_POST['heure_depart'],$_POST['heure_arrive'],$_POST['ville_depart'],$_POST['ville_arrive'],$_POST['frais'],$_POST['no_avion']));
          $op = true;
        }else{
          $op = false;
        }
        break;

      /* edit */
      case 'edit':
        $hd = str_replace("T"," ",$_POST['heure_depart']);
        $ha = str_replace("T"," ",$_POST['heure_arrive']);
        $query = "UPDATE vol SET `heure_depart`='".$hd."', `heure_arrive`='".$ha."', `ville_depart`='".$_POST['ville_depart']."' ,`ville_arrive`='".$_POST['ville_arrive']."', `frais`='".$_POST['frais']."', `no_avion`='".$_POST['no_avion']."'"." WHERE `no_vol`='".$_GET['id']."'";
        $qUpdate = $dbh->prepare($query);
        $qUpdate->execute();
        $op = true;
        break;
      default:
        break;
    }

    // message
    if(empty($_POST['no_vol']) || empty($_POST['ville_depart']) || empty($_POST['ville_arrive']) || empty($_POST['frais'])){
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
    $qVol=$dbh->query("SELECT * FROM vol WHERE no_vol='".$_GET['id']."'");
    $gVol = $qVol->fetchAll(PDO::FETCH_ASSOC);
    $vol = [
      'no_vol' => $gVol[0]['no_vol'],
      'heure_depart'=> str_replace(" ","T",$gVol[0]['heure_depart']),
      'heure_arrive'=> str_replace(" ","T",$gVol[0]['heure_arrive']) ,
      'ville_depart'=> $gVol[0]['ville_depart'],
      'ville_arrive'=> $gVol[0]['ville_arrive'],
      'frais'=> $gVol[0]['frais'],
      'avion'=> $gVol[0]['no_avion'],
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
          <li><a href="form.php?action=create">Ajouter vol</a></li>
          <li class="active"><a href="#">Modifier vol</a></li>
        </ul>
      </div>
    </nav>

    <?php if($message) : ?>
      <div class="message <?php print $message['type'] ?>">
        <?php print $message['content']; ?>
      </div>
    <?php endif; ?>
    <div class="container">
    <h2> <?php print $action; ?> vol <?php print $id; ?></h2>

    <form action="form.php?action=<?php print $_GET['action']; ?>&id=<?php print isset($_GET['id'])?$_GET['id']:''; ?>" method="post">
    <!-- no_vol -->
    <div class="form-group">
      <label for="edit-no_vol">ID du vol: </label>
      <input type="text" class="form-control" name="no_vol" id="edit-no_vol" value="<?php print $vol['no_vol']; ?>" <?php print $id_avalaible; ?> ><br>
      <?php if($id_avalaible == "disabled"): ?>
      <input class="form-control" type="hidden" name="no_vol" value="<?php print $vol['no_vol']; ?>">
      <?php endif; ?>
    </div>
    <!-- Heure depart -->
    <div class="form-group">
      <label for="edit-heure_depart">Heure DEPART: </label>
      <input class="form-control" type=datetime-local step=1 name="heure_depart" id="edit-heure_depart" value="<?php print $vol['heure_depart']; ?>"><br>
    </div>
    <!-- Heure arrive -->
    <div class="form-group">
      <label for="edit-heure_arrive">Heure ARRIVE: </label>
      <input class="form-control" type="datetime-local" step=1 name="heure_arrive" id="edit-heure_arrive" value="<?php print $vol['heure_arrive']; ?>"><br>
    </div>
    <!-- Ville depart -->
    <div class="form-group">
      <label for="edit-ville_depart">Ville DEPART: </label>
      <input class="form-control" type="text" name="ville_depart" id="edit-ville_depart" value="<?php print $vol['ville_depart']; ?>"><br>
    </div>
    <!-- Ville arrivee -->
    <div class="form-group">
      <label for="edit-ville_arrive">Ville ARRIVE: </label>
      <input class="form-control" type="text" name="ville_arrive" id="edit-ville_arrive" value="<?php print $vol['ville_arrive']; ?>"><br>
    </div>
    <!-- frais -->
    <div class="form-group">
      <label for="edit-frais">Frais: </label>
      <input class="form-control" type="text" name="frais" id="edit-frais" value="<?php print $vol['frais']; ?>"><br>
    </div>
    <!-- avion -->
    <div class="form-group">
      <label for="edit-no_avion">Avions: </label>
      <select class="form-control" name="no_avion" id="edit-no_avion">
        <?php foreach($dbh->query('SELECT no_avion,type FROM avion') as $row) : ?>
        <?php $selected = ""; ?>
        <?php if($vol['avion'] == $row['no_avion']){ $selected = "selected";} ?>
        <option <?php print $selected; ?> value="<?php print $row['no_avion'] ; ?>"><?php print $row['no_avion'] . ' ' . '||' . ' ' . $row['type'] ; ?></option>
        <?php endforeach; ?>
      </select><br>
    </div>
    <input type="submit" value="<?php print $action; ?>">
    </form>
  </div>
  </body>

</html>
