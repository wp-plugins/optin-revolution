<?php
/** Make sure that the WordPress bootstrap has run before continuing. */      
require_once( '../../../wp-load.php' );  
require_once( 'optinrev-fn.php' );

$get_optin = (isset( $_GET['popup'] )) ? htmlentities($_GET['popup']) : 'optin1';
$is_view = (isset( $_GET['view'] )) ? htmlentities($_GET['view']) : '';

$optin = optinrev_get( $get_optin );
$dir = plugin_dir_url( __FILE__ );

if ( $optin ) {
   $optin = unserialize( $optin );
  } else {
  //load default
  $optin = array(
    'optinrev_round_border' => '',    
    'optinrev_wbg_color' => '#FFFFFF',
    'optinrev_wbg_opacity' => 0,
    'optinrev_border_opacity' => 0,
    'optinrev_pwbg_color' => '#FFFFFF',
    'optinrev_border_color' => '#000000',
    'optinrev_border_radius' => 0,
    'optinrev_border_thickness' => 1,    
    'optinrev_top_margin' => 50,
    'optinrev_wwidth' => 700,
    'optinrev_hheight' => 400,
    'optinrev_delay' => 0,
    'optinrev_close_popup_image' => 'close1',
    'optinrev_close_button' => 'top:230px;right:230px;',
    'optinrev_link_color' => '#1122CC',
    'optinrev_link_underline' => ''    
   ); 
}

//dynamic close button
$ww = $optin['optinrev_wwidth'];
$wh = $optin['optinrev_hheight'];
$bw = $optin['optinrev_border_thickness'] - 1;
//goto website button
$loc = ( $optin['optinrev_gotowebsite'] == 'bottom' ) ? ( $wh - 56 ) : 20;

$close_btn = array(
  'close1' => array( ($ww - ( 30 / 2 ) ) + $bw, -( 30/2 ) - $bw ),
  'close2' => array( ($ww - ( 45 / 2 ) ) + $bw, -( 45/2 ) - $bw ),
  'close3' => array( ($ww - ( 60 / 2 ) ) + $bw, -( 60/2 ) - $bw ),
  'close4' => array( ($ww - ( 30 / 2 ) ) + $bw, -( 30/2 ) - $bw ),
  'close5' => array( ($ww - ( 45 / 2 ) ) + $bw, -( 45/2 ) - $bw ),
  'close6' => array( ($ww - ( 60 / 2 ) ) + $bw, -( 60/2 ) - $bw ),
  'close7' => array( $ww - 272, $loc ),
  'close8' => array( $ww - 272, $loc ),
  );
  
$lpos = $close_btn[ $optin['optinrev_close_popup_image'] ];
$close_btn = 'left:'. $lpos[0] .'px;top:'. $lpos[1] .'px;';

$top_margin = ( !$is_view ) ? 'margin-top:45px;' : '';
$li_padding = ( $is_view ) ? 'line-height: 35px !important;' : '';

//IE only
$border_color = (is_ie()) ? $optin['optinrev_border_color'] : 'rgba('. implode(',',hex2dec($optin['optinrev_border_color'])) .','. ($optin['optinrev_border_opacity']/100) .')';
$htc = (is_ie() && $is_view) ? 'behavior: url('. $dir .'css/PIE.htc);':'';  

