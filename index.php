<?php
function parseURI()
{
    $uri=ltrim($_SERVER['REQUEST_URI'],'/');
    $tmp=explode('/',$uri);
    if($tmp[0]=='genieacs')
    {
        $result['type']='genieacs';
    }
    elseif($tmp[0]=='gui')
    {
        $result['type']='gui';
    }
    else{
        $result['type']='';
    }

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

require_once "./lib/LCsvGenieacsInterface.php";

require_once "./lib/LCsvGenieacs.class.php";
require_once "./lib/LCsvGenieacsApi.class.php";
require_once "./lib/LCPE.class.php";
require_once "./lib/LCsvGenieacsServer.class.php";
require_once "./lib/LCsvGuiServer.class.php";

$route=parseURI();

if($route['type']=='genieacs')
{
    $GENIESERVER = new LCsvGenieacsServer();

    $result = $GENIESERVER->execute($_SERVER['REQUEST_METHOD'], $route['type'], $route['uri'], $_SERVER['HTTP_AUTHORIZATION'], $route['id']);

    echo $result;
}
elseif($route['type']=='gui')
{
    $GENIESERVER = new LCsvGuiServer();

    $result = $GENIESERVER->execute($_SERVER['REQUEST_METHOD'], $route['type'], $route['uri'], $_SERVER['HTTP_AUTHORIZATION'], $route['id']);

    echo $result;
}
else
{
    if($_FILES)
    {
        $target=$CONFIG['general']['cpedir'].$_FILES['confFile']['name'];
        if(!move_uploaded_file($_FILES['confFile']['tmp_name'], $target))
        {
            echo "Could't upload file. Check privileges.";
            die();
        }
    }
    elseif(array_key_exists('cpeid',$_GET))
    {
        $id=str_replace('.csv','',$_GET['cpeid']);
        $LCPE = new LCPE($id);
        $LCPE->ExecuteAction();
    }
    elseif(array_key_exists('remove',$_GET))
    {
        if(!unlink($CONFIG['general']['cpedir'].$_GET['remove']))
        {
            echo "Could't remove file. Check privileges.";
            die();
        }
    }

    if($CONFIG['general']['cpedir'])
    {
        echo "<h2>Genieacs CSV data source</h2><BR>";
        echo "Upload file:";
        echo '<form action="" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="confFile" id="fileToUpload">
  <input type="submit" value="Upload file" name="submit">
</form>';
        $files = scandir($CONFIG['general']['cpedir']);

        if($files)foreach($files as $file)
        {
            if($file!='.' && $file!='..')
                print '<a href="?cpeid='.$file.'">'.$file.'</a>'.' <a href="?remove='.$file.'">X</a>'."<BR>";
        }
    }
    else
    {
        echo "Set configuration!";
    }
}
?>
