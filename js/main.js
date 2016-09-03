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
	$( "#transactionDate" ).datepicker({
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
	$("#submitTransactionForm").submit(function(event){
		var url = "../php/SubmitTransaction.php"; // the script where you handle the form input.
	    $.ajax({
			type: "POST",
			url: url,
			data: $("#submitTransactionForm").serialize(), // serializes the form's elements.
			success: function(data)
			{
				ShowMessage(data);
			}
		});
		event.preventDefault();
	})
	$('tr[data-href]').on("click", function() {
		document.location = $(this).data('href');
	});
	$.fn.editable.defaults.mode = 'popup';
	$('.edit').editable();
    
});
//The message should include necessary html tags for styling. for example <div class='alert alert-success'> </div>
function ShowMessage(message){
	$("#infoMessageBox").hide();
	$("#infoMessageBox").html(message);
	$("#infoMessageBox").slideDown(200).delay(5000).fadeOut(200);
}