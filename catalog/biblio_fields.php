<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

	require_once("../shared/common.php");

	require_once(REL(__FILE__, "../model/MaterialTypes.php"));
	require_once(REL(__FILE__, "../model/MaterialFields.php"));
	require_once(REL(__FILE__, "../model/Collections.php"));
	require_once(REL(__FILE__, "../catalog/inputFuncs.php"));

?>
<p class="note">
<?php echo T("Fields marked are required"); ?>
</p>

<table id="biblioFldTbl" class="primary">
	<?php ## ----------------------- ## ?>
	<thead>
	<tr>
		<th colspan="2" valign="top" nowrap="yes" align="left">
			<?php echo T("Item"); ?>
		</th>
	</tr>
	</thead>
	
	<?php ## ----------------------- ## ?>
	<tbody id="nonMarcBody">
	<tr>
		<td nowrap="true" class="primary">
			<sup>*</sup>
			<label for="materialCd"><?php echo T("Type of Material:"); ?></label>
		</td>
		<td valign="top" class="primary">
<?php
	$matTypes = new MaterialTypes;
	$attrs = array('onchange'=>"matCdReload()");
	if (isset($biblio['material_cd'])) {
		$material_cd_value = $biblio['material_cd'];
	} elseif (isset($_GET['material_cd'])) {
	  $material_cd_value = $_GET['material_cd'];
	} else {
		$material_cd_value = $matTypes->getDefault();
	}
	echo inputfield('select', "materialCd", $material_cd_value, $attrs, $matTypes->getSelect());
?>
		</td>
	</tr>
	<tr>
		<td nowrap="true" class="primary">
			<sup>*</sup>
			<label for="collectionCd"><?php echo T("Collection:"); ?></label>
		</td>
		<td valign="top" class="primary">
			<?php
				$collections = new Collections;
				$attrs = array();
				if (isset($biblio['collection_cd'])) {
					$value = $biblio['collection_cd'];
				} else {
					$value = $collections->getDefault();
				}
				echo inputfield('select', "collectionCd", $value, $attrs, $collections->getSelect());
			?>
		</td>
	</tr>
	<tr>
		<td nowrap="true" class="primary" valign="top">
			<label for="opacFlg"><?php echo T("Show in OPAC:"); ?></label>
		</td>
		<td valign="top" class="primary">
			<input type="checkbox" id="opacFlg" name="opacFlg" value="CHECKED"
				<?php if (isset($biblio) and $biblio['opac_flg'] == 'Y') echo H('checked="checked"'); ?> />
		</td>
	</tr>
	<tr>
		<td colspan="2" nowrap="true" class="primary">
			<b><?php echo T("USMarc Fields:"); ?></b>
		</td>
	</tr>
	</tbody>

	<?php ## ----------------------- ## ?>
	<tbody id="marcBody">
