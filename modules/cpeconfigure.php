<?php

$id=$_GET['id'];

$LCPE = new LCPE($LAPI, $id);
$LCPE->ExecuteAction();

header('Location: ?m=welcome');
?>
