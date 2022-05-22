<?php

if(array_key_exists('filename', $_POST))
{
    file_put_contents($CONFIG['general']['templatedir'].$_POST['filename'], $_POST['file']);
}

if(array_key_exists('id', $_GET))
{
	$filename=$_GET['id'].'.csv';
	$file=file_get_contents($CONFIG['general']['templatedir'].$filename);
}

$smarty->assign('Name', 'Edit Template : '.$filename);

$smarty->assign('filename', $filename);
$smarty->assign('file', $file);
$smarty->assign('id', $_GET['id']);

$smarty->display('templateedit.html');
?>
