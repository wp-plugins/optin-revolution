jQuery(document).ready(function($){
    var sp = document.createElement('span');
    $(sp).attr('class','status').css('font-weight','bold');    
    var pro = {                
        msg: function( msg ) {$('.status').remove();$('.submit').append( $(sp).css('color','#ffcc00').html( msg ) );},
        load: function( msg ) {$('.status').remove();pro.msg( msg );setTimeout(function(){$('.status').fadeOut();}, 2000) },
        error: function( msg ) {$('.status').remove();$('.submit').append( $(sp).css('color','red').html( 'Error : ' + msg ) );setTimeout(function(){$('.status').remove();}, 2000)},        
        verify: function() {        
        pro.msg('Verifying member account and clickbank order number...');        
        setTimeout(function(){
        $.post('admin-ajax.php', {action : "optinrev_action", authenticate : $('#cred_form').serialize()}, function( res ){          
                        
          if ( res == 'invalid_user' ) {
          pro.error('Invalid Member Account.');
          return false;
          }
                            
          if ( res == 'invalid_order' ) {
          pro.error('Invalid Clickbank Order Number.');
          return false;
          }
          
          if ( res == 'invalid' ) {
          pro.error('Invalid Clickbank Order Number / Member Account.');
          return false;
          }
          
          if ( res == 'refund' ) {
          pro.error('Product has been refund.');
          return false;
          }          
                  
          if ( res == 'valid' )
          {        
            $.post('admin-ajax.php', {action : "optinrev_action", pro_authorized : $('#cred_form').serialize()}, function( res ){
            if (res == 'valid') {pro.load('Successfully verified.');location.reload();}        
            });
          }
          
          return false;                    
          });}, 2000); 
        }        
        }
    $('#cred_form').submit(function(){pro.verify();return false;});    
  });  