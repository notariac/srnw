<?php 
 $patron = "/^[0-9]{9,9}$/";
 if(preg_match($patron, "44538236"))
 {
 	echo "Si";
 }
 else {
 	echo "No";
 }
?>