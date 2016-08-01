<script language="JavaScript" >
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
// JavaScript Document - JSAdmin.php
"use strict"

/* ************************************************************************** */
/* Base class for DB lookup table maintenance
 *   url: data server URL
 *   form: id of html form to use
 *   dbAlias: nickname of server database
 *   opts: js object containing as a minimum: listHdr, editHdr, newHdr
 *
 * @author Fred LaPlante, June 2016
 */

class Admin {
    constructor ( url, form, dbAlias, hdrs, listFlds, opts ) {
    	this.url = url;
    	this.editForm = form;
    	this.dbAlias = dbAlias;
    	this.listHdr = hdrs.listHdr;
    	this.editHdr = hdrs.editHdr;
    	this.newHdr = hdrs.newHdr;
    	this.listFlds = listFlds;
    	this.keyFld = opts.keyFld || 'code';
    	this.focusFld = opts.focusFld || 'description';
    	this.noshows = opts.noshows || [];
    	this.noshows.push(this.keyFld);

        this.delConfirmMsg = <?php echo '"'.T("Are you sure you want to delete ").'"'; ?>;
        this.cancelStr = <?php echo '"'.T("Cancel").'"'?>;

    	this.initWidgets();

    	$('#msgDiv').hide();
    	$('#reqdNote').css('color','red');
    	$('.reqd sup').css('color','red');
    	$('#updateMsg').hide();

	$('.newBtn').click(function(e) {e.preventDefault();});
    	$('.newBtn').on('click',null,$.proxy(this.doNewFields,this));
    	$('.actnBtns').on('click',null,$.proxy(this.doSubmitFields,this));
    	//$('#cnclBtn').on('click',null,$.proxy(this.resetForms,this));
    	$('#cnclBtn').on('click',null,$.proxy(this.doBackToList,this));

    	this.fetchList();
    	this.resetForms()
    };

	//------------------------------
    initWidgets () {
    };

    resetForms () {
    	$('#editDiv').hide();
        $('#listHdr').html(this.listHdr);
        $('#editHdr').html(this.editHdr);
        $('#cnclBtn').val('Go Back');
    	$('#listDiv').show();
    };

    doBackToList () {
        //console.log('in back to list');
    	$('#msgDiv').hide('slow', $.proxy(this.backHandler, this));
    };
    backHandler (e) {
    	this.resetForms();
    	this.fetchList();
    };

	//------------------------------
    fetchList () {
    	var params = { 'cat':this.dbAlias,
    				   'mode':'getAll_'+this.dbAlias,
    							 };
        $.post( this.url, params, $.proxy(this.fetchHandler,this), 'json');
    };
    fetchHandler (dataAray) {
        this.json = dataAray;	// will be re-used later for editing

    	var $theTbl = $('#showList'),
    		$theList = $theTbl.find('tbody'),
    		html = '',
    		item, ident,
    		test = '';

    	$theList.html('');

    	for (var obj in dataAray) {
    		item = dataAray[obj],
    		ident = item[this.keyFld];
    		// these are static in all rows
       	    html  = '<tr>\n';
     		html += '	<td valign="top">\n';
       	    html += this.addFuncBtns( ident );
       	    html += '	</td>\n';

    		// these vary by form in use
    		for (var fld in this.listFlds) {
    			var theClass = this.listFlds[fld];
    			if (theClass == 'image') {
    				html += '	<td valign="top">'
    					 +'		<img src="../images/'+item[fld]+'" width="20" height="20" align="middle">'
    					 + 		item[fld] + '</td>\n';
    			}
    			else if (theClass == 'textarea') {
    				html += '	<td valign="top" class="'+theClass+'">'
    					 +  '		<textarea cols="100" readonly>'+item[fld]+'</textarea></td>\n';
    			}
    			else {
    				html += '	<td valign="top" class="'+theClass+'">'+item[fld]+'</td>\n';
    			}
    		}
    		html += '</tr>\n';
    		$theList.append(html);
    		$('#row'+ident).on('click',null,$.proxy(this.doEditFields,this));
    	}

    	var $stripes = $theTbl.find('tbody.striped');
    	$stripes.find('tr:odd td').addClass('altBG');
    	$stripes.find('tr:even td').addClass('altBG2');
    };
    addFuncBtns (ident) {
    	var html = '';
    	html  = '		<input type="button" id="row'+ident+'" class="editBtn" value="'+<?php echo "'".T("edit")."'"; ?>+'" />\n';
    	html += '		<input type="hidden" value="'+ident+'"  />\n';
    	return html;
    };
       
    doEditFields (e) {
        var code = $(e.target).next().val(),
    	    ident = this.keyFld,
    		n;
    	for (n in this.json) {
    		var item = this.json[n];
    	    if (item[ident] == code) {
    			this.showFields(item);
    			this.crnt = code;
    			return false;
    		}
    	}
    	return false;
    };
	
