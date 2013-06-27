jQuery(document).ready(function($){
        
if ( optinrev_visited_once > 0 ) {                
    if ( !wtfn.read_cookie( 'optinrev_visited_once' ) )                        
    wtfn.create_cookie( 'optinrev_visited_once', location.href, optinrev_visited_once );
    else {
    return false;
    }
}


if ( optinrev_show_time == "show_times_per_session" ) {
    if ( !wtfn.read_cookie( 'optinrev_session_browser' ) )                        
    wtfn.create_cookie( 'optinrev_session_browser', location.href, 0 );
    else {
    return false;
    }
}

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
                action_url = $(v).val().replace( /(http:\/\/)/ig, '' );
                $(v).remove();
            }

            //icontact
            if ( $(v).attr('name') == 'listid' ) { listid = $(v).val(); }
            if ( $(v).attr('name') == 'specialid' ) { $(v).attr('name', 'specialid:' + listid); }
        }

        if ( v.type === 'text' )
        {

            //aweber
            if ( $(v).attr('name') == 'name(awf_first)' ) { $(v).attr('name', 'name (awf_first)'); }
            if ( $(v).attr('name') == 'name(awf_last)' ) { $(v).attr('name', 'name (awf_last)'); }

            plc[ $(v).attr('id') ] = $(v).val();
            ids.push( $(v).attr('id') );

            $(v).bind("focus", function(){
            if ( $(this).val() == plc[ $(this).attr('id') ] ) $(this).val('');
            $( dialog.data ).find('#required').remove();
            }).bind("blur", function(){
            if ( $(this).val().length == 0 ) $(this).val( plc[ $(this).attr('id') ] );
            });
            
            cvlu = $(v).val();
            if ( $.browser.msie )
            $(v).val('');
            
            //input field redesigned
            if ( $.browser.msie ) {
                                    
            pd = parseInt( $(v).css('padding-top') );
            $(v).css('padding','');
            styl = $(v).attr('style');
            styl = styl + ';line-height:' + (pd+16) + 'px !important;padding: '+ (pd + 2) +'px 0px '+ pd +'px 0px !important;';
            $(v).attr('style', styl );                        
            $(v).val(cvlu);
            $(v.parentNode).css({'margin-top':3});
            
            }

        }
        });

        //onsubmit
        $('#wm', dialog.data).click(function(){

            $( dialog.data ).find('#required').remove();//tip
                        
            for( r = 0; r < ids.length; r++ ) {
                if ( typeof isvalid[ ids[r] ] !== 'undefined' ) {
                    var inp = $('#' + ids[r], dialog.data );
                    //name validation
                    if ( ids[r].indexOf('name') != -1 ) {

                        if ( inp.val().length == 0 ) {
                        inp.after( input_valid.msg( inp.val() + ' is required') );
                        return false;
                        }

                        if ( plc[ ids[r] ] == inp.val() ) {
                        inp.after( input_valid.msg( inp.val() + ' is required') );
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
                } else {
                
                   var inp = $('#' + ids[r], dialog.data );
                
                   if ( ids[r].indexOf('email') !== -1 ) {

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
                    cur_act = cur_act.replace( /mailchimp.us1.list-manage.com/ig, action_url );
                    $('#mce_getaccessed', dialog.data).attr( 'action', cur_act );
                }
            }

            //getresponse           
            if ( mail_form_name == 'getresponse' ) {
                mname = document.createElement('hidden');
                mname.name = 'name';
                mname.value = $('#first_name', dialog.data).val() + ' ' + $('#last_name', dialog.data).val();                
                $('#mce_getaccessed', dialog.data).append( mname );
            }
            
            if ( mail_form_name == 'constantcontact' ) {
            $('#email', dialog.data).attr('name', 'ea');
            }            

            $('#mce_getaccessed', dialog.data).submit();
            $.modal.close();
        });        
        
        $(document).bind('keydown.simplemodal', function (e) {
				if (e.keyCode === 13) { $('#wm', dialog.data).click(); }        
        });
        
    }
,
onClose: function(dialog){
$.modal.close();}
})}, 1000 * modal_delay);
});
