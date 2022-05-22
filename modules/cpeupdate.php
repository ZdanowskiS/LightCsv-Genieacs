<?php
$devid=$_GET['id'];
$file=$_POST['update'];

$LCPE = new LCPE($LAPI, $_GET['id']);

$LCPE->Download($file);


header('Location: ?m=cpelist');
?>
