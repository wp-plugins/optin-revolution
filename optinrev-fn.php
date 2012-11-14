<?php if (!defined('ABSPATH')) die('You are not allowed to call this page directly.');

  define( 'OPTINREV_DIR', plugin_dir_url( __FILE__ ) );
  define( 'OPTINREV_DIR_PATH', plugin_dir_path( __FILE__ ) );
  define( 'OPTINREV_XMLRPC_URL', 'http://lic.optinrevolution.com/xmlrpc.php' );
  define( 'OPTINREV_SUPPORT', 'http://wordpress.org/support/plugin/optin-revolution/' );
  define( 'OPTINREV_SOCIAL_URL', 'http://goo.gl/U6GWY' );
  define( 'OPTINREV_SOCIAL_TITLE', 'Check out this KILLER FREE Wordpress plugin that allows you to create unique UNBLOCKABLE Wordpress popups!' );
  define( 'OPTINREV_TWEET', 'https://twitter.com/share' );
  
  //patch for w3totalcache  
  if ( defined('W3TC') ) {
      if ( !get_option('optinrev_w3tc_patch_request') )
      {
          $w3file_req = W3TC_LIB_W3_DIR . '/Request.php';
          if ( is_writable( $w3file_req ) )
          {   
              if ( $w3req = @file_get_contents( $w3file_req ) ) {         
                  $w3req = preg_replace('/array_merge\(*.*\)/', 'array_merge( (array)$_GET, (array)$_POST )', $w3req);
                  if ( $hd = @fopen( W3TC_LIB_W3_DIR . '/Request.php', 'c+' ) ) {
                      @fwrite( $hd, $w3req );
                      update_option( 'optinrev_w3tc_patch_request', time() );   
                  }
                  fclose( $hd );
              }
          }          
      }
  }
  
  if ( !function_exists('is_optinrev') ) {
      function is_optinrev() {
          if ( isset($_GET['page']) && $page = esc_html($_GET['page']) ){
          if ( preg_match('/optin|optin1|optin-pro-settings/', $page ) ) { return true; }
          } 
          return false;
      }
  }  

  if ( !function_exists('optinrev_post') ) {   
      function optinrev_post( $p, $ret = false ) {
        if ( !$ret )
        echo (isset($_POST[ $p ])) ? stripcslashes($_POST[ $p ]) : '';
        else
        return (isset($_POST[ $p ])) ? stripcslashes($_POST[ $p ]) : '';
      }
  }
  
  if ( !function_exists('optinrev_unique_id') ) {
      function optinrev_unique_id() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_row( "select max(id) as ccnt from $tb_options" );
        return ( $wpdb->num_rows > 0 ) ? $res->ccnt + 1 : 0;
      }
  }
  
  if ( !function_exists('optinrev_popups') ) {
      function optinrev_popups() {  
        $optin = array( 'optin1' => 'Optin Popup 1' );
        optinrev_update( 'optinrev_popups', serialize($optin) );
        return $optin;
      }
  }  
  
  if ( !function_exists('optinrev_update') ) {
      function optinrev_update( $name, $value ) {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        
        if ( optinrev_get( $name ) ) {
          $wpdb->update( $tb_options, array('content' => $value), array('name' => $name)  );
          } else {
          $wpdb->insert( $tb_options, array('name' => $name, 'content' => $value) );
        }    
      }
  }
  
  if ( !function_exists('optinrev_get') ) {
      function optinrev_get( $name ) {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_row( $wpdb->prepare("select name, content from $tb_options where name = %s", $name) );
        return ( $wpdb->num_rows > 0 ) ? $res->content : false;
      }
  }
  
  if ( !function_exists('optinrev_delete') ) {
      function optinrev_delete( $name ) {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->query( $wpdb->prepare("delete from $tb_options where name = %s", $name) );
      }
  }
  
  if ( !function_exists('optinrev_browser_info') ) {
      function optinrev_browser_info($agent=null) 
      {  
        $known = array('msie', 'firefox', 'safari', 'opera', 'netscape', 'chrome');  
        $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
        $pattern = '#(?<browser>' . join('|', $known) .
          ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
        
        if (!preg_match_all($pattern, $agent, $matches)) return array();
        
        $i = count($matches['browser'])-1;
        return (isset($matches['browser'][$i]))?$matches['browser'][$i]: 'unknown';
      }
  }
  
  if ( !function_exists('optinrev_is_pro_authorized') ) {
      function optinrev_is_pro_authorized() 
      {
         if ( optinrev_get('optinrev_pro_authorized') ) {return true;} return false;
      }
  }
  
  if ( !function_exists('optinrev_is_pro_installed') ) {
      function optinrev_is_pro_installed() {
         if ( optinrev_get('optinrev_pro_installed') ) {return true;} return false;
      }
  }
  
  if ( !function_exists('optinrev_manually_queue_update') ) {
      function optinrev_manually_queue_update()
      {
         $transient = get_site_transient("update_plugins");
         set_site_transient("update_plugins", optinrev_transient_update_plugins($transient));
      }
  }

  if ( !function_exists('optinrev_enqueue') ) {
      function optinrev_enqueue( $option ) {
         global $plugin_page;
         $dir = OPTINREV_DIR;
         
         switch( $option ) {
         case 0:          
          wp_enqueue_style( 'optinrev_style', $dir . 'css/optinrev-style.css' );
              
          wp_enqueue_script( 'jibtn', $dir . 'js/jquery.ibutton.js' );
          wp_enqueue_script( 'jsadmin', $dir . 'js/optinrev-admin.js' );          
         break;
         case 1:
          //Style
          wp_enqueue_style( 'jqueryui_css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/ui-lightness/jquery-ui.css' );
          wp_enqueue_style( 'optinrev_css', $dir . 'optinrev-css.php?popup=' . $plugin_page .'&view=1' );
          wp_enqueue_style( 'optinrev-style', $dir . 'css/optinrev-style.css' );
          
          // wp js      
          wp_enqueue_script( 'jquery' );
          wp_enqueue_script( 'jquery-ui-slider' );
          wp_enqueue_script( 'jquery-form' );
              
              
          wp_deregister_script( 'jquery_jscolor' );
          wp_register_script( 'jquery_jscolor', $dir . 'jscolor/jscolor.js' );              
          wp_enqueue_script( 'jquery_jscolor' );
           
          wp_deregister_script( 'jibtn' );
          wp_register_script( 'jibtn', $dir . 'js/jquery.ibutton.js' );
          wp_enqueue_script( 'jibtn' );
          
          
          wp_deregister_script( 'jquery_metadata' );
          wp_register_script( 'jquery_metadata', $dir . 'js/jquery.metadata.js' );
          wp_enqueue_script( 'jquery_metadata' );
          
          wp_deregister_script( 'jquery_easing' );
          wp_register_script( 'jquery_easing', $dir . 'js/easing.js' );    
          wp_enqueue_script( 'jquery_easing', $dir . 'js/easing.js' );          
            
          //Modal    
          wp_deregister_script( 'jquery_modaljs' );
          wp_register_script( 'jquery_modaljs', $dir . 'js/jquery.simplemodal.js' );
          wp_enqueue_script( 'jquery_modaljs' );
          //IE curvy corner
          wp_deregister_script( 'curyvc' );
          wp_register_script( 'curvyc', $dir . 'js/curvycorners.js' );
          wp_enqueue_script( 'curvyc' );
         break;
         case 2:
         wp_enqueue_style( 'optinrev_style', $dir . 'css/optinrev-style.css' );         
         break;
         }         
      }
  }
  
  if ( !function_exists('optinrev_mail_providers') ) {
      function optinrev_mail_providers() {
        //mail provider inputs
        $mailpro = array (
                    'aweber' => array(  
                                 'action' => 'http://www.aweber.com/scripts/addlead.pl', 
                                 'hidden' => 'listname,meta_web_form_id,meta_message,meta_adtracking,redirect,meta_redirect_onlist,meta_required,pixel_tracking_id',
                                 'text' => 'name,email',
                                 'input' => 'name,email,listname,meta_web_form_id,meta_message,meta_adtracking,redirect,meta_redirect_onlist,pixel_tracking_id'
                                ),
                                
                    'icontact' => array (//specialid:{} will pick the listid
                                 'action' => 'https://app.icontact.com/icp/signup.php',  
                                 'hidden' => 'listid,specialid,clientid,formid,reallistid,doubleopt,redirect,errorredirect',
                                 'text' => 'fields_email,fields_fname,fields_lname',
                                 'input' => 'fields_email,fields_fname,fields_lname,listid,specialid,clientid,formid,reallistid,doubleopt,redirect,errorredirect'
                                ),
                                
                    'getresponse' => array (
                                 'action' => 'https://app.getresponse.com/add_contact_webform.html',  
                                 'hidden' => 'webform_id',
                                 'text' => 'name,email',
                                 'input' => 'name,email,webform_id'
                                ),                                
                    'mailchimp' => array (
                                 'action' => 'http://mailchimp.us1.list-manage.com/subscribe/post',  
                                 'hidden' => 'mcu,mcid,mcaction',
                                 'text' => 'email',
                                 'input' => 'email,mcu,mcid,mcaction' 
                                ),
                                
                    'constantcontact' => array (
                                 'action' => 'http://visitor.r20.constantcontact.com/d.jsp',  
                                 'hidden' => 'llr,m,p',
                                 'text' => 'email',
                                 'input' => 'email,llr,m,p' 
                                )                                
                                );
        optinrev_update( 'optinrev_mail_providers', serialize( $mailpro ) );      
      }  
  }

  if ( !function_exists('optinrev_setcookie') ) {
    function optinrev_setcookie( $cookie_name, $cookie_value ) {
        @setcookie( $cookie_name, $cookie_value, time() + 3600*24, COOKIEPATH, COOKIE_DOMAIN, false );
    }    
  }
  
  if ( !function_exists('optinrev_visited_ip') ) {
    function optinrev_visited_ip() {
      if (!isset($_COOKIE['optinrev_visited_ip'])) {                       
      optinrev_setcookie( 'optinrev_visited_ip', $_SERVER['REMOTE_ADDR'] );
      }
    }
    add_action( 'init', 'optinrev_visited_ip');
  }

  if ( !function_exists('optinrev_visited_once') ) {
    function optinrev_visited_once() {
      if (!isset($_COOKIE['optinrev_visited_once'])) { 
      optinrev_setcookie( 'optinrev_visited_once', $_SERVER['REMOTE_ADDR'] );
      }
    }
    add_action( 'init', 'optinrev_visited_once' );
  }

  if ( !function_exists('optinrev_session_browser') ) {
    function optinrev_session_browser() {
      if (!isset($_COOKIE['optinrev_session_browser'])) {
      @setcookie( 'optinrev_session_browser', $_SERVER['REMOTE_ADDR'] , 0, COOKIEPATH, COOKIE_DOMAIN, false );      
      }
    }
    add_action( 'init', 'optinrev_session_browser' );
  }
  

  if ( !function_exists('optinrev_check_update') ) {
      function optinrev_check_update() {
        global $wp_version, $optinrev_installed_version;  
        $blog = urlencode( get_option('home') );
    	  // use the WP HTTP class if it is available	
    		$http_args = array(
    			'body'			=> "blog=$blog&version=$optinrev_installed_version",
    			'headers'		=> array(
    				'Content-Type'	=> 'application/x-www-form-urlencoded; ' .
    									'charset=' . get_option( 'blog_charset' ),
    				'Host'			=> 'lic.optinrevolution.com',
    				'User-Agent'	=> "WordPress/{$wp_version}"
    			),
    			'httpversion'	=> '1.0',
    			'timeout'		=> 15
    		);
    		
    		$response = wp_remote_post( 'http://lic.optinrevolution.com', $http_args );
    		if ( is_wp_error( $response ) )
    		return '';
    
    		return true;
      }
  }

  if ( !function_exists('optinrev_get_image') ) {
      function optinrev_get_image( $optin ) {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare( "select name, content from $tb_options where name like %s", '%'. $optin .'_images_%' ) );        
        return ( $wpdb->num_rows > 0 ) ? $res : false;      
      }
  }
  
  if ( !function_exists('optinrev_delete_image') ) {
      function optinrev_delete_image( $optin ) {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare( "select name, content from $tb_options where name like %s", '%'. $optin .'_delete_images_%' ) );        
        return ( $wpdb->num_rows > 0 ) ? $res : false;      
      }
  }
  
  if ( !function_exists('optinrev_download_url') ) {
  function optinrev_download_url()
  {
    include_once( ABSPATH . 'wp-includes/class-IXR.php' );
    
    if ( $auth = optinrev_get('optinrev_pro_authorized') )
    {
        $auth = unserialize( $auth );
        $client = new IXR_Client( OPTINREV_XMLRPC_URL );
    
        if( !$client->query( 'proplug.get_download_url', $auth['amember_receipt'], $auth['amember_email'], $auth['amember_pass'], 'pro', get_bloginfo('url') ) )
        return false;
    
        return $client->getResponse();
    }
    
    return false;
  }
  }
  
  if ( !function_exists('optinrev_get_media') ) {
      function optinrev_get_media( $id ) {
      global $wpdb;
      $tb_posts = $wpdb->prefix . 'posts';
      $res = $wpdb->get_row( $wpdb->prepare( "select ID, post_title, post_name, guid from $tb_posts where ID = %d", $id ) );
      return ( $wpdb->num_rows > 0 ) ? $res : false;
      }  
  }
  
  
  if ( !function_exists('optinrev_has_optinmedia') ) {      
      function optinrev_has_optinmedia( $id, $type = 'action_button' ) {
      return ( $m = optinrev_get( $type .'_'. $id ) );
      }
  }
  
  //action button  
  if ( !function_exists('optinrev_get_action_button') ) {      
      function optinrev_get_action_button() {
      global $wpdb;
      $cr_btn = basename(optinrev_get('optinrev_active_action_button'));
      $cr_btn = explode( '.', $cr_btn );      
      $tb_posts = $wpdb->prefix . 'posts';
      $res = $wpdb->get_row( $wpdb->prepare( "select ID, post_title, post_name, guid from $tb_posts where guid like %s", '%'.$cr_btn[0].'%' ) );
      return ( $wpdb->num_rows > 0 ) ? $res : false;      
      }
  }
  
  if ( !function_exists( 'optinrev_socials' ) ) {
      function optinrev_socials() {
      echo '
          <ul>
              <li><a href="http://www.facebook.com/share.php?u='.OPTINREV_SOCIAL_URL.'&title='.OPTINREV_SOCIAL_TITLE.'" title="'.OPTINREV_SOCIAL_TITLE.'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" class="flike"></a></li>
              <li><a href="https://twitter.com/share?url='.OPTINREV_SOCIAL_URL.'&text='.OPTINREV_SOCIAL_TITLE.'" title="'.OPTINREV_SOCIAL_TITLE.'" target="_new" class="tweet"></a></li>
              <li><a href="https://plus.google.com/share?url='.OPTINREV_SOCIAL_URL.'" title="'.OPTINREV_SOCIAL_TITLE.'" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" class="googleplus"></a></li>
              <li><a href="http://del.icio.us/post?url='.OPTINREV_SOCIAL_URL.'&title='.OPTINREV_SOCIAL_TITLE.'" title="'.OPTINREV_SOCIAL_TITLE.'" target="_new" class="delicious"></a></li>
              <li><a href="http://www.stumbleupon.com/submit?url='.OPTINREV_SOCIAL_URL.'" title="'.OPTINREV_SOCIAL_TITLE.'" target="_new" class="stumbleupon"></a></li>
              <li><a href="http://digg.com/submit?url='.urlencode(OPTINREV_SOCIAL_URL).'&title='.OPTINREV_SOCIAL_TITLE.'" title="'.OPTINREV_SOCIAL_TITLE.'" target="_new" class="digg"></a></li>
              <li><a href="http://www.linkedin.com/shareArticle?mini=true&url='.urlencode(OPTINREV_SOCIAL_URL).'&title='.OPTINREV_SOCIAL_TITLE.'&summary='.OPTINREV_SOCIAL_TITLE.'" title="'.OPTINREV_SOCIAL_TITLE.'" target="_new" class="inshare"></a></li>
              <li><a href="http://pinterest.com/pin/create/button/?url='.urlencode(OPTINREV_SOCIAL_URL).'&media=http://optinrevolution.com/img/pin.png&description='.OPTINREV_SOCIAL_TITLE.'" title="'.OPTINREV_SOCIAL_TITLE.'" target="_new" class="pinit"></a></li>
          </ul>      
      ';
      }
  }
  
  if ( !function_exists( 'optinrev_paypal_donate' ) ) {
      function optinrev_paypal_donate() {
      echo '
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
      <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
      </form>      
      ';
      }
  }
  
  if ( !function_exists( 'optinrev_cssminify' ) ) {
      function optinrev_cssminify($data)
      {
        $data = preg_replace( '#/\*.*?\*/#s', '', $data );
        // remove single line comments, like this, from // to \\n
        //$data = preg_replace('/(\/\/.*\n)/', '', $data);
        // remove new lines \\n, tabs and \\r
        $data = preg_replace('/(\t|\r|\n)/', '', $data);
        // replace multi spaces with singles
        $data = preg_replace('/(\s+)/', ' ', $data);
        //Remove empty rules
        $data = preg_replace('/[^}{]+{\s?}/', '', $data);
        // Remove whitespace around selectors and braces
        $data = preg_replace('/\s*{\s*/', '{', $data);
        // Remove whitespace at end of rule
        $data = preg_replace('/\s*}\s*/', '}', $data);
        // Just for clarity, make every rules 1 line tall
        //$data = preg_replace('/}/', "}\n", $data);
        $data = str_replace( ';}', '}', $data );
        $data = str_replace( ', ', ',', $data );
        $data = str_replace( '; ', ';', $data );
        $data = str_replace( ': ', ':', $data );
        $data = preg_replace( '#\s+#', ' ', $data );
        return $data;
      }
  }
  
  //utils
  if ( !function_exists('hex2dec') ) {
  function hex2dec( $hex ) {$color = str_replace('#', '', $hex);$ret = ARRAY('r' => hexdec(substr($color, 0, 2)),'g' => hexdec(substr($color, 2, 2)),'b' => hexdec(substr($color, 4, 2)));return $ret;}
  }
  if ( !function_exists('is_ie') ) {
  function is_ie(){if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {return true;} else {return false;}}
  }
  if ( !function_exists('getContrast50') ) {
  function getContrast50($hexcolor){return (hexdec($hexcolor) > 0xffffff/2) ? 'black':'white';}
  }
  
        

