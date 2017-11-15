<?php
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  // action
  if(isset($_GET['action']) && ($_GET['action']=='delete')){ /* delete */
    $qDelete = $dbh->prepare("DELETE FROM reservation WHERE `no_reservation`='".$_GET['id']."'");
    $qDelete->execute();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!--<link rel="stylesheet" href="../style/style.css">-->
    <link rel="stylesheet" href="../style/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="../style/ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../style/maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
          <li><a href="../index.php">Acceuil</a></li>
          <li><a href="../avion/list.php">Gerer les avions</a></li>
          <li><a href="../avion/form.php?action=create">Ajouter un avion</a></li>
          <li class="active"><a href="#">Reservation</a>
        </ul>
      </div>
    </nav>

    <div class="container">
    <h2> Liste des places et reservations </h2>
    <table class="table table-condensed" style="width:70%">
      <thead>
      <tr>
        <th>Place</th>
        <th>Commentaire</th>
        <th>Actions</th>
      </tr>
      </thead>
      <?php foreach($dbh->query("SELECT * FROM place WHERE no_avion='".$_GET['avion']."' ORDER BY no_place ASC") as $row) : ?>
      <tbody>
      <tr>
        <!-- id de la place -->
        <td><?php print $row['no_place'] ; ?></td>
        <!-- check if reservation exists -->
        <?php $query=$dbh->query("SELECT no_reservation FROM reservation WHERE no_vol='".$_GET['vol']."' AND no_place='".$row['no_place']."'")->fetch(); ?>
        <?php if($query): ?>
        <!-- exists -->
        <td><span class="glyphicon glyphicon-ok" style="color:green;"></span> Place réservée </td>
        <td><a href="form.php?action=edit&id=<?php print $query['no_reservation']; ?>&vol=<?php print $_GET['vol'] ?>&avion=<?php print $_GET['avion'] ?>&place=<?php print $row['no_place']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
            <a href="list.php?action=delete&id=<?php print $query['no_reservation']; ?>&vol=<?php print $_GET['vol'] ?>&avion=<?php print $_GET['avion'] ?>&place=<?php print $row['no_place'];?>" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a></td>
        <?php else: ?>
        <!-- not exists -->
        <td></td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="form.php?action=create&vol=<?php print $_GET['vol'] ?>&avion=<?php print $_GET['avion'] ?>&place=<?php print $row['no_place']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Reserver"><span class="glyphicon glyphicon-shopping-cart"></span></a></td>
        <?php endif; ?>
      </tr>
      </tbody>
      <?php endforeach; ?>
    </table>
  </div>

  </body>

</html>
