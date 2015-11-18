
// place any jQuery/helper plugins in here, instead of separate, slower script files.
(function($) {

	$(document).ready(function() {
		$.ajax({
			type: 'GET',
			url: "/home/slow?q=1",
			async: true
		}).done(function(msg) {
			$('.one').html(msg);
		});

		$.ajax({
			type: 'GET',
			url: "/home/slow?q=2",
		}).done(function(msg) {
			$('.two').html(msg);
		});

		$.ajax({
			type: 'GET',
			url: "/home/slow?q=3",
		}).done(function(msg) {
			$('.three').html(msg);
		});
	});

}(jQuery));

