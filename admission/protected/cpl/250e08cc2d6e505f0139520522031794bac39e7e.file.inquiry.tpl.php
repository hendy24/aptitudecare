<?php /* Smarty version Smarty-3.1.13, created on 2015-11-04 21:13:03
         compiled from "/home/aptitude/dev/protected/tpl/patient/inquiry.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1898317468563ad74f335656-32237354%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '250e08cc2d6e505f0139520522031794bac39e7e' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl/patient/inquiry.tpl',
      1 => 1409240561,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1898317468563ad74f335656-32237354',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'states' => 0,
    'state' => 0,
    'abbr' => 0,
    'schedule' => 0,
    'auth' => 0,
    'mode' => 0,
    'patient' => 0,
    'SITE_URL' => 0,
    'data' => 0,
    'userCreated' => 0,
    'userModified' => 0,
    'facility' => 0,
    'weekSeed' => 0,
    'room' => 0,
    'refby' => 0,
    'hospital' => 0,
    'referralOrgs' => 0,
    'org' => 0,
    'caseManager' => 0,
    'availOptions' => 0,
    'e' => 0,
    's' => 0,
    'h' => 0,
    'physician' => 0,
    'user' => 0,
    'ortho' => 0,
    'doctor' => 0,
    'pharmacy' => 0,
    'codes' => 0,
    '_history' => 0,
    'homeHealth' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_563ad74f5fc6b1_73245697',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563ad74f5fc6b1_73245697')) {function content_563ad74f5fc6b1_73245697($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><?php echo smarty_set_title(array('title'=>"Inquiry Record"),$_smarty_tpl);?>



<?php $_smarty_tpl->smarty->_tag_stack[] = array('javascript', array()); $_block_repeat=true; echo smarty_javascript(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


function setConfirmUnload(on) {
    
     window.onbeforeunload = (on) ? unloadMessage : null;

}

function unloadMessage() {
    
     return 'You have entered new data on this page.  If you navigate away from this page without first saving your data, the changes will be lost.';

}

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_javascript(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


$("#home-health-field").hide();

if ($("#homehealth-search").val() != "") {
	$("#home-health-field").show();
}

$("#scheduled-home-health").click(function() {
	if ($("#scheduled-home-health").attr('checked')) {
		$("#home-health-field").show();
	} else {
		$("#home-health-field").hide();
	}
});


$(".phone-format").text(function(i, text) {
    return text.replace(/(\d<?php echo htmlspecialchars(3, ENT_QUOTES, 'UTF-8');?>
)(\d<?php echo htmlspecialchars(3, ENT_QUOTES, 'UTF-8');?>
)(\d<?php echo htmlspecialchars(4, ENT_QUOTES, 'UTF-8');?>
)/, "($1) $2-$3");
});

<?php $_smarty_tpl->tpl_vars['states'] = new Smarty_variable(getUSAStates(), null, 0);?>
var states = [
<?php  $_smarty_tpl->tpl_vars['state'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['state']->_loop = false;
 $_smarty_tpl->tpl_vars['abbr'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['states']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['state']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['state']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['state']->key => $_smarty_tpl->tpl_vars['state']->value){
$_smarty_tpl->tpl_vars['state']->_loop = true;
 $_smarty_tpl->tpl_vars['abbr']->value = $_smarty_tpl->tpl_vars['state']->key;
 $_smarty_tpl->tpl_vars['state']->iteration++;
 $_smarty_tpl->tpl_vars['state']->last = $_smarty_tpl->tpl_vars['state']->iteration === $_smarty_tpl->tpl_vars['state']->total;
?>
<?php if ($_smarty_tpl->tpl_vars['state']->value!=''){?>
	{
		value: "<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
",
		label: "(<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['abbr']->value, ENT_QUOTES, 'UTF-8');?>
) <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['state']->value, ENT_QUOTES, 'UTF-8');?>
"
	}
	<?php if ($_smarty_tpl->tpl_vars['state']->last!=true){?>,<?php }?>
<?php }?>
<?php } ?>
];

$("#state-search").autocomplete(
	{
		minLength: 0,
		source: states,
		focus: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			return false;
		},
		select: function( event, ui ) {
			$( "#state-search" ).val( ui.item.label );
			$( "#state" ).val( ui.item.value );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "</a>" )
		.appendTo( ul );
	};


// calculate age on the fly
$("#birthday").change(function() {
	 if ($(this).val() == '') {
	 	$("#age").html('');
	 	return false;
	 }
	 var now = new Date()

	 // validate
	 var parts = $(this).val().split("/");
	 var month = parseInt(parts[0], 10);
	 var day = parseInt(parts[1], 10);
	 var year = parseInt(parts[2], 10);
	 	 
	 var msg = '';
	 
	 if (! (month >=1 && month <= 12) ) {
	 	msg += 'Birthday: month must be a number between 1 and 12.\n'; 
	 }
	 if (! (day >=1 && day <= 31) ) {
	 	msg += 'Birthday: day must be a number between 1 and 31.\n'; 
	 }
	 if ( year > now.getFullYear() ) {
	 	msg += 'Birthday: Year may not be in the future.\n'; 
	 }
	 
	 if (msg != '') {
	 	jAlert(msg, 'Attention');
	 	$("#age").html('');
	 	return false;
	 }
	 
	 var born = new Date($(this).val());
	 var years = Math.floor((now.getTime() - born.getTime()) / (365 * 24 * 60 * 60 * 1000));
	 if (! isNaN(years) ) {
		$("#age").html(years);
	 } else {
	 	$("#age").html('');
	 }
}).blur(function() { $(this).trigger("change"); }).trigger("change");

<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->canEditInquiry($_smarty_tpl->tpl_vars['schedule']->value->getFacility())==false||$_smarty_tpl->tpl_vars['mode']->value!="edit"){?>

$("#inquiry-form input, #inquiry-form select, #inquiry-form textarea").attr("disabled", true).css("color", "#000").css("border", "none").css("background", "none");
$("#inquiry-form select").attr("disabled", true).css("color", "#000");

<?php }else{ ?>

// enforce formats on certain fields
$(".date").mask("99/99/9999");
$(".phone").mask("(999) 999-9999");
$("#age").mask("9?99");
$("#ssn").mask("999-99-9999");
//$("#medicare_number").mask("999999999a");



<?php }?>

$("#icd9-code-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'coord', action: 'searchCodes', term: req.term}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.short_desc + " [" + val.code + "]";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#icd9").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#admit-from-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#admit-from").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#hospital-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHospital', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#hospital").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#case-manager-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'caseManager', action: 'searchCaseManagers', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.pubid = val.pubid;
				obj.label = val.last_name + ", " + val.first_name;
				obj.phone = val.phone;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#case-manager").val(ui.item.value);
		$("#cm-phone").html(ui.item.phone + " &nbsp;&nbsp;<a href=" + SITE_URL + "/?page=caseManager&action=edit&case_manager=" + ui.item.pubid + "&isMicro=1 rel=shadowbox>Edit</a>");
		e.target.value = ui.item.label;		
	}
});

$("#homehealth-search").autocomplete({
	minLength: 4,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'hospital', action: 'searchHomeHealth', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#home-health").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});


$("#physician-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#physician").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#ortho-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#ortho").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});

$("#doctor-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'physician', action: 'searchPhysicians', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.last_name + ", " + val.first_name + " M.D. " + "(" + val.state + ")";
				obj.phone = val.phone;
				obj.fax = val.fax;
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#doctor").val(ui.item.value);
		$("#doc-phone").val(ui.item.phone);
		$("#doc-fax").val(ui.item.fax);
		e.target.value = ui.item.label;	
	}
	
});


$("#pharmacy-search").autocomplete({
	minLength: 3,
	source: function(req, add) {
		$.getJSON(SITE_URL, { page: 'pharmacy', action: 'searchPharmacies', term: req.term, facility: $("#facility").val()}, function (json) {
			var suggestions = [];
			$.each (json, function(i, val) {
				var obj = new Object;
				obj.value = val.id;
				obj.label = val.name + " (" + val.address + ' ' + val.city + ', ' + val.state + ")";
				suggestions.push(obj);
			});
			add(suggestions);
		});
	}
	,select: function (e, ui) {
		e.preventDefault();
		$("#pharmacy").val(ui.item.value);
		e.target.value = ui.item.label;		
	}
});


$("#submit-button").click(function(e) {
	e.preventDefault();
	setConfirmUnload(false);
	$("#inquiry-form").submit();
});

$("#return-to-dashboard").click(function(e) {
	e.preventDefault();
	setConfirmUnload(false);
	$("#inquiry-form").submit();
});


<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php if ($_smarty_tpl->tpl_vars['patient']->value!=''){?>
	<?php $_smarty_tpl->tpl_vars['data'] = new Smarty_variable(get_object_vars($_smarty_tpl->tpl_vars['patient']->value->getRecord()), null, 0);?>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['auth']->value->getRecord()->isAdmissionsCoordinator()==1){?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=coord" id="return-to-dashboard" class="back-to-dashboard button" style="margin-top: 10px;">Back to Dashboard</a>
<?php }?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=patient&action=printInquiry<?php if ($_smarty_tpl->tpl_vars['schedule']->value==''){?>&id=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->pubid, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?>&schedule=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
<?php }?>&mode=edit" target="_blank" class="right"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/print.png" /></a>
<br />
<br />
<h1 class="text-center">Pre-Admission Inquiry Record</h1>

<h2 class="text-center"><span class="text-14">for</span> <?php if ($_smarty_tpl->tpl_vars['data']->value['last_name']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['last_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['data']->value['first_name']!=''){?>, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['first_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['data']->value['middle_name']!=''){?> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['middle_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?></h2>
<br />
<?php $_smarty_tpl->tpl_vars['userModified'] = new Smarty_variable($_smarty_tpl->tpl_vars['patient']->value->siteUserModified(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['userCreated'] = new Smarty_variable($_smarty_tpl->tpl_vars['patient']->value->siteUserCreated(), null, 0);?>
<div id="created-info">
	This record was created <strong><?php echo htmlspecialchars(smarty_datetime_format($_smarty_tpl->tpl_vars['patient']->value->datetime_created), ENT_QUOTES, 'UTF-8');?>
</strong> <?php if ($_smarty_tpl->tpl_vars['userCreated']->value!=false){?> by <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['userCreated']->value->getFullName(), ENT_QUOTES, 'UTF-8');?>
 <?php }?>and last modified <strong><?php echo htmlspecialchars(smarty_datetime_format($_smarty_tpl->tpl_vars['patient']->value->datetime_modified), ENT_QUOTES, 'UTF-8');?>
</strong><?php if ($_smarty_tpl->tpl_vars['userModified']->value!=false){?> by <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['userModified']->value->getFullName(), ENT_QUOTES, 'UTF-8');?>
<?php }?>.
</div>

<form name="admissions" method="post" action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" id="inquiry-form"> 
	<input type="hidden" name="page" value="patient" />
	<input type="hidden" name="action" value="submitInquiry" />
	<input type="hidden" name="id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['patient']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="schedule" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" id="facility" name="facility" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->pubid, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" id="facility-state" name="facility_state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->state, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="_path" value="<?php echo htmlspecialchars(urlencode(currentURL()), ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->state, ENT_QUOTES, 'UTF-8');?>
" />
	<input type="hidden" name="weekSeed" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['weekSeed']->value, ENT_QUOTES, 'UTF-8');?>
" />
	<table cellpadding="0" border="0" id="inquiry-form"> 
	
	<tr>
		<th colspan="3">Transporation &amp; Hospital Contact Info</th>
	</tr>
	<tr class="form-header-row">
		<td width="33%">Transportation</td>
		<td width="33%">Transportation Provider</td>
		<td width="33%">Pick-Up Time</td>
	</tr>
	<tr>
		<td valign="top">
			<input type="radio" name="trans" value="wheelchair"<?php if ($_smarty_tpl->tpl_vars['data']->value['trans']=='wheelchair'){?> checked<?php }?> />Wheelchair<br /> 
			<input type="radio" name="trans" value="stretcher"<?php if ($_smarty_tpl->tpl_vars['data']->value['trans']=='stretcher'){?> checked<?php }?> />Stretcher<br /> 
			<input type="checkbox" name="o2" value="1"<?php if ($_smarty_tpl->tpl_vars['data']->value['o2']==1){?> checked<?php }?> />Oxygen <input type="text" name="o2_liters" style="width: 25px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['o2_liters'], ENT_QUOTES, 'UTF-8');?>
" /> liters
			
		</td> 
		<td valign="top">
			<input type="text" name="trans_provider" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['trans_provider'], ENT_QUOTES, 'UTF-8');?>
" size="25" />
		</td>
		<td valign="top"><input type="text" size="20" name="datetime_pickup" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['datetime_pickup'], ENT_QUOTES, 'UTF-8');?>
" /></td>
	</tr>
	<tr class="form-header-row">
		<td>Patient Location/Room Number</td>
		<td>Number to call for nursing report</td>
		<td>Name of Nurse</td>
	</tr>
	<tr>
		<td>
			<?php if ($_smarty_tpl->tpl_vars['schedule']->value!=''){?>
				<?php $_smarty_tpl->tpl_vars['room'] = new Smarty_variable($_smarty_tpl->tpl_vars['schedule']->value->getRoom(), null, 0);?>
				<?php if ($_smarty_tpl->tpl_vars['room']->value->id!=''){?>
					<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['schedule']->value->getFacility()->name, ENT_QUOTES, 'UTF-8');?>
:  Room #<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['room']->value->number, ENT_QUOTES, 'UTF-8');?>
	
				<?php }?>
			<?php }else{ ?>
				No Room Assigned
			<?php }?>
		</td>
		<td><input type="text" size="15" name="nursing_report_phone" class="phone" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['nursing_report_phone'], ENT_QUOTES, 'UTF-8');?>
" />
		<td><input type="text" name="referral_nurse_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['referral_nurse_name'], ENT_QUOTES, 'UTF-8');?>
" size="25" /></td>
	</tr>
	
	
	<tr class="form-header-row">
		<td><strong>Referred by type:</strong></td>
		<td><strong>Referred by:</strong></td>
		<td><strong>Referred by Phone:</strong></td>
	</tr>
	<tr>
		<?php if ($_smarty_tpl->tpl_vars['data']->value['referred_by_type']=="Organization"){?>
			<?php $_smarty_tpl->tpl_vars['refby'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->load($_smarty_tpl->tpl_vars['data']->value['referred_by_id']), ENT_QUOTES, 'UTF-8');?>

		<?php }elseif($_smarty_tpl->tpl_vars['data']->value['referred_by_type']=="Doctor"){?>
			<?php $_smarty_tpl->tpl_vars['refby'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->load($_smarty_tpl->tpl_vars['data']->value['referred_by_id']), ENT_QUOTES, 'UTF-8');?>

		<?php }elseif($_smarty_tpl->tpl_vars['data']->value['referred_by_type']=="Case Manager"){?>
			<?php $_smarty_tpl->tpl_vars['refby'] = new Smarty_variable(CMS_Case_Manager::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->load($_smarty_tpl->tpl_vars['data']->value['referred_by_id']), ENT_QUOTES, 'UTF-8');?>

		<?php }?>
		<td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['referred_by_type'], ENT_QUOTES, 'UTF-8');?>
</td>
		<td><?php if ($_smarty_tpl->tpl_vars['data']->value['referred_by_type']=="Organization"){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }elseif($_smarty_tpl->tpl_vars['data']->value['referred_by_type']=="Other"){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['referred_by_name'], ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->first_name, ENT_QUOTES, 'UTF-8');?>
<?php }?></td>
		<td><?php if ($_smarty_tpl->tpl_vars['data']->value['referred_by_type']=="Other"){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['referred_by_phone'], ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['refby']->value->phone, ENT_QUOTES, 'UTF-8');?>
<?php }?></td>
	</tr>
	
	
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	$("#referral-org-name").change(function(e) {
		if ($("#referral-org-name option:selected").val() == '__OTHER__') {
			jPrompt('Enter the name of the referring organization', '', 'User Input', function(r) {
				if (r == null || r == '') {
					$("#referral-org-name-other").val('').hide();
					$("#referral-org-name :selected").attr("selected", false);
					$("#referral-org-name :first-child").attr("selected", true);
				} else {
					$("#referral-org-name-other").attr("disabled", false).val(r).show();
				}
			}); 
		} else {
			$("#referral-org-name-other").val('').attr("disabled", true).hide();
		}
	});	
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	<tr class="form-header-row">
		<td>Admit From:</td>
		<td>Case Manager</td>
		<td>Case Manager Phone</td>
	</tr>
	<tr>
		<td>
			<?php if ($_smarty_tpl->tpl_vars['data']->value['admit_from']!=''){?>
				<?php $_smarty_tpl->tpl_vars['hospital'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value->load($_smarty_tpl->tpl_vars['data']->value['admit_from']), ENT_QUOTES, 'UTF-8');?>

			<?php }?>
			<input type="text" id="admit-from-search" style="width: 232px;" size="30" value="<?php if ($_smarty_tpl->tpl_vars['data']->value['admit_from']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['hospital']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['referral_org_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?>" /><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=hospital&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="new-admit-add-item" /></a>
			<input type="hidden" name="admit_from" id="admit-from" />
			<!-- <select class="referral-org" id="referral-org-name" name="referral_org_name">
			<?php $_smarty_tpl->tpl_vars['referralOrgs'] = new Smarty_variable(CMS_Patient_Admit::referralOrgs(), null, 0);?>
				<option value="">Select...</option>
				<option value="__OTHER__">Other organization (not listed here)</option>
				<option value=""></option>
			<?php  $_smarty_tpl->tpl_vars['org'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['org']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['referralOrgs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['org']->key => $_smarty_tpl->tpl_vars['org']->value){
$_smarty_tpl->tpl_vars['org']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['org']->value->referral_org_name, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['data']->value['referral_org_name']==$_smarty_tpl->tpl_vars['org']->value->referral_org_name){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['org']->value->referral_org_name, ENT_QUOTES, 'UTF-8');?>
</option>
			<?php } ?>
			</select>
			<input type="text" disabled id="referral-org-name-other" name="referral_org_name_OTHER" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['referral_org_name_OTHER'], ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['data']->value['referral_org_name_OTHER']==''){?> style="display: none;"<?php }?> />
 -->		</td>
		<td>
			<?php if ($_smarty_tpl->tpl_vars['data']->value['case_manager_id']!=''){?>
				<?php $_smarty_tpl->tpl_vars['caseManager'] = new Smarty_variable(CMS_Case_Manager::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['caseManager']->value->load($_smarty_tpl->tpl_vars['data']->value['case_manager_id']), ENT_QUOTES, 'UTF-8');?>

			<?php }?>
			<input type="hidden" id="case-manager" name="case_manager_id" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['caseManager']->value->id, ENT_QUOTES, 'UTF-8');?>
" />
			<input type="text" id="case-manager-search" value="<?php if ($_smarty_tpl->tpl_vars['data']->value['case_manager_id']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['caseManager']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['caseManager']->value->first_name, ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?>""<?php }?>" /><?php if ($_smarty_tpl->tpl_vars['data']->value['case_manager_id']!=''){?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=caseManager&action=edit&case_manager=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['caseManager']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&isMicro=1" rel="shadowbox;width=425;height=425"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/edit.png" class="edit-item"></a><?php }?><a rel="shadowbox;width=425;height=425" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=caseManager&action=add&isMicro=1"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="add-item" /></a>
		</td>
		<td><p id="cm-phone"><?php if ($_smarty_tpl->tpl_vars['data']->value['case_manager_id']!=''&&$_smarty_tpl->tpl_vars['caseManager']->value->phone!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['caseManager']->value->phone, ENT_QUOTES, 'UTF-8');?>
<?php }?></p></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	
	
	
	
	<!-- Patient Information -->
	<tr> 
		<th colspan="3">Patient Information</th> 
	</tr>
	<tr class="form-header-row">
		<td>
			First: <br />
			<input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['first_name'], ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" />
		</td>
		<td>
			Middle: <br />
			<input type="text" name="middle_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['middle_name'], ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" /> 
		</td>
		<td>
			Last:<br />
			<input type="text" name="last_name" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['last_name'], ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" />
		</td> 
	</tr>
	<tr class="form-header-row"> 
		<td colspan="3">Street Address:<br /> 
			<input type="text" name="address" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['address'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	</tr>
	<tr class="form-header-row">							
		<td>City:<br /> 
			<input type="text" name="city" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['city'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	
		<td>State<br />
		<input type="text" id="state-search" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['state'], ENT_QUOTES, 'UTF-8');?>
" />
		<input type="hidden" name="state" id="state" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['state'], ENT_QUOTES, 'UTF-8');?>
" />
		</td> 
		<td> 
			Zip<br /> 
			<input type="text" name="zip" style="width: 50px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['zip'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
	</tr> 
	
	<tr class="form-header-row"> 
		<td valign="top">Phone Number:<br /> 
			<input type="text" class="phone" name="phone" id="phone" style="width: 100px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['phone'], ENT_QUOTES, 'UTF-8');?>
" />
			<select name="phone_type">
				<option value=''></option>
				<option value="HOME"<?php if ($_smarty_tpl->tpl_vars['data']->value['phone_type']=="HOME"){?> selected<?php }?>>HOME</option>
				<option value="CELL"<?php if ($_smarty_tpl->tpl_vars['data']->value['phone_type']=="CELL"){?> selected<?php }?>>CELL</option>
				<option value="WORK"<?php if ($_smarty_tpl->tpl_vars['data']->value['phone_type']=="WORK"){?> selected<?php }?>>WORK</option>
			</select>
		</td> 
		<td valign="top">Phone Number (secondary):<br /> 
			<input type="text" class="phone" name="phone_alt" id="phone_alt" style="width: 100px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['phone_alt'], ENT_QUOTES, 'UTF-8');?>
" /> 
			<select name="phone_alt_type">
				<option value=''></option>
				<option value="HOME"<?php if ($_smarty_tpl->tpl_vars['data']->value['phone_alt_type']=="HOME"){?> selected<?php }?>>HOME</option>
				<option value="CELL"<?php if ($_smarty_tpl->tpl_vars['data']->value['phone_alt_type']=="CELL"){?> selected<?php }?>>CELL</option>
				<option value="WORK"<?php if ($_smarty_tpl->tpl_vars['data']->value['phone_alt_type']=="WORK"){?> selected<?php }?>>WORK</option>
			</select>
		</td> 
		
		<td valign="top" width="30" >Date of Birth:<br /> 
			<input type="text" name="birthday" id="birthday" class="date" style="width: 80px" value="<?php echo htmlspecialchars((($tmp = @smarty_modifier_date_format(strtotime($_smarty_tpl->tpl_vars['data']->value['birthday']),"%m/%d/%Y"))===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
	</tr>
	
	<tr class="form-header-row">
		
		<td valign="top">Age:<br /> 
			<span id="age" class="normal-font"></span> 
		</td> 

		<td colspan="1">Sex:<br /> 
			<span class="normal-font">
			<input type="radio" name="sex" value="Male"<?php if ($_smarty_tpl->tpl_vars['data']->value['sex']=="Male"){?> checked<?php }?> />Male<br /> 
			<input type="radio" name="sex" value="Female"<?php if ($_smarty_tpl->tpl_vars['data']->value['sex']=="Female"){?> checked<?php }?> />Female
			</span>
		</td> 
		<td> 
			Social Security Number:<br /> 
			<input type="text" name="ssn" id="ssn" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['ssn'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	</tr> 
	
	<tr class="form-header-row"> 
		<td valign="top"> Ethnicity:<br /> 
			<select name="ethnicity" style="width: 125px"> 
				<option value=""></option>
				<?php  $_smarty_tpl->tpl_vars['e'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['e']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['availOptions']->value['ethnicities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['e']->key => $_smarty_tpl->tpl_vars['e']->value){
$_smarty_tpl->tpl_vars['e']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['e']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['data']->value['ethnicity']==$_smarty_tpl->tpl_vars['e']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['e']->value, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select> 
		</td> 
		
		<td valign="top">Marital Status:<br /> 
			<select name="marital_status" style="width: 100px"> 
				<option value=""></option> 
				<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['availOptions']->value['maritalStatus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
?>
				<option value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value, ENT_QUOTES, 'UTF-8');?>
"<?php if ($_smarty_tpl->tpl_vars['data']->value['marital_status']==$_smarty_tpl->tpl_vars['s']->value){?> selected<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['s']->value, ENT_QUOTES, 'UTF-8');?>
</option>
				<?php } ?>
			</select> 
		</td> 
		
		<td valign="top">Religion:<br /> 
			<input name="religion" type="text" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['religion'], ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" /><br /> 
		</td> 
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>	
	
	
	
	
	
	
	
	<!-- Hospital Info -->
	<tr>
		<th colspan="3">Hospital, Physician and Insurance Info</th>
	</tr>
	<tr class="form-header-row"> 
		<td valign="top">Hospital<br /> 
			<?php if ($_smarty_tpl->tpl_vars['data']->value['hospital_id']!=''){?>
				<?php $_smarty_tpl->tpl_vars['h'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['h']->value->load($_smarty_tpl->tpl_vars['data']->value['hospital_id']), ENT_QUOTES, 'UTF-8');?>

			<?php }?>
			<input type="text" id="hospital-search" style="width: 232px;" size="30" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['h']->value->name, ENT_QUOTES, 'UTF-8');?>
" /><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=hospital&action=add&type=Hospital&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="new-admit-add-item" /></a>
			<input type="hidden" name="hospital_id" id="hospital" />
		</td> 

		<?php if ($_smarty_tpl->tpl_vars['data']->value['physician_id']!=''){?>
			<?php $_smarty_tpl->tpl_vars['physician'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->load($_smarty_tpl->tpl_vars['data']->value['physician_id']), ENT_QUOTES, 'UTF-8');?>

		<?php }?>
		<td>Attending Physician:<br /> 
			<?php if ($_smarty_tpl->tpl_vars['user']->value==true){?>
			<input type="text" id="physician-search" value="<?php if ($_smarty_tpl->tpl_vars['data']->value['physician_id']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }elseif($_smarty_tpl->tpl_vars['data']->value['physician_name']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['physician_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?>" size="28" valign="top" /><?php if ($_smarty_tpl->tpl_vars['data']->value['physician_id']!=''){?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=edit&physician=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/edit.png" class="edit-item" /></a><?php }?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=add&type=physician&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="add-item" /></a>
			<input type="hidden" name="physician" id="physician" />
			<?php }else{ ?>
			<?php if ($_smarty_tpl->tpl_vars['data']->value['physician_id']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['physician']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }elseif($_smarty_tpl->tpl_vars['data']->value['physician_name']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['physician_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?>
			<?php }?>
		</td> 

		<?php if ($_smarty_tpl->tpl_vars['data']->value['ortho_id']!=''){?>
			<?php $_smarty_tpl->tpl_vars['ortho'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->load($_smarty_tpl->tpl_vars['data']->value['ortho_id']), ENT_QUOTES, 'UTF-8');?>

		<?php }?>
		<td>Orthopedic Surgeon:<br /> 
				<input type="text" id="ortho-search" size="28" value="<?php if ($_smarty_tpl->tpl_vars['data']->value['ortho_id']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }elseif($_smarty_tpl->tpl_vars['data']->value['surgeon_name']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['surgeon_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?>" /><?php if ($_smarty_tpl->tpl_vars['data']->value['ortho_id']!=''){?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=edit&physician=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ortho']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/edit.png" class="edit-item" /></a><?php }?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=add&type=surgeon&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="add-item" /></a>
				<input type="hidden" name="ortho" id="ortho" />
 		</td> 
	</tr> 
	
	<tr class="form-header-row"> 
		<?php if ($_smarty_tpl->tpl_vars['data']->value['doctor_id']!=''){?>
			<?php $_smarty_tpl->tpl_vars['doctor'] = new Smarty_variable(CMS_Physician::generate(), null, 0);?>
			<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['doctor']->value->load($_smarty_tpl->tpl_vars['data']->value['doctor_id']), ENT_QUOTES, 'UTF-8');?>

		<?php }?>
		<td>Primary Doctor:<br /> 
			<input valign="top" type="text" id="doctor-search" value="<?php if ($_smarty_tpl->tpl_vars['data']->value['doctor_id']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['doctor']->value->last_name, ENT_QUOTES, 'UTF-8');?>
, <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['doctor']->value->first_name, ENT_QUOTES, 'UTF-8');?>
 M.D.<?php }elseif($_smarty_tpl->tpl_vars['data']->value['doctor_name']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['doctor_name'], ENT_QUOTES, 'UTF-8');?>
<?php }?>" size="28" /><?php if ($_smarty_tpl->tpl_vars['data']->value['doctor_id']!=''){?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=edit&physician=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['doctor']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/edit.png" class="edit-item" /></a><?php }?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=physician&action=add&type=doctor&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="add-item" /></a>
			<input type="hidden" name="doctor" id="doctor"
		</td> 
		<td>Primary Doctor Phone:<br />
			<span class="normal-font">
			<?php if ($_smarty_tpl->tpl_vars['doctor']->value){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['doctor']->value->phone, ENT_QUOTES, 'UTF-8');?>
<?php }?>
			</span>
		</td>
		<td>Primary Doctor Fax:<br />
			<span class="normal-font">
			<?php if ($_smarty_tpl->tpl_vars['doctor']->value){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['doctor']->value->fax, ENT_QUOTES, 'UTF-8');?>
<?php }?>
			</span>
		</td>
	</tr>
	<tr class="form-header-row">
		<td>Hospital Room number:<br /> 
			<input valign="top" type="text" name="hospital_room" style="width:40px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['hospital_room'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td colspan="2">Pharmacy:<br />
			<?php if ($_smarty_tpl->tpl_vars['data']->value['pharmacy_id']!=''){?>
				<?php $_smarty_tpl->tpl_vars['pharmacy'] = new Smarty_variable(CMS_Pharmacy::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pharmacy']->value->load($_smarty_tpl->tpl_vars['data']->value['pharmacy_id']), ENT_QUOTES, 'UTF-8');?>

			<?php }?>
			<input type="text" id="pharmacy-search" value="<?php if ($_smarty_tpl->tpl_vars['data']->value['pharmacy_id']!=''){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pharmacy']->value->name, ENT_QUOTES, 'UTF-8');?>
<?php }?>" style="width: 232px;" valign="top" /><?php if ($_smarty_tpl->tpl_vars['data']->value['pharmacy_id']!=''){?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=pharmacy&action=edit&pharmacy=<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['pharmacy']->value->pubid, ENT_QUOTES, 'UTF-8');?>
&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/edit.png" class="edit-item" /></a><?php }?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=pharmacy&action=add&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="add-item" /></a>
			<input type="hidden" name="pharmacy" id="pharmacy" />
		</td>
		
	</tr> 
	
	<tr class="form-header-row"> 
		<td valign="top">Hospital Stay Dates:<br /> 
			<span class="normal-font">
			From:<br /><input type="text" name="hospital_date_start" class="date-picker date" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['data']->value['hospital_date_start'],"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
" style="width: 232px;" /><br /> 
			To:<br /><input type="text" name="hospital_date_end" class="date-picker date" style="width: 232px;" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['data']->value['hospital_date_end'],"%m/%d/%Y"), ENT_QUOTES, 'UTF-8');?>
" /> 
			</span>
		</td> 
		
		<td colspan="1">Billing Info:<br /> 
			<span class="normal-font">
			<input type="radio" name="paymethod" class="paymethod" id="paymethod-medicare" value="Medicare"<?php if ($_smarty_tpl->tpl_vars['data']->value['paymethod']=='Medicare'){?> checked<?php }?> />Medicare<br /> 
			<input type="radio" name="paymethod" class="paymethod" value="HMO"<?php if ($_smarty_tpl->tpl_vars['data']->value['paymethod']=='HMO'){?> checked<?php }?> />HMO<br /> 
			<input type="radio" name="paymethod" class="paymethod" value="Rugs"<?php if ($_smarty_tpl->tpl_vars['data']->value['paymethod']=='Rugs'){?> checked<?php }?> />Rugs<br /> 
			<input type="radio" name="paymethod" class="paymethod" value="Private"<?php if ($_smarty_tpl->tpl_vars['data']->value['paymethod']=='Private'){?> checked<?php }?> />Private Pay<br /> 
			</span>
		</td> 
		
		<td valign="top" colspan="2">3 Night Hospital Stay?<br /> 
			<span class="normal-font">
			<input type="radio" name="three_night" value="1"<?php if ($_smarty_tpl->tpl_vars['data']->value['three_night']==1){?> checked<?php }?> />Yes<br /> 
			<input type="radio" name="three_night" value="0"<?php if ($_smarty_tpl->tpl_vars['data']->value['three_night']===0){?> checked<?php }?> />No<br />	
			</span>
		</td> 
	</tr> 
	<tr class="form-header-row">
<!--
 		<td>ICD-9 Code:<br />
			<?php if ($_smarty_tpl->tpl_vars['data']->value['icd9_id']!=''){?>
				<?php $_smarty_tpl->tpl_vars['codes'] = new Smarty_variable(CMS_Icd9_Codes::generate(), null, 0);?>
				<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['codes']->value->load($_smarty_tpl->tpl_vars['data']->value['icd9_id']), ENT_QUOTES, 'UTF-8');?>

			<?php }?>
	 		<input type="text" style="width: 232px;" id="icd9-code-search" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['codes']->value->desc, ENT_QUOTES, 'UTF-8');?>
"} />
	 		<input type="hidden" name="icd9_code" id="icd9_code" />
 		</td>
-->
		<td>Chest X-Rays<br />
		<span class="normal-font">
		<input type="radio" name="x_rays_received" value="0"<?php if ($_smarty_tpl->tpl_vars['data']->value['x_rays_received']==0){?> checked<?php }?> /> Not Received
		<br />
		<input type="radio" name="x_rays_received" value="1"<?php if ($_smarty_tpl->tpl_vars['data']->value['x_rays_received']==1){?> checked<?php }?> /> Received
		</span>
		</td>
		<td>Toured<br />
		<span class="normal-font">
		<input type="radio" name="toured" value="0"<?php if ($_smarty_tpl->tpl_vars['_history']->value['toured']==0){?> checked<?php }?> /> No
		<br />
		<input type="radio" name="toured" value="1"<?php if ($_smarty_tpl->tpl_vars['data']->value['toured']==1){?> checked<?php }?> /> Yes
		</span>
		</td>
		<td>Medicare ID:<br /> 
			<input type="text" name="medicare_number" id="medicare_number" class="medicare-field" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['medicare_number'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<!--<td colspan="1">Medicare Days Used:<br /> 
			<input type="text" name="medicare_days_used" class="medicare-field" style="width: 30px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['medicare_days_used'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
		<td valign="top" colspan="2">Medicare Days Available:<br /> 
			<input type="text" name="medicare_days_available" class="medicare-field" style="width: 30px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['medicare_days_available'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td>  removed by kwh 2012-06-21 -->						
	</tr> 
	<tr  class="form-header-row">
		<td colspan="1">Supplemental Ins.:<br /> 
			<input type="text" name="supplemental_insurance_name" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['supplemental_insurance_name'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td colspan="1">Supplemental Ins. ID:<br /> 
			<input type="text" name="supplemental_insurance_number" style="width: 150px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['supplemental_insurance_number'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td></td>
	</tr>
	<tr class="form-header-row"> 
		<td>HMO / Insurance:<br /> 
			<input type="text" name="hmo_name" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['hmo_name'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
		<td>Authorization #:<br /> 
			<input type="text" name="hmo_auth_number" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['hmo_auth_number'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
		<td>HMO ID#:<br /> 
			<input type="text" name="hmo_number" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['hmo_number'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	</tr> 
	<tr class="form-header-row">
		<td>Patient Type:<br /><br />
			<input type="radio" name="patient_type" value="0" style="font-weight: normal" <?php if ($_smarty_tpl->tpl_vars['schedule']->value->long_term==0){?> checked<?php }elseif($_smarty_tpl->tpl_vars['schedule']->value->long_term==''){?> checked<?php }?> /><span class="normal-font">Short Term</span> &nbsp;&nbsp;
			<input type="radio" name="patient_type" value="1"  <?php if ($_smarty_tpl->tpl_vars['schedule']->value->long_term==1){?> checked<?php }?> /><span class="normal-font">Long Term</span>
		</td>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	
	
	
	
	<!-- Emergency Contact Info -->
	<tr>
		<th colspan="3">Emergency Contact &amp; Private Guarantor Info</th>
	</tr>
	<tr class="form-header-row"> 
		<td colspan="3"><strong>Emergency Contact #1:</strong></td>
	</tr>
	<tr class="form-header-row">
		<td>
			Name:<br /> 
			<input type="text" name="emergency_contact_name1" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_name1'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
		<td>Relationship:<br /> 
			<input type="text" name="emergency_contact_relationship1" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_relationship1'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td>Address:<br /> 
			<input type="text" name="emergency_contact_address1" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_address1'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	</tr>
	<tr>
		<td>Phone (primary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone1" id="emergency_contact_phone1" style="width: 150px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_phone1'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td>Phone (secondary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone_alt1" id="emergency_contact_phone_alt1" style="width: 150px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_phone_alt1'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td></td>
		<td></td>
	</tr> 
	<tr class="form-header-row"> 
		<td colspan="3"><strong>Emergency Contact #2:</strong></td>
	</tr>
	<tr class="form-header-row">
		<td>
			Name:<br /> 
			<input type="text" name="emergency_contact_name2" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_name2'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
		<td>Relationship:<br /> 
			<input type="text" name="emergency_contact_relationship2" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_relationship2'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td>Address:<br /> 
			<input type="text" name="emergency_contact_address2" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_address2'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	</tr>
	<tr class="form-header-row">
		<td>Phone (primary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone2" id="emergency_contact_phone2" style="width: 150px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_phone2'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td>Phone (secondary):<br /> 
			<input type="text" class="phone" name="emergency_contact_phone_alt2" id="emergency_contact_phone_alt2" style="width: 150px" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['emergency_contact_phone_alt2'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td></td>
		<td></td>
	</tr> 
	<tr class="form-header-row"> 
		<td colspan="3"><strong>Private Pay Guarantor:</strong></td>
	</tr>
	<tr class="form-header-row">
		<td>Name: <br />
			<input type="text" name="private_pay_guarantor_name" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['private_pay_guarantor_name'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		
		<td>Relationship<br /> 
			<input type="text" name="private_pay_guarantor_relationship" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['private_pay_guarantor_relationship'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 

		<td>Address<br /> 
			<input type="text" name="private_pay_guarantor_address" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['private_pay_guarantor_address'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
	</tr>
	<tr class="form-header-row">
		<td>Phone<br /> 
			<input type="text" class="phone" name="private_pay_guarantor_phone" style="width: 232px;" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['private_pay_guarantor_phone'], ENT_QUOTES, 'UTF-8');?>
" /> 
		</td> 
		<td></td>
		<td></td>
	</tr> 
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	
	
	
	
	
	<tr>
		<th colspan="3">Admission Info &amp; Discharge Plan</th>
	</tr>
	
	<tr class="form-header-row"> 
		<td colspan="3">Admission Diagnosis:<br /> 
			<textarea name="other_diagnosis" rows="5" cols="80"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['other_diagnosis'], ENT_QUOTES, 'UTF-8');?>
</textarea><br /> 
		</td> 
	</tr>
	<tr>
		<td><input type="checkbox" id="elective" name="elective" value="1"<?php if ($_smarty_tpl->tpl_vars['schedule']->value->elective==1){?> checked<?php }?> /> Patient is an elective surgery</td>
	</tr>
	<tr>
		<tr class="form-header-row">
		<td colspan="3">Discharge Plan:<br /> 
			<textarea name="discharge_plan" rows="5" cols="80"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['discharge_plan'], ENT_QUOTES, 'UTF-8');?>
</textarea><br /> 
		</td> 
	</tr> 
	<tr>
		<td style="padding-top: 20px;"><input type="checkbox" name="scheduled_home_health" id="scheduled-home-health" value="1"<?php if ($_smarty_tpl->tpl_vars['patient']->value->scheduled_home_health==1){?> checked<?php }?>  />Pre-scheduled Home Health</td>
	</tr>
	<tr>
	<?php if ($_smarty_tpl->tpl_vars['patient']->value->scheduled_home_health){?>
		<?php $_smarty_tpl->tpl_vars['homeHealth'] = new Smarty_variable(CMS_Hospital::generate(), null, 0);?>
		<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['homeHealth']->value->load($_smarty_tpl->tpl_vars['schedule']->value->home_health_id), ENT_QUOTES, 'UTF-8');?>

	<?php }?>
		<td id="home-health-field" colspan="3">
			<input type="text" id="homehealth-search" style="width: 300px;" size="30" placeholder="Enter the name of the home health agency" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['homeHealth']->value->name, ENT_QUOTES, 'UTF-8');?>
" /><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/?page=hospital&action=add&type=Home%20Health&isMicro=1" rel="shadowbox;width=550;height=450"><img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
/images/add.png" class="new-admit-add-item" /></a>
			<input type="hidden" name="home_health" id="home-health" />
		</td>
	</tr>


	
	<tr class="form-header-row"> 
		<td colspan="3">Comments:<br /> 
			<textarea name="comments" rows="5" cols="80"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['comments'], ENT_QUOTES, 'UTF-8');?>
</textarea><br /> 
		</td> 
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php if ($_smarty_tpl->tpl_vars['schedule']->value->elective){?>
	<tr>
		<td colspan="3" align="left">
			<input type="checkbox" name="confirmed" value="1"<?php if ($_smarty_tpl->tpl_vars['schedule']->value->confirmed==1){?> checked<?php }?> /> Elective admit has been confirmed 
		</td>
	</tr>
	<?php }?>
	
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('jQueryReady', array()); $_block_repeat=true; echo smarty_jQueryReady(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

		
		if ($("#discharge-summary").attr("checked")) {
			$("#discharge-summary-date-row").show();
		} else {
			$("#discharge-summary-date-row").hide();
		}
		
		$("#discharge-summary").change(function() {
			if ($("#discharge-summary").attr("checked")) {
				$("#discharge-summary-date-row").show();
			} else {
				$("#discharge-summary-date-row").hide();
			}
		});
		
		$(".schedule-datetime").datetimepicker({
			timeFormat: "hh:mm tt",
			stepMinute: 15,
			hour: 13,	
		});
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_jQueryReady(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	<tr>
		<td><input type="checkbox" name="discharge_summary" id="discharge-summary" <?php if ($_smarty_tpl->tpl_vars['data']->value['datetime_dc_summary']!=''){?> checked<?php }?> /> Discharge Summary received from Hospital</td>
	</tr>
	<tr id="discharge-summary-date-row">
		<td align="right"><strong>Date & Time Received:</strong> <input class="schedule-datetime" id="discharge-summary-date" name="datetime_dc_summary" value="<?php echo htmlspecialchars(smarty_modifier_date_format($_smarty_tpl->tpl_vars['data']->value['datetime_dc_summary'],"%m%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
" /></td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			<input type="checkbox" name="referral" value="1"<?php if ($_smarty_tpl->tpl_vars['data']->value['referral']==1){?> checked<?php }?> /> Yes, referral was received from hospital 
		</td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			<input type="checkbox" name="final_orders" value="1"<?php if ($_smarty_tpl->tpl_vars['data']->value['final_orders']==1){?> checked<?php }?> /> Yes, final orders have been received 
		</td>
	</tr>
<!-- 		<br />
		<br />
		<div style="float: right;"><input type="submit" value="Save" /></div> -->
	<?php if ($_smarty_tpl->tpl_vars['mode']->value=='edit'){?>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr> 
		<td colspan="3" style="text-align: right; margin-right: 5px;"> 
			<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['SITE_URL']->value, ENT_QUOTES, 'UTF-8');?>
" style="margin-right: 8px;" class="button">Cancel</a> 
		</td> 
		<td colspan="2"> 
			<input type="submit" style="float: right" id="submit-button" value="Submit" /> 
		</td> 
	</tr>
	<tr> 
			</tr>  
	<?php }?>	
	</table> 
	
</form><?php }} ?>