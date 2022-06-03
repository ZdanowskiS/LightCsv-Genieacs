<?php

class LCsvGenieacsServer {

	private $method;
	private $uri;
	private $token;
	private $id;

    public $storage;
    
	public function __construct() { 
    global $CONFIG;

        if(array_key_exists('storageclass',$CONFIG['general']))
            $storagename=$CONFIG['general']['storageclass'];
        else
            $storagename= 'LCsvStorageFile';

        $this->storage= new $storagename();
	}

    public static function addServer()
    {
        $result=array('class' => 'LCsvGenieacsServer',
                        'function' => 'execute',
                        'name' => 'genieacs');
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

	public function execute($data)
	{
		$this->method=$data['method'];
		$this->uri=$data['uri'];
		$this->id=$data['id'];

		$this->token=$data['token'];

		if($this->token)
		{
			$result =$this->CheckToken();

			if(!$result){
				return '401 Unauthorized';
			}
		}
		elseif(($this->method!='POST' || $this->uri!='authenticate'))
		{
			return '401 Unauthorized';
		}

		switch($this->method) {
			 case 'GET':
                 return $this->GET();
              break;
			 case 'POST':
                 return $this->POST();
              break;
			 case 'PUT':
                 return $this->PUT();
              break;
			default:
				return '400 Bad Request';
		}
	}

	public function CheckToken()
	{
        $result=FALSE;
        $this->hostid=$this->getHostByToken($this->token);

        if($this->hostid)
            $result=TRUE;

        return $result;
    }

	public function PUT()
	{
        $input=json_decode(file_get_contents('php://input'));

		switch($this->uri) {
			default:
				return '400 Bad Request';
		}
	}

	public function POST()
	{
		switch($this->uri) {
			default:
				return '400 Bad Request';
		}
	}

	public function GET()
	{
        $input=json_decode(file_get_contents('php://input'));

		switch($this->uri) {
			case 'actionnodeadd':

                $this->storage->renameCPE($input->{'serial'},$input->{'cpeid'});
                $tasklist = $this->storage->GetActionTasks($input->{'cpeid'});

			    return json_encode($tasklist);
            break;
			default:
				return '400 Bad Request';
		}
	}
}

?>
