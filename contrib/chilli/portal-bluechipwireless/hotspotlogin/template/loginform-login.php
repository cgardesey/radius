<?php




?>


<?php
	echo "
	<form name='form1' method='post' action='$loginpath'>
		<input type='hidden' name='challenge' value='$challenge'>
		<input type='hidden' name='uamip' value='$uamip'>
		<input type='hidden' name='uamport' value='$uamport'>
		<input type='hidden' name='userurl' value='$userurl'>
	";
?>

<table class="login_form">
  <tr>
    <td align="right">Username:</td>

    <td>
<input class="login_username" type="text" name="UserName"
               value="" size="16" />
    </td>
  </tr>
  <tr>
    <td align="right">Password:</td>
    <td>
      <input class="login_password" type="password" name="Password" size="16" />
    </td>

  </tr>
  <tr>
    <td colspan="2" align="right">
	<input type='hidden' name='button' value='Login'>
      <input class="login_submit" type="button" name="button" value="Login" 
	  	<?php echo "onClick=\"javascript:popUp('$loginpath?res=popup1&uamip=$uamip&uamport=$uamport')\"" ?> />
	
		<input type="checkbox" name="tos" id="toscheckbox"> 
			<font size='1'>I agree to the <a href="http://www.bluechipwireless.com/samui_internet_service_terms.html">Terms & Conditions </a> </font>
    </td>
  </tr>
</table>
</form></div>
<!--sputnik form ends above-->





