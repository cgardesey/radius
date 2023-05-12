
function changeInteger(dstObj,action) {

	var dstElem = document.getElementById(dstObj);
	var dstElemVal = dstElem.value;
	if (action == 'increment') {
		dstElem.value = parseInt(dstElemVal)+1;
	} else {
		if (dstElemVal < 0)
			exit;
		dstElem.value = parseInt(dstElemVal)-1;
	}

}


function toggleAttributeCustom() {

	// disable the custom attributes
	var elem1 = document.getElementById('dictAttributesCustom');
	var elem2 = document.getElementById('addAttributesCustom');
	elem1.disabled = false;
	elem2.disabled = false;
	
	var elem3 = document.getElementById('dictVendors0');
	var elem4 = document.getElementById('reloadAttributes');
	var elem5 = document.getElementById('dictAttributesDatabase');
	var elem6 = document.getElementById('addAttributesVendor');
	elem3.disabled = true;
	elem4.disabled = true;
	elem5.disabled = true;
	elem6.disabled = true;

}


function toggleAttributeSelectbox() {

	// disable the custom attributes
	var elem1 = document.getElementById('dictAttributesCustom');
	var elem2 = document.getElementById('addAttributesCustom');
	elem1.disabled = true;
	elem2.disabled = true;
	
	var elem3 = document.getElementById('dictVendors0');
	var elem4 = document.getElementById('reloadAttributes');
	var elem5 = document.getElementById('dictAttributesDatabase');
	var elem6 = document.getElementById('addAttributesVendor');
	elem3.disabled = false;
	elem4.disabled = false;
	elem5.disabled = false;
	elem6.disabled = false;
}



function toggleRandomUsers() {

	//disable field
	document.batchuser.elements['startingIndex'].disabled=true;
	
	// enable required fields
	//document.batchuser.elements['length_pass'].disabled=false;
	document.batchuser.elements['length_user'].disabled=false;
}


function toggleIncrementUsers() {

	//disable field
	//document.batchuser.elements['length_pass'].disabled=true;
	document.batchuser.elements['length_user'].disabled=true;

	// enable required fields
	document.batchuser.elements['startingIndex'].disabled=false;
}


function toggleUserAuth() {

	//disable the mac auth
	document.newuser.elements['macaddress'].disabled=true;
	document.newuser.elements['group_macaddress[]'].disabled=true;

	// disable pincode auth
	document.newuser.elements['pincode'].disabled=true;
	document.newuser.elements['group_pincode[]'].disabled=true;

	//enable the user auth
	document.newuser.elements['username'].disabled=false;
	document.newuser.elements['password'].disabled=false;
	document.newuser.elements['passwordType'].disabled=false;
	document.newuser.elements['groups[]'].disabled=false;
	document.newuser.elements['usergroup'].disabled=false;

}

function togglePinCode() {

	// disable pincode auth
	document.newuser.elements['pincode'].disabled=false;
	document.newuser.elements['group_pincode[]'].disabled=false;

	//disable the mac auth
	document.newuser.elements['macaddress'].disabled=true;
	document.newuser.elements['group_macaddress[]'].disabled=true;

	// disable the user auth
	document.newuser.elements['username'].disabled=true;
	document.newuser.elements['password'].disabled=true;
	document.newuser.elements['passwordType'].disabled=true;
	document.newuser.elements['groups[]'].disabled=true;
	document.newuser.elements['usergroup'].disabled=true;

}

function toggleMacAuth(state) {

	// enable the mac auth
	document.newuser.elements['macaddress'].disabled=false;
	document.newuser.elements['group_macaddress[]'].disabled=false;

	// disable the user auth
	document.newuser.elements['username'].disabled=true;
	document.newuser.elements['password'].disabled=true;
	document.newuser.elements['passwordType'].disabled=true;
	document.newuser.elements['groups[]'].disabled=true;
	document.newuser.elements['usergroup'].disabled=true;

	// disable pincode auth
	document.newuser.elements['pincode'].disabled=true;
	document.newuser.elements['group_pincode[]'].disabled=true;

}



