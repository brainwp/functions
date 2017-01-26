
function faz_ajax(data, action){
	$.ajax({
	  url:  odin_main.ajaxurl,
	  type: 'POST',
	  data: data,
	  cache: false,
	  dataType: 'json',
	  success: function(response) {	
		 
	  	response=jQuery.parseJSON(response);

	  },
 	  error: function(e){ 
      	response="erro";
   	 }
  });
	return response;
}
