<?php

$action=$_GET['action'];
$in=$_GET['in'];

$LCSV= new LCsvGenieacs();

switch($action){
    case 'gettemplate':
        echo file_get_contents($CONFIG['general']['templatedir'].$in);
    break;
    default:
        return null;

}

?>
