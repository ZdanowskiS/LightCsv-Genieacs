<?php

$LCSV= new LCsvGenieacs();

if($_FILES)
{
    $target=$CONFIG['general']['cpedir'].$_FILES['confFile']['name'];
    if(!move_uploaded_file($_FILES['confFile']['tmp_name'], $target))
    {
        echo "Could't upload file. Check privileges.";
        die();
    }
    header('Location: ?m=welcome');
}
elseif($_GET['action']=='create')
{
    if($_POST['devid'])
        $filename=$_POST['devid'];
    else
        $error='Device ID is required.';

    if(!$error && !preg_match('/^.*csv/',$_POST['devid']))
        $filename=$filename.'.csv';

    if(!$error && file_exists($CONFIG['general']['cpedir'].$filename))
        $error='Configuration file exists.';

    if(!$error)
    {
        file_put_contents($CONFIG['general']['cpedir'].$filename, $_POST['configuration']);

        header('Location: ?m=fileedit&id='.$_POST['devid']);
    }
}

if($CONFIG['general']['apinamelist'])
{
	$acsfiles=scandir($CONFIG['general']['cpedir']);

	$cpelist = $LAPI->GetAllDevices();

	if($cpelist)foreach($cpelist as $cpe)
	{
        if(in_array($cpe['_id'].".csv",$acsfiles))
            continue;

		$namelist[]=$cpe['_id'];
	}
}

$smarty->assign('Name', 'New File');

$smarty->assign('apinamelist', $CONFIG['general']['apinamelist']);
$smarty->assign('namelist', $namelist);
$smarty->assign('templatelist', $LCSV->getTemplateList());

$smarty->assign('error', $error);
$smarty->display('fileadd.html');

?>
