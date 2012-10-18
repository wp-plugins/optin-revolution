<?php
/**
 * @package Optin Revolution
 * @version 1.0
 */

/*
  Plugin Name: Optin Revolution
  Plugin URI: http://wordpress.org/extend/plugins/optin-revolution/
  Description: Optin Revolution is a WordPress popup plugin is quite possibly the best way in the world for you to create supercharged unblockable popups to grow your list of subscribers! To get started: 1) Click the "Activate" link to the left of this description, 2) Go to your Optin Revolution settings page, and 3) Watch the video on the settings page which will show you how to get started creating your cool popups.
  Author: Optin Revolution
  Version: 1.0.2
  Author URI: http://optinrevolution.com/
  License: GPL2+
*/

if (!session_start()) {
 session_start();
}

require_once('optinrev-fn.php');

global 
$optinrev_db_version, 
$optinrev_installed_version, 
$plugin_name, 
$plugin_url,
$wp_version;   
//init
$plugin_name = 'optin-revolution/optinrev.php';
$optinrev_db_version = '1.0';
$optinrev_installed_version = '1.0.2';
$wp_version = get_bloginfo('version');

function optinrev_admin_actions()
{
  global $submenu;
      
  add_utility_page( _('Optin Revolution'), _('Optin Revolution'), 0, 'optin', 'optinrev_admin' );
  
  $optin = optinrev_popups();
  foreach( $optin as $ok => $ov )
  add_submenu_page( 'optin', __( $ov ), __( $ov ), 0, $ok, 'optinrev_setup' );
  //submenu list
  add_submenu_page( 'optin', 'Optin Revolution <code>Pro</code>', 'Optin Revolution <code>Pro</code>', 0, 'optin-pro-settings', 'optinrev_pro' );
  add_submenu_page( '', __(''), __(''), 0, 'browser-issue', 'optinrev_browser_alert' );  
        
  //changed name    
  $submenu['optin'][0][0] = __('Settings');
}

add_action( 'admin_menu', 'optinrev_admin_actions' );

