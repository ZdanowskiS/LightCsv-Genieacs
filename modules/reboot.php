<?php

$id=$_GET['id'];

if($id)
{
    $LCPE = new LCPE($LAPI,$id);
    $LCPE->Reboot();
}
else
{
    $files = scandir($CONFIG['general']['cpedir']);
    if($files)foreach($files as $file)
    {
        if($file!='.' && $file!='..')
        {
            $id=str_replace('.csv','',$file);
            $LCPE = new LCPE($LAPI,$id);
            $LCPE->Reboot();
        }
    }
}

header('Location: ?m=welcome');
?>
