$(document).ready(function() {
	// $(".fixed-plugin").hide();
	
    $(".dropdown-item").on('click', function(e) {
    	e.preventDefault();
    	$("#logout-form").submit();
    });
});