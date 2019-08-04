(function($){
	function closePopover() {
		$("#cg-popowner").popover('destroy');
		$("#cg-popowner").parent().removeClass("show-popover");
		$("#cg-popowner").removeAttr('id');
	}

	$(document).ready(function() {

		$(window).bind("ajaxSend", function() {
			$('#spinner').show();
		}).bind("ajaxStop", function() {
			$('#spinner').hide();

			var uls = $('#famTree ul');
			for (var i = 0; i < uls.length; i++) {
				var childrens = $(uls[i]).children();
				if(childrens.length > 1){
					var heights = [];
					for (var j = 0; j < childrens.length; j++) {
						var height = $(childrens[j]).find('div.indi').first().height();
						heights.push(height)
					}
					for (var k = 0; k < childrens.length; k++) {
						$(childrens[k]).find('div.indi').first().height(Math.max.apply(Math,heights));
					}
					
					
				}
				
			}
			
		}).bind("ajaxError", function() {
			$('#spinner').hide();
		});

		
/* Premium Code Stripped by Freemius */



	});

})(jQuery)