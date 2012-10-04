jQuery(document).ready(function($) {
	$('.show-versions').click(function(event) {
		event.preventDefault();
		$(this).parent().next().slideToggle();
	})
});
