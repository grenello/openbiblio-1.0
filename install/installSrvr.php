<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
	
	$doing_install = true;
  require_once("../shared/common.php");
  
	require_once(REL(__FILE__, "../install/InstallQuery.php"));

	$ptr = new InstallQuery();

//print_r($_REQUEST);
	switch ($_REQUEST['mode']){
  	#-.-.-.-.-.-.-.-.-.-.-.-.-
		case 'connectDB':
			//echo "connecting to db\n";
			$error = $ptr->connect_e();
			if ($error) 
				echo $error->toStr(); 
			else 
				echo "OK";
			break;
			
		case 'getDbVersion':
			//echo "fetching version\n";
			$version = $ptr->getCurrentDatabaseVersion();
			if (!$version || empty($version)) {
				echo "noDB";
			} else {	
				echo $version;
			}
			break;
			
		case 'getLocales':
			//echo "fetching locales\n";
			$arr_lang = Localize::getLocales();
			foreach ($arr_lang as $langCode => $langDesc) {
				echo '<option value="'.H($langCode).'">'.H($langDesc)."</option>\n";
			}
			break;
			
		case 'doFullInstall':
			//echo "full install underway\n";
			echo 	$ptr->freshInstall($Locale, $_POST['installTestData']);
			break;
			
  	#-.-.-.-.-.-.-.-.-.-.-.-.-
		default:
		  echo "<h4>invalid mode: &gt;$_REQUEST[mode]&lt;</h4><br />";
			break;
	}
?>