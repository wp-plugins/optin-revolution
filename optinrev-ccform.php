<?php
  //Constant Contact Form

  if ( !isset( $_POST['url'] ) ) die('0');

  $share_url = strip_tags($_POST['url']);

  if ( !strstr( $share_url, 'constantcontact.com' ) ) die('0');

  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $share_url,
      CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
      CURLOPT_FOLLOWLOCATION => 1
  ));

  $res = curl_exec($curl);
  curl_close($curl);

  if ( $res ) {

  $res = preg_replace('/\s+/',' ', $res);
  $res = preg_replace('/\n\r/','', $res);
  $res = preg_replace('/<div[^>]+>|<\/div>|<span[^>]+>|<\/span>|<span>|<fieldset>|<\/fieldset>/','', $res);
  $res = preg_replace('/<p>.*<\/p>/','', $res);
  $res = preg_replace('/\/manage\/optin\?/','http://visitor.r20.constantcontact.com/manage/optin?', $res);

  preg_match('/<form[^>]+>.*<\/form>/i', $res, $m);

  echo ( count($m) > 0 ) ? $m[0] : '0';

  } else echo '0';

?>