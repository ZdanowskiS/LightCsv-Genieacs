<?php

interface LCsvGenieacsApiInterface
{

    public function setURL($url);

    public function isJSON($data);

    public function GET($action);

    public function GetAllDevices();

    public function GetDeviceById($id);

    public function GetDeviceBySerial($serial);

    public function GetFaults($id);

	public function GetFiles();

    public function DELETE($action);

    public function DelTag($id, $tag);

    public function DelDevice($id);

    public function PUT($action, $data);

    public function POST($action, $data);

    public function RefreshObject($id, $param);

    public function AddObject($data,$param);

    public function SetParameter($data,$parameters);

    public function GetParameter($data,$parameter);

	public function AddTag($id, $tag);

    public function Reboot($id);

    public function FactoryReset($id);

    public function Download($id,$filename);

	public function PutFile($file, $data);
}

?>