function setText(srcObj,dstObj) {

	var srcElem = document.getElementById(srcObj);
	var elemVal = srcElem.options[srcElem.selectedIndex].value;

	var dstElem = document.getElementById(dstObj);
	var dstElemVal = dstElem.value;
	var res = (dstElemVal * elemVal);
	dstElem.value = res;

}



function setStringText(srcId,dstId) {

	var srcElem = document.getElementById(srcId);

	if (srcElem.type == "text")
		var elemVal = srcElem.value;

	if (srcElem.type == "select-one")
		var elemVal = srcElem.options[srcElem.selectedIndex].value;

	var dstElem = document.getElementById(dstId);
	dstElem.value = elemVal;

}



function setStringTextMulti(srcId,dstId1, dstId2) {

	var srcElem = document.getElementById(srcId);

	if (srcElem.type == "select-one")
		var elemVal = srcElem.options[srcElem.selectedIndex].value;

	var srcElemValArray = elemVal.split("||");

	var dstElem1 = document.getElementById(dstId1);
	dstElem1.value = srcElemValArray[0];

	var dstElem2 = document.getElementById(dstId2);
	dstElem2.value = srcElemValArray[1];

}




function toggleShowDiv(idName) {

	var divs = document.getElementsByTagName('div');
	for(i=0;i<divs.length;i++) {
		if (divs[i].id.match(idName)) {
			if (document.getElementById) {							// compatible with IE5 and NS6
				if (divs[i].style.display=="inline")
					divs[i].style.display="none";
				else
	 				divs[i].style.display="inline";
			} else if (document.layers) {							// compatible with Netscape 4
				if (document.layers[divs[i]].display=='visible')
					document.layers[divs[i]].display = 'hidden';
				else
					document.layers[divs[i]].display = 'visible';
			} else {
				if (document.all.hideShow.divs[i].visibility=='visible')		// compatible with IE4
					document.all.hideShow.divs[i].visibility = 'hidden';
				else
					document.all.hideShow.divs[i].visibility = 'visible';
			}
		}
	}
}


function small_window(user,pass,time) {

	var newWindow;
	var currentTime = new Date();
	var props = "scrollBars=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no,width=500,height=200";
	newWindow = window.open("about:blank","blank",props);

        newWindow.document.write("<html><body>");
	newWindow.document.write("Thank you. <br/>");
	newWindow.document.write("Your username is: ");
	newWindow.document.write(user);
	newWindow.document.write("<br/>");
	newWindow.document.write("Your password is: ");
	newWindow.document.write(pass);
	newWindow.document.write("<br/>");
	newWindow.document.write("Your timecredit is: ");
	newWindow.document.write(time);
	newWindow.document.write("<br/>");
	newWindow.document.write("<br/>");
	newWindow.document.write("Receipt produced on: ");
	newWindow.document.write(currentTime);
	newWindow.document.write("<br/>");
	newWindow.document.write("Enginx HotSpot System ");
	newWindow.document.write("<br/>");
        newWindow.document.write("</body></html>");
        newWindow.document.close();

}



function SetChecked(val,chkName,formname) {
        dml=document.forms[formname];
	len = dml.elements.length;
        var i=0;
        for( i=0 ; i<len ; i++) {
                if (dml.elements[i].name==chkName) {
                dml.elements[i].checked=val;
                }
        }
}



function removeCheckbox(formName,pageDst) {

        var count = 0;
        var form = document.getElementsByTagName('input');

        for (var i=0; i < form.length; ++i) {
                var e = form[i];
                if (e.type == 'checkbox' && e.checked)
                ++count;
        }


	// if no items were checked there's no reason to submit the form
	if (count == 0) {
		alert("No items selected");
		return;
	}


        if (confirm("You are about to remove " + count + " records from database\nDo you want to continue?"))  {
		document.forms[formName].action=pageDst;
		document.forms[formName].submit();
                return true;
        }

        return false;
}





function disableCheckbox(formName,pageDst) {

        var count = 0;
        var form = document.getElementsByTagName('input');
	var values = "";

        for (var i=0; i < form.length; ++i) {
                var e = form[i];
                if (e.type == 'checkbox' && e.checked) {
			values += "username[]=" + e.value + "&";
	                ++count;
		}
        }

	var strUsernames = values.substr(0,values.length-1);


	// if no items were checked there's no reason to submit the form
	if (count == 0) {
		alert("No items selected");
		return;
	}


        if (confirm("You are about to disable " + count + " users\nDo you want to continue?"))  {

		ajaxGeneric("include/management/userOperations.php","userDisable","returnMessages",strUsernames);

		return true;

        }

        return false;
}






