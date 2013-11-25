<?php

session_start();
if (!empty($_FILES)) 
{
    $tempFile = $_FILES['Filedata']['tmp_name'];                          // 1
    $fileparts = pathinfo($_FILES['Filedata']['name']);
    $ext = $fileparts['extension'];

    //$targetPath = 'doc/';  
    $targetPath = 'minutas/';  
    $filetypes = array("rtf");
    $flag = false;
    foreach($filetypes as $typ)
    {
    	if($typ==strtolower($ext))
    	{
    		$flag = true;
    	}
    }    
    if($flag)
    {
        $targetFile =  str_replace('//','/',$targetPath).str_replace(' ','_',$_FILES['Filedata']['name']);
        $name = str_replace(' ','_',$_FILES['Filedata']['name']);
        if( move_uploaded_file($tempFile,$targetFile))
        {	
            echo "1###".$name;
            chmod($targetFile, 0777);
        }
        else
        {
            echo "0###Error";
        }
    }
    else 
    {
    	echo "0###Extension no apcetada";
    }    
         
}
 

?>   