function optinrev_plugin_admin_init()
{  
 global $plugin_page;
  
 $dir = plugin_dir_url( __FILE__ );
 
  //Clear all cookies
  if ( isset($_GET['cookies']) && $cls_cookies = esc_html( $_GET['cookies'] ) ) {
      if ( $cls_cookies === 'clear' ) {            
          foreach ( $_COOKIE as $key => $value ) {
          setcookie( $key, $value, time() - 3600, '/' );
          }
          update_option('optinrev_cookies', 'cleaned');
          wp_redirect( 'admin.php?page=optin' ); exit;
      }
  }
 
  // Browser Issue Alert
  if ( $plugin_page !== 'browser-issue' )
  {
      //check ie version
      if ( preg_match( '/(?i)msie [1-7]/',$_SERVER['HTTP_USER_AGENT'] ) )
      { 
          wp_redirect( 'admin.php?page=browser-issue' ); exit;        
      }
  }
  
  //enabled
  if (isset( $_GET['enable'] )) {
  optinrev_update('optinrev_popup_enabled', 'true');
  wp_redirect( 'admin.php?page='. $plugin_page ); exit;
  }
  
  //optin
  if ( $plugin_page == 'optin' )
  {
    //enabled
    if (isset( $_GET['enable'] )) {
    optinrev_update('optinrev_popup_enabled', 'true');
    }
    optinrev_enqueue(0);
    
    
  } else if ( preg_match('/optin[1-5]/', $plugin_page) )
  { 
    optinrev_enqueue(1);    
    //wotoptin images
   if ( !optinrev_get( 'optinrev_default_images' ) ) {
      $wot_imgs = array(
        'get_access' => array( 'get_access1' => $dir . 'images/get_access1.png', 'get_access2' => $dir . 'images/get_access2.png', 'get_access3' => $dir . 'images/get_access3.png'),
        'close_btn' => array( 'close1' => $dir . 'images/close1b.png', 'close2' => $dir . 'images/close2b.png', 'close3' => $dir . 'images/close3b.png', 'close4' => $dir . 'images/close1r.png', 'close5' => $dir . 'images/close2r.png', 'close6' => $dir . 'images/close3r.png', 'close7' => $dir . 'images/btn1.png', 'close8' => $dir . 'images/btn2.png' )
      );
      optinrev_update( 'optinrev_default_images', serialize( $wot_imgs ) );  
      //init get access button  
      foreach( $wot_imgs['get_access'] as $km => $vm ) {            
        optinrev_update( 'optinrev_cuid_'. optinrev_unique_id() , $km .'.png|'. $km );
      }
   }

   //wotoptin uploaded images
   if ( !optinrev_get( 'optinrev_default_uploads_images' ) ) {
      $wot_imgs = array( 'arrow-animated' => $dir . 'assets/arrow-animated.gif', 'optin-box' => $dir . 'assets/optin-box.png');      
      optinrev_update( 'optinrev_default_uploads_images', serialize( $wot_imgs ) );  
      //init get access button  
      foreach( $wot_imgs as $km => $vm ) {      
        list($txt, $ext) = explode( '.', basename($vm) );        
        optinrev_update( 'optinrev_uid_'. optinrev_unique_id(), basename($vm) . '|' . basename($vm) );
      }
   }   
   
   //default content
   if ( !optinrev_get( 'optinrev_default' ) ) {
        $default_optin = array(
            'action' => 'optinrev_action',
            'save_setup_settings' => 'optin1',
            'optinrev_data' => '<div style="position: absolute; left: 29px; top: 8px; z-index: 1; text-align: center; width: 90%;border: 1px solid transparent;"><span><span style="font-family: impact, chicago; font-size: 30pt; color: #ff0000;" data-mce-style="font-family: impact, chicago; font-size: 30pt; color: #ff0000;">DISCOVER HOW I INCREASED MY OPTIN RATE BY 500% IN 5 MINS!</span><br></span></div><div style="position: absolute; left: 285px; top: 325px;border: 1px solid transparent;"><img id="wm" src="'.$dir.'assets/get_access2.png" alt="" border="0"></div><div style="position: absolute; left: 1px; top: 104px; z-index: 1; width: 100%; text-align: center;border: 1px solid transparent;"><span style="font-size: 14pt; background-color: #ffff99;" data-mce-style="font-size: 14pt; background-color: #ffff99;"><strong><span style="font-family: tahoma, arial, helvetica, sans-serif;" data-mce-style="font-family: tahoma, arial, helvetica, sans-serif;">Enter your email address to get your <span style="text-decoration: underline;" data-mce-style="text-decoration: underline;">FREE</span> video!</span></strong></span></div><div style="position: absolute; left: 0px; top: 148px;border: 1px solid transparent;"><img id="optinrev_uid_28" src="'.$dir.'assets/optin-box.png" alt="" border="0"></div><div style="position: absolute; left: 317px; top: 142px;border: 1px solid transparent;"><img id="optinrev_uid_33" src="'.$dir.'assets/arrow-animated.gif" alt="" border="0"></div><br><a id="poweredby" data-mce-href="http://wordpress.org/extend/plugins/optin-revolution/" href="http://wordpress.org/extend/plugins/optin-revolution/" target="_new" style="position: absolute; left: 220px; top: 430px; ">Powered by : Optin Revolution</a><form method="post" id="mce_getaccessed" action="http://www.aweber.com/scripts/addlead.pl" target="_blank"><div style="display:none;"><input type="hidden" name="listname" value="optinrev_plug"><input type="hidden" name="meta_web_form_id" value="258192310"><input type="hidden" name="meta_message" value="1"><input type="hidden" name="meta_adtracking" value="wordpress_plugin"><input type="hidden" name="redirect" value="http://www.aweber.com/thankyou-coi.htm?m=audio"></div><div style="position:absolute; left: 0px; top: 0px;border: 1px solid transparent;display:none;"><input type="text" name="name" id="name" value="Enter Your Name..." style="font-size:20px !important;color:#000000 !important;height:50px !important;width:240px !important;background-color:#FFFFCC !important;border:5px solid #666666 !important;"></div><div style="position:absolute; left: 302px; top: 259px;border: 1px solid transparent;"><input type="text" name="email" id="email" value="Enter Your Email..." style="font-size:20px !important;color:#000000 !important;height:50px !important;width:240px !important;background-color:#FFFFCC !important;border:5px solid #666666 !important;"></div><span style="display:none;"><img src="http://forms.aweber.com/form/displays.htm?id=TKwcjJxMzIwM"></span></form>',
            'optinrev_close_button' => 'left:592px;top:37px;',
            'optinrev_close_button_class' => 'close2',
            'optinrev_dragging' => null,
            'optinrev_call_action_button' => 'get_access2',
            'optinrev_excerpt' => '<div id="simplemodal-container" style="width: 600px; height: 420px; border: 8px solid #000000; background-color: #ffffff; -moz-border-radius: 25px; -webkit-border-radius: 25px; border-radius: 25px;"><div id="close" class="close2" style="left:574px; top:-26px;"> </div><div id="simplemodal-data" class="simplemodal-data"><div style="position: absolute; left: 29px; top: 8px; z-index: 1; text-align: center; width: 90%;"><span><span style="font-family: impact, chicago; font-size: 30pt; color: #ff0000;">DISCOVER HOW I INCREASED MY OPTIN RATE BY 500% IN 5 MINS!</span><br /></span></div><div style="position: absolute; left: 285px; top: 325px;"><img id="wm" src="'.$dir.'assets/get_access2.png" alt="" border="0" /></div><div style="position: absolute; left: 1px; top: 104px; z-index: 1; width: 100%; text-align: center;"><span style="font-size: 14pt; background-color: #ffff99;"><strong><span style="font-family: tahoma, arial, helvetica, sans-serif;">Enter your email address to get your <span style="text-decoration: underline;">FREE</span> video!</span></strong></span></div><div style="position: absolute; left: 0px; top: 148px;"><img id="optinrev_uid_28" src="'.$dir.'assets/optin-box.png" alt="" border="0" /></div><div style="position: absolute; left: 317px; top: 142px;"><img id="optinrev_uid_33" src="'.$dir.'assets/arrow-animated.gif" alt="" border="0" /></div><br /><a id="poweredby" style="position: absolute; left: 220px; top: 430px;" href="http://wordpress.org/extend/plugins/optin-revolution/" target="_new">Powered by : Optin Revolution</a><form id="mce_getaccessed" action="http://www.aweber.com/scripts/addlead.pl" method="post" target="_blank"><div style="display: none;"><input type="hidden" name="listname" value="optinrev_plug" /><input type="hidden" name="meta_web_form_id" value="258192310" /><input type="hidden" name="meta_message" value="1" /><input type="hidden" name="meta_adtracking" value="wordpress_plugin" /><input type="hidden" name="redirect" value="http://www.aweber.com/thankyou-coi.htm?m=audio" /></div><div style="position: absolute; left: 0px; top: 0px; border: 1px solid transparent; display: none;"><input id="name" style="font-size: 20px !important; color: #000000 !important; height: 50px !important; width: 240px !important; background-color: #ffffcc !important; border: 5px solid #666666 !important;" type="text" name="name" value="Enter Your Name..." /></div><div style="position: absolute; left: 302px; top: 259px;"><input id="email" style="font-size: 20px !important; color: #000000 !important; height: 50px !important; width: 240px !important; background-color: #ffffcc !important; border: 5px solid #666666 !important;" type="text" name="email" value="Enter Your Email..." /></div><span style="display: none;"><img src="http://forms.aweber.com/form/displays.htm?id=TKwcjJxMzIwM" alt="" /></span></form></div></div>',
            'optinrev_email_form_opt' => 'aweber',
            'optinrev_email_form' => array('aweber' => array('name' => 'Enter Your Name...', 'email' => 'Enter Your Email...', 'listname' => 'optinrevolution', 'meta_web_form_id' => '258192310', 'meta_message' => 1,'meta_adtracking' => 'wordpress_plugin', 'redirect' => 'http://www.aweber.com/thankyou-coi.htm?m=audio', 'meta_redirect_onlist' => null, 'pixel_tracking_id' => 'TKwcjJxMzIwM')),
            'optinrev_input_enabled' => array('name' => 0), 'validate' => array('email' => 1), 'optinrev_inputh' => 50, 'optinrev_inputw' => 240, 'optinrev_inputtc' => '#000000', 'optinrev_inputfz' => 20, 'optinrev_inputc' => '#FFFFCC', 'optinrev_inputb' => '#666666', 'optinrev_inputbt' => 5, 'optinrev_wbg_color' => '#000000', 'optinrev_wbg_opacity' => 50, 'optinrev_delay' => 0, 'optinrev_pwbg_color' => '#FFFFFF', 'optinrev_border_color' => '#000000', 'optinrev_border_thickness' => 8, 'optinrev_border_opacity' => 75, 'optinrev_border_radius' => 25, 'optinrev_round_border' => 'on', 'optinrev_top_margin' => 127, 'optinrev_wwidth' => 600, 'optinrev_hheight' => 420, 'optinrev_close_popup_image' => 'close2', 'optinrev_gotowebsite' => 'top'
        );
       optinrev_update( 'optinrev_default', serialize( $default_optin ) );
   }
   
   // mail providers
   if ( !optinrev_get('optinrev_mail_providers') ) {
       optinrev_mail_providers();
   }
    
  } else if ( $plugin_page == 'optin-pro-settings' ) {    
    wp_enqueue_style( 'optinrev-style', $dir . 'css/optinrev-style.css' );
  }
  optinrev_update( 'optinrev_poweredby', 'true' );
}

if (is_optinrev())
add_action( 'admin_init', 'optinrev_plugin_admin_init' );
 
function optinrev_transient_update_plugins($transient)  
{  
    if( optinrev_is_pro_authorized() && !optinrev_is_pro_installed())
    {        
        if ( $download_url = optinrev_download_url() ) {
            $obj = new stdClass();
            $obj->slug = 'optin';  
            $obj->new_version = '1.0.2';  
            $obj->url = 'http://optinrevolution.com';
            $obj->package = $download_url;  
            $transient->response[$plugin_name] = $obj;
        }
    }      
    return $transient;
}  
add_filter('pre_set_site_transient_update_plugins', 'optinrev_transient_update_plugins');

function optinrev_pro_action_needed( $plugin )
{
  global $plugin_name;
  if( $plugin == $plugin_name && optinrev_is_pro_authorized() && !optinrev_is_pro_installed() )      
  {
    optinrev_manually_queue_update();
    $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $plugin, 'upgrade-plugin_' . $plugin);
  ?>
    <td colspan="3" class="plugin-update"><div class="update-message" style="-moz-border-radius-bottomleft:5px; -moz-border-radius-bottomright:5px; -moz-border-radius-topleft:5px; -moz-border-radius-topright:5px; border-style:solid; border-width:1px; margin:5px; background-color:#FFEBE8; border-color:#CC0000; padding:3px 5px;"><?php printf(__('Optin Revolution Pro installation isn\'t quite complete yet.<br/>%1$sAutomatically Upgrade to Enable Optin Revolution Pro%2$s', 'optin'), '<a href="'.$inst_install_url.'">', '</a>'); ?></div></td>
  <?php
  }
}
add_action('after_plugin_row', 'optinrev_pro_action_needed');

