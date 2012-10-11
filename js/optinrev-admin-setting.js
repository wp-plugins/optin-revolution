  jQuery(document).ready(function($){            
	$("#optinrev_optin1_enabled").iButton({change: function($input){$.post("admin-ajax.php", {action : "optinrev_action", "optin_popup" : "optinrev_optin1_enabled", "enabled" : $input.is(":checked")}, function(res){wtfn.msg('Successfully Updated.');});}});
  $("#optinrev_autosave").iButton({change: function($input){$.post("admin-ajax.php", {action : "optinrev_action", "optinrev_autosave" : $input.is(":checked")}, function(res){wtfn.msg('Successfully Updated.');});}});
  $("#optinrev_poweredby").iButton({change: function($input){$.post("admin-ajax.php", {action : "optinrev_action", "optinrev_poweredby" : $input.is(":checked")}, function(res){wtfn.msg('Successfully Updated.');});}});
     	
	$('input[name="optinrev_show[]"]').change(function(){
	v = $(this).val();
	$('#save_showset').show();
	});
	
	$('#optinrev_save_showset').click(function(){
	jQuery('#save_showset').hide();        
	$('input[name="optinrev_show[]"]').each(function(i,v){
	   vl = $(v).val(); 
	   if ( $(v).attr('checked') ) {            
	       if ( vl == 'times_per_session' ) {
	       wtfn.optinrev_show_popup('show_times_per_session');       
	       } else if ( v == 'times_per_session' ) {
	       wtfn.optinrev_show_popup('show_once_in');
	       }       
	   }
	});
	});
	
	$('#save_showset').hide();
	    
    });