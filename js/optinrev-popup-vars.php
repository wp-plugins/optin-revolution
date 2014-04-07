<?php
 require_once( '../../../../wp-load.php' );

  if ( !defined( 'ABSPATH' ) ) die('');//keep silent
  if ( !defined( 'OPTINREV_LITE' ) ) die();

  header('Content-type: text/javascript');

  if ( isset($_GET['popup']) && intval($_GET['popup']) && $popup = esc_html( $_GET['popup'] ) )
  {

  $dir = plugin_dir_url(dirname(__FILE__));

  $optin = optinrev_get( 'optinrevolution/optin' . $popup );
  $optin = unserialize( $optin );

  $content = preg_replace("/'/","\'",preg_replace('/\s+/', ' ',stripcslashes($optin['optinrev_data'])));

  $modal_delay = (isset($optin['optinrev_delay']))?$optin['optinrev_delay']:0;

  $round_corner = (isset($optin['optinrev_border_radius']))?$optin['optinrev_border_radius']:0;

  $validate = '{}';
  if ( isset( $optin['optinrev_femail_validate'] ) && $optin['optinrev_femail_validate'] == 'on' ) {
  $validate = '{email:1}';
  }

  //Wysija
  $wysija_msg = '';
  $wysija_id = '';
  if ( isset($optin['wysija_list_id']) && $wysija_list_id = $optin['wysija_list_id'] )
  {
    $wysija_msg = 'You\'ve successfully subscribed. Check your inbox now to confirm your subscription.';
    $wysija_id = $wysija_list_id;
  }

  $optinrev_visited_once = 0;

  $show_time = optinrev_get('optinrev_show_popup');
  $ts = explode( '|', $show_time );
  $show_time = $ts[0];

  if ( $ts[0] == 'show_once_in' ) {
  $optinrev_visited_once = $ts[1];
  }

  $optinrev_ctcurl = ( isset($optin['optinrev_ctcurl']) && $optin['optinrev_foptin_active']=='constantcontact') ? $optin['optinrev_ctcurl'] : '';

  echo '/* <![CDATA[ */var purl="'. $dir .'", _0a38277f56e2d868e6088d7345f218f7 = document.createElement(\'div\'), ch = jQuery(window).height(), exh = 30, _0a38277f56e2d868e6088d7345f218f7 = jQuery(_0a38277f56e2d868e6088d7345f218f7).html(\''. $content .'\'), tshow = "'. $optinrev_play .'", isvalid = '. $validate .', mail_form_name = \''.$optin['optinrev_foptin_active'].'\',optinrev_close_button_class = \''. $optin['optinrev_close_button_class'].'\', optinrev_top_margin = '.$optin['optinrev_top_margin'].',optinrev_wbg_opacity = '.$optin['optinrev_wbg_opacity'].', modal_delay = '. $modal_delay .', box_started = 0, rnd='.$round_corner.',optinrev_visited_once='.$optinrev_visited_once.', optinrev_show_time="'. $show_time .'", optinrev_isie="'. optinrev_ie_version() .'", optinrev_wysija_id="'. $wysija_id .'" ,optinrev_wysija_msg="'. $wysija_msg .'", optinrev_ctcurl="'. $optinrev_ctcurl .'";/* ]]> */';

  } else die();
?>