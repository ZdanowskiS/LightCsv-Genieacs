<?php
function parseURI()
{
    $uri=ltrim($_SERVER['REQUEST_URI'],'/');
    $tmp=explode('/',$uri);
    if($tmp[0]=='genieacs')
    {
        $result['type']='genieacs';
    }
    $result['uri']=preg_replace('/[^a-z]/', '',$tmp[1]);
    $result['id']=intval($tmp[2]);

    return $result;
}

$CONFIG_FILE ='configuration.ini';
define('CONFIG_FILE', $CONFIG_FILE);

$CONFIG = (array) parse_ini_file(CONFIG_FILE, true);

require_once "./lib/LCsvGenieacsApi.class.php";
require_once "./lib/LCsvGenieacs.class.php";
require_once "./lib/LCPE.class.php";
require_once "./lib/LCsvGenieacsServer.class.php";

$route=parseURI();

if($route['type']=='genieacs')
{
    $GENIESERVER = new LCsvGenieacsServer();

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
    elseif($_GET['cpeid'])
    {
        $id=str_replace('.csv','',$_GET['cpeid']);
        $LCPE = new LCPE($id);
        $LCPE->ExecuteAction();
    }
    elseif($_GET['remove'])
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
