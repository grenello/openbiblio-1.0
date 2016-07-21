<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

/**
 * typically accessed using the T("...") function
 * provided in the ..../shared/templates.php
 */

#****************************************************************************
#* Formats
#****************************************************************************
$trans["headerDateFormat"]	 = "m.d.Y";

#****************************************************************************
#* Common translation text, arranged alphabetically
#****************************************************************************
####### A #######
####### B #######
####### C #######
$trans["Camera"] = "الة تصوير";
$trans["Cancel"] = "إلغاء";
$trans["Cataloging"] = "الفهرسة";
####### D #######
####### E #######
$trans["Email"] = "عنوان البريد الإلكتروني";
####### F #######
####### G #######
####### H #######
####### I #######
####### J #######
####### K #######
####### L #######
####### M #######
####### N #######
####### O #######
####### P #######
####### Q #######
####### R #######
####### S #######
####### T #######
####### U #######
####### V #######
####### W #######
####### Y #######
####### Z #######

 ## ##################################
 ## adds suport for plugins - fl, 2009
 ## ##################################
		$list = getPlugIns('tran.tran');
		for ($x=0; $x<count($list); $x++) {
			include($list[$x]);
		}
 ## ##################################