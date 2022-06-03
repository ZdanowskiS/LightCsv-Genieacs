<?php

class LCsvGenieacs implements LCsvGenieacsInterface {

    public $storage;

	public function __construct(&$storage) { 
        $this->storage = &$storage;
	}

    public function existsCPE($name)
    {
        return $this->storage->existsCPE($name);
    }

    public function saveCPE($name, $data)
    {
        $this->storage->saveCPE($name, $data);
        return;
    }

    public function updateCPE($name, $key, $value)
    {
        $this->storage->updateCPE($name, $key, $value);
    }

    public function getCPEList()
    {
        return $this->storage->getCPEList();
    }

    public function renameCPE($serial, $uid)
    {
        $this->storage->renameCPE($serial, $uid);
        return;
    }

    public function getActionTasks($id)
    {
        $this->storage->getActionTasks($id);
        return $result;
    }

    public function getTemplateList()
    {
        return $this->storage->getTemplateList();
    }
}

?>
