<?php

$action=$_GET['action'];
$id=$_GET['id'];

$CPE = new BaseCPE($LAPI,$id);

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
	case 'testWANPPP':
    case 'testWLAN':
	case 'testWANIP':
        $action=str_replace('test','',$action);

		$test=$CPE->testModel($action);

        $result='<TABLE>';
            $result.='<TR>';
            $result.='<TH>Name</TH><TH>Timestamp</TH><TH>Type</TH><TH>Writable</TH><TH>Value</TH>';
            $result.='</TR>';
        if($test)foreach($test as $name =>$item)
        {
            $result.='<TR>';
            $result.=sprintf('<TD><I>%s</I></TD><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD>',$name,$item['_timestamp'],$item['_type'],$item['_writable'],$item['_value']);
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
	default:
		return NULL;
}

?>