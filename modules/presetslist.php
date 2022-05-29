<?php

$presets=$LAPI->GetPresets();
$smarty->assign('Name', 'Presets List');
$smarty->assign('presets',$presets);
$smarty->display('presetslist.html');
?>
