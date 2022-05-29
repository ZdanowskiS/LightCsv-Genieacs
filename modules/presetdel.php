<?php

$id=$_GET['id'];

$LAPI->DelPreset($id);

header('Location: ?m=presetslist');

?>
