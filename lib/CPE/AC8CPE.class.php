<?php

class AC8CPE extends BaseCPE{

    public $name='AC8';

	public $data_model=array('WANPPP' => array(
										'Enable' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Enable',
										'Username' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Username',
										'Password' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Password'),
								'WLAN' => array(
										'SSID' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID',
										'KeyPassphrase' => 'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.KeyPassphrase',
										'Enable'=> 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Enable'),
								'WANIP' => array (
										'AddressingType' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.AddressingType',
										'ExternalIPAddress' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.ExternalIPAddress',
										'ConnectionStatus' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.ConnectionStatus',
										'DNSEnabled' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.DNSEnabled',
										'DNSServers' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.DNSServers',
										'DefaultGateway' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.DefaultGateway',
										'MACAddress' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.MACAddress',
										'SubnetMask' => 'InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANIPConnection.1.SubnetMask'),
                                'ConfigPassword' => array(
                                        'ConfigPassword' => 'InternetGatewayDevice.LANConfigSecurity.ConfigPassword',
                                        ),
                                'Layer2Bridging' => array(
                                        'VLANEnable' => 'InternetGatewayDevice.Layer2Bridging.Bridge.1.VLANEnable',
                                        'VLANName' => 'InternetGatewayDevice.Layer2Bridging.Bridge.1.BridgeName',
                                        'VLANID' => 'InternetGatewayDevice.Layer2Bridging.Bridge.1.VLANID',
                                        ),
                                'QoS' => array(
                                        'QoSEnable' => 'InternetGatewayDevice.WANDevice.1.X_Tn_QoS.Enable',
                                        'QoSDownband' => 'InternetGatewayDevice.WANDevice.1.X_Tn_QoS.Downband',
                                        'QoSUpband' => 'InternetGatewayDevice.WANDevice.1.X_Tn_QoS.Upband',
                                        )
							);

    static function addCPE()
    {
        return 'AC8';
    }

    static function addCPEFunctions()
    {
        $result['GetAssociatedDevices']=array('name' => 'GetAssociatedDevices',
                        'function' => 'getAssociatedDevices');

        $result['GetHosts']=array('name' => 'GetHosts',
                        'function' => 'getHosts');
        return $result;
    }

    public function getAssociatedDevices()
    {
        $tree[]=array('MAC' => array('InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.1.AssociatedDeviceMACAddress',
                                        'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.2.AssociatedDeviceMACAddress',
                                        'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.3.AssociatedDeviceMACAddress'),
                        'RSSI' => array('InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.1.X_Tn_RSSI',
                                        'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.2.X_Tn_RSSI',
                                        'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.3.X_Tn_RSSI'),
                        'RxRate' => array('InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.1.X_Tn_RxRate',
                                            'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.2.X_Tn_RxRate',
                                            'InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice.3.X_Tn_RxRate'));

        $refresh[]='InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.AssociatedDevice';
        $result=$this->GetParameter($refresh);

        $device=$this->GetDeviceById();

        foreach($tree as $key => $branch)
        {
            foreach($branch as $name => $paramlist)
            {
                foreach($paramlist as $row => $param)
                {
                    $stree=$device[0];
                    $sbranch=explode(".",$param);

        		    foreach($sbranch as $sname)
        		    {
                        if(is_array($stree))
            		        $stree = $this->getBranch($sname, $stree);
        		    }
                    $data[$key][$row][$name]=$stree;
                }
            }
        }

        $body='';
        $result='<TABLE>';
        $result.='<TR><TH>ID</TH><TH>MAC</TH><TH>RSSI</TH><TH>RxRate</TH></TR>';
        foreach($data as $key => $branch)
        {
            foreach ($branch as $row => $r)
            {
                $body.='<TR>';
                $body.="<TD>$row</TD>";
                foreach($r as $name => $param)
                {
                    $body.="<TD>".(is_array($param) ? $param['_value'] : '-')."</TD>";
                }
                $body.='</TR>';
            }
        }
        $result.=$body;
        $result.='</TABLE>';

        return $result;
    }

    public function getHosts()
    {
        $refresh[]='InternetGatewayDevice.LANDevice.1.Hosts';
        $result=$this->GetParameter($refresh);

        $device=$this->GetDeviceById();

        $tree=$device[0];
        $branch=explode(".",'InternetGatewayDevice.LANDevice.1.Hosts.HostNumberOfEntries');

        foreach($branch as $name)
        {
            $tree = $this->getBranch($name, $tree);
        }
        $count=$tree['_value'];

        $tree[]=array('MAC' => 'InternetGatewayDevice.LANDevice.1.Hosts.Host.%i.MACAddress',
                        'name' => 'InternetGatewayDevice.LANDevice.1.Hosts.Host.%i.HostName',
                        'interfaceTYPE' => 'InternetGatewayDevice.LANDevice.1.Hosts.Host.%i.InterfaceType');

        foreach($tree as $key => $branch)
        {
            if(is_array($branch))foreach($branch as $name => $param)
            {
                    $i=1;
                    while($i<=$count){
                        $stree=$device[0];
                        $sbranch=explode(".",str_replace('%i',$i,$param));
        		        foreach($sbranch as $sname)
        		        {
            		        $stree = $this->getBranch($sname, $stree);
        		        }
                        $data[$i][$name]=$stree['_value'];
                        $i++;
                    }
               
            }
        }
        $body='';
        $result='<TABLE>';
        $result.='<TR><TH>ID</TH><TH>MAC</TH><TH>name</TH><TH>Interface type</TH></TR>';

        if(is_array($data))foreach ($data as $row => $r)
        {
            $body.='<TR>';
            $body.="<TD>$row</TD>";
            foreach($r as $name => $param)
            {
                $body.="<TD>".$param."</TD>";
            }
            $body.='</TR>';
        }

        $result.=$body;
        $result.='</TABLE>';

        return $result;
    }
}
?>
