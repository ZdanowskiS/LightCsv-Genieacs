<?php

$filename=(array_key_exists('filename', $_POST) ? $_POST['filename'] : $_GET['id'].'.csv');

if(array_key_exists('filename', $_POST))
{
    file_put_contents($CONFIG['general']['cpedir'].$_POST['filename'], $_POST['file']);

	if($_POST['filename']!=$_POST['filenameold'])
		unlink($CONFIG['general']['cpedir'].$_POST['filenameold']);
}

$namelist=array();
if($CONFIG['general']['apinamelist'])
{
	$acsfiles=scandir($CONFIG['general']['cpedir']);

	$cpelist = $LAPI->GetAllDevices();

	if($cpelist)foreach($cpelist as $cpe)
	{
		if(in_array($cpe['_id'].'.csv', $acsfiles))
			continue;

		$namelist[]=$cpe['_id'];
	}
}

$file=file_get_contents($CONFIG['general']['cpedir'].$filename);

$smarty->assign('Name', 'Edit File: '.$filename);

$smarty->assign('apinamelist', $CONFIG['general']['apinamelist']);
$smarty->assign('namelist', $namelist);

$smarty->assign('filename', $filename);
$smarty->assign('file', $file);
$smarty->assign('id', $_GET['id']);

$smarty->display('fileedit.html');
?>
