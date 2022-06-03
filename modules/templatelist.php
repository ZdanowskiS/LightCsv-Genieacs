<?php 

$LCSV= new LCsvGenieacs($STORAGE);

$smarty->assign('Name', 'Template List');
$smarty->assign('templates', $LCSV->getTemplateList());
$smarty->display('templatelist.html');

?>
