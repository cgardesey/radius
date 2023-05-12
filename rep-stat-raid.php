<?php


    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include('library/check_operator_perm.php');

	include_once('library/config_read.php');
    $log = "visited page: ";
    $logQuery = "performed query on page: ";
    include('include/config/logging.php');


?>


<?php
	include_once ("library/tabber/tab-layout.php");
?>
<?php

    include ("menu-reports-status.php");
  	
?>	
		
		
		<div id="contentnorightbar">
		
		<h2 id="Intro"><a href="#"  onclick="javascript:toggleShowDiv('helpPage')">RAID Status
		<h144>&#x2754;</h144></a></h2>

		<div id="helpPage" style="display:none;visibility:visible" >
			<br/>
		</div>
		<br/>



<div class="tabber">


<?php

	if (!file_exists('/proc/mdstat')):
?>
	<font color='red'><b>Error</b> accessing RAID device information:</font>
	<br/><br/>

<?php 
	else: 
	exec("cat /proc/mdstat  | awk '/md/ {print $1}'", $mdstat);

?>

	<font color='red'><b>Error</b> accessing RAID device information:</font>
	<br/><br/>

	<?php
		foreach($mdstat as $mddevice):
	?>

			<div class="tabbertab" title="<?php echo $mddevice ?>">

				<?php
					$output = "";
					$cmd = "sudo /sbin/mdadm --detail /dev/$mddevice";
					exec($cmd, $output);
				?>
		
				<table class='summarySection'>
		
					<?php
						foreach($output as $line):
					?>
			
					<?php
						list($var, $val) = split(":", $line);
					?>
			
					  <tr>
						<td class='summaryKey'> <?php echo $var ?> </td>
						<td class='summaryValue'><span class='sleft'> <?php echo $val ?> </span> </td>
					  </tr>
			
					<?php endforeach; ?>
		
				</table>
		
			</div>

	<?php endforeach; ?>
<?php endif; ?>

</div>




		</div>
		
		<div id="footer">
		
<?php
        include 'page-footer.php';
?>
		
		</div>
		
</div>
</div>


</body>
</html>
