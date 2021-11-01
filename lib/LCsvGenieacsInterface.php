<?php

interface LCsvGenieacsInterface
{
    public function existsCPE($name);

    public function createCPE($name, $key, $value);

    public function updateCPE($name, $key, $value);

    public function renameCPE($serial, $uid);

    public function getActionTasks($id);

    public function getHostByToken($token);
}

?>
