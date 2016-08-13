$( document ).ready(function() {
    console.log( "ready!" );
    $( "#addPersonDateFrom" ).datepicker({
		inline: true,
		dateFormat: "dd-mm-yy"
	});
	$( "#addPersonDateTo" ).datepicker({
		inline: true,
		dateFormat: "dd-mm-yy"
	});
	 $("#submitPersonForm").submit(function(event){
	 	
		var url = "../php/SubmitPerson.php"; // the script where you handle the form input.

	    $.ajax({
			type: "POST",
			url: url,
			data: $("#submitPersonForm").serialize(), // serializes the form's elements.
			success: function(data)
			{
				ShowMessage(data);
			}
		});

	 	event.preventDefault();
	 })
	/*$.ajax({
  		method: "POST",
  		url: "some.php",
  		data: { name: "John", location: "Boston" }
	}).done(function( msg ) {
    	alert( "Data Saved: " + msg );
  	});*/
});
//The message should include necessary html tags for styling. for example <div class='alert alert-success'> </div>
function ShowMessage(message){
	$("#infoMessageBox").hide();
	$("#infoMessageBox").html(message);
	$("#infoMessageBox").slideDown(200).delay(5000).fadeOut(200);
}