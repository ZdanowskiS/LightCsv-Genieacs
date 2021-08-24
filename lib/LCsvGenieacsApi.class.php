<?php

class LCsvGenieacsApi {

	private $url;

	public function __construct(){

	}

    public function setURL($url)
    {
        $this->url=$url;
    }

	public function GET($action)
	{
		if(!$this->url)
			return;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $this->url.$action);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
    		'Accept: application/json',
		));
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		$result = curl_exec($curl);

		return json_decode($result,1);
	}

	public function GetDeviceById($id)
	{
		$data=array('_id'=>$id);
		$action = 'devices/?query='.json_encode($data, JSON_HEX_QUOT);
		$result = $this->GET($action);

		return $result;
	}

	public function GetDeviceBySerial($serial)
	{
		$data=array('_deviceId._SerialNumber'=>$serial);
		$action = 'devices/?query='.json_encode($data, JSON_HEX_QUOT);

		return $this->GET($action);
	}

	public function DELETE($action)
	{
		if(!$this->url)
			return;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_URL, $this->url.$action);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
    		'Accept: application/json',
		));
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);

		$result = curl_exec($curl);

		return json_decode($result,1);
	}

	public function PUT($action,$data)
	{
		if(!$this->url)
			return;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_URL, $this->url.$action);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
    		'Accept: application/json',
		));
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		$result = curl_exec($curl);

		return json_decode($result,1);
	}

	public function POST($action,$data)
	{
		if(!$this->url)
			return;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_URL, $this->url.$action);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
    		'Accept: application/json',
		));
		curl_setopt($curl, CURLOPT_POST, 1);

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec($curl);

		return json_decode($result,1);
	}

	public function RefreshObject($id,$param)
	{
		$action='devices/'.$id.'/tasks?connection_request';

		$array=array('name' => 'refreshObject',
						'objectName' =>$param
					);

		$data=json_encode($array);

		$result = $this->POST($action,$data);

		return $result;
	}

	public function AddObject($data,$parameters)
	{
		if($parameters)foreach($parameters as $key => $parameter)
		{
			$param_array[$key][]=$parameter['parameter'];
			$param_array[$key][]=$parameter['value'];
			$param_array[$key][]=$parameter['type'];
		}

		$action='devices/'.$data['deviceid'].'/tasks?connection_request';

		$array=array('name' => 'addObject',
						'objectName' =>
								$param_array
					);
	}

	public function SetParameter($data,$parameters)
	{
		if($parameters)foreach($parameters as $key => $parameter)
		{
			$param_array[$key][]=$parameter['parameter'];
			$param_array[$key][]=$parameter['value'];
			$param_array[$key][]=$parameter['type'];
		}

		$action='devices/'.$data['deviceid'].'/tasks?connection_request';

		$array=array('name' => 'setParameterValues',
						'parameterValues' =>
								$param_array
					);
		$data=json_encode($array);

		$result = $this->POST($action,$data);

		return $result;
	}

	public function GetParameter($data,$parameter)
	{
		$action='devices/'.$data['deviceid'].'/tasks?connection_request';

		$array=array('name' => 'getParameterValues',
						'parameterNames' =>
								$parameter
					);

		$data=json_encode($array);

		$result = $this->POST($action,$data);
	}

	public function AddTag($id, $tag)
	{
		$action='devices/'.$id.'/tags/'.$tag;

		$result = $this->POST($action,$data);
		
		return;
	}

	public function DelTag($id, $tag)
	{
		$action='devices/'.$id.'/tags/'.$tag;
		$result = $this->DELETE($action);
		
		return;
	}

	public function GetFiles()
	{
		$action = 'files/';

		return $this->GET($action);
	}

	public function PutFile($file, $data)
	{
		$action = 'files/'.$data['filename'];

		if(!$this->url)
			return;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_URL, $this->url.$action);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'fileType: '.$data['type'],
    		'oui: '.$data['oui'],
    		'productClass: '.$data['productClass'],
    		'version: '.$data['version'],
			'Content-Description: File Transfer',
			'Content-Transfer-Encoding: binary'
		));

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS,file_get_contents($file));
		$result = curl_exec($curl);

		return json_decode($result,1);
	}

	public function DownloadFile($id,$filename)
	{

		$action='devices/'.$id.'/tasks?connection_request';

		$array=array('name' => 'download',
						'file' =>$filename
					);

		$data=json_encode($array);

		$result = $this->POST($action,$data);

		return $result;
	}
}

?>
