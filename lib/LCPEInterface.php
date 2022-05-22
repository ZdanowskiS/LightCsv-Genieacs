<?php

interface LCPEInterface
{
    public function init($deviceid);

    public function AddTag($tag);

    public function DelTag($tag);

    public function DelDevice();

    public function RefreshObject($param);

    public function GetParameter($param);

    public function GetDeviceById();

    public function GetFaults();

    public function Download($filename);

    public function Reboot();

    public function FactoryReset();

    public function SetParameter($param);

    public function ExecuteTask($task);

    public function ExecuteAction();

    public function GetDeviceSummary();
}

?>
