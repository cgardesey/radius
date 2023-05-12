<?php


// Syntax:
// $row['TABLE']['FIELD'] = 'mysql';

switch($configValues['FREERADIUS_VERSION']) {
	case '1' :
		$row['postauth']['user'] = 'user';
		$row['postauth']['date'] = 'date';
		break;
	case '2' :
		// down
	case '3' :
		// down
	default  :
		$row['postauth']['user'] = 'username';
		$row['postauth']['date'] = 'authdate';
		break;
}
