<?php

class LCPE extends LCsvGenieacs {

    private $deviceid;
    private $node;

    public $connection;

	public function __construct($id=0) {

		$this->connection= new LCsvGenieacsApi();

        if($id)
            $this->init($id);
    }

    public function init($id)
    {
        global $CONFIG;

        $this->deviceid=$id;

        $this->connection->setURL($CONFIG['general']['ip']);

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

    public function RefreshObject($param)
    {
        $this->connection->RefreshObject($this->deviceid, $param);
    }

    public function GetParameter($param)
    {
        $this->connection->GetParameter(array('deviceid' => $this->deviceid), $param);
    }

    public function GetDeviceById()
    {
        return $this->connection->GetDeviceById($this->deviceid);
    }

    public function Download($filename)
    {
        return $this->connection->Download($this->deviceid, $filename);
    }

    public function Reboot()
    {
        return $this->connection->Reboot($this->deviceid);
    }

    public function factoryReset()
    {
        return $this->connection->factoryReset($this->deviceid);
    }

    public function ExecuteTask($task)
    {
        if($task['name']=="addTag"){
            $result = $this->AddTag($task['param']);
        }
        elseif($task['name']=="getParameterValues")
        {
            $result = $this->GetParameter($task['param']);
        }
        else{
				$param_array[0][]=$task['param'];
				$param_array[0][]=$task['value'];
				$param_array[0][]='xsd:string';

				$array=array('name' => 'setParameterValues',
							'parameterValues' =>$param_array
						);

		        $uri='devices/'.$this->deviceid.'/tasks?connection_request';

				$result = $this->connection->POST($uri,json_encode($array));

				$param_array=array();
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
}
?>
