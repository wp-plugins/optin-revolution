<?php
  require_once( '../../../../../../../wp-load.php' );
  @define( 'WP_CACHE', false ); 
  @define('DONOTCACHEPAGE', true);
  @define('DONOTCACHEDB', true);
  @define('DONOTMINIFY', true);
  @define('DONOTCDN', true);
  @define('DONOTCACHCEOBJECT', true);      
  $mce = includes_url() . 'js/tinymce/';
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Insert/Edit Link</title>  
	<script type="text/javascript" src="<?php echo $mce;?>tiny_mce_popup.js?ver=349-20120314"></script>
	<script type="text/javascript" src="<?php echo $mce;?>utils/mctabs.js?ver=349-20120314"></script>
	<script type="text/javascript" src="<?php echo $mce;?>utils/form_utils.js?ver=349-20120314"></script>
	<script type="text/javascript" src="<?php echo $mce;?>utils/validate.js?ver=349-20120314"></script>
	<script type="text/javascript" src="js/link.js?ver=349-20120314"></script>
</head>
<body id="link" style="display: none">
<form onsubmit="LinkDialog.update();return false;" action="#">
	<div class="tabs">
		<ul>
			<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">{#advanced_dlg.link_title}</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper">
		<div id="general_panel" class="panel current">
			<table border="0" cellpadding="4" cellspacing="0">
				<tr>
					<td class="nowrap"><label for="href">{#advanced_dlg.link_url}</label></td>
					<td><table border="0" cellspacing="0" cellpadding="0"> 
						<tr> 
							<td><input id="href" name="href" type="text" class="mceFocus" value="" style="width: 200px" onchange="LinkDialog.checkPrefix(this);" /></td> 
							<td id="hrefbrowsercontainer">&nbsp;</td>
						</tr> 
					</table></td>
				</tr>
				<tr>
					<td><label for="link_list">{#advanced_dlg.link_list}</label></td>
					<td><select id="link_list" name="link_list" onchange="document.getElementById('href').value=this.options[this.selectedIndex].value;"></select></td>
				</tr>
				<tr>
					<td><label id="targetlistlabel" for="targetlist">{#advanced_dlg.link_target}</label></td>
					<td><select id="target_list" name="target_list"></select></td>
				</tr>
				<tr>
					<td class="nowrap"><label for="linktitle">{#advanced_dlg.link_titlefield}</label></td>
					<td><input id="linktitle" name="linktitle" type="text" value="" style="width: 200px" /></td>
				</tr>        
			</table>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
		</div>
	</div>
</form>
</body>
</html>
