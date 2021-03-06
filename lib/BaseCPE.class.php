<?php

class BaseCPE extends LCPE{

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
                                        'VLANEnable' => 'InternetGatewayDevice.Layer2Bridging.Bridge.1.VLAN.1.VLANEnable',
                                        'VLANName' => 'InternetGatewayDevice.Layer2Bridging.Bridge.1.VLAN.1.VLANName',
                                        'VLANID' => 'InternetGatewayDevice.Layer2Bridging.Bridge.1.VLAN.1.VLANID',
                                        )
							);

    public function GetDeviceById()
    {
         return $this->connection->GetDeviceById($this->deviceid);
    }

	private function isJSON($data)
	{
		return is_string($data) && is_array(json_decode($data, 1)) ? true : false;
	}

    public function GetDataModel()
    {
        return $this->data_model;
    }

	public function DownloadDiagnostics($downloadurl)
	{
		$task=array('param' => 'InternetGatewayDevice.DownloadDiagnostics.DownloadURL',
						'value' => $downloadurl);

		$this->ExecuteTask($task);

		$task=array('param' => 'InternetGatewayDevice.DownloadDiagnostics.DiagnosticsState',
						'value' => 'Requested');

		$this->ExecuteTask($task);
	}

	public function GetDownloadSpeed()
	{
		$param[]='InternetGatewayDevice.DownloadDiagnostics.DiagnosticsState';
		$param[]='InternetGatewayDevice.DownloadDiagnostics.BOMTime';
		$param[]='InternetGatewayDevice.DownloadDiagnostics.EOMTime';
		$param[]='InternetGatewayDevice.DownloadDiagnostics.TestBytesReceived';

		$test=$this->GetParameter($param);

		$device=$this->GetDeviceById();
		$result['DiagnosticsState']=$device[0]['InternetGatewayDevice']['DownloadDiagnostics']['DiagnosticsState']['_value'];
        if($result['DiagnosticsState']=='Completed')
        {
		    $result['BOMTime']=$device[0]['InternetGatewayDevice']['DownloadDiagnostics']['BOMTime']['_value'];
		    $result['EOMTime']=$device[0]['InternetGatewayDevice']['DownloadDiagnostics']['EOMTime']['_value'];
		    $result['TestBytesReceived']=$device[0]['InternetGatewayDevice']['DownloadDiagnostics']['TestBytesReceived']['_value'];

		    $result['Speed']=round(($result['TestBytesReceived']*8/1024/1024)/(strtotime($result['EOMTime'])-strtotime($result['BOMTime'])),2).'Mb/s';
		    $result['TestBytesReceived']=$result['TestBytesReceived']/1024/1024;
        }
		return $result;
	}

	public function getBranch($name, $branch)
	{
        return (array_key_exists($name, $branch) ? $branch[$name] : '');
	}

	public function testModel($function)
	{
		foreach($this->data_model[$function] as $key => $param)
		{
			$data=$this->GetParameter(array($param));
			if(!is_array($data) && (strpos($data, 'Error') !== FALSE)){
				$error[$key]=$data;
			}
		}

		if(!is_array($error))
		{
			$device=$this->GetDeviceById();
			foreach($this->data_model[$function] as $key => $param)
			{
				$tree=$device[0];
				$branch=explode(".",$param);

        		foreach($branch as $name)
        		{
                    if(is_array($tree))
            		    $tree = $this->getBranch($name, $tree);
        		}
                $tree['branch']=$param;
        		$result[$key]=$tree;
			}
		}
		else
			$result=$error;

		return $result;
	}

}

?>