    showFields (item) {
        $('#fieldsHdr').html(this.editHdr);
        $('#addBtn').hide();
        $('#updtBtn').show();
        $('#deltBtn').show();

    	$('#editTbl').find('input:not(:button):not(:submit), textarea, select').each(function () {
    		var tagname = $(this).get(0).tagName;
    		if (tagname == 'select') {
    			$(this).val([item[this.id]]);
    		}
    		else if ($(this).is('[type=checkbox]')) {
    			$(this).val([item[this.id]]);
    		}
    		else if ($(this).is('[type=radio]')) {
    			$(this).val([item[this.name]]);
    		}
    		else if ($(this).is('[type=file]')) {
    			$(this).val([item[this.id]]);
    		}
    		else {
    			$(this).val(item[this.id]);
    		}
            // key field must be static for updates (marked 'addOnly' in html)
            var theClass = $(this).get(0).className;
            if (theClass == 'addOnly') {
                $(this).attr('readOnly',true);
            }
    	});
    	for (var n in this.noshows){
    		$('#'+this.noshows[n]).attr('required',false).hide();
    	};
        $('#editForm input:visible:first').focus();
    	$('#codeReqd').hide();
    	$('#listDiv').hide();
    	$('#editDiv').show();
    };
	
    doNewFields (e) {
        document.forms['editForm'].reset();
        $('#fieldsHdr').html(this.newHdr);
    	for (var n in this.noshows){
    		$('#'+this.noshows[n]).attr('readonly',true).attr('required',false).hide();
    	};
        $('#editTbl').find('input.addOnly').removeAttr('readonly');
    	$('#codeReqd').show();
    	$('#deltBtn').hide();
    	$('#updtBtn').hide();
        $('#addBtn').show();

    	$('#listDiv').hide();
        $('#editForm input:visible:first').focus();
    	$('#editDiv').show();
    	return false;
    };
	
    doSubmitFields (e) {
        //console.log('in JSAdmin::doSubmitFields()');
    	e.preventDefault();
    	e.stopPropagation();
    	var theId = e.target.id;
    	switch (theId) {
    		case 'addBtn':	this.doAddBtn();	  break;
    		case 'updtBtn':	this.doUpdtBtn();  break;
    		case 'deltBtn':	this.doDeltBtn();  break;
    		default: $('#msgArea').html("'"+theId+"' is not a valid action button id");
    				 $('#msgDiv').show();
    	}
    };
	doAddBtn () {
        this.doAddFields()
    }
	doUpdtBtn () {
        this.doUpdateFields()
    }
	doDeltBtn () {
        this.doDeleteFields();
    }

    doAddFields () {
        //console.log('in JSAdmin::doAddFields()');
    	$('#msgDiv').hide();
    	$('#mode').val('addNew_'+this.dbAlias);
    	$('#cat').val(this.dbAlias);
    	var parms = this.doGatherParams();
    	$.post(this.url, this.doAssembleParams(parms), $.proxy(this.addHandler,this), 'json');
    	return false;
    };
    doGatherParams () {
        return $('#editForm').serializeArray();
    };
    doAssembleParams (params) {
	var numParams = params.length;
        for (var i = (numParams-1); i >= 0; i--) {
            if ((params[i].value.length < 1)) {
                 params.splice(i, 1);
            }
        }
	return jQuery.param(params);
    };
    addHandler (response) {
        //console.log(response);
        this.seqNum = response[0];
    	this.showResponse(response[1]);
    };

    doUpdateFields () {
    	$('#updateMsg').hide();
    	$('#msgDiv').hide();
    	$('#mode').val('update_'+this.dbAlias);
    	$('#cat').val(this.dbAlias);
	var parms = this.doGatherParams();
    	if ($('#newImageFile').length)
    		parms.push($('#newImageFile').serializeArray());
    	$.post(this.url, this.doAssembleParams(parms), $.proxy(this.updateHandler, this));
    	return false;
    };
    updateHandler (response) {
    	this.showResponse(response);
    };
	
    doDeleteFields (e) {
    	var msg = this.delConfirmMsg+'\n>>> '+$('#'+this.focusFld).val()+' <<<';
        if (confirm(msg)) {
      	   var parms = {'cat':this.dbAlias,
    					'mode':'d-3-L-3-t_'+this.dbAlias,
    					'code':$('#'+this.keyFld).val(),
    					'description':$('#description').val(),
    								};
    		parms[this.keyFld] = $('#'+this.keyFld).val();
      	    $.post(this.url, parms, $.proxy(this.deleteHandler,this));
    	}
    	return false;
    };
    deleteHandler (response){
    	this.showResponse(response);
    };

    showResponse (response) {
        //console.log('rcvd response from server: '+response);
    	if (($.trim(response)).indexOf('Success') > 0){
    		$('#msgArea').html(response);
    		$('#msgDiv').show();
            this.doBackToList();
    	} else {
    	    $('#msgArea').html(response);
    	    $('#msgDiv').show();
        }
        return
    };
}
</script>
