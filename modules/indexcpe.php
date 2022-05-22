<?php

function getBranche($branch,$key)
{
	return $branch[$key];
}

$LCPE = new LCPE($LAPI);
$devices = $LCPE->connection->GetAllDevices();

if($devices)foreach($devices as $k => $device)
{
	$templatefile=$device['_deviceId']['_ProductClass'].'.csv';
    $target=$CONFIG['general']['cpedir'].$device['_id'].'.csv';
    if (!file_exists($filename)) {
		$configuration='';
		if(file_exists($CONFIG['general']['templatedir'].$templatefile))
		{
			$handle=fopen($CONFIG['general']['templatedir'].$templatefile,'r');
        	while(($data = fgetcsv($handle,1000,";")) !== FALSE)
        	{
				$tree=explode('.',$data[3]);
				$branch=$device;
				foreach($tree as $val)
				{
					$branch=getBranche($branch,$val);
				}

				$data[4]=$branch['_value'];
				$configuration.=implode(';',$data)."\n";
        	}
        	fclose($handle);
		}
        file_put_contents($target,$configuration);
    }
}

header('Location: ?m=welcome');
?>
