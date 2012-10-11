  var imgs = ['cursor_drag_arrow.png','delete-ic.png','aweber.png','constant_contact.png','getresponse.png','icontact.png','mailchimp.png','chrome-logo.png','firefox-logo.png','ie8-logo.jpeg','ie9-logo.jpeg'];  
  var jsoptin_load = { init: function(){
      jQuery(imgs).each(function(i,v){
      _im = jQuery("<img>");_im.hide();
      _im.bind("load", function(){ jQuery(this).hide(); });
      jQuery('body').append(_im);
      _im.attr('src', wtp + 'images/' + v );
      });  
  }};
  var bkspc = false, crt = 0;  
  
  //misc functions
  //wtfn.submit();
  var wtfn = {
    //message alert
  msg: function( msg ) {
    jQuery('#post-message').hide();
    jQuery('#post-message').stop().animate({top: jQuery('#post-message').offset().top + (jQuery(window).height() / 2), left : (jQuery(window).width() / 2) - 200}).fadeIn().html( msg );    
    setTimeout(function(){ jQuery('#post-message').fadeOut(); }, 2000);
  },
  is_dragging: function() {return (jQuery('#optinrev_dragging').val() == 1) ? true : false;},
  is_round_border_enabled: function() {return (jQuery('#optinrev_round_border').attr('checked')) ? true : false;},
  save: function(submit) {
      var wtdom = tinyMCE.activeEditor.dom, clb = wtdom.get('close'), cl = 'left:' + j(clb).offset().left + 'px;top:' + j(clb).offset().top + ';', c = wtdom.get('simplemodal-container'), clb_class = wtdom.getAttrib( clb, 'class' );
      
      if (submit)
      j('#save_setting_spin').show();
            
      j.inputfields_update();
      
      is_editing = true;
      tinyMCE.activeEditor.isNotDirty = 1;
      
      j('#optinrev_close_button').val( cl );
      j('#optinrev_close_button_class').val( clb_class );
      j('#optinrev_excerpt').val( tinyMCE.activeEditor.getContent() );
      j('#optinrev_data').val( j(c).find('.simplemodal-data').html() );
      //wait while saving
      setTimeout(function(){
      j.post('admin-ajax.php', j('#optinrev_setup_form').serialize(), function(res){      
        if ( res == 'success' ){if ( submit ){ j('#save_setting_spin').hide();wtfn.msg('Successfully Updated.');}}
        j('#save_setting_spin').hide();        
        return false;
      });
      }, 20); 
  },
  redraw: function()
  {
   var r, wtdom = tinyMCE.activeEditor.dom, rnd = parseInt( j('#optinrev_border_radius').val() );   
   
   if ( tinyMCE.isIE )
   {  
   
   modstyle = 'width:'+ j('#optinrev_vwidth').val() + 'px;height:' + j('#optinrev_vheight').val() + 'px;border:' + j('#optinrev_vborder_thickness').val() + 'px solid ' + j('input[name="optinrev_border_color"]').val() + ';background-color:' + j('input[name="optinrev_pwbg_color"]').val() + ';-moz-border-radius:'+ rnd +'px; -webkit-border-radius:'+ rnd +'px; border-radius: '+ rnd +'px;-khtml-border-radius:'+ rnd +'px';   
   //heigh onresize
   j('#' + tinyMCE.activeEditor.id + '_ifr').height( parseInt(j('#optinrev_vheight').val()) + 100 );
   wtdom.setAttrib(wtdom.get('simplemodal-container'),'style', modstyle);
   
   var settings = {
      tl: { radius: rnd },
      tr: { radius: rnd },
      bl: { radius: rnd },
      br: { radius: rnd },      
      antiAlias: true
    } 
     
     if ( apc )
     {
     
     con = wtdom.get('simplemodal-container');
     wtdom.remove( 'curvy1' );
     wtdom.remove( 'curvy2', true );
     
     var obj = new curvyObject( settings, con );
     obj.applyCorners();
     
     } else {
            
     wtdom.remove( 'curvy1' );
     wtdom.remove( 'curvy2', true );     
     
     var obj = new curvyObject( settings, wtdom.get('simplemodal-container') );
     obj.applyCorners();     
     apc = true;
     
     }
        
   } else {   
   
   if ( !apc ) {   
   wtdom.remove( 'curvy1' );
   wtdom.remove( 'curvy2', true );   
   apc = true;
   }
   
   border_radius = '-moz-border-radius: '+ j('#optinrev_border_radius').val() +'px;-webkit-border-radius: '+ j('#optinrev_border_radius').val() +'px;border-radius: '+ j('#optinrev_border_radius').val() +'px;';      
   border_opacity = (parseFloat(j('#optinrev_border_opacity').val()) / 100);
   modstyle = 'width:'+ j('#optinrev_vwidth').val() + 'px;height:' + j('#optinrev_vheight').val() + 'px;border:' + j('#optinrev_vborder_thickness').val() + 'px solid rgba(' + wtfn.rgb(j('input[name="optinrev_border_color"]').val()) + ','+ border_opacity +');background-color:' + j('input[name="optinrev_pwbg_color"]').val() + ';' + border_radius + 'border:' + j('#optinrev_vborder_thickness').val() + 'px solid rgb(' + wtfn.rgb(j('input[name="optinrev_border_color"]').val()) +')';
   //heigh onresize
   j('#' + tinyMCE.activeEditor.id + '_ifr').height( parseInt(j('#optinrev_vheight').val()) + 100 );      
   wtdom.setAttrib(wtdom.get('simplemodal-container'),'style', modstyle);
   }   
   //close button    
   if ( clb = wtdom.get('close') ) {
       wtdom.setStyles( clb, wtfn.close_button_pos(0) );   
       wtdom.setAttrib( clb, 'data-mce-style', 'left:' + wtfn.close_button_pos(0).left + 'px; top:'+ wtfn.close_button_pos(0).top + 'px;' );
   } 
   j('#' + tinyMCE.activeEditor.id + '_ifr').attr('title', null);
   wtfn.pwby();       
      
  },//redraw
  remove_img: function( img, img2 )
  {
    var wtdom = tinyMCE.activeEditor.dom;
    if (!img) return false;
    
    if (confirm('Do you want to delete?'))
    {
        j('.spin').show();
        j.post('admin-ajax.php', {action : "optinrev_action", optinrev_remove_img : img}, function( res ){        
        if ( res.length > 0 )
        {           
          var ac = j.parseJSON( res );
          if ( ac.action == 'success' ) {         
          
          j('.spin').hide();          
          p = wtdom.get(img2);
          wtdom.remove( p.parentNode );          
          
          wtfn.save(0);
          
          j('#optinrev_added_images').load('admin-ajax.php', {action : 'optinrev_action', optinrev_added_images : wtpage});
          j('#optinrev_jspopup_images').load('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_images : wtpage});          
          wtfn.msg('Successfully removed.');          
          is_editing = true;
                    
          }         
          
        }
        });
    }
  },//remove_img
  inputs_setup: function()
  {
    var wtdom = tinyMCE.activeEditor;
    var input_style = 'font-size:'+ j('input[name="optinrev_inputfz"]').val() +'px !important;color:'+ j('input[name="optinrev_inputtc"]').val() +' !important;height:' + j('#optinrev_inputh').val() + 'px !important;width:' + j('#optinrev_inputw').val() + 'px !important;background-color:' + j('input[name="optinrev_inputc"]').val() + ' !important;border:' + j('input[name="optinrev_inputbt"]').val() + 'px solid ' + j('input[name="optinrev_inputb"]').val() + ' !important;';
    var mceform = wtdom.dom.get('mce_getaccessed');
    
    if ( mceform ) {
        j('input, select, radio, checkbox', mceform).each(function(i, v){
          if ( v.type === 'text' ){
          wtdom.dom.setAttrib( v, 'style', input_style );    
          }
        });
    }    
  },//inputs setup
  delete_image: function( img ) {
          
    if (confirm('Do you want to delete?\nIf Ok, This image will deleted all in Optin Popup.'))
    {           
        j('.spin').show();
        j.post('admin-ajax.php', {action : "optinrev_action", optinrev_del_upload : img}, function( res ){        
        if ( res.length > 0 )
        {
          var ac = j.parseJSON( res );
          if ( ac.action == 'success' ) {
          j('#optinrev_upload_id').val( 'optinrev_uid_' + ac.btn_count );
          j('.spin').hide();      
          j.post('admin-ajax.php', {action : 'optinrev_action', optinrev_del_image_briefcase : img});                    
          wtfn.msg('Successfully deleted.');
          }          
          j('#load_list_img').load('admin-ajax.php', {action : 'optinrev_action', optinrev_load_uploads : 1});
        }
        });
    }
  },//del upload image
  input_setenabled: function( id, state ) {
    if (tinyMCE.activeEditor != null) {
        var wtdom = tinyMCE.activeEditor.dom;
        if (fname = wtdom.get( id )) {
        wtdom.setStyle( fname.parentNode, 'display', ( (state == 1) ? 'inline' : 'none' ) );
        }
        //checking undefined value        
        j('input', wtdom.get('mce_getaccessed')).each(function(i, v){
          if ( v.type === 'text' ){
          if ( v.value == 'undefined' )
          wtdom.setAttrib( v, 'value', '' );    
          }
        });
    }
  },//input set enabled
  delete_button: function( img ) {      
    if (confirm('Do you want to delete?\nIf Ok, This button will deleted all in Optin Popup and will set a default button.'))
    {
        j('.spin').show();
        j.post('admin-ajax.php', {action : "optinrev_action", optinrev_del_cupload : img}, function( res ){        
        if ( res.length > 0 ) {        
          var ac = j.parseJSON( res );
          if ( ac.action == 'success' ) {
          j('#optinrev_upload_id').val( 'optinrev_cuid_' + ac.btn_count );
          j('.spin').hide();                
          wtfn.msg('Successfully deleted.');
          }          
          j('#load_list_img2').load('admin-ajax.php', {action : 'optinrev_action', optinrev_action_button_uploads : 1}, function(){
          //set default;
          j('input[name="action_button_update[]"]').each(function(i,v){ if ( i == 0 ) { j(v).attr('checked', true); }});
          });
        }
        });
    }
  },//delete button
  action_image_view : function(id) {
    vl = j('#' + id).val().split('|');        
    j('#action_btn_default').html('<img src="'+ vl[1] +'" border="0">');
  },
  action_image_view_hover : function( vl ) {
  j('#action_btn_view').html('<img src="'+ vl +'" border="0">');
  },
  add_image_view : function(id) {
  vl = j('#'+id).val().split('|');
  j('#add_image_view').html('<img src="'+ vl[1] +'" border="0">');
  },
  add_image_view_hover : function( vl ) {
  vl = vl.split('|');
  j('#add_image_view').html('<img src="'+ vl[1] +'" border="0">');
  },    
  add_action_image : function(id) {
    var wtdom = tinyMCE.activeEditor.dom, mn = wtdom.get('simplemodal-container'), mn_w = jQuery(mn).width();
    if ( j('#' + id).val().length > 0 ) {        
    if ( img = wtdom.get('wm') ) {
    
        vl = j('#' + id).val().split('|');
        
        j('#action_btn_default').html('<img src="'+ vl[1] +'" border="0">');
    
        j('#optinrev_call_action_button').val( j('#' + id + ' option:selected').text() );
        wtdom.replace( wtdom.create('img', {id : 'wm', 'src' : vl[1], 'border' : 0}, null), img );
        
        //prev gap        
        crnb = wtdom.get('wm');
        pcrnb = crnb.parentNode;
        bgp = mn_w - jQuery(crnb).width();
        
        wtdom.setStyle( pcrnb, 'left', (bgp - 20) );
        
        wtfn.msg('Done updated action button.');        
    }
    }      
  },//action button
  jspopup: function( n, s )
  {
    j.post('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup : n, show : s }, function( res ){
    if ( res === 'success' ) {
    j('#optinrev_jspopup_images').load('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_images : 'show'});
    wtfn.msg( 'JS Popup ' + (( s == 'enabled' ) ? 'enabled' : 'disabled')  );
    }
    });  
  }, //js popup
   
  mail_provider_save: function( provider ) {    
    if ( provider )
    {       
      j.post('admin-ajax.php', {action : "optinrev_action", optinrev_mail_webform : j('#optinrev_email_form').val(), mail_provider : provider});
    } 
  },
  mail_provider_get: function( provider ) {        
    if ( provider )
    {       
      j.post('admin-ajax.php', {action : "optinrev_action", mail_provider : provider}, function(data){ j('#optinrev_email_form').val(data); });
    } 
  },////mail provider
  
  //INPUTS
  delete_input: function( id ) {
    var wtdom = tinyMCE.activeEditor.dom, mceform = wtdom.get('mce_getaccessed');
    if ( confirm('You want delete this input in editor?') ) {
    
        if ( inp = wtdom.get(id) ) {
            wtdom.remove( inp );        
        }        
        j('#ls_' + id).remove();    
    } else return false;
  },  
  //optin preview
  preview : function()
  {
    var l = window.location.href.toString().replace(/(&show=optin)/ig,''), wtdom = tinyMCE.activeEditor.dom, cl = j(wtdom.get('close')).attr('class'), sd = wtdom.get('simplemodal-container'), c = j(sd).find('.simplemodal-data').html(), ms = vld = {}, box_delay = j('#optinrev_vdelay').val(), ch = j(window).height(), exh = 30;  
    var input_valid = {msg: function(m){alert(m);},
    is_email: function( email ) {var re = /^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;return re.test( email );},
    is_name: function( name ) {var re = /^[a-zA-Z]+$/;return re.test( name );}
    };
    
    setTimeout(function(){                  
    j.modal(c,{
    closeClass: cl,
    position: [ j('#optinrev_vtop_margin').val() + 'px', null ],
    opacity: j('#optinrev_wbg_opacity').val(), focus : false,    
    onShow: function(dialog) {
                
        var t = j("#simplemodal-container").offset().top, plc = {}, redirect = false;                
        if ( t === 0 ) {dialog.container[0].style.marginTop = (Math.ceil(t) + 18) + 'px';}
        var ids = [], listid = 0;        
                                                                                                  
        //form inputs        
        j('input, select, radio, checkbox', dialog.data).each(function(i, v){
        
        if ( v.type === 'hidden' ) {
        //mailchimp
        if ( j(v).attr('name') == 'mcid' ) { j(v).attr('name', 'id'); }            
        if ( j(v).attr('name') == 'mcu' ) { j(v).attr('name','u'); }
        
        //icontact
        if ( j(v).attr('name') == 'listid' ) { listid = j(v).val(); }
        if ( j(v).attr('name') == 'specialid' ) { j(v).attr('name', 'specialid:' + listid); }        
        }
                      
        if ( v.type === 'text' )
        {                             
            plc[ j(v).attr('id') ] = j(v).val();
            ids.push( j(v).attr('id') );
            
            j(v).bind("focus", function(){
            if ( j(this).val() == plc[ j(this).attr('id') ] ) j(this).val('');
            j( dialog.data ).find('#required').remove();
            }).bind("blur", function(){
            if ( j(this).val().length == 0 ) j(this).val( plc[ j(this).attr('id') ] );  
            });
        }
        });
             
        //image click
        j("#imglabel", dialog.data).hide();
      	j("img", dialog.data).click(function () {
          var p = j(this).parent();
          if ( m = j(p).attr('data-mce-popup') ) {
              j( jsmv ).each(function(i, v) {
                  if ( m === v.name ) {
                  alert( v.option_value.replace(/\\\\r/g,'\n').replace(/\\/g,'') );
                  }
              });
          }
      		return false;
      	});
        
        //onsubmit
        j('#wm', dialog.data).click(function(){        
                    
            j( dialog.data ).find('#required').remove();//tip
            
            for( r in ids ) {
                if ( typeof isvalid[ ids[r] ] !== 'undefined' ) {
                    var inp = j('#' + ids[r], dialog.data );
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
                j('#email', dialog.data).attr('name', 'MERGE0');
                j('#email', dialog.data).attr('id', 'MERGE0');
            }            

            j('#mce_getaccessed', dialog.data).submit();                 
        });
    },
    onClose: function(dialog){
    window.location = l;    
    }});
    }, 1000 * box_delay);
    return false;
  },//optin preview
  
  ///JS popup images
  add_jsmessage: function() {    
    if ( j('#optinrev_setup_form #text_alert_messages').val().length == 0 ) {
        alert('No messages to save. Please fill the message entry.');
        return false;
    }
  
    j.post('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_messages_add : j('#optinrev_setup_form #text_alert_messages').val()}, function(res){
      if (res == 'success') { wtfn.msg( 'Alert message has been added.'); 
      j('#optinrev_jspopup_messages').load('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_messages : 'load'});
      }  
    });
    return false;     
  }, 
  delete_jsmessage: function() {
  j.post('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_messages_del : j('#optinrev_setup_form #select_alert_messages').val()}, function(res){
    if (res == 'success') { wtfn.msg( 'Alert message has been deleted.');
    j('#optinrev_jspopup_messages').load('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_messages : 'load'});        
    }  
  });
  return false;
  }, 
  edit_alert_message: function() {
  var p = j('#optinrev_setup_form'), sl = j('#select_alert_messages option:selected', p).text(); 
  j('#text_alert_messages', p).val( sl ); 
  
  if ( j('#mscancel', p).length == 0 ) 
  j('#jsm_del', p).after('&nbsp;<span class="submit" id="mscancel"><input type="button" value="Cancel" onclick="optinrev_alert_message_cancel();"></span>');
  
  j('#jsm_add', p).hide();
  j('#jsm_wupdate', p).show();
  j('#jsm_edit', p).hide();
  }, 
  cancel_alert_message: function() {
  var p = j('#optinrev_setup_form');
  j('#text_alert_messages', p).val('');
  j('#jsm_add', p).show();
  j('#jsm_wupdate', p).hide();
  j('#mscancel', p).remove();
  j('#jsm_edit', p).show();
  }, 
  update_jsmessage: function() {
  var p = j('#optinrev_setup_form'); 
  j.post('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_messages_update : j('#select_alert_messages', p).val(), optinrev_jspopup_messages: j('#text_alert_messages', p).val()}, function(res){
  if (res == 'success') { wtfn.msg( 'Alert message has been updated.');
  j('#optinrev_jspopup_messages').load('admin-ajax.php', {action : 'optinrev_action', optinrev_jspopup_messages : 'load'});
  }}) 
  },///JS popup images
  pwby: function() {var wtdom = tinyMCE.activeEditor.dom, mn = wtdom.get('simplemodal-container'), mn_h = jQuery(mn).height(), mn_w = jQuery(mn).width(), bw = (mn) ? parseInt(mn.style.border.substring(0, mn.style.border.indexOf('px'))) : 0;if ( is_poweredby == 'true' ) {wtdom.remove('poweredby');wtdom.add( wtdom.get('simplemodal-data'), 'a', {'id': 'poweredby', 'href': 'http://wordpress.org/extend/plugins/optin-revolution/', 'target': '_new', style : { 'position': 'absolute', left: ((mn_w / 2) - 80), top: (mn_h + 2) + bw }}, 'Powered by : Optin Revolution');}},
  mce_toolbar:function( state ) {
  var ctrl = 'fontselect,fontsizeselect,forecolor,backcolor,moveforward,movebackward,textbox,jspopupimg,lineheight,bold,italic,underline,bullist,numlist,justifyleft,justifycenter,justifyright,justifyfull,link,unlink,wp_adv,removeformat,outdent,indent,input_align_left,input_align_top,object_align_top,object_align_bottom,object_align_center,object_align_left,object_align_right';
  tinyMCE.each( ctrl.split(','), function(v, i) {
  
  if ( /^(input_align_left|input_align_top)$/i.test(v) ) {
     tinyMCE.activeEditor.controlManager.setDisabled( v, !state );    
      } else {
     tinyMCE.activeEditor.controlManager.setDisabled( v, state );
  } 
  
  tinyMCE.activeEditor.controlManager.setDisabled( 'absolute', true );
  tinyMCE.activeEditor.controlManager.setDisabled( 'textedit', true );
  });
  }, 
  rgb: function(color) {    
    var result;    
    if (result = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(color)) return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3])];    
    if (result = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(color)) return [parseFloat(result[1]) * 2.55, parseFloat(result[2]) * 2.55, parseFloat(result[3]) * 2.55];    
    if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color)) return [parseInt(result[1], 16), parseInt(result[2], 16), parseInt(result[3], 16)];
    if (result = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(color)) return [parseInt(result[1] + result[1], 16), parseInt(result[2] + result[2], 16), parseInt(result[3] + result[3], 16)];
  },
  prevent_default: function(e){
    if(e.preventDefault){ e.preventDefault()}
    else{e.stop()};
  
    e.returnValue = false;
    e.stopPropagation();    
  },
 set_optin_default: function( msg ) {  
  jQuery.post('admin-ajax.php', {action : 'optinrev_action', optinrev_popup_reset : jQuery('#page').val()}, function(res){
    if (res == 'success') {
    if ( msg ) {
    wtfn.msg( 'The content has been reset. Please wait. It will reload the page.');    
    setTimeout(function(){ window.location.reload(); }, 1000);
    } else {
    wtfn.msg( 'The default content has been set. Please wait. It will reload the page.');    
    setTimeout(function(){ window.location.reload(); }, 1000);
    }    
    }  
  });
 },
 clear_layer: function( ed ) {
   if ( ed ) { 
       jQuery( ed.getDoc() ).find('#mceWotlayer').removeAttr('class');
       jQuery( ed.getDoc() ).find('#mceWotlayer').removeAttr('id');
       jQuery( ed.getDoc() ).find('#mceWotmove').remove();        
       jQuery( ed.getDoc() ).find('#mceDeleteObj').remove();       
       jQuery( ed.getDoc() ).find('#zindex').remove();
   }
 },
 tinymce_event: function( e ){   
   //exclude the event
   if ( e.type == 'dblclick' ) return false;
   if ( e.type == 'mousedown' ) {
   if (e.target.id === 'tinymce') return false;
   }
   
   if ( e.type == 'keydown' ) return false;
   
   if (/close|simplemodal-container/.test(e.target.id)) return false;
    
   if (wtfn.is_dragging())
   return false;
       
   return true;
 }, 
 //tinymce setup callback
 tinymce: function( ed )
 {
    var elm;
    
   _ccdom = function( ed, target, remove ) {
     var t = tinymce, sel;
     
     nl = 0;
			tinymce.walk(target, function(n) {
          if ( t.isIE ) {
                  if ( typeof n.style !== 'undefined' ) {
                  if ( typeof n.style.direction !== 'undefined' ) {
                          if ( n.style.direction == 'ltr' ) {
                                ed.dom.setAttrib( n, 'unselectable', 'on' );
                                ed.dom.setAttrib( n, 'id', 'curvy1' );                                            
                                n.contentEditable = false;
                                n.attachEvent("oncontrolselect", function(){ return false; });
                                n.attachEvent("onmousedown", function(e){ e.returnValue = false; this.hideFocus = true; });
                                n.attachEvent("onselect", function(){ this.hideFocus = true; return false; });
                                n.attachEvent("onclick", function(){ return false; });
                                n.attachEvent("ondrag", function(){ return false; });                                                                                                
                          }                          
                      }
                  }
                  
                  if ( n.className === 'autoPadDiv' ) {
                      ed.dom.setAttrib( n, 'id', 'curvy2' );
                  } 
          }
				//if (n.nodeName === 'div')
          if ( n.className === 'simplemodal-data' )
          {
              ed.dom.setAttrib( n, 'id', 'simplemodal-data' );          
    					nl = n;
          }
           
			}, 'childNodes');
     
     if ( nl )
     if ( ed.dom.get( nl ).hasChildNodes() )
     {
     
      var dl = [];      
      tinymce.walk(nl, function(n) {
				if ( n.nodeName === 'DIV' )        
				dl.push( n );
           
			}, 'childNodes');
      
      
       tinymce.each( dl, function(v, k)  {
       if ( v.style.display !== 'none' ) {       
       s = ed.dom.getAttrib( v, 'style' );      
       
       if ( remove )
       {
          //remove marker            
          ps = ed.dom.parseStyle( s );
          delete ps.border;                     
          s = ed.dom.serializeStyle( ps, null );
          
          if ( typeof v !== 'undefined' )
          ed.dom.setAttrib( v, 'style', s );       
        
        } else {
        
        //marker
        if ( v.nodeName !== 'FORM' && v.id !== 'simplemodal-container' && v.className !== 'simplemodal-data' ) {                
        ed.dom.setAttrib( v, 'style', s + 'border: 1px solid transparent;' );
        if ( v.firstChild )
        if ( v.firstChild.nodeName == '#text' ) {
          if ( v.innerHTML.length > 0 ) {  
               //span wraping
               inh = v.innerHTML;                  
               v.innerHTML = '<span>'+ inh +'</span>';
          }
        }
        }        
        
        tinymce.dom.Event.add( v, 'mouseover', function(e)
        {
          if ( e.target.id == 'zindex' ) return false;
          el = e.target.parentNode;
          
          if ( !wtfn.is_dragging() ) return false;
          if ( is_drag ) return false;          
          if ( el == null) return false;                    
          if ( el.id === 'simplemodal-container' ) return false;
          if ( el.className === 'simplemodal-data' ) return false;
                    
          if ( el = ed.dom.getParent( e.target, 'DIV' ) )
          { 
            
            if ( el.id === 'mceWotmove' ) return false;
            if ( el.id === 'mceDeleteObj' ) return false;
            
            elm = el;            
            if ( tinymce.isIE )
            {
              ed.dom.setAttrib( el, 'unselectable', 'on' );            
              el.contentEditable = false;
              el.attachEvent("oncontrolselect", function(){ return false; });               
            }
                        
            _drawLayer(el);
          }
        });
        
        tinymce.dom.Event.add( v, 'mouseout', function(e)
        {           
            if ( !wtfn.is_dragging() ) return false;
            if ( is_drag ) return false;
            
            var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
            
            if ( typeof elm != 'undefined' && elm )
            {  
               if ( reltg ) 
               if ( /^(simplemodal-container|wm|curvy1)$/i.test(reltg.id) )
               {             
                  _removeLayer(1);                 
               }          
            }
        });        
                     
        }       
       }//display:none;
       });  //EACH
     }
   }
      
   ed.onInit.add( function(ed, e)
   {
     var t = tinymce, dobj, tdobj, tx = ty = x = y = ml = mov = 0, mlp = {}, is_grip = is_drag = false, bwn = ed.dom.get('simplemodal-container'), nodeItem = 'UL,H1,H2,H3,H4,H5,H6', ed_ifr = j('#' + ed.id + '_ifr').height();
     
     if ( ed.getContent().length == 0 ) { wtfn.set_optin_default(0); }
     //init
     wtfn.mce_toolbar( wtfn.is_dragging() );     
     //main marker
     if ( typeof bwn === 'undefined' || bwn === null ) return false;
     if ( tinymce.isIE )
     {      
      ed.dom.setAttrib( bwn, 'unselectable', 'on' );            
      bwn.contentEditable = false;
      bwn.attachEvent("onmousedown", function(){ return false; });
      bwn.attachEvent("onselect", function(){ return false; });
      bwn.attachEvent("onclick", function(){ return false; });
      bwn.attachEvent("ondrag", function(){ return false; });
      bwn.attachEvent("oncontrolselect", function(){ return false; });
    } 
    
     // if has no close button         
     if ( typeof bwn.childNodes[1] === 'undefined' )
     {
        clb = document.createElement('div');
        clb.setAttribute( 'id', 'close' );
        clb.setAttribute( 'class', j('input[name="optinrev_close_popup_image"]').val() );
        clb.setAttribute( 'style', j('#optinrev_close_button').val() );        
        bwn.insertBefore( clb, bwn.firstChild );
     }
     
     //preview
     if ( parseInt(window.location.href.toString().indexOf('show')) > 0 )
     {
       ed.isNotDirty = 1;
       wtfn.preview();
       return false;
     }          
     
     if ( !tinymce.isIE ) {
     ed.getDoc().designMode = 'off';     
     jQuery(ed.getBody()).attr('contenteditable', 'false' );
     }
     
     //border transparent
     _ccdom(ed, bwn, 0);
     
     //checking if theres no input field
     if ( !ed.dom.get('mce_getaccessed') ) {
     wtfn.save(0);
     }
     
     ed.isNotDirty = 1;
     wtfn.pwby();     
    
    j(bwn).dblclick(function(e) {
    wtfn.prevent_default(e);  
    return false;
    });
     
    //add image from briefcase
    if ( is_bfcase != 0 ) {
    wtfn.action_add_image_briefcase( is_bfcase );            
    }
    
    //add image from briefcase
    if ( is_delbfcase != 0 ) {
    wtfn.action_del_image_briefcase( is_delbfcase );            
    }     
    
    //update action button
    if ( is_actionbtn != 0 ) {
    wtfn.action_add_button_briefcase( is_actionbtn );         
    }     
     _drawLayer = function( el )
     {  
        if ( el && el.className != 'mceWotlayer' )
        {
          ed.dom.setAttrib( el, 'class', 'mceWotlayer' );
          ed.dom.setAttrib( el, 'id', 'mceWotlayer' );
          //drag box
          ed.dom.add(el, 'div', {id : 'mceWotmove'});
          
          if ( el.firstChild.id != 'wm' && el.firstChild.nodeName != 'INPUT' )          
          ed.dom.add(el, 'div', {id : 'mceDeleteObj'});
                    
        }
     }
     
     _removeLayer = function( rem )
     {  
        wtfn.clear_layer( ed );
     }     

     _nodeMove = function(dobj, e)
     {                
       is_drag = true;
       tx = parseInt( dobj.style.left + 0 );
       ty = parseInt( dobj.style.top + 0 );
       x = e.clientX;
       y = e.clientY; 
     }
     //remove a specific style
     _removeAStyle = function( ed, dobj, astyle )
     {
        s = ed.dom.getAttrib( dobj, 'style' );
        ps = ed.dom.parseStyle( s );
        delete ps[astyle];
        s = ed.dom.serializeStyle( ps, null );
        ed.dom.setAttrib( dobj, 'style', s );
     }

    function makeObj(event) {
        var obj = new Object(), o = {}, p = ed.dom.get('simplemodal-container');
        e = ( event.target ) ? event.target : event.srcElement;
        
        is_drag = true;        
        obj.element = e;
    
        obj.minBoundX = 0;
        obj.minBoundY = 0;//(e.nodeName === 'IMG') ? -6 : 0;
        
        if ( e.id === 'mceWotmove' ) {        
        o = { 'offsetWidth' : e.parentNode.offsetWidth, 'offsetHeight' : e.parentNode.offsetHeight, 'offsetLeft' : e.parentNode.offsetLeft, 'offsetTop' : e.parentNode.offsetTop };                  
        } else {        
        
        ofh = e.offsetHeight;//(e.nodeName === 'IMG') ? (e.offsetHeight + 7) : e.offsetHeight;
        ofw = e.offsetWidth;//(e.nodeName === 'IMG') ? (e.offsetWidth + 3) : e.offsetWidth;
        
        o = { 'offsetWidth' : ofw, 'offsetHeight' : ofh, 'offsetLeft' : e.offsetLeft, 'offsetTop' : e.offsetTop };        
        }

        obj.maxBoundX = obj.minBoundX + parseInt(p.style.width) - o.offsetWidth;
        obj.maxBoundY = obj.minBoundY + parseInt(p.style.height) - o.offsetHeight;
    
        obj.posX = event.clientX - o.offsetLeft;
        obj.posY = event.clientY - o.offsetTop;
    
        return obj;
    } 
      
      ///CLOSE BUTTON
      tinymce.dom.Event.add(ed.dom.get('close'), 'mousedown', function(e)
      {
        wtfn.prevent_default(e);
        return false;           
      });
      ///CLOSE BUTTON
      
      tinymce.dom.Event.add(ed.getDoc(), 'mousedown', function(e)
      {                  
        el = e.target;
        
        wtfn.prevent_default(e);
                
        //lock the target
        if ( el.nodeName === 'HTML' || el.id == 'simplemodal-container' ) {
            return false;        
        }
        
        if ( /^(tinymce|simplemodal-container|close|poweredby)$/i.test(el.id) ) return false;        
        if ( el.parentNode.id === 'simplemodal-container' ) return false;
        if ( el.parentNode.className === 'simplemodal-data' ) return false;
        
        //delete an object
        if ( e.target.id === 'mceDeleteObj' ) {             
             if ( confirm('Do you want to remove ?') ) {
                 ed.dom.remove( el.parentNode );
             }
        }       
      
        //if dragging
        if ( wtfn.is_dragging() )
        {    
          //move selection
          if ( el.id == 'mceWotmove' )
          {   
              tdobj = el.parentNode;
              dobj = makeObj(e);
          }
          
        } else 
        {         
                  
          if ( el.id == 'zindex' ) return false;
          
          wtfn.clear_layer( ed );
        
          var el = ed.dom.getParent(el, 'DIV');
                    
          //design once
          if ( el ) {
            
            if ( el.className != 'mceWotlayer' )
            {               
              if ( tinymce.isIE )
              {
                  el.contentEditable = true;
                  el.onselectstart = function(){return true;}
              }
              
              if ( el && el.className != 'mceWotlayer' )
              {              
                ed.dom.setAttrib( el, 'class', 'mceWotlayer' );
                ed.dom.setAttrib( el, 'id', 'mceWotlayer' );
                
                if ( el.firstChild.id != 'wm' && el.firstChild.nodeName != 'INPUT' )          
                ed.dom.add(el, 'div', {id : 'mceDeleteObj'});                                               
                
              }
              
              //action button exclude
              if ( el.id == 'wm' ) {
                  return false;          
              }         
            }
            
            //textedit
            if ( el.id == 'mceWotlayer' ) {
            ed.controlManager.setDisabled( 'textedit', e.target.nodeName != 'SPAN' );            
            }
                                      
          }
        
        }
        
        //draggable protection
        if ( /IMG/.test( e.target.nodeName ) ) {           
           return false;
        }    
        
        
        ed.undoManager.add();
      });
      
      //move inside the modal
      tinymce.dom.Event.add(ed.getDoc(), 'mousemove', function(e)
      {
        if ( is_grip && dobj )
        {
          is_drag = false;
          
          _x = tx + e.clientX - x;
          _y = ty + e.clientY - y;
          
          //close button drag
          if ( dobj.id == 'close' )
          {
              ed.dom.setStyle( dobj, 'left', _x );
              ed.dom.setStyle( dobj, 'top', _y );              
          }        
        }
            
        if ( is_drag && dobj )
        {            
            l = Math.max(dobj.minBoundX, Math.min(e.clientX - dobj.posX, dobj.maxBoundX));
            t = Math.max(dobj.minBoundY, Math.min(e.clientY - dobj.posY, dobj.maxBoundY));
            
            ed.dom.setStyle( tdobj, 'left', l );
            ed.dom.setStyle( tdobj, 'top', t );
        }     
      });
      
      tinymce.dom.Event.add(ed.getDoc(), 'mouseup', function(e)
      { 
        if ( wtfn.is_dragging() )
        wtfn.prevent_default(e);
        
        is_drag = false;
        is_grip = false;        
        redraw = 0;        
        _removeAStyle( ed, e.target, 'cursor' );
      });
      
      tinymce.dom.Event.add(ed.getDoc(), 'blur', function(e)
      {                 
        ed.dom.remove('zindex');        
      });
            
      tinymce.dom.Event.add(ed.getWin(), "mouseover", function(e){
          if ( is_editing )
          {              
             ed.dom.remove( 'mceDeleteObj' );
             _removeLayer(1);
             ed.dom.remove('zindex');
             wtfn.redraw();
             _ccdom(ed, ed.dom.get('simplemodal-container'), null);
             is_editing = false; 
          }                              
      });      
      
      tinymce.dom.Event.add(ed.getWin(), "mousedown", function(e){                    
          if ( e.target.id === 'tinymce' ) {
              wtfn.prevent_default(e);
              return false;             
          }               
      });   
            
   });
  
 ed.onLoadContent.add(function(ed, o) {
    wl = ed.dom.get('mceWotlayer');
    j(wl).removeAttr('id', null);
    j(wl).removeAttr('class', null);
    ed.dom.remove('mceDeleteObj');    
    ed.dom.remove('zindex');
    ed.dom.remove('mceWotmove');
    wtfn.redraw();
    
 });
   
  ed.onPreProcess.add(function(ed, o) {                        
      if (o.get)
      { 
        //data tag
        if ( sd = ed.dom.get('simplemodal-data') )
        sd.removeAttribute('id', 0);
        
        jQuery( ed.getDoc() ).find('.mceWotlayer').removeAttr('class');
        jQuery( ed.getDoc() ).find('.mceWotlayer').removeAttr('id');
        jQuery( ed.getDoc() ).find('#mceDeleteObj').remove();
        
        ed.dom.remove( 'mceDeleteObj' );
        ed.dom.remove( 'mceWotmove' );        
        ed.dom.remove('zindex');
        
        _ccdom(ed, ed.dom.get('simplemodal-container'), 1);
        
      }
  });
  
  ed.onPostProcess.add(function(ed, o) {                        
      if (o.get)
      { 
        //data tag
        if ( sd = ed.dom.get('simplemodal-data') )
        sd.removeAttribute('id', 0);        
        
        jQuery( ed.getDoc() ).find('.mceWotlayer').removeAttr('class');
        jQuery( ed.getDoc() ).find('.mceWotlayer').removeAttr('id');
        jQuery( ed.getDoc() ).find('#mceDeleteObj').remove();        
        
        ed.dom.remove( 'mceDeleteObj' );
        ed.dom.remove( 'mceWotmove' );        
        ed.dom.remove('zindex');        
        
        wtfn.redraw();
        
        _ccdom(ed, ed.dom.get('simplemodal-container'), 1);
        
      }
  });
  
  ed.onDblClick.add(function(ed, e) {
  wtfn.prevent_default(e);
  return false;    
  });
   
 },//mce
  close_button_pos : function( btn_class ) {
  var wtdom = tinyMCE.activeEditor.dom, bcls = wtdom.get('close'), mn = wtdom.get('simplemodal-container'), mn_w = jQuery(mn).width(), mn_h = jQuery(mn).height(), gw_loc = jQuery('#optinrev_gotowebsite').val();

  bw = (mn) ? parseInt(mn.style.border.substring(0, mn.style.border.indexOf('px'))) : 1;
  
  loc = 20;
  if ( bcls ) {
      if ( /close7|close8/.test(bcls.className) ) {      
      loc = ( gw_loc == 'bottom' ) ? (mn_h - 56) : loc;  
      } 
  }
    
  var clpos = {
    'close1' : { left: ((mn_w - (30 / 2)) + (bw-1)), top: -( 30 / 2 ) - (bw - 1) },
    'close2' : { left: ((mn_w - (45 / 2)) + (bw-1)), top: -( 45 / 2 ) - (bw - 1) },
    'close3' : { left: ((mn_w - (60 / 2)) + (bw-1)), top: -( 60 / 2 ) - (bw - 1) },
    'close4' : { left: ((mn_w - (30 / 2)) + (bw-1)), top: -( 30 / 2 ) - (bw - 1) },
    'close5' : { left: ((mn_w - (45 / 2)) + (bw-1)), top: -( 45 / 2 ) - (bw - 1) },
    'close6' : { left: ((mn_w - (60 / 2)) + (bw-1)), top: -( 60 / 2 ) - (bw - 1) },
    'close7' : { left: (mn_w - 272), top: loc },
    'close8' : { left: (mn_w - 272), top: loc }
  }
  
  if ( !btn_class )
  btn_class = jQuery(bcls).attr('class');
  
  return clpos[btn_class];      
  },
  gw_loc_btn: function( loc ) {
    var wtdom = tinyMCE.activeEditor.dom, bcls = wtdom.get('close'), mn = wtdom.get('simplemodal-container'), mn_h = jQuery(mn).height();
    if ( /close7|close8/.test(bcls.className) ) {
       if (loc == 'bottom') {
       wtdom.setStyle( bcls, 'top', (mn_h - 56) );
       } else {
       wtdom.setStyle( bcls, 'top', 20 );
       } 
    }
  },
  input_autotext: function( id, vl ) {
  var wtdom = tinyMCE.activeEditor.dom;  
  if ( txt = wtdom.get( id ) ) {
  wtdom.setAttrib( txt, 'value', vl );
  }    
  },
  /**
   * IMAGES
   */     
  //auto add images from briefcase
  action_add_image_briefcase: function( bfcase ) { 
     if ( tinyMCE.activeEditor != null ) { 
         var wtdom = tinyMCE.activeEditor.dom;
         jQuery.each( jQuery.parseJSON(bfcase), function(i,v) {
            var img = v;
            jQuery.post('admin-ajax.php', {action : 'optinrev_action', optinrev_add_image : img, optinrev_curr_page : wtpage}, function(res){              
              
              var ac = j.parseJSON( res );
              if ( ac.action == 'success' )
              {
                //set the marker         
                wtdom.add( wtdom.get('simplemodal-data'), 'div', {
                style : { 'position': 'absolute', left: 10, top: 10, 'border': '1px solid transparent' }
                }, wtdom.create('img', {id : img, 'src' : ac.image, 'border' : 0}, null));
                
                //TODO       
                tinyMCE.activeEditor.isNotDirty = 0;
                is_editing = true;
                
                setTimeout(function(){
                wtfn.save(0);
                }, 2000);
                
              }  
            });
         });   
     }
  },
  //add image to the canvas
  action_add_image: function( img ) {  
   if ( confirm('Are you sure, you want to insert this image in Optin Popup 1 ?') ) {
   jQuery.post('admin-ajax.php', {action : 'optinrev_action', optinrev_add_image_briefcase : img, optinrev_curr_page : 'optin1'}, function(){wtfn.msg( 'Successfully added.' );});
   }
   return false; 
  },
  //delete image to the canvas
  action_del_image: function( img ) {     
   if ( confirm('Are you sure, you want to delete this image ?') ) {
   jQuery.post('admin-ajax.php', {action : 'optinrev_action', optinrev_del_image_briefcase : img, optinrev_curr_page : 'optin1'}, function(){wtfn.msg( 'Successfully deleted.' );});   
   }
   return false; 
  },
  //auto del images from briefcase
  action_del_image_briefcase: function( delbfcase ) {
     if ( tinyMCE.activeEditor != null ) {
         var wtdom = tinyMCE.activeEditor.dom;
         jQuery.each( jQuery.parseJSON(delbfcase), function(i,v) {         
         wtdom.remove(v);                  
         });         
         tinyMCE.activeEditor.isNotDirty = 0;
         is_editing = true;
         
         setTimeout(function(){
         wtfn.save(0);
         }, 2000);
     }
  },
  /**
   * IMAGES---------------------------------------------------
   */

  /**
   * ACTION BUTTON
   */     
  //auto add action button from briefcase
  action_add_button_briefcase: function( is_actionbtn ) {   
     if ( tinyMCE.activeEditor != null ) { 
        var wtdom = tinyMCE.activeEditor.dom, mn = wtdom.get('simplemodal-container'), mn_w = jQuery(mn).width();
        if ( img = wtdom.get('wm') )
        {        
          wtdom.replace( wtdom.create('img', {id : 'wm', 'src' : is_actionbtn, 'border' : 0}, null), img );                  
          //prev gap        
          crnb = wtdom.get('wm');
          pcrnb = crnb.parentNode;          
          bgp = mn_w - jQuery(crnb).width();          
          wtdom.setStyle( pcrnb, 'left', (bgp - 30) );
          tinyMCE.activeEditor.isNotDirty = 0;
          
         setTimeout(function(){
         wtfn.save(0);
         }, 2000);
        }   
     }
  },
  action_del_action_button: function( todel_action ) {
  if ( tinyMCE.activeEditor != null ) { 
      var wtdom = tinyMCE.activeEditor.dom;  
      jQuery.each( jQuery.parseJSON( todel_action ), function(i,v) {
        b = v.split('|');  
        if ( img = wtdom.get('wm') ) {           
           if ( img.src.indexOf( b[0] ) >= 0 ) {
           wtfn.action_add_button_briefcase( b[1] );
           }          
        }         
      });
  }
  },
  //add action button to the canvas
  action_add_button: function( img ) {   
   if ( confirm('Are you sure, you want to update the action button of Optin Popup 1') ) {
   jQuery.post('admin-ajax.php', {action : 'optinrev_action', optinrev_add_button_briefcase : img, optinrev_curr_page : 'optin1'}, function(){wtfn.msg( 'Successfully updated.' );});   
   }
   return false; 
  }
  /**
   * ACTION BUTTON---------------------------------------------------
   */   
     
  }//end fn
  
