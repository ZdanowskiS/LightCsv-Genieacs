<?php

$files = scandir('./lib/CPE');


if($files)foreach($files as $file)
{
    if($file!='.' && $file!='..')
    {
        require_once './lib/CPE/'.$file;
    }
}

?>
