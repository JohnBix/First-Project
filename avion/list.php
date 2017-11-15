<?php
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  // action
  if(isset($_GET['action']) && ($_GET['action']=='delete')){ /* delete */
    $qDelete = $dbh->prepare("DELETE FROM avion WHERE `no_avion`='".$_GET['id']."'");
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
          <li><a href="../index.php">Accueil</a></li>
          <li class="active"><a href="#">Gerer les avions</a></li>
          <li><a href="form.php?action=create">Ajouter un avion</a></li>
        </ul>
      </div>
    </nav>

    <h2> Liste des avions </h2>
    <table class="table table-condensed"style="width:100%">
      <thead>
      <tr>
        <th>ID de l'avion</th>
        <th>Type</th>
        <th>Nombre de place</th>
        <th>Actions</th>
      </tr>
      </thead>
      <?php foreach($dbh->query('SELECT * FROM avion ORDER BY no_avion DESC') as $row) : ?>
      <tbody>
      <tr>
        <td><?php print $row['no_avion'] ; ?></td>
        <td><?php print $row['type']; ?></td>
        <td><?php print $row['nb_place'] ; ?></td>
        <td><a href="../place/list.php?avion=<?php print $row['no_avion']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Place"><span class="glyphicon glyphicon-tag"></span></a>&nbsp;&nbsp;
          <a href="form.php?action=edit&id=<?php print $row['no_avion']; ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Modifier"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;
          <a href="list.php?action=delete&id=<?php print $row['no_avion']; ?>" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Supprimer"><span class="glyphicon glyphicon-trash"></span></a></td>
      </tr>
      </tbody>
      <?php endforeach; ?>
    </table>

  </body>

</html>