function optinrev_pro_get_started_headline()
{ 
  global $plugin_name;
  
  $this_uri = preg_replace('#&.*?$#', '', str_replace( '%7E', '~', $_SERVER['REQUEST_URI']));
  
  if(isset($_GET['action']) && $_GET['action'] == 'upgrade-plugin')
  return;
  

  
  if ( isset( $_GET['page'] ) ) {
      
      ?>
      <div class="error" style="padding:8px;"><?php echo __('Notice: The image and button uploader has been removed due to problems with folder permissions. You may need to re-add your images and buttons back to the stage. To upload or attach an image/button to the stage use the Wordpress Media Library. <a href="http://www.youtube.com/watch?v=3LMZqiRV-JE&autoplay=1&rel=0" target="_blank">Click here for a video tutorial on adding images and buttons to the stage.</a>'); ?></div>
      <?php
          
      if ( !optinrev_get('optinrev_popup_enabled') || optinrev_get('optinrev_popup_enabled') == 'false' ) {
      ?>
      <div class="error" id="_disopt" style="padding:8px;"><?php printf(__('Optin Revolution Popup is disabled.<br/>%1$sEnable it now.%2$s', 'optin'), '<a href="'. $this_uri .'&enable=1">','</a>'); ?></div>  
      <?php
      }  
  }

  if ( optinrev_is_pro_authorized() && !optinrev_is_pro_installed() )
  {     
    optinrev_manually_queue_update();
    $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $plugin_name, 'upgrade-plugin_' . $plugin_name);
    ?>
    <div class="error" style="padding:8px;"><?php printf(__('Optin Revolution Pro installation isn\'t quite complete yet.<br/>%1$sAutomatically Upgrade to Enable Optin Revolution Pro%2$s', 'optin'), '<a href="'.$inst_install_url.'">','</a>'); ?></div>  
    <?php 
  }
}
add_action('admin_notices', 'optinrev_pro_get_started_headline');

function optinrev_activate()
{
  global $wpdb, $optinrev_db_version, $optinrev_installed_version;  

  $table_name = $wpdb->prefix . "optinrev";
  $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64) NOT NULL DEFAULT '',
	`content` LONGTEXT NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `name` (`name`)
  );";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
   
  optinrev_update( 'optinrev_db_version', $optinrev_db_version );  
  optinrev_update( 'optinrev_installed_version', $optinrev_installed_version );  
  optinrev_delete( 'optinrev_pro_installed' );
  optinrev_check_update();
}
register_activation_hook( __FILE__, 'optinrev_activate' );

function optinrev_deactivate()
{
  global $wpdb;
  
  delete_site_transient( $plugin_name );
	$t1 = $wpdb->prefix . 'optinrev';
  $t2 = $wpdb->prefix . 'optinrev_analytics';

	$sql = "DROP TABLE IF EXISTS ". $t1;
	$wpdb->query($sql);
  
  $sql = "DROP TABLE IF EXISTS ". $t2;
	$wpdb->query($sql);
}
register_deactivation_hook( __FILE__, 'optinrev_deactivate' );

function optinrev_js()
{
  global $plugin_page, $wp_version;
  
  $dir = plugin_dir_url( __FILE__ );  
    
  $optin = optinrev_popups();  
  //mail providers
  $mailpro = json_encode( unserialize(optinrev_get('optinrev_mail_providers')) );
  //is autosave
  $autosave = optinrev_get('optinrev_autosave');
  $poweredby = optinrev_get('optinrev_poweredby');                                                
  
  if ( isset($plugin_page) )
  $_POST = unserialize(optinrev_get( $plugin_page ));  
  
  //mail provider set
  $mail_form_name = trim($_POST['optinrev_email_form_opt']);
  
  //briefcase images - it will insert to the canvas
  $is_bfcase = 0;
  if ( $imgs = optinrev_get_image( $plugin_page )) {
      $is_bfcase = array();
      foreach( $imgs as $v ) { 
        $is_bfcase[] = $v->content;
        optinrev_delete( $v->name ); 
      }
      $is_bfcase = json_encode($is_bfcase);
  }
  
  $is_delbfcase = 0;
  if ( $imgs = optinrev_delete_image( $plugin_page )) {
      $is_delbfcase = array();
      foreach( $imgs as $v ) {
        $is_delbfcase[] = $v->content;
        optinrev_delete( $v->name );
      }
      $is_delbfcase = json_encode($is_delbfcase);
  }
  
  //briefcase button - it will insert to the canvas
  $is_actionbtn = 0;
  if ( $case_btn = optinrev_get('optinrev_add_button_briefcase') )
  {               
      $is_actionbtn = $case_btn;       
      optinrev_update( 'optinrev_active_action_button', $is_actionbtn );
      optinrev_delete( 'optinrev_add_button_briefcase' );
  }      
  
  $is_upload = optinrev_get( 'optinrev_upload' );  
    
  $ht = '';
  //setting page
  if ( $plugin_page == 'optin' )
  {           
  $ht .= '$("#optinrev_popup_enabled").iButton({change: function($input){$(\'#_disopt\').hide();$.post("admin-ajax.php", {action : "optinrev_action", "optinrev_popup_enabled" : $input.is(":checked")}, function(res){wtfn.msg(\'Successfully Updated.\');});}});';
  ?>
  <script type="text/javascript">  
  var admin = {cookies : function() {if ( confirm('Are you sure you want to clear all cookies ?') ) {window.location.href = 'admin.php?page=optin&cookies=clear';}}};
  jQuery(document).ready(function($){<?php echo $ht;?>});
  </script>  
  <?php }
  else if ( preg_match('/optin[1-5]/', $plugin_page ) ) { include('js/optinrev-js.php'); echo '<script type="text/javascript" src="'.$dir.'js/optinrev.js?ver='.$wp_version.'"></script>'; }
  else if ( $plugin_page == 'optin-pro-settings' ) { echo '<script type="text/javascript" src="'.$dir.'js/optinrev-pro-setting.js?ver='. $wp_version .'"></script>'; }
}//jsload

if (is_optinrev())
add_action( 'admin_head', 'optinrev_js' );

//Pro settings
//Optin Setting
function optinrev_pro()
{
  global $plugin_name, $plugin_url;
  $this_uri = preg_replace('#&.*?$#', '', str_replace( '%7E', '~', $_SERVER['REQUEST_URI']));
  
  $auth_info = array();  
  if ( $auth_info = optinrev_get('optinrev_pro_authorized') )
  $auth_info = unserialize( $auth_info );
 
  if ( !optinrev_get('optinrev_notice') )
  if( optinrev_is_pro_authorized()  )
  {
    $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $plugin_name, 'upgrade-plugin_' . $plugin_name);
    optinrev_update('optinrev_notice', 'pro');    
    ?>
    <div id="message" class="updated fade">
    <strong><?php printf(__('Your Username & Password was accepted<br/>Now you can %1$sUpgrade Automatically!%2$s', 'optin'), "<a href=\"{$inst_install_url}\">","</a>"); ?></strong>
    </div>
  <?php
  }           
  
