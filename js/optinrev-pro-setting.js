jQuery(document).ready(function($){
    var sp = document.createElement('span');
    $(sp).attr('class','status').css('font-weight','bold');    
    var pro = {                
        msg: function( msg ) {$('.status').remove();$('.submit').append( $(sp).css('color','#ffcc00').html( msg ) );},
        load: function( msg ) {$('.status').remove();pro.msg( msg );setTimeout(function(){$('.status').fadeOut();}, 3000) },
        error: function( msg ) {$('.status').remove();$('.submit').append( $(sp).css('color','red').html( 'Error : ' + msg ) );setTimeout(function(){$('.status').remove();}, 3000)},        
        verify: function() {        
        pro.msg('Verifying member account and the domain...');        
        setTimeout(function(){
        $.post('admin-ajax.php', {action : "optinrev_action", authenticate : $('#cred_form').serialize()}, function( res ){          
                       
          res = $.trim(res);             
                        
          if ( res.indexOf('invalid_user') !== -1 ) {
          pro.error('Invalid Email.');
          return false;
          }                            
          
          if ( res.indexOf('invalid_domain') !== -1 ) {
          pro.error('You need to login to your member account and activate the domain');
          return false;
          }

          if ( res.indexOf('invalid_member') !== -1 ) {
          pro.error('Invalid Member Account');
          return false;
          }

          if ( res.indexOf('locked_member') !== -1 ) {
          pro.error('Your account has been locked. Please contact the support.');
          return false;
          }                    
          
          if ( res.indexOf('refund') !== -1 ) {
          pro.error('Product has been refund.');
          return false;
          }          
                  
          if ( res.indexOf('valid') !== -1 )
          {        
            $.post('admin-ajax.php', {action : "optinrev_action", pro_authorized : $('#cred_form').serialize()}, function( res ){
            if ($.trim(res) == 'valid') {pro.load('Successfully verified.');location.reload();}        
            });
          }
          
          return false;                    
          });}, 2000); 
        }        
        }
    $('#cred_form').submit(function(){pro.verify();return false;});    
  });  