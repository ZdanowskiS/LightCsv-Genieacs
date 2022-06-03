<?php

if(!$STORAGE->deleteTemplate($_GET['id']))
{
    echo "Could't remove file. Check privileges.";
    die();
}

header('Location: ?m=templatelist');
?>
