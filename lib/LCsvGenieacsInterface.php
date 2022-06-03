<?php

interface LCsvGenieacsInterface
{
    public function existsCPE($name);

    public function updateCPE($name, $key, $value);

    public function renameCPE($serial, $uid);

    public function getActionTasks($id);

}

?>
