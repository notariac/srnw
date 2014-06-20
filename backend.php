<?php 

function clear_tags($text)
{
   $text = str_replace("{", "", $text);
   $text = str_replace("}", "", $text);
   $text = str_replace("\par", "", $text);
   $text = str_replace("\b", "", $text);
   $text = str_replace("\qj", "", $text);
   $text = str_replace("\q", "", $text);
   $text = str_replace("\ ", "", $text);
   return trim($text);
}

$html .= "{\\qj PLACA DE RODAJE ".utf8_decode(N°).": \b MX-02088.} \\par";

?>
<pre>
	<?php echo $html; ?>
</pre>
<?php 
	$html = clear_tags($html);
?>
<pre>
	<?php echo $html; ?>
</pre>