tinyMCEPopup.requireLangPack();

var TextEditDialog = {
  isw100 : false,    
  clearbr : function ( wl ) {
        itx = wl;        
        li = itx.lastIndexOf('<br>');
        return itx.substring( 0, li ) + itx.substring( itx.lastIndexOf('<br>') + 4, itx.length );
  },
	init : function() {
		var wl, ed = tinyMCEPopup.editor;    
    tinymce.each(ed.dom.select('div', ed.getDoc()), function(e) {
        if ( e.className == 'mceWotlayer' ) {
         wl = e;
        }		
	  });
    
    //width
    if (wd = ed.dom.getStyle( wl, 'width' )) {
        document.getElementById('wlayer').value = wd.replace(/%/,'');
    }
    
    if ( wl ) {        
        ps = ed.dom.parseStyle( ed.dom.getAttrib(wl, 'style') );        
        delete ps.left;
        delete ps.top;
        
        //if 100%
        if ( ps.width == '100%' )
        {        
          ps.width = '98%';
          this.isw100 = true;
          } else {
          this.isw100 = false;
        }        
                             
        s = ed.dom.serializeStyle( ps, null );
        
        document.getElementById('mce_textedit').style.width = '100%';
        document.getElementById('mce_textedit').style.height = '420px';    
        document.getElementById('mce_textedit').value = '<div id="templayer" style="'+ s +'">'+ wl.innerHTML +'</div>';                        
    }
	},  
 	insert : function() {
       
    var ed = tinyMCEPopup.editor, dom = tinyMCE.activeEditor.dom, tmp = dom.get('templayer'), wl = ed.dom.get('mceWotlayer');
    var obstg = ed.dom.get('simplemodal-container'), stgstl = ed.dom.parseStyle( ed.dom.getAttrib(obstg, 'style') );
    
    if ( !tmp ) {
    tmp = dom.select('div')[0]; 
    dom.setAttrib( tmp, 'id', 'templayer' );
    }
    
    dom.show('mceDeleteObj');    
    ed.dom.setHTML( 'mceWotlayer', tmp.innerHTML );    
    
    if ( st = dom.getAttrib( tmp, 'style' ) )
    {        
        
        if ( this.isw100 )
        st = st.replace(/98\%/,'100%');
    
        if ( st.length > 0 ) {
            st = dom.parseStyle( st );
            ed.dom.setStyles( wl, st );            
            
            if ( this.isw100 ) {
            ed.dom.setStyle( wl, 'left', 1 );            
            }
        }
        
    } else {
    
        if ( document.getElementById('wlayer').value == '' ) {        
            //remove width            
            ps = ed.dom.parseStyle( ed.dom.getAttrib(wl, 'style') );
            delete ps.width;                     
            s = ed.dom.serializeStyle( ps, null );
            ed.dom.setAttrib( wl, 'style', s );
        }    
    
    }
    
		tinyMCEPopup.close();
	}  
};
tinyMCEPopup.onInit.add(TextEditDialog.init, TextEditDialog);