$unrem_css = '
img.wotimg {border: 1px dashed #888 !important;}
.mceImageDrag {padding:0px !important;position:absolute;z-index:9999;}
#close {position:absolute;z-index:1001;text-decoration:none;border: 1px solid transparent;}
.mceImageSelect {position:relative;padding:0px !important;border: 1px dashed #888 !important;}
.mceWotlayer {border: 1px dashed #888 !important;z-index:999;padding:0px !important;margin:0px !important;}
';
if ( $is_view ) $unrem_css = ''; 

$view_cleaned = '
#zindex {position:absolute;width:50px;padding:0px;font-size:9px;font-weight:normal;background-color:#f0f0f0;color:#404040;padding:2px}
#imglabel {position:absolute;left:0px;bottom:0px;padding:0px;font-size:9px;font-weight:normal;background-color:red;color:#ffffff;padding:0px 2px 0px 2px;}
#mceWotmove {position:absolute;right:-1px;bottom:-1px;width:14px;height:14px; background: url('.$dir.'images/cursor_drag_arrow.png) no-repeat center center;cursor:move;background-color:#fff;z-index:99999;}
#mceDeleteObj {position:absolute;left:-1px;top:-12px;width:12px;height:12px; background: url('.$dir.'images/delete-ic.png) no-repeat center center;cursor:pointer;background-color:#fff;z-index:99999;}
';
if ( $is_view ) $view_cleaned = '';
//is rounded border
$optin['optinrev_border_radius'] = ( $optin['optinrev_round_border'] == 'on' ) ? $optin['optinrev_border_radius'] : '0';

$form_css = ( $is_view ) ? ( ( isset($optin['optinrev_email_form_css']) ) ? stripcslashes($optin['optinrev_email_form_css']) : '') : '';

$wm_hand = ( $is_view ) ? '#simplemodal-container #wm {cursor:pointer;}' : '';

$pwd_color = ( !$is_view ) ? 'black' : getContrast50( $optin['optinrev_wbg_color'] );

//resizable
$resizable = '
.ui-icon { width: 18px; height: 18px; background-image: url('.$dir.'images/hgrip.png); }
.ui-icon-gripsmall-diagonal-se { background-repeat: no-repeat; background-position: left top; }
.ui-icon-gripsmall-diagonal-se:hover { background-repeat: no-repeat; background-position: left -18px; }
.ui-resizable-handle { position: absolute;font-size: 0.1px; z-index: 99999; display: block; cursor: se-resize; }
.ui-resizable-disabled .ui-resizable-handle, .ui-resizable-autohide .ui-resizable-handle { display: none; }
.ui-resizable-se { cursor: se-resize; width: 18px; height: 18px; right: -8px;bottom: -8px;}
';

if ( $is_view ) $resizable = '';

$lnk_under = 'none';
if ( isset( $optin['optinrev_link_underline'] ) )
$lnk_under = ( $optin['optinrev_link_underline'] == 'on' ) ? 'underline' : 'none';

$lnk_color = ( isset($optin['optinrev_link_color']) ) ? $optin['optinrev_link_color'] : '';

$mcebody = ( !$is_view ) ? '#tinymce {overflow:hidden !important;} #tinymce a:link, #tinymce a:visited, #tinymce a:hover, #tinymce a:active {color:'.$lnk_color.';text-decoration:'.$lnk_under.';}' : '';

//Close button
$active_close_button = '#simplemodal-container .close1 {background:url('.$dir.'images/close1b.png) no-repeat; width:39px; height:39px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';
if ( $is_view ) {
if ( isset( $optin['optinrev_close_popup_image'] ) && $sbtn = $optin['optinrev_close_popup_image'] ) {
    switch( $sbtn ) {
    case 'close1':
    $active_close_button = $active_close_button;     
    break;
    case 'close2':
    $active_close_button = '#simplemodal-container .close2 {background:url('.$dir.'images/close2b.png) no-repeat; width:52px; height:52px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;
    case 'close3':
    $active_close_button = '#simplemodal-container .close3 {background:url('.$dir.'images/close3b.png) no-repeat; width:62px; height:62px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;
    case 'close4':
    $active_close_button = '#simplemodal-container .close4 {background:url('.$dir.'images/close1r.png) no-repeat; width:39px; height:39px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;
    case 'close5':
    $active_close_button = '#simplemodal-container .close5 {background:url('.$dir.'images/close2r.png) no-repeat; width:52px; height:52px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;
    case 'close6':
    $active_close_button = '#simplemodal-container .close6 {background:url('.$dir.'images/close3r.png) no-repeat; width:62px; height:62px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;
    case 'close7':
    $active_close_button = '#simplemodal-container .close7 {background:url('.$dir.'images/btn1.png) no-repeat; width:263px; height:47px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;        
    case 'close8':
    $active_close_button = '#simplemodal-container .close8 {background:url('.$dir.'images/btn2.png) no-repeat; width:263px; height:47px;'.$close_btn.'display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}';    
    break;            
    }   
}} else {
$active_close_button = <<<CLOSE_BTN
#simplemodal-container .close1 {background:url({$dir}images/close1b.png) no-repeat; width:39px; height:39px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close2 {background:url({$dir}images/close2b.png) no-repeat; width:52px; height:52px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close3 {background:url({$dir}images/close3b.png) no-repeat; width:62px; height:62px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close4 {background:url({$dir}images/close1r.png) no-repeat; width:39px; height:39px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close5 {background:url({$dir}images/close2r.png) no-repeat; width:52px; height:52px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close6 {background:url({$dir}images/close3r.png) no-repeat; width:62px; height:62px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close7 {background:url({$dir}images/btn1.png) no-repeat; width:263px; height:47px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
#simplemodal-container .close8 {background:url({$dir}images/btn2.png) no-repeat; width:263px; height:47px;{$close_btn}display:inline; z-index:3200; position:absolute;cursor:pointer;text-decoration:none;}
CLOSE_BTN;
}

ob_start();
header('Content-Type: text/css; charset=utf-8');
header("cache-control: must-revalidate");
$offset = 60 * 7200;
$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire);

$modal = <<<LOAD_CSS
#simplemodal-overlay {background-color:{$optin['optinrev_wbg_color']};z-index: 9999 !important;}
#simplemodal-container {position:absolute;{$top_margin}height:{$optin['optinrev_hheight']}px;width:{$optin['optinrev_wwidth']}px;background-color:{$optin['optinrev_pwbg_color']};border:{$optin['optinrev_border_thickness']}px solid {$border_color};-moz-border-radius: {$optin['optinrev_border_radius']}px;-webkit-border-radius: {$optin['optinrev_border_radius']}px;border-radius: {$optin['optinrev_border_radius']}px;-khtml-border-radius:{$optin['optinrev_border_radius']}px;{$htc}z-index: 9999 !important;}
#simplemodal-container img {padding:0px;border:none;-webkit-appearance: none;-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;}
#simplemodal-container .simplemodal-data a:link, 
#simplemodal-container .simplemodal-data a:visited, 
#simplemodal-container .simplemodal-data a:hover 
#simplemodal-container .simplemodal-data a:active {color:{$lnk_color};text-decoration:{$lnk_under};}  
#simplemodal-container .simplemodal-data span {line-height:110% !important;margin:0px !important;padding:0px !important;}
#simplemodal-container .simplemodal-data ul {padding:0px;margin:0px;}
#simplemodal-container .simplemodal-data ul li {list-style: disc inside !important;{$li_padding}}
#simplemodal-container .simplemodal-data {font-family: arial;font-size: 12px;}
{$active_close_button}
{$unrem_css}
{$wm_hand}
{$view_cleaned}
{$resizable}
{$mcebody}
#no_thanks_btn {cursor:pointer;display:none;position:absolute;width: 263px; height: 47px;background: url({$dir}images/no-thanks.png) no-repeat left top;z-index:999999;}
#poweredby {cursor:pointer;color: {$pwd_color} !important;text-decoration:none !important;}
#poweredby:hover {text-decoration:underline;}
LOAD_CSS;
echo optinrev_cssminify($modal);
ob_end_flush();
?>