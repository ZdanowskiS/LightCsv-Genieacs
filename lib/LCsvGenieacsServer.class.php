<?php

class LCsvGenieacsServer extends LCsvGenieacs {

	private $method;
	private $uri;
	private $token;
	private $id;

    private $hostid;

    public static function addServer()
    {
        $result=array('class' => 'LCsvGenieacsServer',
                        'function' => 'execute',
                        'name' => 'genieacs');
        return $result;
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

                $this->renameCPE($input->{'serial'},$input->{'cpeid'});
                $tasklist = $this->GetActionTasks($input->{'cpeid'});

			    return json_encode($tasklist);
            break;
			default:
				return '400 Bad Request';
		}
	}
}

?>
