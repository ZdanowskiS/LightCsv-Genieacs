<?php

class LCsvGenieacs{

	public $connection;

	public function __construct() { 

		$this->connection= new LCsvGenieacsApi();
	}

    public function renameFile($serial, $uid)
    {
        global $CONFIG;

        if(file_exists($CONFIG['general']['cpedir'].$serial.'.csv'))
        {
            if(file_exists($CONFIG['general']['cpedir'].$uid.'.csv'))
            {
                unlink($CONFIG['general']['cpedir'].$uid.'.csv');
            }
            rename($CONFIG['general']['cpedir'].$serial.'.csv',$CONFIG['general']['cpedir'].$uid.'.csv');
        }
        return;
    }

    public function GetActionTasks($id)
    {
        global $CONFIG;

        if(file_exists($CONFIG['general']['cpedir'].$id.'.csv')){
            $handle=fopen($CONFIG['general']['cpedir'].$id.'.csv','r');
        }else {
            die("File does not exists.");
        }
        while(($data = fgetcsv($handle,1000,";")) !== FALSE)
        {
            $result[]=array('name' => $data[0],
                        'taskname' => $data[1],
                        'typename' => $data[2],
                        'param' => $data[3],
                        'value' => $data[4]);
        }
        fclose($handle);

        return $result;
    }

    public function GetHostByToken($token)
    {
        global $CONFIG;

        if($CONFIG['general']['token']==$token)
            return TRUE;
        else
            return FALSE;

    }
}

?>