<?php
	function getlabel($f) {
		global $LOC;
		$label = "";
		if ($f['label'] != "") {
			$label = $f['label'];
		} elseif ($f['subfield'] != "") {
			$idx = sprintf("%03d$%s", $f['tag'], $f['subfield']);
			$label = $LOC->getMarc($idx);
		} else {
			$label = $LOC->getMarc($f['tag']);
		}
		return $label;
	}
	function mkinput($fid, $sfid, $data, $f) {
		return array('fieldid' => $fid,
			'subfieldid' => $sfid,
			'data' => $data,
			'tag' => $f['tag'],
			'subfield' => $f['subfield_cd'],
			'label' => getlabel($f),
			'required' => $f['required'],
			'form_type' => $f['form_type']);
	}

	$mf = new MaterialFields;

	// get field specs in 'display postition' order
	$fields = $mf->getMatches(array('material_cd'=>$material_cd_value), 'position');

	## anything to process for current media type (material_cd) ?
	if ($fields->count() == 0) {
		echo "<tr><td colspan=\"2\" class=\"primary\">.T('No fields to fill in.').</td></tr>\n";
	}
	
	## build an array of fields to be displayed on user form
	$inputs = array();
	while (($f=$fields->next())) {
		// make a set of marc tags to be processed
		$tags = array();
		if (isset($biblio)) {
			$tags = $biblio['marc']->getFields($f['tag']);
			if ($f['auto_repeat'] != Tag && count($tags) > 0) {
				$tags = array($tags[0]);
			}
		}
		if (count($tags) > 0) {
			foreach ($tags as $t) {
				$subfs = array();
				if ($f['subfield_cd'] != "") {
					$subfs = $t->getSubfields($f['subfield_cd']);
					if ($f['auto_repeat'] != Subfield && count($subfs) > 0) {
						$subfs = array($subfs[0]);
					}
				}
				foreach ($subfs as $sf) {
					array_push($inputs,
						mkinput($t->fieldid,
							$sf->subfieldid,
							$sf->data, $f));
				}
				if (count($subfs) == 0 || $f['auto_repeat'] == 'Subfield') {
					array_push($inputs, mkinput($t->fieldid, NULL, NULL, $f));
				}
				for ($n=0; $n<$f['repeatable']; $n++) {
					array_push($inputs, mkinput($t->fieldid, NULL, NULL, $f));
				}
			}
		}
		else if (count($tags) == 0 ) {
			for ($n=0; $n<=$f['repeatable']; $n++) {
				array_push($inputs, mkinput(NULL, NULL, NULL, $f));
			}
		}
	}

	## now build html for those input fields
	foreach ($inputs as $n => $i) {
		$marcInputFld = H($i['tag']).H($i['subfield']);
		echo "<tr> \n";
		echo "	<td class=\"primary\" valign=\"top\"> \n";

//		if ($i['required'] == 'Y') {  // db field is defined as TinyInt not char
		if ($i['required']) {
			echo '	<sup>*</sup>';
		}
		echo "	<label for=\"$marcInputFld\">".H($i['label'].":")."</label>";

		echo "	</td> \n";
		echo "	<td valign=\"top\" class=\"primary\"> \n";

		echo inputfield('hidden', "fields[".H($n)."][tag]",         H($i['tag']))." \n";
		echo inputfield('hidden', "fields[".H($n)."][subfield_cd]", H($i['subfield']))." \n";
		echo inputfield('hidden', "fields[".H($n)."][fieldid]",     H($i['fieldid']))." \n";
		echo inputfield('hidden', "fields[".H($n)."][subfieldid]",  H($i['subfieldid']))." \n";

		$attrs = array("id"=>"$marcInputFld");
		if ($i['required']) {
		  $attrs["class"] = "marcBiblioFld reqd";
		}
		else {
		  $attrs["class"] = "marcBiblioFld";
		}
		if ($i['form_type'] == 'text') {
		  $attrs["size"] = "50"; $attrs["maxLength"] = "75";
			echo inputfield('text', "fields[".H($n)."][data]", H($i['data']),$attrs)." \n";
		} else {
			// IE seems to make the font-size of a textarea overly small under
			// certain circumstances.  We force it to a sane value, even
			// though I have some misgivings about it.  This will make
			// the font smaller for some people.
			$attrs["style"] = "font-size:10pt; font-weight: normal;";
			$attrs["rows"] = "7"; $attrs["cols"] = "38";
			echo inputfield('textarea', "fields[".H($n)."][data]", H($i['data']),$attrs)." \n";
		}
		echo "</td> \n";
	echo "</tr> \n";
	}
?>
	</tbody>

	<?php ## ----------------------- ## ?>
	<tfoot>
	<tr>
		<td align="center" colspan="2" class="primary">
			<input type="submit" value="<?php echo T("Submit"); ?>" class="button" />
			<input type="button" onclick="parent.location='<?php echo H($cancelLocation);?>'" value="<?php echo T("Cancel"); ?>" class="button" />
		</td>
	</tr>
	</tfoot>
</table>
