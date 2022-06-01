<?php

class Hooks {

	private $server = array();
    private $cpe =array();
    private $cpefunctions = array();

    public function __construct(){

        $classeslist=get_declared_classes();

        if($classeslist)foreach($classeslist as $class)
        {
            if(method_exists($class,"addServer"))
            {
                $obj = new $class;
                $data=$obj->addServer();

                $this->reginsterServer($data);
            }

            if(method_exists($class,'addCPE'))
            {
                $obj = new $class;
                $data=$obj->addCPE();

                $this->reginsterCPE($data);

                if(method_exists($class,'addCPEFunctions'))
                {
                    $function=$obj->addCPEFunctions();
                    $this->reginsterCPEfunctions($obj->name, $function);
                }
            } 
        }
	}

    public function reginsterServer($data)
    {
        $name=$data['name'];
        $this->server[$name][]= $data;
    }

    public function execute($hook, $data)
    {
        foreach($this->server[$hook] as $k => $elem)   
        {
            $obj= new $elem['class']();
            $fname=$elem['function'];
            return $obj->$fname($data);
        }
    }

    public function existsServer($name)
    {
        if(array_key_exists($name,$this->server))
            return true;
        else
            return false;
    }

    public function reginsterCPE($name)
    {
        $this->cpe[$name]= TRUE;
    }  

    public function reginsterCPEfunctions($name, $data)
    {
        $this->cpefunctions[$name]=$data;
    }  

    public function getCPEfunctions($name)
    {
        return $this->cpefunctions[$name];
    }

    public function existsCPE($name)
    {
        if(array_key_exists($name,$this->cpe))
            return true;
        else
            return false;
    }
}

?>
