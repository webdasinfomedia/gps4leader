jQuery(document).ready(function($) {
	
	 $("#current_crm").change(function(){
			
		
		 var selection = $("#current_crm").val();
		
		
		var optionval = $(this);
			var curr_data = {
					action: 'cart2crm_connectoin_setting_block',
					current_crm: $("#current_crm").val(),			
					dataType: 'json'
					};					
					
					$.post(cart2crm.ajax, curr_data, function(response) {					
					$('#crm_setting_block').html(response);
					});
						
						
		
	});
	 //CustomCRM
	 $("body").on("click", "#confirm_order", function(){
		var url = $(this).attr("customurl");
		var confirm_order = $("#confirm_order1").val();	
		if(confirm_order == 'on')
		{	 
			$(this).attr('src',url+'off.png');
			$("#confirm_order1").val('off');
		}
		else
		{
			$(this).attr('src',url+'on.png');
			$("#confirm_order1").val('on');
			
		}
		  var curr_data = {
							action: 'ajax_cart2crm_integration',
							confirm_order: confirm_order,			
							dataType: 'json'
							};
							//alert('hello');
							$.post(cart2crm.ajax, curr_data, function(response) {
								
							
							});	
	});
	 
	 $("body").on("click", "#aband_cartimg", function(){
			var url = $(this).attr("customurl");
			var aband_cart = $("#aband_cart").val();	
			if(aband_cart == 'on')
			{	 
				$(this).attr('src',url+'off.png');
				$("#aband_cart").val('off');
			}
			else
			{
				$(this).attr('src',url+'on.png');
				$("#aband_cart").val('on');
				
			}
			  var curr_data = {
								action: 'ajax_cart2crm_cart_integration',
								aband_cart: aband_cart,			
								dataType: 'json'
								};
								//alert('hello');
								$.post(cart2crm.ajax, curr_data, function(response) {
									
								
								});	
		});
	
	 $("#select_table").change(function(){
			
			
		 var selection = $("#select_table").val();
		
		
		var optionval = $(this);
			var curr_data = {
					action: 'ajax_cart2crm_table_field',
					select_table: $("#select_table").val(),			
					dataType: 'json'
					};					
					
					$.post(cart2crm.ajax, curr_data, function(response) {
						;
					$('#fieldlist').html(response);
					});
						
						
		
	});
	 
	 
	 //SugarCRM
	 $("body").on("click", "#sugar_confirm_order", function(){
			var url = $(this).attr("customurl");
			var confirm_order = $("#confirm_order1").val();	
			if(confirm_order == 'on')
			{	 
				$(this).attr('src',url+'off.png');
				$("#confirm_order1").val('off');
			}
			else
			{
				$(this).attr('src',url+'on.png');
				$("#confirm_order1").val('on');
				
			}
			  var curr_data = {
								action: 'ajax_cart2crm_sugar_order_integration',
								confirm_order: confirm_order,			
								dataType: 'json'
								};
								//alert('hello');
								$.post(cart2crm.ajax, curr_data, function(response) {
									
								
								});	
		});
		 
		 $("body").on("click", "#sugar_aband_cartimg", function(){
				var url = $(this).attr("customurl");
				var aband_cart = $("#aband_cart").val();	
				if(aband_cart == 'on')
				{	 
					$(this).attr('src',url+'off.png');
					$("#aband_cart").val('off');
				}
				else
				{
					$(this).attr('src',url+'on.png');
					$("#aband_cart").val('on');
					
				}
				  var curr_data = {
									action: 'ajax_cart2crm_sugar_cart_integration',
									aband_cart: aband_cart,			
									dataType: 'json'
									};
									//alert('hello');
									$.post(cart2crm.ajax, curr_data, function(response) {
										
									
									});	
			});
		
		 
		//SuiteCRM
		 $("body").on("click", "#suite_confirm_order", function(){
				var url = $(this).attr("customurl");
				var confirm_order = $("#confirm_order1").val();	
				if(confirm_order == 'on')
				{	 
					$(this).attr('src',url+'off.png');
					$("#confirm_order1").val('off');
				}
				else
				{
					$(this).attr('src',url+'on.png');
					$("#confirm_order1").val('on');
					
				}
				  var curr_data = {
									action: 'ajax_cart2crm_suite_order_integration',
									confirm_order: confirm_order,			
									dataType: 'json'
									};
									//alert('hello');
									$.post(cart2crm.ajax, curr_data, function(response) {
										
									
									});	
			});
			 
			 $("body").on("click", "#suite_aband_cartimg", function(){
					var url = $(this).attr("customurl");
					var aband_cart = $("#aband_cart").val();	
					if(aband_cart == 'on')
					{	 
						$(this).attr('src',url+'off.png');
						$("#aband_cart").val('off');
					}
					else
					{
						$(this).attr('src',url+'on.png');
						$("#aband_cart").val('on');
						
					}
					  var curr_data = {
										action: 'ajax_cart2crm_suite_cart_integration',
										aband_cart: aband_cart,			
										dataType: 'json'
										};
										//alert('hello');
										$.post(cart2crm.ajax, curr_data, function(response) {
											
										
										});	
				});
	 
	 
});