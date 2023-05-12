<?php



if ((isset($failureMsg)) && ($failureMsg != "")) {
	echo "<div class='failure'>
		$failureMsg
	</div>
	";
}


if ((isset($successMsg)) && ($successMsg != "")) {
	echo "<div class='success'>
		$successMsg
	</div>
	";
}

