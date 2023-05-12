<?php


    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

	include('library/check_operator_perm.php');


	include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');

    include ("library/config_read.php");

    if (isset($_REQUEST['submit'])) {

		if (isset($_REQUEST['config_lang']))
			$configValues['CONFIG_LANG'] = $_REQUEST['config_lang'];
			
            include ("library/config_write.php");
    }
	

	
?>		

<?php

    include ("menu-config.php");

?>		
		
		
		<div id="contentnorightbar">
		
				<h2 id="Intro"><a href="#" onclick="javascript:toggleShowDiv('helpPage')"><?php echo t('Intro','configlang.php') ?>
				<h144>&#x2754;</h144></a></h2>
                <div id="helpPage" style="display:none;visibility:visible" >
					<?php echo t('helpPage','configlang') ?>
					<br/>
				</div>
                <?php
					include_once('include/management/actionMessages.php');
                ?>

				<form name="langsettings" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <fieldset>

                <h302> <?php echo t('title','Settings'); ?> </h302>
                <br/>

                <ul>


                <li class='fieldset'>
                <label for='config_lan' class='form'><?php echo t('all','PrimaryLanguage')?></label>
		<select name="config_lang" class='form'>
			<option value="en"> English </option>
			<option value="ru"> Russian </option>
			<option value="hu"> Hungarian </option>
			<option value="it"> Italian </option>
			<option value="es_VE"> Spanish - Venezuelan </option>
			<option value="pt_br"> Portuguese - Brazilian </option>
			<option value="ja"> Japanese </option>
		</select>
		</li>

                <li class='fieldset'>
                <br/>
                <hr><br/>
                <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' class='button' />
                </li>

                </ul>

        </fieldset>

				</form>

	
				<br/><br/>
				

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
