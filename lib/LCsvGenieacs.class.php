<?php

class LCsvGenieacs implements LCsvGenieacsInterface {

	public function __construct() { 
	}

    private function writeFile($name,$lines)
    {
        global $CONFIG;

        $fout = fopen($CONFIG['general']['cpedir'].$name.'.csv', 'w');
        foreach ($lines as $line) {
            fputcsv($fout, $line,';');
        }
        fclose($fout);

        return;
    }

    public function existsCPE($name)
    {
        global $CONFIG;

        if(file_exists($CONFIG['general']['cpedir'].$name.'.csv'))
            return TRUE;
        else
            return FALSE;
    }

    public function createCPE($name, $key, $value)
    {
        $lines[]=array("setdata","setParameterValues","parameterNames",$key,$value);
        $this->writeFile($name,$lines);

        return;
    }

    public function updateCPE($name, $key, $value)
    {
        global $CONFIG;

        $handle=fopen($CONFIG['general']['cpedir'].$name.'.csv','r');

        $lines;
        $found=FALSE;
        while(($data = fgetcsv($handle,1000,";")) !== FALSE)
        {
            if($data[3]==$key)
            {
                $data[4]=$value;
                $found=TRUE;
            }
            $lines[]=array($data[0],$data[1],$data[2],$data[3],$data[4]);
        }
        fclose($handle);

        if(!$found)
            $lines[]=array("setdata","setParameterValues","parameterNames",$key,$value);

        $this->writeFile($name,$lines);

        return;
    }

    public function renameCPE($serial, $uid)
    {
        global $CONFIG;

        if($this->existsCPE($serial))
        {
            if($this->existsCPE($uid))
            {
                unlink($CONFIG['general']['cpedir'].$uid.'.csv');
            }
            rename($CONFIG['general']['cpedir'].$serial.'.csv',$CONFIG['general']['cpedir'].$uid.'.csv');
        }
        return;
    }

    public function getActionTasks($id)
    {
        global $CONFIG;

        if($this->existsCPE($id)){
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

    public function getHostByToken($token)
    {
        global $CONFIG;

        if($CONFIG['general']['token']==$token)
            return TRUE;
        else
            return FALSE;

    }

    public function getTemplateList()
    {
        global $CONFIG;
        $files = scandir($CONFIG['general']['templatedir']);

        foreach($files as $file)
        {
            if($file!='.' && $file!='..')
            {
                $id=str_replace('.csv','',$file);

                $templates[]=array('id' =>$id,
                    'file' => $file);
            }
        }
        return $templates;
    }
}

?>
