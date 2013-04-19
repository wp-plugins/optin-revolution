// php.rcena@gmail.com
(function() { 

tinymce.create('tinymce.plugins.JSPopupImage', {
   
   init : function(ed, url) {
   var t = this, o;
   
	 ed.addCommand('mceSetJSPopupImage', function( ui, v )
   {  
      wtfn.upgrade(1);    		
      return false;  		
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
                
                mlb.add( 'Attach a Javascript Popup Message',  '' );                
                return mlb;
           break;                                    
        }

        return null;
    }    

  });

// Register plugin with a short name
tinymce.PluginManager.add('jspopupimg', tinymce.plugins.JSPopupImage);
})();