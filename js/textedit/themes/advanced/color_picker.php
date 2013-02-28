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
	<title>{#advanced_dlg.colorpicker_title}</title>
	<script type="text/javascript" src="<?php echo $mce;?>tiny_mce_popup.js?ver=349-20120314"></script>
	<script type="text/javascript" src="<?php echo $mce;?>utils/mctabs.js?ver=349-20120314"></script>
	<script type="text/javascript" src="js/color_picker.js?ver=349-20120314"></script>
</head>
<body id="colorpicker" style="display: none" role="application" aria-labelledby="app_label">
	<span class="mceVoiceLabel" id="app_label" style="display:none;">{#advanced_dlg.colorpicker_title}</span>
<form onsubmit="insertAction();return false" action="#">
	<div class="tabs">
		<ul>
			<li id="picker_tab" aria-controls="picker_panel" class="current"><span><a href="javascript:mcTabs.displayTab('picker_tab','picker_panel');" onmousedown="return false;">{#advanced_dlg.colorpicker_picker_tab}</a></span></li>
			<li id="rgb_tab" aria-controls="rgb_panel"><span><a href="javascript:;" onclick="mcTabs.displayTab('rgb_tab','rgb_panel');" onmousedown="return false;">{#advanced_dlg.colorpicker_palette_tab}</a></span></li>
			<li id="named_tab" aria-controls="named_panel"><span><a  href="javascript:;" onclick="javascript:mcTabs.displayTab('named_tab','named_panel');" onmousedown="return false;">{#advanced_dlg.colorpicker_named_tab}</a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper">
		<div id="picker_panel" class="panel current">
			<fieldset>
				<legend>{#advanced_dlg.colorpicker_picker_title}</legend>
				<div id="picker">
					<img id="colors" src="img/colorpicker.jpg" onclick="computeColor(event)" onmousedown="isMouseDown = true;return false;" onmouseup="isMouseDown = false;" onmousemove="if (isMouseDown && isMouseOver) computeColor(event); return false;" onmouseover="isMouseOver=true;" onmouseout="isMouseOver=false;" alt="" />

					<div id="light">
						<!-- Will be filled with divs -->
					</div>

					<br style="clear: both" />
				</div>
			</fieldset>
		</div>

		<div id="rgb_panel" class="panel">
			<fieldset>
				<legend id="webcolors_title">{#advanced_dlg.colorpicker_palette_title}</legend>
				<div id="webcolors">
					<!-- Gets filled with web safe colors-->
				</div>

				<br style="clear: both" />
			</fieldset>
		</div>

		<div id="named_panel" class="panel">
			<fieldset id="named_picker_label">
				<legend id="named_title">{#advanced_dlg.colorpicker_named_title}</legend>
				<div id="namedcolors" role="listbox" tabindex="0" aria-labelledby="named_picker_label">
					<!-- Gets filled with named colors-->
				</div>

				<br style="clear: both" />

				<div id="colornamecontainer">
					{#advanced_dlg.colorpicker_name} <span id="colorname"></span>
				</div>
			</fieldset>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="submit" id="insert" name="insert" value="{#apply}" />
		</div>

		<div id="preview"></div>

		<div id="previewblock">
			<label for="color">{#advanced_dlg.colorpicker_color}</label> <input id="color" type="text" size="8" class="text mceFocus" aria-required="true" />
		</div>
	</div>
</form>
</body>
</html>
