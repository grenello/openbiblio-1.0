<script language="JavaScript" >
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
// JavaScript Document

mbf = {
	<?php
		echo "delConfirmMsg: '".T("Are you sure you want to delete ")."',\n";
		echo "listHdr: '".T("Custom Member Fields")."',\n";
		echo "editHdr: '".T("Editing Custom Fields")."',\n";
		echo "newHdr: '".T("Add new custom field")."',\n";
	?>
	
	init: function () {
		mbf.initWidgets();

		mbf.url = 'adminSrvr.php';
		mbf.editForm = $('#editForm');

		$('#reqdNote').css('color','red');
		$('.reqd sup').css('color','red');
		$('#updateMsg').hide();

		$('#showForm .newBtn').on('click',null,mbf.doNewFields);
		$('#editForm').on('submit',null,mbf.doSubmits);
		//$('#updtBtn').on('click',null,mbf.doUpdateFields);
		$('#cnclBtn').on('click',null,mbf.resetForms);
		//$('#deltBtn').on('click',null,mbf.doDeleteFields);

		mbf.resetForms()
	  $('#msgDiv').hide();
		mbf.fetchFields();
	},
	
	//------------------------------
	initWidgets: function () {
	},
	resetForms: function () {
		//console.log('resetting!');
	  $('#listHdr').html(mbf.listHdr);
	  $('#editHdr').html(mbf.editHdr);
		$('#editDiv').hide();
		$('#listDiv').show();
    $('#cnclBtn').val('Cancel');
	},
	doBackToList: function () {
		$('#msgDiv').hide(10000);
		mbf.resetForms();
		mbf.fetchFields();
	},
	
	//------------------------------
	fetchFields: function () {
	  $.getJSON(mbf.url,{ 'cat':'mbrFlds', 'mode':'getAllMbrFlds' }, function(dataAray){
	    mbf.json = dataAray;
			var html = '';
			for (obj in dataAray) {
				var item = dataAray[obj];
	    	html += '<tr>\n';
    		html += '	<td valign="top">\n';
				html += '		<input type="button" class="editBtn" value="'+<?php echo "'".T("edit")."'"; ?>+'" />\n';
				html += '		<input type="hidden" value="'+item['code']+'"  />\n';
	    	html += '	</td>\n';
    		html += '	<td valign="top">'+item['code']+'</td>\n';
    		html += '	<td valign="top">'+item['description']+'</td>\n';
	    	html += '</tr>\n';
			}
			$('#showList tBody').html(html);

			$('.editBtn').on('click',null,mbf.doEdit);
			$('table tbody.striped tr:odd td').addClass('altBG');
			$('table tbody.striped tr:even td').addClass('altBG2');
		});
	},

	doEdit: function (e) {
	  var code = $(e.target).next().val();
		//console.log('you wish to edit code: '+code);
		for (n in mbf.json) {
		  if (mbf.json[n]['code'] == code) {
				mbf.showFields(mbf.json[n]);
			}
		}
		return false;
	},
	
	showFields: function (fields) {
		//console.log('showing : '+fields['description']);
	  $('#fieldsHdr').html(mbf.editHdr);
	  $('#addBtn').hide();
	  $('#updtBtn').show();
	  $('#deltBtn').show();
	  //$('#description').focus();
	  document.getElementById('description').focus();

		$('#code').val(fields['code']).attr('readonly',true).attr('required',false);
		$('#description').val(fields['description']);
		$('#codeReqd').hide();

		$('#listDiv').hide();
		$('#editDiv').show();
	},
	
	doNewFields: function () {
	  document.forms['editForm'].reset();
	  $('#fieldsHdr').html(mbf.newHdr);
		$('#code').attr('readonly',false)
							.attr('required',true);
		$('#codeReqd').show();
		$('#deltBtn').hide();
		$('#updtBtn').hide();
	  $('#addBtn').show();
		$('#listDiv').hide();
		$('#editDiv').show();
	  document.getElementById('code').focus();
		return false;
	},
	
	doSubmits: function (e) {
		e.preventDefault();
		e.stopPropagation();
		var theId = $("#editForm").find('input[type="submit"]:focus').attr('id');
		switch (theId) {
			case 'addBtn':	mbf.doAddFields();	break;
			case 'updtBtn':	mbf.doUpdateFields();	break;
			case 'deltBtn':	mbf.doDeleteFields();	break;
		}
	},
	
	doAddFields: function () {
		$('#mode').val('addNewMbrFld');
		var parms = $('#editForm').serialize();
		//console.log('adding: '+parms);
		$.post(mbf.url, parms, function(response) {
			if (response.substr(0,1)=='<') {
				//console.log('rcvd error msg from server :<br />'+response);
				$('#msgArea').html(response);
				$('#msgDiv').show();
			}
			else {
				$('#msgArea').html(response);
				$('#msgDiv').show();
			  mbf.doBackToList();
			}
		});
		return false;
	},

	doUpdateFields: function () {
		$('#updateMsg').hide();
		$('#msgDiv').hide();
		$('#mode').val('updateMbrFld');
		var parms = $('#editForm').serialize();
		//console.log('updating: '+parms);
		$.post(mbf.url, parms, function(response) {
			if (response.substr(0,1)=='<') {
				//console.log('rcvd error msg from server :<br />'+response);
				$('#msgArea').html(response);
				$('#msgDiv').show();
			}
			else {
				$('#msgArea').html(response);
				$('#msgDiv').show();
			  mbf.doBackToList();
			}
		});
		return false;
	},
	
	doDeleteFields: function (e) {
		var msg = mbf.delConfirmMsg+'\n>>> '+$('#description').val()+' <<<';
	  if (confirm(msg)) {
	  	var parms = {	'cat':'mbrFlds', 'mode':'d-3-L-3-tMbrFld', 'code':$('#code').val(), 'description':$('#description').val() };
	  	$.post(mbf.url, parms, function(response){
				if (($.trim(response)).substr(0,1)=='<') {
					//console.log('rcvd error msg from server :<br />'+response);
					$('#msgArea').html(response);
					$('#msgDiv').show();
				}
				else {
					$('#msgArea').html(response);
					$('#msgDiv').show();
			  	mbf.doBackToList();
				}
			});
		}
		return false;
	},
};

$(document).ready(mbf.init);
</script>
