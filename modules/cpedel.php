<?php

$id=$_GET['id'];

$LCPE = new LCPE($LAPI, $id);
$LCPE->DelDevice();

header('Location: ?m=cpelist');

?>
