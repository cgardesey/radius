<?php


$dictionaryFile = "dictionary.sample";						// configure dictionary filename

if (file_exists($dictionaryFile) && is_readable($dictionaryFile)) {
	$myDictionary = file($dictionaryFile);					// read dictionary file into local variable


	$myVendor = $dictionaryFile;						// by default we set the vendor name to be the file name
	$myAttribute;								// variables are initialized
	$myType;

	foreach($myDictionary as $line) {
	
		if (preg_match('/^#/', $line))
			continue;						// if a line starts with # then it's a comment, skip it

		if (preg_match('/^\n/', $line))
			continue;						// if a line is empty, we skip it as well

		if (preg_match('/^VALUE/', $line))
			continue;						// if a line starts with VALUE we have no use for it, we skip it

		if (preg_match('/^BEGIN-VENDOR/', $line))
			continue;						// if a line starts with BEGIN-VENDOR we have no use for it, we skip it

		if (preg_match('/^END-VENDOR/', $line))
			continue;						// if a line starts with END-VENDOR we have no use for it, we skip it

		
		if (preg_match('/^VENDOR/', $line)) {				// extract vendor name

			if (preg_match('/\t/', $line))
				list($junk, $vendor) = preg_split('/\t+/', $line);		// check if line is splitted by a sequence of tabs
			
			else if (preg_match('/ /', $line))
				list($junk, $vendor) = preg_split('/[ ]+/', $line);		// check if line is splitted by a sequence of whitespaces

			if ($vendor != "") {
				$myVendor = "'".trim($vendor)."'";
			}
			continue;
		}



		if (preg_match('/^ATTRIBUTE/', $line)) {				// extract attribute name

			if (preg_match('/\t/', $line))
				list($junk, $attribute, $junk2, $type) = preg_split('/\t+/', $line);		// check if line is splitted by 
														// a sequence of tabs
			
			else if (preg_match('/ /', $line))
				list($junk, $attribute, $junk2, $type) = preg_split('/[ ]+/', $line);		// check if line is splitted by 
														//a sequence of whitespaces
			if ($attribute != "")
				$myAttribute = "'".trim($attribute)."'";

			if ($type != "")
				$myType = "'".trim($type)."'";
			else
				$myType = "NULL";

			echo "vendor: $myVendor\tattribute: $myAttribute\ttype: $myType\n";

		}
		
	}
	

} else {
	echo "error: file doesn't exist or is not readable\n";
}


?>
