<?php if (!defined('ABSPATH')) die('You are not allowed to call this page directly.');

  define( 'OPTINREV_DIR', plugin_dir_url( __FILE__ ) );
  define( 'OPTINREV_DIR_PATH', plugin_dir_path( __FILE__ ) );
  define( 'OPTINREV_XMLRPC_URL', 'http://lic.optinrevolution.com/xmlrpc.php' );
  define( 'OPTINREV_SUPPORT', 'http://wordpress.org/support/plugin/optin-revolution/' );
  define( 'SOCIAL_URL', 'http://goo.gl/U6GWY' );
  define( 'SOCIAL_TITLE', 'Check out this KILLER FREE Wordpress plugin that allows you to create unique UNBLOCKABLE Wordpress popups!' );
  define( 'TWEET', 'https://twitter.com/share' );

  if ( !function_exists('post') ) {   
      function post( $p, $ret = false ) {
        if ( !$ret )
        echo (isset($_POST[ $p ])) ? stripcslashes($_POST[ $p ]) : '';
        else
        return (isset($_POST[ $p ])) ? stripcslashes($_POST[ $p ]) : '';
      }
  }
  
  if ( !function_exists('optinrev_callaction_btn_count') ) {
      function optinrev_callaction_btn_count() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_row( "select max(id) as ccnt from $tb_options" );
        return ( $wpdb->num_rows > 0 ) ? $res->ccnt + 1 : 0;
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
  
  if ( !function_exists('optinrev_callaction_btns') ) {
      function optinrev_callaction_btns() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare("select name, content from $tb_options where name like %s", 'optinrev_callaction_btn%' ));
        return ( $wpdb->num_rows > 0 ) ? $res : false;    
      }
  }
  
  if ( !function_exists('optinrev_uploads') ) {
      function optinrev_uploads() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare("select name, content from $tb_options where name like %s", 'optinrev_uid_%' ));
        return ( $wpdb->num_rows > 0 ) ? $res : false;    
      }
  }
  
  if ( !function_exists('optinrev_added_images') ) {
      function optinrev_added_images() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare("select name, content from $tb_options where name like %s", '%_img_uid_%' ));
        return ( $wpdb->num_rows > 0 ) ? $res : false;    
      }
  }   
    
  
  if ( !function_exists('optinrev_jsmessages') ) {
      function optinrev_jsmessages() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare("select name, content from $tb_options where name like %s", 'optinrev_jsmessages_%' ));
        return ( $wpdb->num_rows > 0 ) ? $res : 0;    
      }
  }
  
  if ( !function_exists('optinrev_action_button_uploads') ) {
      function optinrev_action_button_uploads() {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare("select name, content from $tb_options where name like %s", 'optinrev_cuid_%' ));
        return ( $wpdb->num_rows > 0 ) ? $res : false;    
      }
  }
  
  if ( !function_exists('optinrev_popups') ) {
      function optinrev_popups() {  
        $optin = array( 'optin1' => 'Optin Popup 1' );
        optinrev_update( 'optinrev_popups', serialize($optin) );
        return $optin;
      }
  }
  
  if ( !function_exists('optinrev_alert_messages') ) {
      function optinrev_alert_messages( $wtpage ) {
        $pc = unserialize(optinrev_get( $wtpage ));
        return ( isset($pc['optinrev_jspopup_images']) ) ? $pc['optinrev_jspopup_images'] : false;
      }
  }
  
  if ( !function_exists('optinrev_images') ) {
      function optinrev_images( $optin ) {
        global $wpdb;
        $tb_options = $wpdb->prefix . 'optinrev';
        $res = $wpdb->get_results( $wpdb->prepare("select name, content from $tb_options where name like %s", $optin . '_img_uid_%' ));
        return ( $wpdb->num_rows > 0 ) ? $res : 0;    
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
  
  if ( !function_exists('optinrev_tip') ) {
      function optinrev_tip( $idx, $title = null ) {
         global $plugin_page;
          
         $tip = optinrev_helper( ( ($plugin_page == 'optin')? 'admin' : 'popup_edit' ) ); 
         //direct title option
         if ( !empty($title) ) {
         $idx = rand();
         return '<a href="javascript:;" class="ifo" id="ifo'. $idx .'" title="'. $title  .'"></a>';
         }
         //array option
         if ( $tip ) {   
         return '<a href="javascript:;" class="ifo" id="ifo'. $idx .'" title="'. $tip[$idx]  .'"></a>';
         }
         return false; 
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
          wp_enqueue_style( 'optinrev-style', $dir . 'css/optinrev-style.css' );
              
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
              
          wp_enqueue_script( 'jquery_jscolor', $dir . 'jscolor/jscolor.js' ); 
          wp_enqueue_script( 'jibtn', $dir . 'js/jquery.ibutton.js' );
          wp_enqueue_script( 'jquery_metadata', $dir . 'js/jquery.metadata.js' );    
          wp_enqueue_script( 'jquery_easing', $dir . 'js/easing.js' );          
            
          //Modal    
          wp_enqueue_script( 'jquery_modaljs', $dir . 'js/jquery.simplemodal.js' );
          //IE curvy corner
          wp_enqueue_script( 'curvyc', $dir . 'js/curvycorners.js' );
         break;
         }
         
         //social network
         //wp_enqueue_script( 'tweet', 'https://platform.twitter.com/widgets.js' );       
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
                                 'action' => 'http://google.us5.list-manage1.com/subscribe/post',  
                                 'hidden' => 'mcu,mcid',
                                 'text' => 'email',
                                 'input' => 'email,mcu,mcid' 
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
    function optinrev_setcookie( $cookie ) {
        setcookie('visited_ip', $cookie, time() + 3600*24, COOKIEPATH, COOKIE_DOMAIN, false);
    }
  }
  
  if ( !function_exists('visited_ip') ) {
    function visited_ip() {
      if (!isset($_COOKIE['visited_ip'])) {
      optinrev_setcookie($_SERVER['REMOTE_ADDR']);
      }
    }
  }

  if ( !function_exists('visited_once') ) {
    function visited_once() {
      if (!isset($_COOKIE['visited_once'])) {
      optinrev_setcookie($_SERVER['REMOTE_ADDR']);
      }
    }
  }

  if ( !function_exists('visited_show_ip') ) {
    function visited_show_ip() {
      if (!isset($_COOKIE['visited_show_ip'])) {
      optinrev_setcookie($_SERVER['REMOTE_ADDR']);
      }
    }
  }

  if ( !function_exists('visited_show_ip_count') ) {
    function visited_show_ip_count() {
      if (!isset($_COOKIE['visited_show_ip_count'])) {
      optinrev_setcookie(0);
      }
    }
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
      if (isset( $_POST['optinrev_show_where'] ) && $show_on = htmlentities( $_POST['optinrev_show_where'] ))
      {
          optinrev_update( 'optinrev_show_where', $show_on );        
          exit();
      }
      
      //showing popup
      if (isset( $_POST['optinrev_show_popup'] ) && $setp = $_POST['optinrev_show_popup']) {
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
          exit();
      }
      //optinrev_pixel_tracking
      if (isset( $_POST['optinrev_pixel_tracking'] )) {
          optinrev_update( 'optinrev_pixel_tracking', $_POST['optinrev_pixel_tracking'] );        
          exit();
      }   
      //uploader
      if (isset( $_POST['optinrev_upload_id'] ) && $uid = $_POST['optinrev_upload_id']) {
          
          $path = dirname( __FILE__ ) . '/uploads/';
          
          $valid_formats = array( 'jpg', 'png', 'gif', 'bmp', 'jpeg' );
          
          $name = $_FILES['optinrev_file_upload']['name'];
          $size = $_FILES['optinrev_file_upload']['size'];
          
          if ( strlen($name) )
          {
              list($txt, $ext) = explode( '.', $name );
              if ( in_array( strtolower($ext), $valid_formats ) )
              {
                
                  $actual_image_name = md5( time() ) . "." . strtolower($ext);
                  $tmp = $_FILES['optinrev_file_upload']['tmp_name'];
                  
                  if( move_uploaded_file( $tmp, $path . $actual_image_name ) )
                  {                       
                    optinrev_update( $uid, $actual_image_name.'|'.$name );                                      
                    echo json_encode( array('action' => 'success', 'btn_count' => optinrev_unique_id()) );                      
                  }
              }
          }
          else echo 'Please select image..!';          
          
          exit();
      }
      
      //delete img
      if ( isset( $_POST['optinrev_remove_img'] ) && $img = $_POST['optinrev_remove_img'])
      {       
         optinrev_delete( $img );
         echo json_encode( array('action' => 'success') );
         exit();      
      }
      
      //delete uploads
      if ( isset( $_POST['optinrev_del_upload'] ) && $img = $_POST['optinrev_del_upload'])
      {
         $delimg = optinrev_get( $img );
         $delimg = explode( '|', $delimg );
         unlink( plugin_dir_path( __FILE__ ) . '/uploads/' . $delimg[0] );
         optinrev_delete( $img );
         echo json_encode( array('action' => 'success', 'btn_count' => optinrev_unique_id()) );
         exit();      
      }
    
      //delete action button uploads
      if ( isset( $_POST['optinrev_del_cupload'] ) && $img = $_POST['optinrev_del_cupload'])
      {
         optinrev_update( 'optinrev_add_button_briefcase', OPTINREV_DIR . 'uploads/get_access1.png' ); 
         $delimg = optinrev_get( $img );
         $delimg = explode( '|', $delimg );
         unlink( plugin_dir_path( __FILE__ ) . '/uploads/' . $delimg[0] );
         optinrev_delete( $img );
         echo json_encode( array('action' => 'success', 'btn_count' => optinrev_unique_id()) );
         exit();      
      }    
      
      //add images
      if ( isset( $_POST['optinrev_add_image'] ) && $add_img = $_POST['optinrev_add_image'] )
      {
          $add_img_id = $_POST['optinrev_curr_page'] . '_img_uid_' . optinrev_unique_id();
          
          $add_imgo = optinrev_get( $add_img );
          $name = explode( '|', $add_imgo );          
          
          $src = OPTINREV_DIR . 'uploads/' . $name[0];
            
          optinrev_update( $add_img_id, $add_imgo .'|'. $add_img );
          echo json_encode( array('action' => 'success', 'image' => $src ) );      
          exit();
      }
      
      //show
      if ( isset( $_POST['optinrev_jspopup'] ) && $jsp = $_POST['optinrev_jspopup'] )
      {       
         if ( $_POST['show'] == 'enabled' )
         optinrev_delete( $jsp );
         else
         optinrev_update( $jsp, 'disabled' );
         
         echo 'success';
         exit(); 
      }
      
      //optinrev_jspopup_images
      if ( isset( $_POST['optinrev_jspopup_images'] ) &&  $imgs = $_POST['optinrev_jspopup_images'] ) {
          $ht = '';
          $brk = 1;
          $r = 1;     
          if ( $ups = optinrev_added_images() ) { 
          foreach( $ups as $b )      
          {
            $name = explode('|', $b->content);
            $src = OPTINREV_DIR . 'uploads/' . $name[0];
            
            //messages
            $jm = optinrev_alert_messages( $imgs );        
            
            $opt = optinrev_get('optinrev_jspopup_image_' . $r);
            //if show
            $fshow = ( $opt == 'disabled' ) ? '<a href="javascript:;" style="float:left;" onclick="wtfn.jspopup( \'optinrev_jspopup_image_'. $r .'\', \'enabled\' );">Enabled</a>' : '<textarea name="optinrev_jspopup_images[]" cols="40" rows="3">'. $jm[ ($r - 1) ] .'</textarea><a href="javascript:;" class="delete-icj" onclick="wtfn.jspopup(\'optinrev_jspopup_image_'.$r.'\',\'disabled\');"></a>';        
            $ht .= '<div class="list-img" id="optinrev_jspopup_image_'.$r.'"><span class="jstxt">'. $r .'</span>'. $fshow .'</div>';
            
            if ( $brk == 5 ) {
            $brk = 0;
            $ht .= '<div style="float:left;width:100%;"></div>';
          }      
          $brk++;$r++;}} else $ht = '<b><em>No image!</em></b>';
          $ht .= '<div class="clear"></div>';
          echo $ht;
          exit();
      }
      
      //added list images
      if ( isset( $_POST['optinrev_added_images'] ) &&  $imgs = $_POST['optinrev_added_images'] ) {
          $ht = '';
          $brk = 1;     
          if ( $ups = optinrev_added_images() ) {
           
          foreach( $ups as $b )      
          {
            $name = explode('|', $b->content);
            $src = OPTINREV_DIR . 'uploads/' . $name[0];
            
            if ( file_exists( OPTINREV_DIR_PATH . 'uploads/' . $name[0]  ) ) {
              $ht .= '<div class="list-img" id="'.$b->name.'"><a href="javascript:;" class="delete-ic" onclick="wtfn.remove_img(\''. $b->name .'\',\''.$name[2].'\');"></a><img src="'.$src.'" border="0" /></div>';
              if ( $brk == 5 ) {
              $brk = 0;
              $ht .= '<div style="float:left;width:100%;"></div>';
            }
            }      
          $brk++;}} else $ht = '<b><em>No inserted images!</em></b>';
          $ht .= '<div class="clear"></div>';
          echo $ht;
          exit();
      }
    
      //Upload Images
      if ( isset( $_POST['optinrev_load_uploads'] ) && intval($_POST['optinrev_load_uploads']) ) {          
          $ht = '';
          $brk = 1;
          $exc = array( 'optin-box', 'arrow-animated' );
          
          if ( $ups = optinrev_uploads() ) {
          foreach( $ups as $b )
          {
            $name = explode('|', $b->content);
            $src = OPTINREV_DIR . 'uploads/' . $name[0];
            if ( file_exists( OPTINREV_DIR_PATH . 'uploads/' . $name[0]  ) )
            {     
                $del = ( strstr( $name[0], 'optin-box' ) ) ? '<a href="javascript:;"></a>':'<a href="javascript:;" class="delete-ic" onclick="wtfn.delete_image(\''.$b->name.'\');"></a>';
                $del = ( strstr( $name[0], 'arrow-animated' ) ) ? '<a href="javascript:;"></a>': $del;            
            
                $ht .= '<div class="list-img" id="'.$b->name.'">'.$del.'<img src="'.$src.'" border="0" /><div><a href="javascript:;" title="Insert / Add to Optin Popup" onclick="wtfn.action_add_image(\''.$b->name.'\');">Add</a></div></div>';
                if ( $brk == 5 ) {
                $brk = 0;
                $ht .= '<div style="float:left;width:100%;"></div>';
                }
            } else {
              optinrev_delete( $b->name );//id remove
            }      
          $brk++;}} else $ht = '<b><em>No uploaded images!</em></b>';
          $ht .= '<div class="clear"></div>';
          echo $ht;
          exit();
      }
    
      //Uploaded action button images
      if ( isset( $_POST['optinrev_action_button_uploads'] ) && intval($_POST['optinrev_action_button_uploads']) ) {
          $ht = '';
          $brk = 1;      
          $active_btn = optinrev_get( 'optinrev_active_action_button' );
          
          if ( $ups = optinrev_action_button_uploads() ) { 
          foreach( $ups as $b )
          {
            $name = explode('|', $b->content);
            $src = OPTINREV_DIR . 'uploads/' . $name[0];
            if ( file_exists( OPTINREV_DIR_PATH . 'uploads/' . $name[0]  ) )
            {
                $del = ( strstr( $name[0], 'get_access' ) ) ? '<a href="javascript:;"></a>':'<a href="javascript:;" class="delete-ic" onclick="wtfn.delete_button(\''.$b->name.'\');"></a>';                
                
                //set default
                if ( empty($active_btn) ) {
                    if ( substr_count($name[0], 'get_access1') ) {                        
                        $active_btn = $src;
                    }     
                }
                
                $sel = ( substr_count( basename($active_btn), $name[0] ) ) ? 'checked' : '';
                 
                $ht .= '<div class="list-img" id="'.$b->name.'">'.$del.'<img src="'.$src.'" border="0" /><div><input type="radio" name="action_button_update[]" id="action_button_update" value="'. $src .'" '.$sel.'>&nbsp;Update</div></div>';
                if ( $brk == 5 ) {
                $brk = 0;
                $ht .= '<div style="float:left;width:100%;"></div>';
            }
            } else {
            optinrev_delete( $b->name );//id remove
            }      
          $brk++;}
          $ht .= '<script type="text/javascript">
          jQuery(document).ready(function($){
             $(\'input[name="action_button_update[]"]\').change(function(){             
             wtfn.action_add_button( $(this).val() );
             });
          });
          </script>';
          
          } else $ht = '<b><em>No uploaded images!</em></b>';
          $ht .= '<div class="clear"></div>';
          echo $ht;
          exit();
      }
      
      //cloning
      if ( isset( $_POST['optinrev_popup_cloned'] ) && $cl_optin = $_POST['optinrev_popup_cloned'] ) {
          optinrev_update( $_POST['optinrev_curr_page'], optinrev_get( $cl_optin ) );        
          echo 'success';
          exit();
      }
      //reset
      if ( isset( $_POST['optinrev_popup_reset'] ) && $reset = $_POST['optinrev_popup_reset'] ) {      
          optinrev_update( $reset, optinrev_get( 'optinrev_default' ) );
          optinrev_update( 'optinrev_active_action_button', 'get_access2.png' );                  
          
          $tb_options = $wpdb->prefix . 'optinrev';
          $res = $wpdb->query( $wpdb->prepare("delete from $tb_options where name like %s", '%_img_uid_%' ) );
          
          echo 'success';             
          exit();
      }
      
      //get the validator
      if ( isset( $_POST['optinrev_mce_validator'] ) && $page = $_POST['optinrev_mce_validator'] ) {
          $p = unserialize(optinrev_get( $page ));    
          echo json_encode($p['optinrev_input_validator']);              
          exit();
      }
      
      //mail provider form
      if ( isset( $_POST['optinrev_mail_webform'] ) && $cur_page = $_POST['optinrev_mail_webform'] ) {
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
                
                $reqvalid = '';
                $req = '';
                $autotxt = '';
                
                if ( strtolower($v) != 'listname' )
                if ( strstr(strtolower($v), 'name') || strstr(strtolower($v), 'email') )                
                {
                  $req = ( isset($optin['validate'][$v]) ) ? 'checked' : '';
                  $reqvalid = 'Validate&nbsp;<input type="checkbox" name="validate['.$v.']" value="1" '.$req.'/>';
                  $autotxt = 'onblur="wtfn.input_autotext(\''.$v.'\', this.value);" id="'. $v .'"';
                }
                    
                if ( $prov == 'mailchimp' )
                $lbl = ucfirst(str_replace('Mc', '', $lbl));
                
                //if has an 'id'
                $lbl = str_replace(' Id', ' ID', $lbl);      
                    
                $htm .= '<div class="row"><label>'.$lbl.'</label><input type="text" name="optinrev_email_form['. $_POST['optinrev_mail_provider'] .']['.$v.']" '.$autotxt.' value="'.$vl.'" size="40">&nbsp;'. $reqvalid .'&nbsp;'.$fname.'</div>';
                      
                }              
              
              echo $htm;
          }
          exit();
      }
      //Member verification
      if ( isset( $_POST['authenticate'] ) && $user = htmlspecialchars($_POST['authenticate']) )
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
      if ( isset( $_POST['pro_authorized'] ) && $pro = htmlspecialchars($_POST['pro_authorized']) )
      {   
          parse_str( str_replace('amp;','', $pro), $post );
          optinrev_update( 'optinrev_pro_authorized', serialize($post) );
          echo 'valid';
          exit;
      }

      //clear analytics each optin  
      if ( isset( $_POST['clear_analytics'] ) && intval( $_POST['clear_analytics'] ) && $optin = htmlspecialchars($_POST['clear_analytics']) )
      {   
          $tb = $wpdb->prefix . 'wotoptin_analytics';
          $wpdb->query( $wpdb->prepare("delete from $tb where optin = %d", $optin) );
          echo 'success';
          exit();
      }
      //clear all analytics
      if ( isset( $_POST['clear_all_analytics'] ) && intval( $_POST['clear_all_analytics'] ) && $optin = htmlspecialchars($_POST['clear_all_analytics']) )
      {   
          $wpdb->query( "delete from " . $wpdb->prefix . "wotoptin_analytics" );
          echo 'success';
          exit();
      }
      //set autosave      
      if ( isset( $_POST['optinrev_autosave'] ) && $autosave = htmlspecialchars($_POST['optinrev_autosave']) )
      {             
          optinrev_update( 'optinrev_autosave', $autosave );
          exit();
      }
      //set poweredby      
      if ( isset( $_POST['optinrev_poweredby'] ) && $poweredby = htmlspecialchars($_POST['optinrev_poweredby']) )
      {             
          optinrev_update( 'optinrev_poweredby', $poweredby );
          exit();
      }
      
      //optinrev_add_image_briefcase
      if ( isset( $_POST['optinrev_add_image_briefcase'] ) && $img = htmlspecialchars($_POST['optinrev_add_image_briefcase']) )
      {   
          $img_id = htmlspecialchars($_POST['optinrev_curr_page']) . '_images_' . optinrev_unique_id();          
          optinrev_update( $img_id, $img );          
          exit();
      }
      
      //optinrev_del_image_briefcase
      if ( isset( $_POST['optinrev_del_image_briefcase'] ) && $img = htmlspecialchars($_POST['optinrev_del_image_briefcase']) )
      {   
          $img_id = htmlspecialchars($_POST['optinrev_curr_page']) . '_delete_images_' . optinrev_unique_id();          
          optinrev_update( $img_id, $img );          
          exit();
      }
      
      //optinrev_add_action button_briefcase
      if ( isset( $_POST['optinrev_add_button_briefcase'] ) && $img = htmlspecialchars($_POST['optinrev_add_button_briefcase']) )
      {             
          optinrev_update( 'optinrev_add_button_briefcase', $img );          
          exit();
      }      
    }}//end action callback
?>