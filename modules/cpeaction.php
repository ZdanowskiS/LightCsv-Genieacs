<?php

$action=$_GET['action'];
$id=$_GET['id'];

$CPE = new BaseCPE($LAPI,$id);
$device=$CPE->GetDeviceById();
$classname=$device[0]['_deviceId']['_ProductClass'];

$classexists=$hooks->existsCPE($classname);
if($classexists)
{
    $classname=$classname.'CPE';
    $CPE = new $classname($LAPI,$id);
    $functions=$hooks->getCPEfunctions($CPE->name);
}

switch($action){
	case 'DownloadDiagnostics':
		$downloadurl=$CONFIG['general']['downloadurl'];
		$CPE->DownloadDiagnostics($downloadurl);
        echo 'GetDownloadSpeed';
	break;
	case 'GetDownloadSpeed':
		$download=$CPE->GetDownloadSpeed();
        if($download['DiagnosticsState']=='Completed')
        {
            $result='<TABLE>';
            $result.='<TR>';
            $result.='<TH>BOM</TH><TH>EOM</TH><TH>Size</TH><TH>Speed</TH>';
            $result.='</TR>';
            $result.='<TR>';
            $result.=sprintf('<TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD>',$download['BOMTime'],$download['EOMTime'],$download['TestBytesReceived'],$download['Speed']);
            $result.='</TR>';
            $result.='</TABLE>';
        }
        else
        {
            $result='Diagnostic Status:'.$download['DiagnosticsState'];
        }
        echo $result;
	break;
    case array_key_exists($action, $CPE->data_model): 
		$test=$CPE->testModel($action);

        $result='<TABLE>';
            $result.='<TR>';
            $result.='<TH>Name</TH><TH>Timestamp</TH><TH>Type</TH><TH>Writable</TH><TH>Value</TH>';
            $result.='</TR>';
        if($test)foreach($test as $name =>$item)
        {
            $result.='<TR>';
            $result.=sprintf('<TD title="%s"><I>%s</I></TD><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD>',$item['branch'],$name,$item['_timestamp'],$item['_type'],$item['_writable'],$item['_value']);
            $result.='</TR>';
        }
        $result.='<TABLE>';
        echo $result;
    break;
	case 'Reboot':
        $CPE->Reboot();
        echo "reload";
    break;
	case 'FactoryReset':
        $CPE->FactoryReset();
        echo "reload";
    break;
    case 'SendConfig':
        $CPE->ExecuteAction();
        echo "reload";
    break;
    case array_key_exists($action, $functions):

        $functionname=$functions[$action]['function'];
        $result=$CPE->$functionname();

        echo $result;
    break;
	default:
		return NULL;
}

?>
