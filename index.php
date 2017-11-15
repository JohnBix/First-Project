<?php

  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  // action
  if(isset($_GET['action']) && ($_GET['action']=='delete')){ /* delete */
    $qDelete = $dbh->prepare("DELETE FROM vol WHERE `no_vol`='".$_GET['id']."'");
    $qDelete->execute();
  }

$verifid = isset($_GET['no_avion']) ? $_GET['no_avion'] : '';

$stmt = $dbh->prepare("SELECT * FROM avion WHERE no_avion = ?");
$stmt->execute([$verifid]);
$avion = $stmt->fetch(PDO::FETCH_OBJ);

  try{

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $ex){

    die($ex->getMessage());
}
?>

<!DOCTYPE html>
<html>
  <head>

    <title>e_Fly</title>
    <!--link rel="stylesheet" href="style/style.css">-->
    <link rel="stylesheet" href="style/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="style/ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="style/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
    </script>

  </head>

  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-align-justify"></span></a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Acceuil</a></li>
          <li><a href="avion/list.php">Gerer les avions</a></li>
          <li><a href="vol/form.php?action=create">Ajouter Vol</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
          <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
      </div>
    </nav>

      <h2> Liste des vols </h2>

      <table class="table table-condensed" style="width:100%">
        <thead>
        <tr>
          <th>ID du vol</th>
          <th>Trajet</th>
          <th>Heure de depart</th>
          <th>Heure d'arrivée</th>
          <th>Frais</th>
          <th>Avion</th>
          <th>Actions</th>
        </tr>
        </thead>
        <?php foreach($dbh->query('SELECT * FROM vol ORDER BY no_vol DESC') as $row) : ?>
        <tbody>
        <tr>
          <td><?php print $row['no_vol'] ; ?></td>
          <td><?php print $row['ville_depart'].' - '.$row['ville_arrive'] ; ?></td>
          <td><?php print $row['heure_depart'] ; ?></td>
          <td><?php print $row['heure_arrive'] ; ?></td>
          <td><?php print $row['frais'] ; ?></td>
          <?php
            $resultats=$dbh->query("SELECT type FROM avion WHERE no_avion='".$row['no_avion']."'");
            $resultats->setFetchMode(PDO::FETCH_OBJ);
          ?>
          <td><?php print $resultats->fetch()->type ?></td>
          <td><a href="reservation/list.php?vol=<?php print $row['no_vol'] ?>&avion=<?php print $row['no_avion'] ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Reservation"><span class="glyphicon glyphicon-download-alt"></span></span></a>&nbsp;&nbsp;
            <a href="vol/form.php?action=edit&id=<?php print $row['no_vol']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
            <a href="index.php?action=delete&id=<?php print $row['no_vol']; ?>" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a></td>
        </tr>
        </tbody>
        <?php endforeach; ?>
      </table>
    </div>
    <div class="col-md-6 col-md-offset-3">
    <form method="GET" action="getrecette.php">
        <input type="hidden" name="no_avion" value="<?php echo '$avion->no_avion; ;'?>">
        <div class="form-group">
            <label for="annee" class="control-label">ANNEE</label>
            <input type="number" name="annee" id="annee" class="form-control">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">VOIR</button>
        </div>
    </form>
</div>

  </body>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.js"></script>
</html>
