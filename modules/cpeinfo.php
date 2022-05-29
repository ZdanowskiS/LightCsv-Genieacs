<?php
function getBranch($name, $branch)
{
    return $branch[$name];
}
$id=$_GET['id'];

$CPE = new BaseCPE($LAPI,$id);

$device=$CPE->GetDeviceById();
$classname=$device[0]['_deviceId']['_ProductClass'];

$classexists=$hooks->existsCPE($classname);
if($classexists)
{
    $classname=$classname.'CPE';
    $CPE = new $classname($LAPI,$id);
}

$device[0]['DeviceID']=array('Manufacturer' => array ( '_value' => $device[0]['_deviceId']['_Manufacturer']),
                                        'ProductClass' => array ( '_value' => $device[0]['_deviceId']['_ProductClass']),
                                        'SerialNumber' => array ( '_value' => $device[0]['_deviceId']['_SerialNumber']));

$cpeinfo=$CPE->GetDeviceSummary($device);

$filename=$id.'.csv';
if(file_exists($CONFIG['general']['cpedir'].$filename))
{
    $tasks=$CPE->getActionTasks($id);

    if($tasks)foreach($tasks as $key => $task)
    {
        $branch=$cpeinfo['cpe'][0];
        $tree=explode(".",$task['param']);
        foreach($tree as $name)
        {
            $branch = getBranch($name, $branch);
        }
        $config[$task['name']]=$branch['_value'];
    }
}
$presets=$LAPI->GetPresets();

$map=array();
foreach($presets as $key => $preset)
{
    $preconditions=explode(" ", $preset['precondition']);

    foreach($preconditions as $k =>$string)
    {
        $branch=$device[0];
        $tree=explode(".",$string);
        foreach($tree as $name)
        {
            $branch = getBranch($name, $branch);
        }

        if($branch['_value'])
            $map[$string]=$branch['_value'];

    }
    foreach($map as $name => $val)
    {
        $preset['precondition']=str_replace($name,'"'.$val.'"',$preset['precondition']);
    }

    $preset['precondition']=str_replace('NOT ','!',$preset['precondition']);
    $preset['precondition']=str_replace('=','==',$preset['precondition']);
    $preset['precondition']=str_replace('IS','==',$preset['precondition']);
    $preset['precondition']="return ".$preset['precondition'].";";

    if(eval($preset['precondition']))
    {
        $cpepreset[]=$preset;
    }
}

$smarty->assign('Name', 'CPE info: '.$id);

$smarty->assign('cache_downloadurl', $CONFIG['general']['downloadurl']);

$smarty->assign('config', $config);
$smarty->assign('cpeinfo', $cpeinfo);
$smarty->assign('cpepreset', $cpepreset);
$smarty->assign('datamodel', $CPE->GetDataModel());
$smarty->assign('faults', $CPE->GetFaults());

$smarty->display('cpeinfo.html');

?>