?>
<div class="wrap fform">
  <div class="get-help">Get Help : <a href="http://optinrevolution.com/tutorials/" target="_new">Tutorials</a> | <a href="http://optinrevolution.com" target="_new">Upgrade to Pro</a></div>
  <div class="icon32" id="icon-options-general"><br /></div><?php echo "<h2>" . __( 'Optin Revolution Pro Settings', 'optinrev_trdom' ) . "</h2>"; ?> <br />  
  <h3>Optin Revolution Pro Account Information</h3>
  <form id="cred_form" name="cred_form" method="post" action="<?php echo $this_uri; ?>">
    <div class="row"><label>ClickBank Order # :</label><input type="text" name="amember_receipt" id="cbo" size="30" value="<?php echo (count($auth_info))?$auth_info['amember_receipt']:'';?>"></div>  
    <div class="row"><label>Email :</label><input type="text" name="amember_email" id="e" size="30" value="<?php echo (count($auth_info))?$auth_info['amember_email']:'';?>"></div>
    <div class="row"><label>Password :</label><input type="password" name="amember_pass" id="p" size="30"></div>  
    <div class="row"><label>&nbsp;</label><span class="submit">
    <input type="submit" name="Submit" value="<?php _e('Save', 'optin'); ?>" />
    </span></div>
  </form>    
  <p><strong>Ready to take your email marketing efforts to the next level?</strong><br>
  <a href="http://optinrevolution.com">Optin Revolution Pro</a> will help you create unique optin popup to increase your email list size!<br><br><a href="http://optinrevolution.com">Learn More »</a></p>
</div>
<?php
}

//Optin Setting
function optinrev_admin()
{
  global $plugin_name, $wp_version;
  
  $dir = plugin_dir_url( __FILE__ );
  
  $optin = optinrev_popups();
  $optinrev_show_popup = optinrev_get('optinrev_show_popup');
  $ispopup = optinrev_get('optinrev_popup_enabled');  
  
  if ( empty($optinrev_show_popup) ) {
      $optinrev_show_popup = 'show_times_per_session|1';
      optinrev_update( 'optinrev_show_popup', $optinrev_show_popup );
  }
  
  $is_showonload = optinrev_get('optinrev_show_where');
  if ( empty($is_showonload) ) {      
      optinrev_update( 'optinrev_show_where', 'show_on_load' );
  }
  
  $is_autosave = optinrev_get('optinrev_autosave');
  if ( empty($is_autosave) ) {
      $is_autosave = 'true';      
      optinrev_update( 'optinrev_autosave', $is_autosave );
  }
  
  $is_poweredby = optinrev_get('optinrev_poweredby');
    
  //if show
  $wshow = explode( '|', $optinrev_show_popup );
  
  $r = 1;
?>
<div>                             
<div class="or-social-icons">  
  <div class="social-center">
  <ul>
      <li><a href="http://www.facebook.com/share.php?u=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="flike"></a></li>
      <li><a href="https://twitter.com/share?url=<?php echo SOCIAL_URL;?>&text=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="tweet"></a></li>
      <li><a href="https://plus.google.com/share?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="googleplus"></a></li>
      <li><a href="http://del.icio.us/post?url=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="delicious"></a></li>
      <li><a href="http://www.stumbleupon.com/submit?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="stumbleupon"></a></li>
      <li><a href="http://digg.com/submit?url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="digg"></a></li>
      <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>&summary=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="inshare"></a></li>
      <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(SOCIAL_URL);?>&media=http://optinrevolution.com/img/pin.png&description=<?php echo SOCIAL_TITLE; ?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="pinit"></a></li>      
  </ul>
  </div>        
</div>
<div class="get-help">Get Help : <a href="http://optinrevolution.com/tutorials/" target="_new">Tutorials</a> | <a href="http://optinrevolution.com" target="_new">Upgrade to Pro</a></div>
<div class="col1">
<div class="wrap fform">
<?php if (get_option('optinrev_cookies')) { ?>
  <div id="message" class="updated fade" style="padding:10px;">
  <strong><?php echo __('Successfully cookies cleaned.'); ?></strong>
  </div>
<?php delete_option('optinrev_cookies');}?>
<div class="icon32" id="icon-options-general"><br /></div><?php echo "<h2>" . __( 'Optin Revolution Settings', 'optinrev_trdom' ) . "</h2>"; ?> <br />
  <div id="post-message" class="updated"></div>      
  <form name="optinrev_form" method="post" action="">
    <p>
        Toggle switches to enable or disable settings. Turning Optin Popup Off will disable all Optin Popups.
        Enable/Disable Optin Popup 1 that you want to splittest. Setting are automatically saved the switch is toggled.
    </p>
    <p><label class="lbladmin"><?php _e('Optin Revolution Popup'); ?></label><div class="fbox"><input type="checkbox" name="optinrev_popup_enabled" id="optinrev_popup_enabled" <?php echo ($ispopup === 'true') ? 'checked="checked"': '';?>/></div><div class="clear"></div></p>
    <p><label class="lbladmin"><?php _e('Auto Save'); ?></label><div class="fbox"><input type="checkbox" name="optinrev_autosave" id="optinrev_autosave" <?php echo ($is_autosave === 'true') ? 'checked="checked"': '';?>/></div><div class="clear"></div></p>    
    <div class="row"><label><?php _e('Show Optin Revolution Popup');?></label>
        <div class="fbox">
        <input type="radio" name="optinrev_show[]" id="show_always" value="always" onchange="wtfn.optinrev_show_popup('show_always');" <?php echo ($wshow[0] === 'show_always')?'checked':'';?>/>&nbsp;Always<br />                
        <input type="radio" name="optinrev_show[]" id="show_times_per_session" value="times_per_session" onchange="wtfn.optinrev_show_popup('show_times_per_session');" <?php echo ($wshow[0] === 'show_times_per_session')?'checked':'';?>/>&nbsp;<input type="hidden" name="optinrev_time_session" id="optinrev_time_session" size="5" value="<?php echo (isset($wshow[1]) && $wshow[0] === 'show_times_per_session') ? $wshow[1]: 1;?>"/>&nbsp;Once per browser session<br />
        <input type="radio" name="optinrev_show[]" id="show_once_in" value="once_in" onchange="jQuery('#show_optin_days').focus();" <?php echo ($wshow[0] === 'show_once_in')?'checked':'';?>/>&nbsp;Once in
        <input type="text" name="optinrev_days" id="show_optin_days" size="4" onclick="jQuery('#show_once_in').change();" onblur="wtfn.optinrev_show_popup('show_once_in');" value="<?php echo (isset($wshow[1]) && $wshow[0] === 'show_once_in') ? $wshow[1] : 1;?>""/>&nbsp;Days
        </div>
        <div class="clear"></div>
    </div>    
    <div class="row" id="save_showset"><label>&nbsp;</label><span class="submit"><input type="button" name="optinrev_save_showset" id="optinrev_save_showset" value="Save"/></span></div>
    <br />
        <?php foreach( $optin as $ok => $ov ) {
        $ispop = ( optinrev_get('optinrev_'. $ok .'_enabled') == 'true' ) ? true : false;
        ?>
        <div><label class="lbladmin"><?php _e('Optin Revolution Popup '. $r); ?></label><div class="fbox"><input type="checkbox" name="optinrev_<?php echo $ok;?>_enabled" id="optinrev_<?php echo $ok;?>_enabled" <?php echo ($ispop) ? 'checked="checked"': '';?>/></div><div class="clear"></div></div><br />    
        <?php $r++;}?>
    <div class="row"><label><?php _e('Cookies'); ?></label><span class="submit"><input type="button" name="optinrev_clean_cookies" value="Clear Cookies" onclick="admin.cookies();"/></span></div>             
  </form>
  <?php echo '<script type="text/javascript" src="'.$dir.'js/optinrev-admin-setting.js?ver='.$wp_version.'"></script>';?>
  <p><iframe width="640" height="360" src="http://www.youtube.com/embed/-SHDzo3bzx4?rel=0" frameborder="0" allowfullscreen></iframe></p>    
</div>
</div>
<div class="col2">
  <div class="need-support-box">
  <h2>Need support?</h2>
  <p>If you are having problems with this plugin, please talk about them in the <a href="<?php echo OPTINREV_SUPPORT;?>" target="_new">Support forums</a>.</p>      
  </div>
  
  <div class="social-box">
  <h2>Be Social, Share Me!</h2>  
  <ul>
        <li><a href="http://www.facebook.com/share.php?u=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="flike"></a></li>
        <li><a href="https://twitter.com/share?url=<?php echo SOCIAL_URL;?>&text=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="tweet"></a></li>
        <li><a href="https://plus.google.com/share?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="googleplus"></a></li>
        <li><a href="http://del.icio.us/post?url=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="delicious"></a></li>  
        <li><a href="http://www.stumbleupon.com/submit?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="stumbleupon"></a></li>
        <li><a href="http://digg.com/submit?url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="digg"></a></li>
        <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>&summary=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="inshare"></a></li>
        <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(SOCIAL_URL);?>&media=http://optinrevolution.com/img/pin.png&description=<?php echo SOCIAL_TITLE; ?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="pinit"></a></li>
  </ul>
  <div class="clear"></div>
  </div>
  
  <div class="spread-the-word-box">
  <h2>Spread the Word!</h2>
  <p>Want to help make this plugin even better? All donations are used to improve this plugin, so donate $5, $10 or $20 now!</p>
  <p>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
  </form>
  </p>
  </div>
</div>
<div class='clear'></div>
</div>
<?php
}

