<?php

session_start();
if (!empty($_FILES)) 
{
    $tempFile = $_FILES['Filedata']['tmp_name'];            
    $fileparts = pathinfo($_FILES['Filedata']['name']);
    $ext = $fileparts['extension'];
    
    //$targetPath = 'doc/';  
    $targetPath = 'minutas/'.$_SESSION['notaria'].'/';
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
        $targetFile =  str_replace('//','/',$targetPath).str_replace(' ','_',"Minuta-".$_POST['correlativo'].".rtf");
        $name = "Minuta-".$_POST['correlativo'].".rtf";
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
    	echo "0###Extension no apcetada, el documento debe ser en formato RTF. (Si tiene problemas, comuniquese con la oficina de Sistemas.)";
    }    
         
}
 

?>   