<?php

if(!unlink($CONFIG['general']['templatedir'].$_GET['file']))
{
    echo "Could't remove file. Check privileges.";
    die();
}

header('Location: ?m=templatelist');
?>
