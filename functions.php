<?php

function id_existe_deja($id,$table,$col){
  $dbh = new PDO('mysql:host=localhost;dbname=e_fly', "root", "");
  $query=$dbh->query("SELECT * FROM ".$table." WHERE ".$col."='".$id."'")->fetch();
  return $query?TRUE:FALSE;
}

?>