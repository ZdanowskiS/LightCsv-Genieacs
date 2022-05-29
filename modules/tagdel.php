<?php

$id=$_GET['id'];
$name=$_GET['name'];

$LCPE = new LCPE($LAPI, $id);
$LCPE->DelTag($name);

header('Location: ?m=cpeinfo&id='.$id);

?>
