<?php

$action=(array_key_exists('action', $_GET) ? $_GET['action']: '');

if($_FILES)
{
    $target=$CONFIG['general']['templatedir'].$_FILES['confFile']['name'];
    if(!move_uploaded_file($_FILES['confFile']['tmp_name'], $target))
    {
        echo "Could't upload file. Check privileges.";
        die();
    }
    header('Location: ?m=welcome');
}
elseif($action=='create')
{
	if(array_key_exists('class', $_POST))
        $oui=$_POST['class'];
    else
        $error='Device class.';

    if(!$error && !preg_match('/^.*csv/',$oui))
        $filename=$oui.'.csv';

    if(!$error && file_exists($CONFIG['general']['templatedir'].$filename))
        $error='Template file exists.';

    if(!$error)
    {
        file_put_contents($CONFIG['general']['templatedir'].$filename, $_POST['configuration']);

        header('Location: ?m=templateedit&id='.$oui);
    }
}

if($CONFIG['general']['apiclasslist'])
{
	$ouifiles=scandir($CONFIG['general']['templatedir']);

	$cpelist = $LAPI->GetAllDevices();

	if($cpelist)foreach($cpelist as $cpe)
	{
		if(in_array($cpe['_deviceId']['_ProductClass'].'.csv', $ouifiles))
			continue;

		$classlist[$cpe['_deviceId']['_ProductClass']]=$cpe['_deviceId']['_ProductClass'];
	}
}

$smarty->assign('Name', 'New Template');

$smarty->assign('apiclasslist', $CONFIG['general']['apiclasslist']);
$smarty->assign('classlist', $classlist);
$smarty->assign('error', $error);

$smarty->display('templateadd.html');

?>
