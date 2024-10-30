jQuery(document).ready(function($) {

	$('ul#hello-preflightcheck li.lines').each(function() {
		var list = $(this);
		var toggle = list.prev();
		toggle
			.addClass('toggle-enabled')
			.css('cursor', 'pointer')
			.click(function() {
				toggle.removeClass('expanded').addClass('toggling');
				list.slideToggle(200, function() {
					toggle.removeClass('toggling').toggleClass('expanded', list.is(':visible'));
				});
			})
		;
	});

});
