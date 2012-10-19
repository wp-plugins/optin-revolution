jQuery(document).ready(function($){
if ( !tshow ) return false;

setTimeout(function(){
$.modal( c, {closeClass: optinrev_close_button_class, position: [ optinrev_top_margin + 'px', null],opacity: optinrev_wbg_opacity, focus: false,
    onShow: function(dialog) {                        
        var t = $("#simplemodal-container").offset().top, plc = {};
        if ( t === 0 ) {dialog.container[0].style.marginTop = (Math.ceil(t) + 18) + 'px';}
        var ids = [], listid = 0, action_url = '';
                        
        //form inputs        
        $('input, select, radio, checkbox', dialog.data).each(function(i, v){
        
        if ( v.type === 'hidden' ) {
            //mailchimp
            if ( $(v).attr('name') == 'mcid' ) { $(v).attr('name', 'id'); }            
            if ( $(v).attr('name') == 'mcu' ) { $(v).attr('name', 'u'); }

            if ( $(v).attr('name') == 'mcaction' ) {
                action_url = $(v).val();
                $(v).remove();
            }            
            
            //icontact
            if ( $(v).attr('name') == 'listid' ) { listid = $(v).val(); }
            if ( $(v).attr('name') == 'specialid' ) { $(v).attr('name', 'specialid:' + listid); }            
        }    
              
        if ( v.type === 'text' )
        {                        
        
            plc[ $(v).attr('id') ] = $(v).val();
            ids.push( $(v).attr('id') );
            
            $(v).bind("focus", function(){
            if ( $(this).val() == plc[ $(this).attr('id') ] ) $(this).val('');
            $( dialog.data ).find('#required').remove();
            }).bind("blur", function(){
            if ( $(this).val().length == 0 ) $(this).val( plc[ $(this).attr('id') ] );  
            });
            
            //redifined style
            s = css.parseCSSBlock( $(v).attr('style') );            
            s.height = (parseInt(s.height) - 12) + 'px !important';
            s = css.cssBlock(s);
            $(v).attr('style', s);           
        }
        });
        
        //onsubmit
        $('#wm', dialog.data).click(function(){        
                    
            $( dialog.data ).find('#required').remove();//tip
            
            for( r in ids ) {
                if ( typeof isvalid[ ids[r] ] !== 'undefined' ) {
                    var inp = $('#' + ids[r], dialog.data );
                    //name validation
                    if ( ids[r].indexOf('name') != -1 ) {                    
                
                        if ( inp.val().length == 0 ) {
                        inp.after( input_valid.msg('Name is required') );            
                        return false;            
                        }
                    
                        if ( plc[ ids[r] ] == inp.val() ) {
                        inp.after( input_valid.msg('Name is required') );            
                        return false;            
                        }
                        
                        if ( inp.val().length > 0 ) {
                            if ( !input_valid.is_name( inp.val() ) ) {  
                            inp.after( input_valid.msg('Invalid name.') );
                            return false;
                            }
                        }
                   
                   }
                   
                   if ( ids[r].indexOf('email') != -1 ) {
                   
                        if ( inp.val().length == 0 ) {
                        inp.after( input_valid.msg('Email address is required') );                                    
                        return false;            
                        }
                    
                        if ( plc[ids[r]] == inp.val() ) {
                        inp.after( input_valid.msg('Email address is required') );
                        return false;            
                        }
                        
                        if ( inp.val().length > 0 ) {
                        if ( !input_valid.is_email( inp.val() ) ) {  
                        inp.after( input_valid.msg('Invalid email address.') );
                        return false;
                        }
                        }
                   }
                }
            }

            //mailchimp changed
            if ( mail_form_name == 'mailchimp' ) {
                $('#email', dialog.data).attr('name', 'MERGE0');
                $('#email', dialog.data).attr('id', 'MERGE0');
                if ( action_url ) {
                    cur_act = $('#mce_getaccessed', dialog.data).attr('action');
                    cur_act = cur_act.replace( /wotcoupon.us4.list-manage1.com/ig, action_url );
                    $('#mce_getaccessed', dialog.data).attr( 'action', cur_act );
                }                                
            }            
            
            $('#mce_getaccessed', dialog.data).submit();
            $.modal.close();                 
        });
    }
,
onClose: function(dialog){
$.modal.close();}
})}, 1000 * modal_delay);
});