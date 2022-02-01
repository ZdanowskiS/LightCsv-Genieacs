<?php

class Hooks {

	private $server = array();

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
}

?>