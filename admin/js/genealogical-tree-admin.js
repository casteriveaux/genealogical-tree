(function( $ ) {
	'use strict';
	$(window).on('load', function(){
		$('#birth-sex').change(function(){
			var gt_sex = $('#birth-sex').val();
			if(!gt_sex) {
				$('tr.tr-husb').show();
				$('tr.tr-wife').show();
			}
			if(gt_sex==='F'){
				$('tr.tr-wife').hide();
				$('tr.tr-husb').show();
			}
			if(gt_sex==='M'){
				$('tr.tr-husb').hide();
				$('tr.tr-wife').show();
			}
		})

	})
})( jQuery );
