<?php

$acsfiles=$LAPI->GetFiles();

$cpelist = $LAPI->GetAllDevices();

$files = scandir($CONFIG['general']['cpedir']);

if($files)foreach($files as $file)
{
    if($file!='.' && $file!='..')
    {
        $id=str_replace('.csv','',$file);

        $devicefile[strtoupper($id)]=$file;
    }
}

$filter['soui']=(array_key_exists('soui', $_POST) ? $_POST['soui'] : '');
$filter['sclass']=(array_key_exists('sclass', $_POST) ? $_POST['sclass'] : '');
$filter['action']=(array_key_exists('action', $_POST) ? $_POST['action'] : '');

if($cpelist)foreach($cpelist as $cpe)
{
	$update=array();
    if($acsfiles)foreach($acsfiles as $acsfile)
    {
        if($acsfile['metadata']['oui']==$cpe['_deviceId']['_OUI'] 
            && $acsfile['metadata']['productClass']==$cpe['_deviceId']['_ProductClass'])
        {
            $update[$acsfile['_id']]=array('filename' => $acsfile['filename'],
                                            'version' => $acsfile['metadata']['version']);
        }
    }
	$ouilist[$cpe['_deviceId']['_OUI']]=$cpe['_deviceId']['_OUI'];
	$productclasslist[$cpe['_deviceId']['_ProductClass']]=$cpe['_deviceId']['_ProductClass'];


	if($filter['soui'] && $cpe['_deviceId']['_OUI']!=$filter['soui'])
		continue;

	if($filter['sclass'] && $cpe['_deviceId']['_ProductClass']!=$filter['sclass'])
		continue;

    $devices[]=array('id' => $cpe['_id'],
                    'urlid' => urlencode($cpe['_id']),
                    'manufacturer' => $cpe['_deviceId']['_Manufacturer'],
                    'productclass' => $cpe['_deviceId']['_ProductClass'],
                    'oui' => $cpe['_deviceId']['_OUI'],
                    'serialnumber' => $cpe['_deviceId']['_SerialNumber'],
                    'softwareversion' => $cpe['InternetGatewayDevice']['DeviceInfo']['SoftwareVersion']['_value'],
                    'file' => $devicefile[strtoupper($cpe['_id'])],
                    'reboottime' => (array_key_exists('Reboot', $cpe) ? $cpe['Reboot']['_value'] : 0),
                    'update' => $update);

	if($filter['action']=='reboot')
	{
	    $LCPE = new LCPE($cpe['_id']);
    	$LCPE->ExecuteReboot();
	}
} 

$order = (array_key_exists('o', $_GET) ? $_GET['o'] : '');
if ($order == '')
    $order = 'manufacturer,asc';

list($order, $direction) = sscanf($order, '%[^,],%s');

($direction == 'desc') ? $direction = 'desc' : $direction = 'asc';
//
if($direction == 'desc')
{
    usort($devices, function($a, $b) {
            global $order;
    	    return strcasecmp($a[$order], $b[$order]);
			});
}
else
{
    usort($devices, function($a, $b) {
            global $order;
            return strcasecmp($b[$order], $a[$order]);
			});
}
//
$listdata['order'] = $order;
$listdata['direction'] = $direction;

$smarty->assign('Name', 'CPE List');

$smarty->assign('ouilist',$ouilist);
$smarty->assign('productclasslist',$productclasslist);

$smarty->assign('filter', $filter);
$smarty->assign('devices', $devices);
$smarty->assign('listdata',$listdata);
$smarty->display('cpelist.html');

?>
