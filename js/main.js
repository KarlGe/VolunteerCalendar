$( document ).ready(function() {
    console.log( "ready!" );
    $( "#addPersonDateFrom" ).datepicker({
		inline: true,
		dateFormat: "dd-mm-yy"
	});
});