/**
 * AJAX Callback action
 */ 

 if ( !function_exists('optinrev_action_callback') )
 {
     function optinrev_action_callback()
     {  
      if ( function_exists('current_user_can') && !current_user_can('manage_options') )
    	die('');//keep silent;
      
      global $wpdb;
      
      //saving setup    
      if (isset( $_POST['save_setup_settings'] ))
      {        
          optinrev_update( $_POST['save_setup_settings'], serialize($_POST) );
                                                    
          echo 'success';                
          exit();
      }
      
      //enabled/disabled
      if (isset( $_POST['optinrev_popup_enabled'] ))
      {             
          optinrev_update( 'optinrev_popup_enabled', $_POST['optinrev_popup_enabled'] );          
          exit();
      }
      //enabled/disabled  
      if (isset( $_POST['optin_popup'] )) {      
          optinrev_update( $_POST['optin_popup'], $_POST['enabled'] );        
          exit();
      }
      //optinrev_show_where
      if (isset( $_POST['optinrev_show_where'] ) && $show_on = esc_html( $_POST['optinrev_show_where'] ))
      {   
          optinrev_update( 'optinrev_show_where', $show_on );
          exit();
      }
      
      //showing popup
      if (isset( $_POST['optinrev_show_popup'] ) && $setp = esc_html($_POST['optinrev_show_popup'])) {
          global $blog_cache_dir;
          
          $setp_ar = explode('|',  $setp );
          $setv = $setp;
          if ( count($setp_ar) > 0 ) {
               if ( $setp_ar[0] == 'show_once_in' )
               {           
                  $et = strtotime( '+' . $setp_ar[1] . ' day' );
                  $setv = $setv. '|' . time() .'|'. $et;
               }
          }
          optinrev_update( 'optinrev_show_popup', $setv );
          
          @setcookie( 'optinrev_visited_ip', null, time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );
          @setcookie( 'optinrev_visited_once', null, time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );
          @setcookie( 'optinrev_session_browser', null, time() - 3600, COOKIEPATH, COOKIE_DOMAIN, false );
          
          unset($_COOKIE['optinrev_session_browser'], $_COOKIE['optinrev_visited_once'], $_COOKIE['optinrev_visited_ip'] );
          unset($_SESSION['optinrev_session_browser'], $_SESSION['optinrev_visited_once'], $_SESSION['optinrev_visited_ip'] );
          
          if ( function_exists('wp_cache_clear_cache') ) {
              wp_cache_clear_cache();
          }          
                  
          exit();
      }
      //optinrev_pixel_tracking                                                   
      if (isset( $_POST['optinrev_pixel_tracking'] )) {
          optinrev_update( 'optinrev_pixel_tracking', esc_html($_POST['optinrev_pixel_tracking']) );        
          exit();
      }
      
      //delete img
      if ( isset( $_POST['optinrev_remove_img'] ) && $img = esc_html($_POST['optinrev_remove_img']))
      {       
         optinrev_delete( $img );
         echo json_encode( array('action' => 'success') );
         exit();      
      }
      
      //add images
      if ( isset( $_POST['optinrev_add_image'] ) && $add_img = esc_html($_POST['optinrev_add_image']) )
      {          
          //images from wp/content/uploads
          $img_id = explode( '_', $add_img );
          $img_id = $img_id[2];
          
          $add_img_id = $_POST['optinrev_curr_page'] . '_img_uid_' . optinrev_unique_id() . '_' . $img_id;
          
          $img = optinrev_get_media( $img_id );    
          $imgurl = parse_url( $img->guid );          
            
          optinrev_update( $add_img, basename( $imgurl['path'] ) .'|'. $add_img );                      
          echo json_encode( array('action' => 'success', 'image' => $imgurl['path'] ) );                
          exit();
      }

      //reset
      if ( isset( $_POST['optinrev_popup_reset'] ) && $reset = esc_html($_POST['optinrev_popup_reset']) ) {      
          optinrev_update( $reset, optinrev_get( 'optinrev_default' ) );
          optinrev_update( 'optinrev_active_action_button', 'get_access2.png' );                  
          
          $tb_options = $wpdb->prefix . 'optinrev';
          $wpdb->query( $wpdb->prepare("delete from $tb_options where name like %s", '%_img_uid_%' ) );
          $wpdb->query( $wpdb->prepare("delete from $tb_options where name like %s", 'stage_img_%' ) );
          $wpdb->query( $wpdb->prepare("delete from $tb_options where name like %s", 'action_button_%' ) );
          
          echo 'success';             
          exit();
      }
      
      //get the validator
      if ( isset( $_POST['optinrev_mce_validator'] ) && $page = esc_html($_POST['optinrev_mce_validator']) ) {
          $p = unserialize(optinrev_get( $page ));    
          echo json_encode($p['optinrev_input_validator']);              
          exit();
      }
      
      //mail provider form
      if ( isset( $_POST['optinrev_mail_webform'] ) && $cur_page = esc_html($_POST['optinrev_mail_webform']) ) {
          //optin setup
          $optin = unserialize( optinrev_get( $cur_page ) );
                      
          if ( $optin )
          {
              //providers
              $prov = $_POST['optinrev_mail_provider']; 
              $mpro = unserialize( optinrev_get( 'optinrev_mail_providers' ) );
              $mdta = explode( ',', $mpro[ $prov ]['input'] );
              //input text
              $inputs = $optin['optinrev_email_form'][ $prov ];
              $inputs_enabled = $optin['optinrev_input_enabled'];              
              
              $htm = '';      
              foreach( $mdta as $v )
              {    
                $fable = (isset($inputs_enabled[ $v ]))?$inputs_enabled[ $v ] : false;
                            
                $vl = ( isset( $inputs[ $v ] ) ) ? $inputs[ $v ] : '';
                $lbl = ucwords( str_replace( '_', ' ', $v ) );
                  
                if ( strtolower($v) != 'listname' )  
                $fname = ( strstr(strtolower($v), 'name') ) ? '<select name="optinrev_input_enabled['.$v.']" id="optinrev_input_'. $v .'" onchange="wtfn.input_setenabled(\''.strtolower($v).'\',this.value);"><option value="1" '. (($fable)?'selected':'') .'>Enabled</option><option value="0" '. ((!$fable)? 'selected':'' ) .'>Disabled</option></select>' : '';                
                else
                $fname = '';
                
                $reqvalid = $req = $autotxt = $ismchimp = '';
                
                if ( strtolower($v) != 'listname' )
                if ( strstr(strtolower($v), 'name') || strstr(strtolower($v), 'email') )                
                {
                  $req = ( isset($optin['validate'][$v]) ) ? 'checked' : '';
                  $reqvalid = 'Validate&nbsp;<input type="checkbox" name="validate['.$v.']" value="1" '.$req.'/>';
                  $autotxt = 'onblur="wtfn.input_autotext(\''.$v.'\', this.value);" id="'. $v .'"';
                }
                    
                if ( $prov == 'mailchimp' ){
                if ( $lbl == 'Mcaction' )
                $ismchimp = '<div class="row"><label>&nbsp;</label><span class="note">Example Value: <b>mailchimp.us1.list-manage.com</b> ( Replace with your url with your action value information )</span></div>';
                
                $vl = str_replace( 'http://', '', $vl );                
                $lbl = ucfirst(str_replace('Mc', '', $lbl));                
                }
                
                //if has an 'id'
                $lbl = str_replace(' Id', ' ID', $lbl);      
                    
                $htm .= '<div class="row"><label>'.$lbl.'</label><input type="text" name="optinrev_email_form['. $_POST['optinrev_mail_provider'] .']['.$v.']" '.$autotxt.' value="'.$vl.'" size="40">'.$ismchimp.'&nbsp;'. $reqvalid .'&nbsp;'.$fname.'</div>';
                      
                }              
              
              echo $htm;
          }
          exit();
      }
      //Member verification
      if ( isset( $_POST['authenticate'] ) && $user = esc_html($_POST['authenticate']) )
      {
          include_once( ABSPATH . 'wp-includes/class-IXR.php' );
          
          parse_str( str_replace('amp;','', $user), $post );
          
          if ( empty($post['amember_receipt']) ) { echo 'invalid_order'; exit(); }
          if ( empty($post['amember_email']) || empty($post['amember_pass']) ) { echo 'invalid_user'; exit(); }        
          if ( !strpos( $post['amember_email'], '@' ) ) { echo 'invalid_user'; exit(); }
    
          $client = new IXR_Client( OPTINREV_XMLRPC_URL );
          
          if( $client->query( 'proplug.is_user_authorized', $post['amember_receipt'], $post['amember_email'], $post['amember_pass'] ) ) {
          echo $client->getResponse();          
          } else {
          echo 'invalid';
          }
          
          exit;
      }
      //Save the info
      if ( isset( $_POST['pro_authorized'] ) && $pro = esc_html($_POST['pro_authorized']) )
      {   
          parse_str( str_replace('amp;','', $pro), $post );
          optinrev_update( 'optinrev_pro_authorized', serialize($post) );
          echo 'valid';
          exit;
      }

      //set autosave      
      if ( isset( $_POST['optinrev_autosave'] ) && $autosave = esc_html($_POST['optinrev_autosave']) )
      {             
          optinrev_update( 'optinrev_autosave', $autosave );
          exit();
      }
      //set poweredby      
      if ( isset( $_POST['optinrev_poweredby'] ) && $poweredby = esc_html($_POST['optinrev_poweredby']) )
      {             
          optinrev_update( 'optinrev_poweredby', $poweredby );
          exit();
      }
      
      //optinrev_add_image_briefcase
      if ( isset( $_POST['optinrev_add_image_briefcase'] ) && $img = esc_html($_POST['optinrev_add_image_briefcase']) )
      {   
          $img_id = esc_html($_POST['optinrev_curr_page']) . '_images_' . optinrev_unique_id();          
          optinrev_update( $img_id, $img );          
          exit();
      }
      
      //optinrev_del_image_briefcase
      if ( isset( $_POST['optinrev_del_image_briefcase'] ) && $img = esc_html($_POST['optinrev_del_image_briefcase']) )
      {   
          $img_id = esc_html($_POST['optinrev_curr_page']) . '_delete_images_' . optinrev_unique_id();          
          optinrev_update( $img_id, $img );          
          exit();
      }
      
      //optinrev_add_action button_briefcase
      if ( isset( $_POST['optinrev_add_button_briefcase'] ) && $img = esc_html($_POST['optinrev_add_button_briefcase']) )
      {             
          optinrev_update( 'optinrev_add_button_briefcase', $img );          
          exit();
      }
      
      //remove an image to the stage
      if ( isset( $_POST['optinrev_remove_object'] ) && $img_id = esc_html( $_POST['optinrev_remove_object'] ) ) {          
          optinrev_delete( $img_id );
          exit();
      }
      
      //getimage size
      if ( isset( $_POST['optinrev_getimagesize'] ) && $img = esc_html( $_POST['optinrev_getimagesize'] ) ) {
          if ( list( $width, $height ) = @getimagesize( $_SERVER['DOCUMENT_ROOT'] . $img ) ) {
              $imgd = array( 'width' => $width, 'height' => $height );
              echo json_encode($imgd);
              } else {
              echo '0';
          }                    
          exit();
      }      
    }}//end action callback
?>