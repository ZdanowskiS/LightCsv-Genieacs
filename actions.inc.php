<?php

    if($_GET['m']=='fileadd' && $_FILES)
    {
        $target=$CONFIG['general']['cpedir'].$_FILES['confFile']['name'];
        if(!move_uploaded_file($_FILES['confFile']['tmp_name'], $target))
        {
            echo "Could't upload file. Check privileges.";
            die();
        }
    }
    elseif($_GET['m']=='cpeconfigure')
    {
        $LCPE = new LCPE($LAPI,$_GET['id']);
        $LCPE->ExecuteAction();
    }
    elseif($_GET['m']=='filedel')
    {
        if(!unlink($CONFIG['general']['cpedir'].$_GET['file']))
        {
            echo "Could't remove file. Check privileges.";
            die();
        }
    }
    elseif($_GET['m']=='reboot')
    {
        $id=$_GET['id'];
        if($id)
        {
            $LCPE = new LCPE($LAPI,$id);
            $LCPE->ExecuteReboot();
        }
        else
        {
            $files = scandir($CONFIG['general']['cpedir']);
            if($files)foreach($files as $file)
            {
                if($file!='.' && $file!='..')
                {
                    $id=str_replace('.csv','',$file);
                    $LCPE = new LCPE($LAPI,$id);
                    $LCPE->ExecuteReboot();
                }
            }
        }
    }
    elseif($_GET['m']=='indexcpe')
    {
        #$LCPE = new LCPE(null);
       # $LCPE->connection->setURL($CONFIG['general']['ip']);
        $LCPE = new LCPE($LAPI);
        $devices = $LCPE->connection->GetAllDevices();

        if($devices)foreach($devices as $k => $device)
        {
            $target=$CONFIG['general']['cpedir'].$device['_id'].'.csv';
            if (!file_exists($filename)) {
                file_put_contents($target,'');
            }
        }
    }
    elseif($_GET['m']=='getsmarty')
    {
        $url='https://github.com/smarty-php/smarty/archive/refs/tags/v4.1.0.zip';
        if(!file_exists('smarty.zip'))
        {
            file_put_contents('smarty.zip', file_get_contents($url));
        }
        $zip = new ZipArchive;

        if ($zip->open('smarty.zip') === TRUE) {
            $name = trim($zip->getNameIndex(0), '/');
            $zip->extractTo('.');
            $zip->close();
            rename($name,'smarty');

            header('Location: ?m=welcome');
        }
    }
    

    if($CONFIG['general']['cpedir'])
    {
        echo '<h2>Light CSV</h2>';
        echo '<p>For full functionality install <a href="https://www.smarty.net/">Smarty.</a></p>';

        if(is_writable(__DIR__))
            echo '<a href="?m=getsmarty">Install Smarty from GitHub</a>';
        else
            echo 'Please make '.__DIR__.' writable or install Smarty manually';

        echo '<h3>Upload file:</h3>';
        echo '<form action="?m=fileadd" method="post" enctype="multipart/form-data">
  Select file:
  <input type="file" name="confFile" id="fileToUpload">
  <input type="submit" value="Upload file" name="submit">
</form>';
        echo '<a href="?m=indexcpe" title="Create empty files for all CPE">Index All CPE</a>';

        $files = scandir($CONFIG['general']['cpedir']);
        echo '<TABLE border="1">';
        echo '<TR>';
        echo '<TH>CPE id</TH><TH><a href="?m=reboot">Reboot</a></TH><TH>Run</TH><TH>Delete</TH>';
        echo '</TR>';
        if($files)foreach($files as $file)
        {
            if($file!='.' && $file!='..')
            {
                $id=str_replace('.csv','',$file);
                $LCPE = new LCPE($LAPI,$id);
                $res=$LCPE->GetDeviceById();
                echo '<TR>';
                    echo '<TD>';
                      if(filesize($CONFIG['general']['cpedir'].$file)!=0)
                        print '<a href="?m=configurecpe&id='.urlencode($id).'" title="Run configuration form file">'.$file.'</a>';
                      else
                        print $file;

                    echo '</TD>';
                    echo '<TD><a href="?m=reboot&id='.$id.'" title="Reboot CPE"> '.$res[0]['Reboot']['_value'].' Reboot</a></TD>';
                    echo '<TD>';
                    echo (filesize($CONFIG['general']['cpedir'].$file) ? '<a href="?m=configurecpe&id='.urlencode($id).'" title="Run configuration form file">-></a>' : '');
                    echo '</TD>';
                    echo '<TD><a href="?m=filedel&file='.urlencode($file).'" title="Delete file">X</a></TD>';
                echo '</TR>';
            }
        }
        echo '</TABLE>';
    }
    else
    {
        echo "Set configuration!";
    }

?>
