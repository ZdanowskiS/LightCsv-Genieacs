<?php 

$action=(array_key_exists('action', $_GET) ? $_GET['action']: '');

if($action=='del')
{
	$marks=(array_key_exists('marks', $_POST) ? $_POST['marks']: '');

	if($marks)foreach($marks as $key => $file)
	{
		unlink($CONFIG['general']['cpedir'].$file);
	}

}


$files = scandir($CONFIG['general']['cpedir']);

if($files)foreach($files as $file)
{
    if($file!='.' && $file!='..')
    {
        $id=str_replace('.csv','',$file);

        $LCPE = new LCPE($LAPI,$id);
        $res=$LCPE->GetDeviceById();

        $devices[]=array('id' =>$id,
                    'urlid' => urlencode($id),
                    'file' => $file,
                    'urlfile' => urlencode($file),
                    'reboottime' => (array_key_exists(0, $res) ? $res[0]['Reboot']['_value'] : 0));
    }
}

$smarty->assign('Name', 'File List');
$smarty->assign('devices', $devices);
$smarty->display('welcome.html');

?>
