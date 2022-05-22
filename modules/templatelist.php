<?php 

$files = scandir($CONFIG['general']['templatedir']);

foreach($files as $file)
{
    if($file!='.' && $file!='..')
    {
        $id=str_replace('.csv','',$file);

        $templates[]=array('id' =>$id,
                    'file' => $file);
    }
}

$smarty->assign('Name', 'Template List');
$smarty->assign('templates', $templates);
$smarty->display('templatelist.html');

?>
