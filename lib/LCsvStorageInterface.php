<?php

interface LCsvStorageInterface
{
    public function saveCPE($name,$lines);

    public function updateCPE($name, $key, $value);

    public function existsCPE($name);

    public function getCPEList();

    public function deleteCPE($id);

    public function renameCPE($serial, $uid);

    public function getActionTasks($id);

    public function existsTemplate($name);

    public function getTemplateList();

    public function getTemplateByLine($name);

    public function deleteTemplate($id);
}

?>