jQuery(document).ready( function($) {
    var wted = {
        msger: function( id, msg ) {
        sp = document.createElement('div');        
        $('.tmsg').remove();        
        $(sp).attr('class', 'tmsg');        
        $(sp).attr('id', '#post-message');        
        $(sp).html( msg );
        $('#' + id).after( $(sp) );        
        setTimeout(function(){ $('.tmsg').remove(); }, 3000);
        },         
        move_closebtn: function() {
        var wtdom = tinyMCE.activeEditor.dom, clb = wtdom.get('close');
        wtdom.setStyles( clb, wtfn.close_button_pos(0) );
        },
        stage_resize: function(w, h) {
        var wtdom = tinyMCE.activeEditor.dom, dt = wtdom.get('simplemodal-data');
        
          tinyMCE.walk(dt, function(n) {
    				if ( n.nodeName === 'DIV' )
            {
              if ( n.style.display !== 'none' ) {
                  if ( n.style.position == 'absolute' ) {
                      if ( w ) {
                          mvl = parseInt(n.style.left) + parseInt( $(n).width() );
                          //check the object width
                          if ( parseInt( $(n).width() ) > w ) {
                              wted.msger('optinrev_swidth','Unable to resize there is an object is more than the width of a stage. Please check.');                              
                              return false;
                          }                          
                                         
                          if ( mvl > w )
                          {                            
                            wtdom.setStyle( n, 'left', (parseInt(n.style.left) - parseInt( $(n).width() )) );                        
                          }
                      } else if ( h ) {                          
                          mvh = parseInt(n.style.top) + parseInt( $(n).height() );
                          //check the object height
                          if ( parseInt( $(n).height() ) > h ) {
                              wted.msger('optinrev_sheight', 'Unable to resize there is an object is more than the height of a stage. Please check.');                              
                              return false;
                          }                          
                          
                          if ( mvh > h ) {
                          wtdom.setStyle( n, 'top', (parseInt(n.style.top) - parseInt( $(n).height() )) );
                          }
                      }                      
                  }                      
              }            
            }  
    			}, 'childNodes');        
        }  
    }
  
    if (/^(optin-image-uploader|optin-action-button-uploader)$/i.test(wtpage) == false)
    {            
    $('#optinrev_wbg_opacity').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#wbg_opacity_slider').slider({value: eval($(this).val())});    
    });
  
    $('#wbg_opacity_slider').slider({
       range : 'min', value : optinrev_wbg_opacity, min : 0, max : 100, slide: function(even, ui){$('#optinrev_wbg_opacity').val( ui.value );}
    });
    
    $('#optinrev_border_opacity').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#border_opacity_slider').slider({value: eval($(this).val())});    
    });
  
    $('#border_opacity_slider').slider({
       range : 'min', value : optinrev_border_opacity, min : 0, max : 100, slide: function(even, ui){
       $('#optinrev_border_opacity').val( ui.value );
       wtfn.redraw();
       }
    });
    
    $('#optinrev_border_radius').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#border_radius_slider').slider({value: eval($(this).val())});
    wtfn.redraw();    
    });
  
    $('#border_radius_slider').slider({
       range : 'min', value : optinrev_border_radius, min : 0, max : 25, slide: function(even, ui){
       $('#optinrev_border_radius').val( ui.value );
       wtfn.redraw();
       }
    });        
    
    $('#optinrev_vborder_thickness').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#optinrev_sborder_thickness').slider({value: eval($(this).val())});    
    });
    
    $('#optinrev_sborder_thickness').slider({
       range : 'min',
       value : optinrev_border_thickness,
       min : 1,
       max : 10,
       slide: function(even, ui){
       $('#optinrev_vborder_thickness').val( ui.value );
       wtfn.redraw();                 
       }       
    });
    
    $('#optinrev_vtop_margin').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#optinrev_stop_margin').slider({value: eval($(this).val())});    
    });
    
    $('#optinrev_stop_margin').slider({
       range : 'min',       
       value : optinrev_top_margin,
       min : 0,
       max : 150,
       slide: function(even, ui){
       $('#optinrev_vtop_margin').val( ui.value );          
       }
    });

    $('#optinrev_vwidth').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#optinrev_swidth').slider({value: eval($(this).val())});
    wtfn.redraw();    
    });    

    $('#optinrev_swidth').slider({
       range : 'min',       
       value : optinrev_wwidth,
       min : 10,
       max : defs.width,
       step: 10,
       slide: function(even, ui){
       $('#optinrev_vwidth').val( ui.value );       
       wtfn.redraw();       
       wted.stage_resize( ui.value , 0);
       wted.move_closebtn();
       }
    });

    $('#optinrev_vheight').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#optinrev_sheight').slider({value: eval($(this).val())});    
    });    
    
    $('#optinrev_sheight').slider({
       range : 'min',       
       value : optinrev_hheight,
       min : 10,
       max : defs.height,
       step: 10,
       slide: function(even, ui){
       $('#optinrev_vheight').val( ui.value );             
       is_editing = true;
       wted.stage_resize(0, ui.value);
       wted.move_closebtn();
       }
    });            

    $('#optinrev_vdelay').keyup(function(event){
    if (event.which == 13) {event.preventDefault();}
    $('#optinrev_sdelay').slider({value: eval($(this).val())});    
    });    

    $('#optinrev_sdelay').slider({
       range : 'min',
       value : optinrev_delay,
       min : 0,
       max : 240,
       slide: function(even, ui){
       $('#optinrev_vdelay').val( ui.value );                 
       }
    });
    
    //optin input setup    
    $('#optinrev_inpuths').slider({
       range : 'min',
       value : optinrev_inputh,
       min : 10,
       max : 50,
       slide: function(even, ui){
       $('#optinrev_inputh').val( ui.value );
       wtfn.inputs_setup();          
       }
    });
    
    $('#optinrev_inputws').slider({
       range : 'min',
       value : optinrev_inputw,
       min : 10,
       max : 160,
       slide: function(even, ui){
       $('#optinrev_inputw').val( ui.value );
       wtfn.inputs_setup();          
       }
    });
    
    $('#optinrev_inputbts').slider({
       range : 'min',
       value : optinrev_inputbt,
       min : 1,
       max : 10,
       slide: function(even, ui){
       $('#optinrev_inputbt').val( ui.value );
       wtfn.inputs_setup();
       }
    });

    $('#optinrev_inputfzs').slider({
       range : 'min',
       value : optinrev_inputfz,
       min : 12,
       max : 72,
       slide: function(even, ui){
       $('input[name="optinrev_inputfz"]').val( ui.value );
       wtfn.inputs_setup();
       }
    });    
    
    $('input[name="optinrev_inputc"], input[name="optinrev_inputb"], input[name="optinrev_inputtc"]').change(function(){
    wtfn.inputs_setup();
    });
    
    $('#optinrev_setup_form').submit(function(){    
      wtfn.save(1);
      return false;
    });
    
    $('input=[name="optinrev_show_where"]').change(function(){
    $.post('admin-ajax.php', {action : "optinrev_action", optinrev_show_where : $(this).val()});
    });
    
    $("#optinrev_round_border").iButton({
    change: function ($input){    
    if ($input.is(":checked")) {
    $('#optinrev_border_radius').val(optinrev_border_radius).keyup(); $('#_nbr').show();
    } else {
    $('#optinrev_border_radius').val(0).keyup();$('#_nbr').hide();
    }    
    }    
    });
    
    }//check other page
      
     $('#optinrev_callupbutton').live('change', function() {
     if ( !is_upload ) {
           alert('Sorry can\'t upload to Optin Revolution upload directory. Please set to writable.');
           return false;
     }     
     $('.spin').show();  		  
     $('#optinrev_fcallupbutton').ajaxForm({complete: function(xhr){
     if ( xhr.responseText ) {
        var ac = $.parseJSON( xhr.responseText );
        if ( ac.action == 'success' ) {        
        $('#optinrev_callaction_btn').val( 'optinrev_callaction_btn' + ac.btn_count );
        $('.spin').hide();
        wtfn.msg('Successfully upload.');        
        }        
        $('#load_list_img').load('admin-ajax.php', {action : 'optinrev_action', optinrev_load_callbtns : 1});
     }
     }}).submit();
     //$(this).val('');
	   });   
     
      //uploader
     $('#optinrev_file_upload').live('change', function() {
       if ( !is_upload ) {
           alert('Sorry can\'t upload to Optin Revolution upload directory. Please set to writable.');
           return false;
       } 
       $('.spin').show();  		  
       $('#optinrev_fuploads').ajaxForm({complete: function(xhr){       
       if ( xhr.responseText ) {     
          var ac = $.parseJSON( xhr.responseText );
          if ( ac.action == 'success' ) {        
          $('#optinrev_upload_id').val( 'optinrev_uid_' + ac.btn_count );
          $('.spin').hide();
          wtfn.msg('Successfully uploaded.');        
          }
          $('#load_list_img').load('admin-ajax.php', {action : 'optinrev_action', optinrev_load_uploads : 1});
          $('#load_list_img2').load('admin-ajax.php', {action : 'optinrev_action', optinrev_action_button_uploads : 1});
       }
       }}).submit();
            
	   });
     
     $('#optinrev_list').change(function(){
        if ( $(this).val() === 'reset' ) {
        $('#action_reset').click();
        return false;
        }
     });
     
     //clone
     $('#action_clone').click(function(){
        var wl = $('#optinrev_list').val(), pg = $('#page').val();
        
        if ( wl === 'reset' ) return false;
        
        if (wl.length == 0)
        {
          wtfn.msg('No optin to clone. Please select.');
          return false;
        }
        
        if ( wl === pg )
        {
          wtfn.msg('You cannot clone by itself. Please select other one.');
          return false;
        }
        
        if (confirm('Are you sure, you want it cloned?')) {
        $.post('admin-ajax.php', {action : 'optinrev_action', optinrev_popup_cloned : wl, optinrev_curr_page : pg}, function(res){
          if (res == 'success') {
          wtfn.msg( wl + ' has been cloned. Please wait. It will reload the page.');
          setTimeout(function(){ window.location.reload(); }, 1000);    
          }  
        });
        }
     });
     
     //reset
     $('#action_reset').click(function(){        
        if (confirm('Are you sure, you want it to reset?')) {
        wtfn.set_optin_default(1);               
        }
        return false;
     });
     
     //preview
     $('#action_preview').click(function(){        
        wtfn.save(0);
        wtfn.msg( 'Please wait. It will reload the page.' );        
        window.setTimeout(function(){window.location = window.location.href + '&show=optin';}, 1000);    
        return false;
     });
     
     $('input[name="optinrev_close_popup_image"]').change(function(){
        var wtdom = tinyMCE.activeEditor.dom, clbtn = wtdom.get('close');
        get_close_btn = $(this).val();  
        if ( clbtn = wtdom.get('close') ) {
            wtdom.setAttrib( clbtn, 'class', get_close_btn );
            wtdom.setStyles( clbtn, wtfn.close_button_pos( get_close_btn ) );                      
        }
     });
     
     $('input[name="optinrev_border_color"],input[name="optinrev_pwbg_color"] ').change(function(){      
      wtfn.redraw();      
     });   

    
    //Email Marketing form selection
    $('input[name="optinrev_email_form_opt"]').change(function(){    
    $('#wotinput_fields').load('admin-ajax.php', {action : 'optinrev_action', optinrev_mail_webform : wtpage, optinrev_mail_provider: $('input[name="optinrev_email_form_opt"]:checked').val()});
    });
    
    $('.mail_opt img').click(function(){$('input[name="optinrev_email_form_opt"][value="'+ $(this).attr('id') +'"]').attr('checked',true).change();});    
    //Save button scroll
    $(window).scroll(function(){if ( $(window).scrollTop() > 170 ) {$('#wotbuttons').css( {'position' : 'fixed', 'right' : '14px', 'top' : '36px', 'z-index': 9999} );} else {$('#wotbuttons').css( {'position' : 'static'} );}});
    jsoptin_load.init();
     
    $.extend({        
    //Email Marketing form update in editor
    inputfields_update : function(){
        var wtdom = tinyMCE.activeEditor.dom, mailopt = $('input[name="optinrev_email_form_opt"]:checked').val();
        var mpr = elh = elt = null;
        var timg = 'http://forms.aweber.com/form/displays.htm?id=';
        var has_el = wpp = ns = trkid = null;
        
        if ( mailpro )
        {
            //data
            mpr = mailpro[ mailopt ];
            //form
            mf = document.createElement('form');
            mf.setAttribute( 'method', 'post' );
            mf.setAttribute( 'id', 'mce_getaccessed' );
            mf.setAttribute( 'action', mpr.action );
            mf.setAttribute( 'target', '_blank' );
            
            if ( mpr.hidden.length > 0 )
            elh = mpr.hidden.split(',');
            
            if ( mpr.text.length > 0 )
            elt = mpr.text.split(',');
            
            //hidden;
            if ( elh ) {
                
                //wrap the hidden
                wh = document.createElement('div');                
                $(wh).attr('style', 'display:none;');
                                
                for( var r = 0; r < elh.length; r++ )
                {
                    hd = document.createElement('input');
                    hd.setAttribute( 'type', 'hidden' );            
                    hd.setAttribute( 'name', elh[r] );
                    
                    elv = $('input[name="optinrev_email_form['+ mailopt +']['+ elh[r] +']"]').val();
                    
                    if ( typeof elv != 'undefined' ) {
                    
                    if ( elh[r] === 'meta_required' )
                    hd.setAttribute( 'value', 'email' );
                    else
                    hd.setAttribute( 'value', elv );
                
                    if ( elv.length > 0 ) {
                        if ( elh[r] != 'pixel_tracking_id' )
                        wh.appendChild( hd );
                        else
                        trkid = elv;
                    }
                    
                    }
                    
                }
                mf.appendChild( wh );
            }

        //textbox
        if ( elt )
        {              
              pos = {x : 10, y : 20};
              for( var r = 0; r < elt.length; r++ )        
              {   
                  //try to check if exists
                  if ( ftxt = wtdom.get( elt[r] ) ) {
                       wpp = ftxt.parentNode;                        
                       has_el = true;
                       pos = { x : $(wpp).position().left, y : $(wpp).position().top };
                  }
                   
                      //input text element
                      tx = document.createElement('input');
                      tx.setAttribute( 'type', 'text' );
                      tx.setAttribute( 'name', elt[r] );
                      tx.setAttribute( 'id', elt[r] );
                      tx.setAttribute( 'value', $('input[name="optinrev_email_form['+ mailopt +']['+ elt[r] +']"]').val() );
                      
                      //wrapping
                      if ( elt[r].indexOf('name') >= 0 ) {
                      ns = ( parseInt($('#optinrev_input_' + elt[r]).val()) === 0 ) ? 'display:none;' : '';
                      } else
                      ns = '';
                      
                      dw = document.createElement('div');
                      $(dw).attr('style', 'position:absolute; left: '+ pos.x +'px; top: '+ pos.y +'px;border: 1px solid transparent;' + ns);                      
                      dw.appendChild( tx );
                      
                      if ( !has_el )
                      pos.y += 20;
                      
                      mf.appendChild( dw );
                              
              }           
          }
        
        
        //add tracking pixel
        if ( mailopt === 'aweber' )
        {
            if ( typeof trkid != 'undefined' ) {
            pimg = document.createElement( 'img' );
            pimg.setAttribute( 'src', timg + trkid );        
            sp = document.createElement( 'span' );
            $(sp).attr( 'style', 'display:none;' );
            sp.appendChild( pimg );
            mf.appendChild( sp );
            }
        }
              
        //clean up form;
        if ( typeof wtdom.select("form")[0] !== 'undefined' ) 
        wtdom.remove(wtdom.select('form')[0]);
        
        //set the marker
        smd = wtdom.get('simplemodal-data');
        smd.appendChild( mf );
        
        //update the design;
        wtfn.inputs_setup();
        //saving        
        
        }//end
      
    }
    
  });
     
  });