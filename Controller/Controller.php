<?php

function getDroit($A,$M,$S){
		$droit=0;
	if(isset($A)){
		$droit+=1;
	}
	if(isset($M)){
		$droit+=2;
	}
	if(isset($S)){
		$droit+=4;
	}
	return $droit;
}

?>