<?php

if(!$STORAGE->deleteCPE($_GET['id']))
{
    echo "Could't remove file. Check privileges.";
    die();
}

header('Location: ?m=welcome');
?>
