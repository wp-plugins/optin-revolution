// php.rcena@gmail.com
(function() {
tinymce.create('tinymce.plugins.InsertVid', {    
    
   init : function(ed, url) {
   var t = this, o;
   
	 ed.addCommand('mceInsertVid', function( ui, v )
   {    
        wtfn.upgrade(1);    		
        return false;
	 });
   
   ed.addButton('insertvid',
   {
   title : 'Insert a Video',
   image : wtp + 'images/ins_vid.gif',
   cmd : 'mceInsertVid'
   });
   
   }
  });

// Register plugin with a short name
tinymce.PluginManager.add('insertvid', tinymce.plugins.InsertVid);
})();