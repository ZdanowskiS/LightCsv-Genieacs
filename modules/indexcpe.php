<?php

function getBranche($branch,$key)
{
	return $branch[$key];
}

$LCPE = new LCPE($LAPI,$STORAGE);
$devices = $LCPE->connection->GetAllDevices();

if($devices)foreach($devices as $k => $device)
{
    if (!file_exists($filename)) {
		$configuration='';
		if($STORAGE->existsTemplate($device['_deviceId']['_ProductClass']))
		{
            $template=$STORAGE->getTemplateByLine($device['_deviceId']['_ProductClass']);
            foreach($template as $line)
            {
				$tree=explode('.',$line[3]);
				$branch=$device;
				foreach($tree as $val)
				{
					$branch=getBranche($branch,$val);
				}

				$line[4]=$branch['_value'];
				$configuration.=implode(';',$line)."\n";
            }
		}
        $STORAGE->saveCPE($device['_id'],$configuration);
    }
}

header('Location: ?m=welcome');
?>
