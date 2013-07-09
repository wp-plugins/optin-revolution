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
    
    jQuery.post('admin-ajax.php', {action : "optinrev_action", optinrev_show_popup : shw}, function(res){
    jQuery('#save_showset').hide();    
    wtfn.msg('Successfully Updated.');    
    });    
  },
  upgrade: function() {
  
  jQuery.modal( '<div id="modalx" style="display:block;">'+ jQuery('#modalx').html() +'</div>', {
  containerCss:{
		backgroundColor:"#404040", 
		borderColor:"#404040", 
		height:246, 
		padding:0, 
		width:400,
    borderRadius:'4px'
	},
	overlayClose:true,
  onShow: function(dialog) {
  
  if ( jQuery.browser.msie && jQuery.browser.version == '7.0') {
      jQuery('.popup-arrow', dialog.data).css('margin-left', -423);
      jQuery('.green img', dialog.data).css('margin-left', -333);      
      jQuery(dialog.container[0]).css({'width':396, 'height':242});            
  }
  
  }    
  });
  
  }
  };