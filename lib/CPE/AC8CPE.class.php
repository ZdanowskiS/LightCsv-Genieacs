<?php

class AC8CPE extends BaseCPE{

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
                                        )
							);

	public function __construct(&$connection=null, $deviceid=null){

		$this->connection=&$connection;
		$this->deviceid=$deviceid;
	}

    public function addCPE()
    {
        return 'AC8';
    }
}
?>
