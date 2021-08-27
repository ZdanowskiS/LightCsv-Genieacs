<?php

class LCsvGenieacsServer extends LCPE {

	private $method;
	private $uri;
	private $token;
	private $id;

    private $hostid;

	public function execute($method, $type, $uri, $token, $id)
	{
		$this->method=$method;
		$this->uri=$uri;
		$this->id=$id;

		$this->token=$token;

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
        $this->hostid=$this->GetHostByToken($this->token);

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

                $this->renameFile($input->{'serial'},$input->{'cpeid'});
                $tasklist = $this->GetActionTasks($input->{'cpeid'});

			    return json_encode($tasklist);
            break;
			default:
				return '400 Bad Request';
		}
	}
}

?>