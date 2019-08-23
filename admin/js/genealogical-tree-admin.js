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

		$('.repetead-field').each(function(){
			$(this).find('.delete').click(function(){
				$(this).parents('.repetead-field').remove();
			})
			var base = this;
			$(this).find('.clone').click(function(){
				var  child = $(base).parent().children().length;
				console.log(child);
				var html = $(base).clone()[0].outerHTML; 
			    var find = ['clone','Add', 0];
			    var replace = ['delete','Delete', child];
			    $.each(find,function(i,v) {
			        html = html.replace(new RegExp('\\b' + v + '\\b', 'g'), replace[i]);
			    });
				$(html).appendTo($(base).parent());
				$('.repetead-field').each(function(){
					$(this).find('.delete').click(function(){
						$(this).parents('.repetead-field').remove();
					})
				})
				$('span[data-ref-c]').each(function(){
					var cop = $(this).data('ref-c');
					$(this).text(cop+1);
				})
			})
		})


	})
})( jQuery );
