<?php
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  // action
  if(isset($_GET['action']) && ($_GET['action']=='delete')){ /* delete */
    $qDelete = $dbh->prepare("DELETE FROM place WHERE `no_place`='".$_GET['id']."'");
    $qDelete->execute();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <!--<link rel="stylesheet" href="../style/style.css"> -->
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
          <li class="active"><a href="../index.php">Acceuil</a></li>
          <li><a href="../avion/list.php">Liste des avions</a></li>
          <li><a href="form.php?action=create&avion=<?php print $_GET['avion'] ?>">Ajouter une place pour cet avion</a></li>
        </ul>
      </div>
    </nav>
    <div class="container"
    <h2> Liste des places pour l'avion <?php print $_GET['avion']; ?> </h2>
    <table class="table table-condensed" style="width:70%">
      <thead>
      <tr>
        <th>ID de la place</th>
        <th>Libelle</th>
        <th>Actions</th>
      </tr>
      </thead>
      <?php foreach($dbh->query("SELECT * FROM place WHERE no_avion='".$_GET['avion']."' ORDER BY no_place DESC") as $row) : ?>
      <tbody>
      <tr>
        <td><?php print $row['no_place'] ; ?></td>
        <td><?php print $row['occupation']; ?></td>
        <td><a href="form.php?action=edit&id=<?php print $row['no_place']; ?>&avion=<?php print $_GET['avion']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
          <a href="list.php?action=delete&id=<?php print $row['no_place']; ?>&avion=<?php print $_GET['avion'] ?>" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a></td>
      </tr>
      </tbody>
      <?php endforeach; ?>
    </table>
    </div>
  </body>

</html>
