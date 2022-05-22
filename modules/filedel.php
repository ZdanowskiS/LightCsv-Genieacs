<?php

if(!unlink($CONFIG['general']['cpedir'].$_GET['file']))
{
    echo "Could't remove file. Check privileges.";
    die();
}

header('Location: ?m=welcome');
?>
