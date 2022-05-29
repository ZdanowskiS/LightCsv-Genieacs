<?php

function parseURI()
{
    $uri=ltrim($_SERVER['REQUEST_URI'],'/');
    $tmp=explode('/',$uri);

    $result['type']=$tmp[0];

    if(array_key_exists(1,$tmp))
        $result['uri']=preg_replace('/[^a-z]/', '',$tmp[1]);
    else
        $result['uri']='';

    if(array_key_exists(2,$tmp))
        $result['id']=$tmp[2];
    else
        $result['id']='';

    return $result;
}

$CONFIG_FILE ='configuration.ini';
define('CONFIG_FILE', $CONFIG_FILE);

$CONFIG = (array) parse_ini_file(CONFIG_FILE, true);
require_once "./lib/Hook.class.php";

require_once "./lib/LCsvGenieacsApiInterface.php";
require_once "./lib/LCsvGenieacsInterface.php";
require_once "./lib/LCPEInterface.php";

require_once "./lib/LCsvGenieacs.class.php";
require_once "./lib/LCsvCache.class.php";
require_once "./lib/LCsvGenieacsApi.class.php";
require_once "./lib/LCPE.class.php";
require_once "./lib/BaseCPE.class.php";

require_once "./lib/loadServers.php";
require_once "./lib/loadCPE.php";

$hooks=new Hooks();

$route=parseURI();

if($hooks->existsServer($route['type']))
{
    $data['method']=$_SERVER['REQUEST_METHOD'];
    $data['uri']=$route['uri'];
    $data['id']=$route['id'];
    $data['token']=$_SERVER['HTTP_AUTHORIZATION'];
    print $hooks->execute($route['type'],$data);
}
else
{
    if($CONFIG['cache']['enable'])
        $cache=new LCsvCache();

    $LAPI=new LCsvGenieacsApi($CONFIG['general']['ip'],$cache);
    if(file_exists('smarty/libs/Smarty.class.php'))
    {
        require 'smarty/libs/Smarty.class.php';
        $smarty = new Smarty;

        if(!array_key_exists('m', $_GET))
            $module='welcome';
        else
            $module=$_GET['m'];

        $smarty->assign('cache_enable', $CONFIG['cache']['enable']);
        $smarty->assign('title', 'Light CSV');

        $file='modules/'. $module . '.php';
        if (file_exists($file)) {
            include $file;
        }
        else {
            echo "No souch page.";
            exit;
        }
    }
    else
        include 'actions.inc.php';
}
?>
