<?php
  require_once( '../../../../../wp-load.php' );    
  $mce = includes_url() . 'js/tinymce/';
  header('Content-Type: text/html; charset=' . get_bloginfo('charset'));  
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title>Text Editing and Formatting</title>
  <link href="css/template.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="<?php echo $mce;?>tiny_mce_popup.js"></script>
  <script type="text/javascript" src="<?php echo $mce;?>wp-tinymce.php"></script>  
  <script type="text/javascript">
    tinyMCE.init({    
        mode : "none",        
        theme : "advanced",                        
        theme_advanced_buttons1 : "fontselect,fontsizeselect,forecolor,backcolor,|,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_path : false,    
        invalid_elements : 'p',
        cleanup : true,
        force_br_newlines : true,
        force_p_newlines : false,   
        forced_root_block : '',
        remove_redundant_brs : false,
        remove_linebreaks : true,
        theme_advanced_font_sizes : "8pt,10pt,12pt,14pt,18pt,24pt,30pt,36pt,48pt,60pt,72pt",
        content_css : "css/content.css",
        setup : function( ed ) {        
        ed.onInit.add( function(ed, e) {
        ed.dom.hide('mceDeleteObj');        
        });
        }    
    });    
    function is_numtext_width( id ) {
       var dom = tinyMCE.activeEditor.dom, vl = 0; 
       var n = document.getElementById(id);
       if (isNaN(parseFloat(n.value)) && !isFinite(n.value)) {
           n.value = '';                           
       } else {
           if ( parseInt(n.value) > 100  ) {
           n.value = '';
           document.getElementById('statwidth').innerHTML = 'Width is over 100%';
           return false;           
           }            
       }
       
       vl = n.value;       
       txted = dom.get('templayer');
              
       if ( vl == '100' ) {
        vl = 98;
        TextEditDialog.isw100 = true;
       }
                            
       dom.setStyle(txted, 'width', ((vl=='')?'': vl + '%'));
    }
    
    function is_load() {
    //loading    
    setTimeout(function(){
    document.getElementById('mce_textedit').style.display = 'inline';
    tinyMCE.execCommand( 'mceAddControl', false, 'mce_textedit' );
    document.getElementById('loading').style.display = 'none';
    document.getElementById('clearb').style.display = 'none';    
    }, 2000);
    }
    
    window.onload = is_load();
	</script>  
</head>
<body> 
	<form onsubmit="TextEditDialog.insert();return false;">
    <p>Textbox Width : <input type="text" name="wlayer" id="wlayer" size="10" onfocus="document.getElementById('statwidth').innerHTML='';" onblur="is_numtext_width(this.id);"/>&nbsp;%&nbsp;<span id="statwidth" style="color:red;"></span></p> 
    <div>    
    <div class="loading" id="loading"><em>Editor Loading</em></div><div id="clearb" style="clear:both;"></div>
    <textarea id="mce_textedit" name="mce_textedit" cols="118" rows="16" style="display:none;"></textarea>  
    </div>
		<div class="mceActionPanel">
			<input type="submit" id="insert" name="apply" value="Update" />
			<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
		</div>
	</form>
  <script type="text/javascript" src="js/textedit.js"></script>
</body> 
</html> 
