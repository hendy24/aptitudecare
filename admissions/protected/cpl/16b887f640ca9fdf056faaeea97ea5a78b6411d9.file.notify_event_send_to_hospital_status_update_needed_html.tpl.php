<?php /* Smarty version Smarty-3.1.13, created on 2016-02-08 10:38:01
         compiled from "/home/aptitude/dev/protected/tpl_email/notify_event_send_to_hospital_status_update_needed_html.tpl" */ ?>
<?php /*%%SmartyHeaderCode:194535042356b8d27994e876-30333167%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '16b887f640ca9fdf056faaeea97ea5a78b6411d9' => 
    array (
      0 => '/home/aptitude/dev/protected/tpl_email/notify_event_send_to_hospital_status_update_needed_html.tpl',
      1 => 1399485577,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '194535042356b8d27994e876-30333167',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'recip_list' => 0,
    'facility' => 0,
    'SITE_URL' => 0,
    'schedule' => 0,
    'ahr' => 0,
    'url' => 0,
    'atHospitalRecord' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56b8d279a04f65_71862536',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56b8d279a04f65_71862536')) {function content_56b8d279a04f65_71862536($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/aptitude/cms2/protected/lib/contrib/Smarty-3.1.13/libs/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>

<p>This email is intended for: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['recip_list']->value, ENT_QUOTES, 'UTF-8');?>
</p>

<table width="90%" border="0">

<tr>
	<td width="100%" style="padding: 10px 0 10px 20px;">

        The status of a hospital stay for a patient at <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['facility']->value->name, ENT_QUOTES, 'UTF-8');?>
 needs to be updated:<br />
        <br />
        Please click for full details:<br />
        <?php $_smarty_tpl->tpl_vars['url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['SITE_URL']->value)."/?page=facility&action=sendToHospital&schedule=".((string)$_smarty_tpl->tpl_vars['schedule']->value->pubid)."&ahr=".((string)$_smarty_tpl->tpl_vars['ahr']->value->pubid), null, 0);?>
		<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url']->value, ENT_QUOTES, 'UTF-8');?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url']->value, ENT_QUOTES, 'UTF-8');?>
</a>
		<p>Details of this hospital stay are below:</p>
		
		<?php $_smarty_tpl->tpl_vars['ahr'] = new Smarty_variable($_smarty_tpl->tpl_vars['atHospitalRecord']->value, null, 0);?>
			<table width="100%">
				<tr>
					<td valign="top"><strong><u>Admitted to AHC</u></strong></td>
		<td valign="top"><?php echo htmlspecialchars(smarty_modifier_date_format(strtotime($_smarty_tpl->tpl_vars['schedule']->value->datetime_admit),"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>
<tr>
		<td valign="top"><strong><u>Room</u></strong></td>
		<td valign="top"><?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['schedule']->value->getRoom()->number)===null||$tmp==='' ? "<i>Unspecified</i>" : $tmp), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>
<tr>
		<td valign="top"><strong><u>Discharge entered by:</u></strong></td>
		<td valign="top"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->dischargeNurse()->getFullName(), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>
<tr>
		<td valign="top"><strong><u>Sent at:</u></strong></td>
		<td valign="top"><?php echo htmlspecialchars(smarty_modifier_date_format(strtotime($_smarty_tpl->tpl_vars['ahr']->value->datetime_sent),"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>
<tr>
		<td valign="top"><strong><u>Hospital:</u></strong></td>
		<td valign="top"><?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['ahr']->value->hospital_name)===null||$tmp==='' ? "<i>Not specified</i>" : $tmp), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>
<tr>
		<td valign="top"><strong><u>Hospital Contact:</u></strong></td>
		<td valign="top"><?php if ($_smarty_tpl->tpl_vars['ahr']->value->hospital_contact_name!=''||$_smarty_tpl->tpl_vars['ahr']->value->hospital_contact_phone!=''){?>
		<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->hospital_contact_name, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ahr']->value->hospital_contact_phone, ENT_QUOTES, 'UTF-8');?>

		<?php }else{ ?>
		<i>Not specified</i>
		<?php }?>
		</td>
</tr>
<tr>
		<td valign="top"><strong><u>Reason</u></strong></td>
		<td valign="top"><?php echo htmlspecialchars((($tmp = @$_smarty_tpl->tpl_vars['ahr']->value->comment)===null||$tmp==='' ? "<i>Unspecified</i>" : $tmp), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>
<tr>
		<td valign="top"><strong><u>Bed-Hold?</u></strong>
		<td valign="top"><?php if ($_smarty_tpl->tpl_vars['ahr']->value->bedhold_offered==1){?>Will discharge from AHC at <?php echo htmlspecialchars(smarty_modifier_date_format(strtotime($_smarty_tpl->tpl_vars['ahr']->value->datetime_bedhold_end),"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
<?php }else{ ?><i>No</i><?php }?></td>
</tr>
<tr>
		<td valign="top"><strong><u>Admitted?</u></strong>
		<td valign="top"><?php if ($_smarty_tpl->tpl_vars['ahr']->value->was_admitted==1){?>Yes<?php }else{ ?>No<?php }?></td>
</tr>
<tr>
		<td valign="top"><strong><u>Updated</u></strong>
		<td valign="top"><?php echo htmlspecialchars(smarty_modifier_date_format(strtotime($_smarty_tpl->tpl_vars['ahr']->value->datetime_updated),"%m/%d/%Y %I:%M %P"), ENT_QUOTES, 'UTF-8');?>
</td>
</tr>				
</table>
        
        
        <br />

	</td>
</tr>

</table>

</body>
</html><?php }} ?>