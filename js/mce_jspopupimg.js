// php.rcena@gmail.com
(function() { 

tinymce.create('tinymce.plugins.JSPopupImage', {
   
   init : function(ed, url) {
   var t = this, o;
   
	 ed.addCommand('mceSetJSPopupImage', function( ui, v )
   {    
        if ( v == null ) return false;
        if ( !o ) return false;  
    		if ( o.nodeName == 'IMG' )
        { 
          p = o.parentNode;          
          if ( p ) {            
            vl = v.split(',');            
            
            //changing label
            if ( p.lastChild.id === 'imglabel' )
            p.removeChild( p.lastChild );
            
            ed.dom.setAttrib( p, 'data-mce-popup', vl[1] );
            p.appendChild( ed.dom.create('div', {id : 'imglabel'}, vl[0] ) );
            
            ed.execCommand('mceRepaint');
          }
        } else ed.windowManager.alert('Unable to set the popup message. Only the image.');
  		
	 });
   
  ed.onMouseDown.add(function(ed, e) {
     if ( e.target.nodeName === 'IMG' && e.target.id != 'wm' )
        o = e.target;
	 });
   
   },      
   createControl: function(n, cm) {
        var ed = tinymce;
        
        switch (n) {
            case 'jspopupimg':            
                
                var mlb = cm.createListBox('jspopupimg', {
                     title : 'JS Popup',
                     onselect : function(v) {                     
                     ed.execCommand('mceSetJSPopupImage', false, v);                         
                     }
                });
                
                tinymce.util.XHR.send({
                   url : 'admin-ajax.php',
                   type : 'POST',
                   content_type : 'application/x-www-form-urlencoded',
                   data : 'action=wotoptin_action&wotoptin_mce_jsmessages=load',
                   success : function(text) {                      
                   l = tinymce.util.JSON.parse(text);                   
                   if ( l ) {
                      
                      tinymce.each( l, function(v, i){
                      
                      mlb.add( '('+ (i + 1) +') ' + v.option_value.replace(/\\\\/g,'\\'),  (i + 1) + ',' + v.option_name );
                                                      
                      });
                      
                      } else {
                      mlb.add( 'No js messages',  '' );
                   }
                   
                   }
                });                
                
                return mlb;
           break;                                    
        }

        return null;
    }    

  });

// Register plugin with a short name
tinymce.PluginManager.add('jspopupimg', tinymce.plugins.JSPopupImage);
})();