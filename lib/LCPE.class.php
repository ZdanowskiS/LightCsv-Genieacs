<?php

class LCPE extends LCsvGenieacs implements LCPEInterface{

    protected $deviceid;
    protected $node;

    public $connection;

	public function __construct(&$connection,$deviceid=0) {

		$this->connection= &$connection;

        if($deviceid)
            $this->init($deviceid);
    }

    public function init($deviceid)
    {
        $this->deviceid=$deviceid;

        return;
    }

    public function AddTag($tag)
    {
        $this->connection->AddTag($this->deviceid, $tag); 
    }

    public function DelTag($tag)
    {
        $this->connection->DelTag($this->deviceid, $tag);
    }

    public function DelDevice()
    {
        $this->connection->DelDevice($this->deviceid);
    }

    public function RefreshObject($param)
    {
        $this->connection->RefreshObject($this->deviceid, $param);
    }

    public function GetParameter($param)
    {
        $this->connection->GetParameter($this->deviceid, $param);
    }

    public function GetDeviceById()
    {
       $result = $this->connection->GetDeviceById($this->deviceid);

       return $result;
    }

    public function test()
    {
        return$this->connection->GetDeviceById($this->deviceid);
        return 'test';
    }

    public function GetFaults()
    {
		return $this->connection->GetFaults($this->deviceid);
    }

    public function Download($filename)
    {
        return $this->connection->Download($this->deviceid, $filename);
    }

    public function Reboot()
    {
        return $this->connection->Reboot($this->deviceid);
    }

    public function FactoryReset()
    {
        return $this->connection->FactoryReset($this->deviceid);
    }

    public function SetParameter($param)
    {
        $parameters[0]['parameter']=$param['param'];
        $parameters[0]['value']=$param['value'];
        $parameters[0]['type']='xsd:string';

        return $this->connection->SetParameter($this->deviceid,$parameters);
    }

    public function ExecuteTask($task)
    {
        if(array_key_exists('name', $task) && $task['name']=="addTag"){
            $result = $this->AddTag($task['param']);
        }
        elseif(array_key_exists('name', $task) && $task['name']=="getParameterValues")
        {
            $result = $this->GetParameter($task['param']);
        }
        else{
            $result=$this->SetParameter($task);
        }
        return $result;
    }

    public function ExecuteAction()
    {
        $task_array = $this->getActionTasks($this->deviceid);

		if($task_array)foreach($task_array as $task)
		{
			$result=$this->ExecuteTask($task);
		}

        return;
    }

    public function ConvertTime($time)
    {
        $dt = new DateTime();
        $tz = $dt->getTimezone();
        $date = new DateTime($time);
        $date->setTimezone(new DateTimeZone($tz->getName()));
        return $date->format('Y-m-d H:i:s');
    }

    public function GetDeviceSummary($cpe=null)
    {
        if(is_null($cpe))
            $cpe =$this->GetDeviceById();

        $result=array('id' => $cpe[0]['_id'],
                    'manufacturer' => $cpe[0]['_deviceId']['_Manufacturer'],
                    'productclass' => $cpe[0]['_deviceId']['_ProductClass'],
                    'serialnumber' => $cpe[0]['_deviceId']['_SerialNumber'],
                    'softwareversion' => $cpe[0]['InternetGatewayDevice']['DeviceInfo']['SoftwareVersion']['_value'],
                    'lastboot' => $cpe[0]['_lastBoot'],
                    'locallastboot' => $this->ConvertTime($cpe[0]['_lastBoot']),
                    'lastinform' => $cpe[0]['_lastInform'],
                    'locallastinform' => $this->ConvertTime($cpe[0]['_lastInform']),
                    'registered' => $cpe[0]['_registered'],
                    'localregistered' => $this->ConvertTime($cpe[0]['_registered']),
                    'reboottime' => $cpe[0]['Reboot']['_value'],
                    'localreboottime' => $this->ConvertTime($cpe[0]['Reboot']['_value']),
                    'tags' => $cpe[0]['_tags'],
                    'cpe' => $cpe);

        return $result;
    }
}
?>
