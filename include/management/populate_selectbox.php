<?php





function populate_payment_type_id($defaultOption = "Select Payment Type", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "") {

        echo "<select onChange=\"javascript:setStringText(this.id,'populate_payment_type_id')\" id='populate_payment_type_id' $mode
                        name='$elementName' class='$cssClass' />
                        <option value='$defaultOptionValue'>$defaultOption</option>
                        <option value=''></option>";

        include 'library/opendb.php';

        $sql = "(SELECT id, value FROM ".$configValues['CONFIG_DB_TBL_DALOPAYMENTTYPES'].")";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
                echo "
                        <option value='".$row['id']."'>".$row['value']."</option>
                        ";

        }

        include 'library/closedb.php';

        echo "</select>";
}




function populate_customer_id($defaultOption = "Select Customer", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'customer_id')\" id='customer_id' $mode
			name='$elementName' class='$cssClass' />
			<option value='$defaultOptionValue'>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        $sql = "(SELECT id, value FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGINVOICESTATUS'].")";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
                echo "  
                        <option value='".$row['id']."'>".$row['value']."</option>
                        ";

        }

        include 'library/closedb.php';

	echo "</select>";
}





function populate_invoice_status_id($defaultOption = "Select Status", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'invoice_status_id')\" id='invoice_status_id' $mode
			name='$elementName' class='$cssClass' />
			<option value='$defaultOptionValue'>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        $sql = "(SELECT id, value FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGINVOICESTATUS'].")";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
                echo "  
                        <option value='".$row['id']."'>".$row['value']."</option>
                        ";

        }

        include 'library/closedb.php';

	echo "</select>";
}







function populate_invoice_type_id($defaultOption = "Select Status", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'populate_invoice_type_id')\" id='populate_invoice_type_id' $mode
			name='$elementName' class='$cssClass' />
			<option value='$defaultOptionValue'>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        $sql = "(SELECT id, value FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGINVOICETYPE'].")";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
                echo "  
                        <option value='".$row['id']."'>".$row['value']."</option>
                        ";

        }

        include 'library/closedb.php';

	echo "</select>";
}






function populate_hotspots($defaultOption = "Select Hotspot", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'hotspot')\" id='hotspot' $mode
			name='$elementName' class='$cssClass' />
			<option value='$defaultOptionValue'>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        // Grabing the group lists from usergroup table

        $sql = "(SELECT distinct(id), name FROM ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS'].")";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)) {
                echo "  
                        <option value='".$row['id']."'>".$row['name']."</option>
                        ";

        }

        include 'library/closedb.php';

	echo "</select>";
}


function populate_plans($defaultOption = "Select Plan", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "", $valueIsId = false) {

	echo "<select $mode name='$elementName' class='$cssClass' tabindex=105 />".
			"<option value='$defaultOptionValue'>$defaultOption</option>".
			"<option value=''></option>";

        include 'library/opendb.php';

        // Grabing the group lists from usergroup table

		$sql = "SELECT distinct(planName), id FROM ".$configValues['CONFIG_DB_TBL_DALOBILLINGPLANS']." WHERE planActive = 'yes' ORDER BY planName ASC;";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow()) {
			

        	if ($valueIsId === true)
        		$value = $row[1];
        	else
        		$value = $row[0];
        		        	
            echo "<option value='$value'> $row[0] </option> ";

        }

		echo "</select>";

        include 'library/closedb.php';
}



function populate_groups($defaultOption = "Select Group", $elementName = "", $cssClass = "form", $mode = "", $defaultOptionValue = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'usergroup')\" id='usergroup' $mode
			name='$elementName' class='$cssClass' tabindex=105 />
			<option value='$defaultOptionValue'>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        // Grabing the group lists from usergroup table

        $sql = "(SELECT distinct(GroupName) FROM ".$configValues['CONFIG_DB_TBL_RADGROUPREPLY'].")".
			"UNION (SELECT distinct(GroupName) FROM ".$configValues['CONFIG_DB_TBL_RADGROUPCHECK'].");";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow()) {
                echo "  
                        <option value='$row[0]'> $row[0] </option>
                        ";

        }

        include 'library/closedb.php';

	echo "</select>";
}