function optinrev_setup() {
  global $plugin_page;
  
  $dir = plugin_dir_url( __FILE__ );
  //default button images
  $def_images = unserialize( optinrev_get('optinrev_default_images') );
  $get_access_btns = $def_images['get_access'];
  $close_btns = $def_images['close_btn'];
  
  //configs
  $optin = optinrev_popups();  
  if ( isset($plugin_page) )
  {
    $_POST = unserialize(optinrev_get( $plugin_page ));
  }
  
  $is_call_action = (post('optinrev_call_action_button', true)) ? post('optinrev_call_action_button', true) : 'get_access1';
  $is_close_btn = (post('optinrev_close_popup_image', true)) ? post('optinrev_close_popup_image', true) : 'close1';
  
  $action_btn_view = $get_access_btns[ $is_call_action ];
  
  //editing content
  $content = (post('optinrev_excerpt', true)) ? post('optinrev_excerpt', true) : '';  
  //email form
  $mail_form = array(
  'aweber' => 'Aweber', 'icontact' => 'Icontact', 'getresponse' => 'GetResponse', 'mailchimp' => 'Mailchimp', 'constantcontact' => 'Constant Contact'
  );
  
  $email_form_opt = (post('optinrev_email_form_opt', true)) ? post('optinrev_email_form_opt', true) : 'aweber';  
  //input text
  $inputs = (isset($_POST['optinrev_email_form'])) ? $_POST['optinrev_email_form'] : '';
  $inputs_enabled = (isset($_POST['optinrev_input_enabled'])) ? $_POST['optinrev_input_enabled'] : '';
  //goto website button location
  $gw = (post('optinrev_gotowebsite', true)) ? post('optinrev_gotowebsite', true) : 'top';
?>
<div>
<div class="or-social-icons">
<div class="social-center">
  <ul>
      <li><a href="http://www.facebook.com/share.php?u=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="flike"></a></li>
      <li><a href="https://twitter.com/share?url=<?php echo SOCIAL_URL;?>&text=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="tweet"></a></li>
      <li><a href="https://plus.google.com/share?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="googleplus"></a></li>
      <li><a href="http://del.icio.us/post?url=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="delicious"></a></li>
      <li><a href="http://www.stumbleupon.com/submit?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="stumbleupon"></a></li>
      <li><a href="http://digg.com/submit?url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="digg"></a></li>
      <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>&summary=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="inshare"></a></li>
      <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(SOCIAL_URL);?>&media=http://optinrevolution.com/img/pin.png&description=<?php echo SOCIAL_TITLE; ?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="pinit"></a></li>
  </ul>
</div>
</div>
<div class="get-help">Get Help : <a href="http://optinrevolution.com/tutorials/" target="_new">Tutorials</a> | <a href="http://optinrevolution.com" target="_new">Upgrade to Pro</a></div>
<div class="col1">
<div class="wrap fform">
<div class="icon32" id="icon-options-general"><br /></div><?php echo "<h2>" . __( $optin[ $plugin_page ] . ' Settings', 'wotp_trdom' ) . "</h2>"; ?> <br />
  <div id="post-message" class="updated"></div>
  <form name="optinrev_setup_form" id="optinrev_setup_form" method="POST" action="admin-ajax.php">
    <input type="hidden" name="action" value="optinrev_action"/>
    <input type="hidden" name="save_setup_settings" id="page" value="<?php echo $plugin_page;?>"/>
    <input type="hidden" name="optinrev_data" id="optinrev_data"/>
    <input type="hidden" name="optinrev_close_button" id="optinrev_close_button" value="<?php post('optinrev_close_button');?>"/>
    <input type="hidden" name="optinrev_close_button_class" id="optinrev_close_button_class"/>
    <input type="hidden" name="optinrev_dragging" id="optinrev_dragging" value="<?php post('optinrev_dragging');?>"/>
    <input type="hidden" name="optinrev_call_action_button" id="optinrev_call_action_button" value="<?php echo $is_call_action;?>">    
    <p>
    <div class="fbox"><span class="submit"><input type="button" name="action_reset" id="action_reset" value="<?php _e('Reset') ?>" /></span></div>
    <div class="rbox" id="wotbuttons">    
    <span class="spin" id="save_setting_spin"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="submit"><input type="submit" class="button-primary" name="action_save_settings" value="<?php _e('Save Settings') ?>" /></span>    
    <span class="submit"><input alt="" title="<?php echo $optin[$plugin_page];?> Preview" id="action_preview" type="button" value="Preview" /></span>
    </div>
    <div class="clear"></div>
    </p>    
    <?php wp_editor( $content, 'optinrev_excerpt', array('textarea_rows' => 14, 'media_buttons' => false) );?><br />
    
    <div class="row"><label class="title"><a class="toggle" id="_box1-t"><?php _e('Email Marketing Form'); ?><span class="_box1-x">[+]</span><span class="_box1-c">[-]</span></a></label><span>&nbsp;</span></div><br />
    <div id="_box1">
    <?php foreach( $mail_form as $k => $v ) { $sel = ($email_form_opt === $k)? 'checked' : ''; ?>
    <div class="mail_opt" style="margin-top:10px;margin-right:8px;"><input type="radio" name="optinrev_email_form_opt" value="<?php echo $k;?>" <?php echo $sel;?>/></div><div class="mail_opt"><img src="<?php echo $dir.'images/'. (($k === 'constantcontact')? 'constant_contact': $k);?>.png" id="<?php echo $k;?>" border="0"/></div>
    <?php }?>
    <div class="clear"></div>    
    
    <div class="row"><label class="title"><?php _e('Input Fields'); ?></label>&nbsp;<span>&nbsp;</span></div>    
    <div id="wotinput_fields"></div>
    </div>
    <div class="row"><label class="title"><a class="toggle" id="_box2-t"><?php _e('Input Fields Setup'); ?><span class="_box2-x">[+]</span><span class="_box2-c">[-]</span></a></a></label>&nbsp;<span>&nbsp;</span></div>    
    <div id="_box2">
    <div class="row"><label><?php _e('Height'); ?></label>    
    <div class="fbox"><input type="text" name="optinrev_inputh" id="optinrev_inputh" value="<?php echo (post('optinrev_inputh',true))?post('optinrev_inputh',true):'50';?>" size="10" readonly>px</div>
    <div class="wjui-box"><div id="optinrev_inpuths" class="wjui"></div></div>
    <div class="clear"></div>
    </div>
    <div class="row"><label><?php _e('Width'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_inputw" id="optinrev_inputw" value="<?php echo (post('optinrev_inputw',true))?post('optinrev_inputw',true):'160';?>" size="10" readonly>px</div>
    <div class="wjui-box"><div id="optinrev_inputws" class="wjui"></div></div>
    <div class="clear"></div>
    </div>
    
    <div class="row"><label><?php _e('Text Color'); ?></label><input type="text" name="optinrev_inputtc" class="color {hash:true}" value="#<?php echo (post('optinrev_inputtc',true))?post('optinrev_inputtc',true):'000000';?>" size="10"></div>    
    
    <div class="row"><label><?php _e('Font Size'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_inputfz" id="optinrev_inputfz" value="<?php echo (post('optinrev_inputfz', true))?post('optinrev_inputfz',true):'12';?>" size="10" readonly>px</div>    
    <div class="wjui-box"><div id="optinrev_inputfzs" class="wjui"></div></div>
    <div class="clear"></div>    
    </div>    
    
    <div class="row"><label><?php _e('Background Color'); ?></label><input type="text" name="optinrev_inputc" class="color {hash:true}" value="#<?php echo (post('optinrev_inputc',true))?post('optinrev_inputc',true):'ffffff';?>" size="10"></div>
    <div class="row"><label><?php _e('Border Color'); ?></label><input type="text" name="optinrev_inputb" class="color {hash:true}" value="#<?php echo (post('optinrev_inputb',true))?post('optinrev_inputb',true):'000000';?>" size="10"></div>
    
    <div class="row"><label><?php _e('Border Thickness'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_inputbt" id="optinrev_inputbt" value="<?php echo (post('optinrev_inputbt', true))?post('optinrev_inputbt',true):'1';?>" size="10" readonly>px</div>    
    <div class="wjui-box"><div id="optinrev_inputbts" class="wjui"></div></div>
    <div class="clear"></div>    
    </div>
    </div>
    <br />    
    <div class="row"><label class="title"><a class="toggle" id="_box3-t"><?php _e('Window Setup'); ?><span class="_box3-x">[+]</span><span class="_box3-c">[-]</span></a></a></label>&nbsp;<span>&nbsp;</span></div>
    <div id="_box3">        
    <div class="row"><label><?php _e('Background Color'); ?></label><input type="text" name="optinrev_wbg_color" id="optinrev_wbg_color" class="color {hash:true}" value="<?php post('optinrev_wbg_color');?>" size="10"></div>
    <div class="row"><label><?php _e('Background Opacity'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_wbg_opacity" id="optinrev_wbg_opacity" value="<?php echo (post('optinrev_wbg_opacity',true))?post('optinrev_wbg_opacity',true):'0';?>" size="10" readonly>%</div>
    <div class="wjui-box"><div id="wbg_opacity_slider" class="wjui"></div></div>
    <div class="clear"></div>
    </div>
        <div class="row"><label><?php _e('Seconds Delay'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_delay" id="optinrev_vdelay" value="<?php echo (post('optinrev_delay',true))?post('optinrev_delay',true):'0';?>" size="10" readonly>Sec</div>
    <div class="wjui-box"><div id="optinrev_sdelay" class="wjui"></div></div>
    <div class="clear"></div>
    </div>
    </div>
    <br />
    <div class="row"><label class="title"><a class="toggle" id="_box4-t"><?php _e('Pop Windows Setting'); ?><span class="_box4-x">[+]</span><span class="_box4-c">[-]</span></a></a></label>&nbsp;<span>&nbsp;</span></div>    
    <div id="_box4">    
    <div class="row"><label><?php _e('Background Color'); ?></label><input type="text" name="optinrev_pwbg_color" class="color {hash:true}" value="<?php echo (post('optinrev_pwbg_color',true))?post('optinrev_pwbg_color',true):'ffffff';?>" size="10"></div>
    <div class="row"><label><?php _e('Border Color'); ?></label><input type="text" name="optinrev_border_color" class="color {hash:true}" value="<?php echo (post('optinrev_border_color',true))?post('optinrev_border_color',true):'000000';?>" size="10"></div>
    
    <div class="row"><label><?php _e('Border Thickness'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_border_thickness" id="optinrev_vborder_thickness" value="<?php echo (post('optinrev_border_thickness', true))?post('optinrev_border_thickness',true):'1';?>" size="10" readonly>px</div>    
    <div class="wjui-box"><div id="optinrev_sborder_thickness" class="wjui"></div></div>
    <div class="clear"></div>    
    </div>
    
    <div class="row"><label><?php _e('Border Opacity'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_border_opacity" id="optinrev_border_opacity" value="<?php echo (post('optinrev_border_opacity',true))?post('optinrev_border_opacity',true):'0';?>" size="10" readonly>%</div>
    <div class="wjui-box"><div id="border_opacity_slider" class="wjui"></div></div>
    <div class="clear"></div>
    </div>
    
    <?php if ( post('optinrev_round_border', true) == 'on' ) { ?>    
    <div class="row" id="_nbr"><label class="lbladmin"><?php _e('Border Radius'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_border_radius" id="optinrev_border_radius" value="<?php echo (post('optinrev_border_radius',true))?post('optinrev_border_radius',true):'0';?>" size="10" readonly>%</div>
    <div class="wjui-box"><div id="border_radius_slider" class="wjui"></div></div>
    <div class="clear"></div>
    </div>
    <?php }?>
    
    <div><label class="lbladmin"><?php _e('Rounded Border'); ?></label><div class="fbox"><input type="checkbox" name="optinrev_round_border" id="optinrev_round_border" <?php echo (post('optinrev_round_border',true)=='on') ? 'checked':'';?>/></div><div class="clear"></div></div>    
    <div class="row"><label><?php _e('Popup Top Margin'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_top_margin" id="optinrev_vtop_margin" value="<?php echo (post('optinrev_top_margin', true))?post('optinrev_top_margin',true):'0';?>" size="10" readonly>px</div>
    <div class="wjui-box"><div id="optinrev_stop_margin" class="wjui"></div></div>
    <div class="clear"></div>
    </div>    
    <div class="row"><label><?php _e('Popup Width'); ?></label>    
    <div class="fbox"><input type="text" name="optinrev_wwidth" id="optinrev_vwidth" value="<?php echo (post('optinrev_wwidth',true))?post('optinrev_wwidth',true):'900';?>" size="10" readonly>px</div>
    <div class="wjui-box"><div id="optinrev_swidth" class="wjui"></div>&nbsp;&nbsp;&nbsp;&nbsp;<span class="description">Maximum width = 900px</span></div>
    <div class="clear"></div>
    </div>
    <div class="row"><label><?php _e('Popup Height'); ?></label>
    <div class="fbox"><input type="text" name="optinrev_hheight" id="optinrev_vheight" value="<?php echo (post('optinrev_hheight',true))?post('optinrev_hheight',true):'600';?>" size="10" readonly>px</div>
    <div class="wjui-box"><div id="optinrev_sheight" class="wjui"></div>&nbsp;&nbsp;&nbsp;&nbsp;<span class="description">Maximum height = 600px</span></div>
    <div class="clear"></div>
    </div>
    </div>
    <br />
    <div class="row"><label class="title"><a class="toggle" id="_box5-t"><?php _e('Close Popup Image'); ?><span class="_box5-x">[+]</span><span class="_box5-c">[-]</span></a></a></label>&nbsp;<span>&nbsp;</span></div>
    <div id="_box5">
    <p>
    <?php $cb = 0;foreach( $close_btns as $bk => $bv ) { $sel = ( $is_close_btn == $bk ) ? 'checked' : '';?>    
        <input type="radio" name="optinrev_close_popup_image" value="<?php echo $bk;?>" <?php echo $sel; ?>>&nbsp;<img src="<?php echo $bv;?>" border="0"/>&nbsp;&nbsp;&nbsp;
        <?php if ( $cb == 5 ) echo '<div>&nbsp;</div>'; ?>    
    <?php $cb++;} ?>
        <p>Location Top / Bottom&nbsp;
        <select name="optinrev_gotowebsite" id="optinrev_gotowebsite" onchange="wtfn.gw_loc_btn(this.value);">
        <option value="top" <?php echo ($gw == 'top')?'selected':'';?>>Top</option>
        <option value="bottom" <?php echo ($gw == 'bottom')?'selected':'';?>>Bottom</option>
        </select></p>
    </p>
    </div>    
    <p>&nbsp;</p>    
    <script type="text/javascript">
        jQuery(document).ready(function($){
        $('#wotinput_fields').load('admin-ajax.php', {action : 'optinrev_action', optinrev_mail_webform : '<?php echo $plugin_page;?>', optinrev_mail_provider: $('input[name="optinrev_email_form_opt"]:checked').val()}, function(){
           $('select', this).each(function(i,v){
              nm = $(v).attr('name');
              if ( nm.indexOf('input_enabled') > 0 ) {
              if ( $(v).val() == 0 ) {
              nm = nm.substring( nm.indexOf('[') + 1, nm.indexOf(']'));
              wtfn.input_setenabled( nm,  $(v).val());
              }   
              }           
           });
        });
        });  
    </script>    
  </form> 
</div>
</div>
<div class="col2">
  <div class="need-support-box">
  <h2>Need support?</h2>
  <p>If you are having problems with this plugin, please talk about them in the <a href="<?php echo OPTINREV_SUPPORT;?>" target="_new">Support forums</a>.</p>      
  </div>
  <div class="social-box">
  <h2>Be Social, Share Me!</h2>  
  <ul>
        <li><a href="http://www.facebook.com/share.php?u=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="flike"></a></li>
        <li><a href="https://twitter.com/share?url=<?php echo SOCIAL_URL;?>&text=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="tweet"></a></li>
        <li><a href="https://plus.google.com/share?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="googleplus"></a></li>
        <li><a href="http://del.icio.us/post?url=<?php echo SOCIAL_URL;?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="delicious"></a></li>
        <li><a href="http://www.stumbleupon.com/submit?url=<?php echo SOCIAL_URL;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="stumbleupon"></a></li>
        <li><a href="http://digg.com/submit?url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="digg"></a></li>
        <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SOCIAL_URL);?>&title=<?php echo SOCIAL_TITLE;?>&summary=<?php echo SOCIAL_TITLE;?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="inshare"></a></li>
        <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(SOCIAL_URL);?>&media=http://optinrevolution.com/img/pin.png&description=<?php echo SOCIAL_TITLE; ?>" title="<?php echo SOCIAL_TITLE;?>" target="_new" class="pinit"></a></li>
  </ul>
  <div class="clear"></div>
  </div>  
  <div class="spread-the-word-box">
  <h2>Spread the Word!</h2>
  <p>Want to help make this plugin even better? All donations are used to improve this plugin, so donate $5, $10 or $20 now!</p>
  <p>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
  </form>
  </p>
  </div>
</div>
<div class='clear'></div>
</div>
<?php
}



function optinrev_browser_alert() {
$dir = plugin_dir_url( __FILE__ );
?>
  <div align="center"><br />   
    <h1>Your using a really old browser, update your browser to configure your Optin Popups</h1>
    <div>
      <table cellpadding="20" cellspacing="0" width="600">
      <tr><td>
      <a href="https://www.google.com/intl/en/chrome/browser/" target="_new"><img src="<?php echo $dir; ?>images/chrome-logo.png" border="0" style="width:auto;height:80px;"/></a></td>
      <td><a href="http://www.mozilla.org/en-US/firefox/new/" target="_new"><img src="<?php echo $dir; ?>images/firefox-logo.png" style="width:auto;height:80px;" border="0"/></a></td>
      <td><a href="http://windows.microsoft.com/en-GB/internet-explorer/downloads/ie-8" target="_new"><img src="<?php echo $dir; ?>images/ie8-logo.jpeg" style="width:auto;height:80px;" border="0"/></a></td>
      <td><a href="http://windows.microsoft.com/en-GB/internet-explorer/downloads/ie-9/worldwide-languages" target="_new"><img src="<?php echo $dir; ?>images/ie9-logo.jpeg" style="width:auto;height:80px;" border="0"/></a></td>
      </div>
      </tr>
      </table>
  </div>
<?php
}

function optinrev_enqueue_scripts() {
  wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'optinrev_enqueue_scripts');

function optinrev_modal_wphead()
{
  if ( !optinrev_get('optinrev_popup_enabled') ) return;  
  $optin = optinrev_get( 'optin1' );  
  if ( empty($optin) ) return;
  
  wp_enqueue_style( 'optinrev_css', plugin_dir_url( __FILE__ ) . 'optinrev-css.php?view=1' );      
  wp_enqueue_script( 'jquery_modaljs', plugin_dir_url( __FILE__ ) . 'js/jquery.simplemodal.js' );
  wp_enqueue_script( 'optinrev_util', plugin_dir_url( __FILE__ ) . 'js/optinrev-utils.js' );    
}

add_action( 'wp_head', 'optinrev_modal_wphead' );

function optinrev_wphead() {
  //optinrev_popup_enabled
  if ( $ispop = optinrev_get('optinrev_popup_enabled') ) {
  if ( $ispop == 'false' ) return false;
  } else return false;
    
  //default  
  $optin_id = 1;
  
  //is popup enabled
  if ( $pop = optinrev_get('optinrev_optin1_enabled') ) {
  if ( $pop == 'false' ) return false;  
  } else return false;
  
  //optin popup
  $optin = optinrev_get( 'optin' . $optin_id );
  
  if ( empty($optin) ) return;   
  
  $optin = unserialize( $optin );
  $dir = plugin_dir_url( __FILE__ );
  
  $content = str_replace("'", "", stripcslashes( $optin['optinrev_data'] ));
  $modal_delay = $optin['optinrev_delay'];
    
  //Popup show  
  $show_time = optinrev_get('optinrev_show_popup');
  $tshow = 0; 
  $ts = explode( '|', $show_time );
  //always
  if ( count($ts) == 0 )
  {
      $tshow = 1;
      add_action( 'init', 'visited_ip' );      
                  
  } else {
     
      if ( $ts[0] == 'show_always')
      {
        $tshow = 1;        
        add_action( 'init', 'visited_ip' );
      } 
      else if ( $ts[0] == 'show_once_in' )
      {
        
        $dates = array();
        for($current = date('Y-m-d', $ts[2]); $current != date('Y-m-d', $ts[3] ); $current = date('Y-m-d', strtotime("$current +1 day"))) {
        $dates[] = $current;
        }      
        $dates[] = date('Y-m-d', $ts[3] );
      
        if ( !isset($_COOKIE['visited_once']) ) {
            $tshow = 1;            
            add_action( 'init', 'visited_once' );
            } else {
           $tshow = 0;
        }
        
        if ( in_array( date('Y-m-d') , $dates) )
        {             
            add_action( 'init', 'visited_once' );
        }
        
        } else {
        
        //session per browser
        if ( !isset( $_SESSION['visited_ip'] ) ) {
            $_SESSION['visited_ip'] = $_SERVER['REMOTE_ADDR'];
            $tshow = 1;
            } else {
            $tshow = 0;
        }
        
      }  
  
  }    
  
  
  $pop_show = optinrev_get('optinrev_show_where');
  
  echo '<script type="text/javascript">var ms = "", el = document.createElement(\'div\'), ch = jQuery(window).height(), exh = 30, c = jQuery(el).html(\''. preg_replace('/\s+/', ' ', $content ). '\'), tshow = '.$tshow.', isvalid = '.((isset($optin['validate']))?json_encode($optin['validate']):'{}').', mail_form_name = \''.$optin['optinrev_email_form_opt'].'\',optinrev_close_button_class = \''. $optin['optinrev_close_button_class'].'\', optinrev_top_margin = '.$optin['optinrev_top_margin'].',optinrev_wbg_opacity = '.$optin['optinrev_wbg_opacity'].', modal_delay = '.$modal_delay.',box_delay = box_started = 0;</script>';
    
  //select to load
  if ( $pop_show == 'show_on_load' ) {
?>
<?php 
echo '<script type="text/javascript" src="'.$dir.'js/optinrev-showonload.js?ver=3.3.1"></script>';
} else if ( $pop_show == 'show_on_exit' ) {
?>
<?php
echo '<script type="text/javascript" src="'.$dir.'js/optinrev-showonexit.js?ver=3.3.1"></script>';
}}

  add_action( 'wp_head', 'optinrev_wphead' );  
  add_action('wp_ajax_optinrev_action', 'optinrev_action_callback');
  
  //WP CSS
  if ( ! function_exists('tdav_css') )
  { 
  	function tdav_css($wp) {
      global $plugin_page;
      $dir = plugin_dir_url( __FILE__ );  
  		$wp = $dir . 'optinrev-css.php?popup=' . $plugin_page;
  	  return $wp;
  	}
  }
  
  if (is_optinrev())
  add_filter( 'mce_css', 'tdav_css' );
  
  //TINYMCE
  function optinrev_mce_before_init( $in )
  {
   $in['theme']= "advanced";
   $in['mode'] = "textareas"; 
   $in['cleanup'] = false;
   $in['plugins'] = 'textedit,inlinepopups,layer,textbox,input_align,ifdragedit,object_align';
   $in['wpautop'] = true;
   $in['apply_source_formatting']=false;
   $in['theme_advanced_buttons1']='textedit,|,moveforward,movebackward,|,textbox,lineheight,|,input_align_left,input_align_top,|,object_align_top,object_align_bottom,object_align_center,object_align_left,object_align_right,|,undo,redo,ifdragedit';
   $in['theme_advanced_buttons2']='';
   $in['theme_advanced_buttons3']='';
   $in['theme_advanced_buttons4']='';
   $in['theme_advanced_resizing']=false;
   $in['invalid_elements'] = 'p';
   $in['force_br_newlines'] = true;
   $in['force_p_newlines'] = false;   
   $in['forced_root_block'] = '';
   $in['setup'] = 'wtfn.tinymce';
   $in['handle_event_callback'] = 'wtfn.tinymce_event';
   return $in;
  }

  if (is_optinrev())
  add_filter('tiny_mce_before_init', 'optinrev_mce_before_init');

  function optinrev_mce_addbuttons()
  {
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
    return;
     
    if ( get_user_option('rich_editing') == 'true') {
    add_filter("mce_external_plugins", "optinrev_mce_plugin");
    }
  }
  
  function optinrev_mce_plugin( $plugin )
  {
    $dir = plugin_dir_url( __FILE__ );
    $plugin['layer'] = $dir . 'js/mce_layer.js';
    $plugin['lineheight'] = $dir . 'js/mce_lineheight.js';    
    $plugin['textbox'] = $dir . 'js/mce_textbox.js';
    $plugin['input_align'] = $dir . 'js/mce_inputalign.js';
    $plugin['ifdragedit'] = $dir . 'js/mce_ifdragedit.js';
    $plugin['object_align'] = $dir . 'js/mce_objectalign.js';
    $plugin['textedit'] = $dir . 'js/textedit/editor_plugin.js';
       
    return $plugin;
  }
  
  if (is_optinrev())
  add_action('init', 'optinrev_mce_addbuttons');

  function optinrev_media_column( $posts_columns ) {
	$posts_columns['optinrev_media'] = _x('Optin Revolution', 'column name'); 
	return $posts_columns;
  }
  add_filter( 'manage_media_columns', 'optinrev_media_column' );  
  
  function optinrev_manage_attachment_media_column( $column_name, $id ) {
  	switch( $column_name ) {
  	case 'optinrev_media':
    $ac_id = 'action_button_' . $id;
    $stg_id = 'stage_img_' . $id;
    $img = optinrev_get_media($id);    
    $imgurl = parse_url($img->guid);
    
    $cr_btn = optinrev_get_action_button();
        
    $is_action_button = ( $cr_btn->ID == $id ) ? 'checked="true"' : '';
    $is_stage_image = (optinrev_has_optinmedia( $id, 'stage_img')) ? 'checked="true"' : '';
    
    echo '<div style="padding:8px;"><div style="padding-bottom:6px;"><input type="checkbox" name="'.$ac_id.'" id="'.$ac_id.'" '.$is_action_button.' onchange="wtfnm.action_update_button(\''.$ac_id.'\',\''.$imgurl['path'].'\');"/>&nbsp;<a href="javascript:;" title="Changed action button in the stage." onclick="jQuery(\'#'.$ac_id.'\').prop(\'checked\', !(jQuery(\'#'.$ac_id.'\').is(\':checked\')));wtfnm.action_update_button(\''.$ac_id.'\',\''.$imgurl['path'].'\');">Action Button</a>&nbsp;<span id="'.$ac_id.'_msg" class="optrmsg"></span></div>
          <div><input type="checkbox" name="'.$stg_id.'" id="'.$stg_id.'" '.$is_stage_image.' onchange="wtfnm.action_add_image(\''.$stg_id.'\');"/>&nbsp;<a href="javascript:;" title="Attach this image in the stage." onclick="jQuery(\'#'.$stg_id.'\').prop(\'checked\', !(jQuery(\'#'.$stg_id.'\').is(\':checked\')));wtfnm.action_add_image(\''.$stg_id.'\');">Attach to Stage&nbsp;<span id="'.$stg_id.'_msg" class="optrmsg"></span></div></div>';  
  	break;
  	default:
  	break;
  	}   
  }
	add_action('manage_media_custom_column', 'optinrev_manage_attachment_media_column', 10, 2);
  
  if ( is_admin() ) {  
  if ( strstr( $_SERVER['SCRIPT_NAME'], 'wp-admin/upload.php' ) ) {
  wp_enqueue_script( 'optinrev_mediajs', plugin_dir_url( __FILE__ ) . 'js/optinrev-media.js' );
  }
  }    
  
  function is_optinrev() {
    if ( isset($_GET['page']) && $page = htmlspecialchars($_GET['page']) )
    {
       if ( preg_match('/optin/', $page ) ) {
       return true;
       }       
    }
    return false;
  }
?>
