var imgs = ['ibutton-slider-default.png','tipsy.png','info-icon.png'];  
var jsoptin_load = { init: function(){
      jQuery(imgs).each(function(i,v){
      _im = jQuery("<img>");_im.hide();
      _im.bind("load", function(){ jQuery(this).hide(); });
      jQuery('body').append(_im);
      _im.attr('src', wtp + 'images/' + v );
      });  
  }};

var wtfn = {
  //message alert
  msgx: function( msg, x, y ) {
    jQuery('#post-message').stop().animate({left: x, top: y}).show().html( msg );    
    setTimeout(function(){ jQuery('#post-message').hide(); }, 2000);
  },
  msg: function( msg ) {
    jQuery('#post-message').hide();
    jQuery('#post-message').stop().animate({top: jQuery('#post-message').offset().top + (jQuery(window).height() / 2), left : (jQuery(window).width() / 2) - 200}).fadeIn().html( msg );    
    setTimeout(function(){ jQuery('#post-message').fadeOut(); }, 2000);
  },  
 optinrev_show_popup : function( state )
  {     
    var shw = state;
    if (shw.length == 0) return false;
    
    if ( state === 'show_once_in' ) {    
    shw = ( state == 'show_once_in' ) ? 'show_once_in|' + jQuery('#show_optin_days').val() : state;
    jQuery('#optinrev_time_session').val(1);
    jQuery('#show_once_in').attr('checked', true);
    } else {
    jQuery('#show_optin_days').val(1);
    }
    
    if ( state === 'show_times_per_session' ) {
    shw = ( state == 'show_times_per_session' ) ? 'show_times_per_session|' + jQuery('#optinrev_time_session').val() : shw;
    jQuery('#show_optin_days').val(1);
    jQuery('#show_times_per_session').attr('checked', true);
    } else {
    jQuery('#optinrev_time_session').val(1);
    }    
    
    jQuery.post('admin-ajax.php', {action : "optinrev_action", optinrev_show_popup : shw}, function(res){wtfn.msg('Successfully Updated.');});    
  }
  };