function populate_vendors($defaultOption = "Select Vendor",$elementName = "", $cssClass = "form", $mode = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'group')\" id='usergroup' $mode
			name='$elementName' class='$cssClass' tabindex=105 />
			<option value=''>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        // Grabing the group lists from usergroup table

        $sql = "SELECT distinct(Vendor) as Vendor FROM ".$configValues['CONFIG_DB_TBL_DALODICTIONARY']." ORDER BY Vendor ASC;";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow()) {
                echo "  
                        <option value='$row[0]'> $row[0] </option>
                        ";

        }

	echo "</select>";

        include 'library/closedb.php';
}






function populate_realms($defaultOption = "Select Realm",$elementName = "", $cssClass = "form", $mode = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'realm')\" id='realmlist' $mode
			name='$elementName' class='$cssClass' tabindex=105 />
			<option value=''>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        // Grabing the group lists from usergroup table

	$configValues['CONFIG_DB_TBL_DALOREALMS'] = "realms";

        $sql = "SELECT distinct(RealmName) as Realm FROM ".$configValues['CONFIG_DB_TBL_DALOREALMS']." ORDER BY Realm ASC;";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow()) {
                echo "  
                        <option value='$row[0]'> $row[0] </option>
                        ";

        }

	echo "</select>";

        include 'library/closedb.php';

}








function populate_proxys($defaultOption = "Select Proxy",$elementName = "", $cssClass = "form", $mode = "") {

	echo "<select onChange=\"javascript:setStringText(this.id,'proxy')\" id='proxylist' $mode
			name='$elementName' class='$cssClass' tabindex=105 />
			<option value=''>$defaultOption</option>
			<option value=''></option>";

        include 'library/opendb.php';

        // Grabing the group lists from usergroup table

	$configValues['CONFIG_DB_TBL_DALOPROXYS'] = "proxys";

        $sql = "SELECT distinct(ProxyName) as Proxy FROM ".$configValues['CONFIG_DB_TBL_DALOPROXYS']." ORDER BY Proxy ASC;";
        $res = $dbSocket->query($sql);

        while($row = $res->fetchRow()) {
                echo "  
                        <option value='$row[0]'> $row[0] </option>
                        ";

        }

	echo "</select>";

        include 'library/closedb.php';

}


function populate_password_types($elementName = "", $cssClass = "form", $mode = "") {

	echo "<select $mode
			name='$elementName' class='$cssClass' tabindex=105 />
			<option value='Cleartext-Password'>Cleartext-Password</option>
			<option value='User-Password'>User-Password</option>
			<option value='Crypt-Password'>Crypt-Password</option>
			<option value='MD5-Password'>MD5-Password</option>
			<option value='SHA1-Password'>SHA1-Password</option>
			<option value='CHAP-Password'>CHAP-Password</option>
			</select>";
}







function drawTables() {

	echo "
		<option value='check'>check</option>
		<option value='reply'>reply</option>
	";
}







function drawOptions() {

	echo "
                <option value='='>=</option>
                <option value=':='>:=</option>
                <option value='=='>==</option>
                <option value='+='>+=</option>
                <option value='!='>!=</option>
                <option value='>'>></option>
                <option value='>='>>=</option>
                <option value='<'><</option>
                <option value='<='><=</option>
                <option value='=~'>=~</option>
                <option value='!~'>!~</option>
                <option value='=*'>=*</option>
                <option value='!*'>!*</option>

        ";
}






function drawTypes() {

	echo "
                <option value='string'>string</option>
                <option value='integer'>integer</option>
                <option value='ipaddr'>ipaddr</option>
                <option value='date'>date</option>
                <option value='octets'>octets</option>
                <option value='ipv6addr'>ipv6addr</option>
                <option value='ifid'>ifid</option>
                <option value='abinary'>abinary</option>
        ";
}



function drawRecommendedHelper() {

	echo "
                <option value='date'>date</option>
                <option value='datetime'>datetime</option>
                <option value='authtype'>authtype</option>
                <option value='framedprotocol'>framedprotocol</option>
                <option value='servicetype'>servicetype</option>
				<option value='kbitspersecond'>kbitspersecond</option>
                <option value='bitspersecond'>bitspersecond</option>
                <option value='volumebytes'>volumebytes</option>
                <option value='mikrotikRateLimit'>mikrotikRateLimit</option>
        ";
}





?>
