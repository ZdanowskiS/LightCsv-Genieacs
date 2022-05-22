<?php
function getBranch($name, $branch)
{
    return $branch[$name];
}
$id=$_GET['id'];

$CPE = new BaseCPE($LAPI,$id);

$cpeinfo=$CPE->GetDeviceSummary();

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

$smarty->assign('Name', 'CPE info: '.$id);

$smarty->assign('cache_downloadurl', $CONFIG['general']['downloadurl']);

$smarty->assign('config', $config);
$smarty->assign('cpeinfo', $cpeinfo);
$smarty->assign('faults', $CPE->GetFaults());

$smarty->display('cpeinfo.html');

?>
