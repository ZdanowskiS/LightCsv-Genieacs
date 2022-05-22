<?php

$cache=new LCsvCache();

$cache->clean();
header('Location: ?m=welcome');

?>