function enableCheckbox(formName,pageDst) {

        var count = 0;
        var form = document.getElementsByTagName('input');
	var values = "";

        for (var i=0; i < form.length; ++i) {
                var e = form[i];
                if (e.type == 'checkbox' && e.checked) {
			values += "username[]=" + e.value + "&";
	                ++count;
		}
        }

	var strUsernames = values.substr(0,values.length-1);


	// if no items were checked there's no reason to submit the form
	if (count == 0) {
		alert("No items selected");
		return;
	}


        if (confirm("You are about to enable " + count + " users\nDo you want to continue?"))  {

		ajaxGeneric("include/management/userOperations.php","userEnable","returnMessages",strUsernames);

		return true;

        }

        return false;
}





function backupRollback(file) {

        if (confirm("Performing a rollback will wipe out your current database tables completely and re-create it from the rollback backup file\nDo you want to continue?"))  {
		window.location.href='?file='+file+'&action=rollback';
                return true;
        }

        return false;
}





var gCounter = 0;
function genericCounter(str) {
	return str+"="+gCounter++;
}








function refillSessionTimeCheckbox(formName,pageDst) {

        var count = 0;
        var form = document.getElementsByTagName('input');
	var values = "";

        for (var i=0; i < form.length; ++i) {
                var e = form[i];
                if (e.type == 'checkbox' && e.checked) {
			values += "username[]=" + e.value + "&";
	                ++count;
		}
        }

	var strUsernames = values.substr(0,values.length-1);


	// if no items were checked there's no reason to submit the form
	if (count == 0) {
		alert("No items selected");
		return;
	}


        if (confirm("You are about to refill session time for a total of " + count + " users\nDo you want to continue?\n\nSuch action will also bill the user!"))  {

		ajaxGeneric("include/management/userOperations.php","refillSessionTime","returnMessages",strUsernames);

		return true;

        }

        return false;
}











function refillSessionTrafficCheckbox(formName,pageDst) {

        var count = 0;
        var form = document.getElementsByTagName('input');
	var values = "";

        for (var i=0; i < form.length; ++i) {
                var e = form[i];
                if (e.type == 'checkbox' && e.checked) {
			values += "username[]=" + e.value + "&";
	                ++count;
		}
        }

	var strUsernames = values.substr(0,values.length-1);


	// if no items were checked there's no reason to submit the form
	if (count == 0) {
		alert("No items selected");
		return;
	}


        if (confirm("You are about to refill session traffic fora total of " + count + " users\nDo you want to continue?\n\nSuch action will also bill the user!"))  {

		ajaxGeneric("include/management/userOperations.php","refillSessionTraffic","returnMessages",strUsernames);

		return true;

        }

        return false;
}






function copyUserBillInfo(obj) {

	if (obj.checked == true) {

		// set contact name
		document.getElementById('bi_contactperson').value = document.getElementById('firstname').value + " " +
		document.getElementById('lastname').value;
		document.getElementById('bi_email').value = document.getElementById('email').value;
		document.getElementById('bi_company').value = document.getElementById('company').value;
		document.getElementById('bi_phone').value = document.getElementById('workphone').value;
		document.getElementById('bi_address').value = document.getElementById('address').value;
		document.getElementById('bi_city').value = document.getElementById('city').value;
		document.getElementById('bi_state').value = document.getElementById('state').value;
		document.getElementById('bi_zip').value = document.getElementById('zip').value;
		
	} else {

		document.getElementById('bi_contactperson').value = "";
		document.getElementById('bi_email').value = "";
		document.getElementById('bi_company').value = "";
		document.getElementById('bi_phone').value = "";
		document.getElementById('bi_address').value = "";
		document.getElementById('bi_city').value = "";
		document.getElementById('bi_state').value = "";
		document.getElementById('bi_zip').value = "";
		
	}



}
