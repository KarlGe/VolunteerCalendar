var waitingForReply = false;
var transactionsHidden = true;
$( document ).ready(function() {
	$('#search-hidden-mode').hideseek({
    	hidden_mode: true,
  		ignore_accents: true,
  		navigation: true,
  		ignore: '.ignore'
  	});

	$('#volunteerSearch').on('click', function(e) {
	    e.stopPropagation();
  		$("#volunteerSearch hr").show();
  		$(".hidden_mode_list").show();
	});

	$(document).on('click', function (e) {
		$("#search-hidden-mode").addClass("activeSearch");
		$("#volunteerSearch hr").hide();
  		$(".hidden_mode_list").hide();
	});

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
	$('#contractCheckBox').click(function() {
		if(!waitingForReply){
			ContractCheckBoxClicked($(this));
		}

	});
	$('.transactionTrash').click(function() {
		if(!waitingForReply){
			ToggleTransaction($(this).parent());
		}
	});
	$( "#showDeletedButton" ).click(function() {
		if(transactionsHidden){
			$(this).html("Hide deleted");
		}
		else{
			$(this).html("Show deleted");	
		}
  		ToggleDeletedTransactions();
	});
	$( "#transactionHistoryTable .deletedTransaction" ).each(function() {
		$(this).hide();
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
function ContractCheckBoxClicked(element){
	waitingForReply = true;
	newValue = element.attr("value") == 1 ? 0 : 1;
	$.post( "../php/UpdateVolunteer.php", { pk: element.attr("data-pk"), name: "contractSigned", value: newValue}).done(function( data ) {
		$(element).attr("value", data);
		if(data == 0){
			element.find("img").addClass("hidden")
		}
		else {	
			element.find("img").removeClass("hidden")
		}
    	waitingForReply = false;
  	});
}
function ToggleDeletedTransactions(){
	if(transactionsHidden){
		$( "#transactionHistoryTable .deletedTransaction" ).each(function() {
			$(this).hide();
			$(this).show();
		});	
		transactionsHidden = false;
	}
	else{
		$( "#transactionHistoryTable .deletedTransaction" ).each(function() {
			$(this).hide();
		});	
		transactionsHidden = true;
	}
	
}
function ToggleTransaction(element){
	waitingForReply = true;
	if(element.attr("active") == 1){
		$.post( "../php/UpdateTransaction.php", { pk: element.attr("data-pk"), name: "delete"}).done(function( data ) {
			if(transactionsHidden){
				element.hide();
			}
	    	waitingForReply = false;
	    	element.attr("active", 0);
	    	element.addClass("deletedTransaction")
	    	trashcanElement = element.find('.trashCan');
	    	trashcanElement.removeClass('trashCan');
	    	trashcanElement.addClass('restore');
	    	trashcanElement.html("Restore");
	    	SetcurrentBalance();
	  	});
	}
	else{
		$.post( "../php/UpdateTransaction.php", { pk: element.attr("data-pk"), name: "restore"}).done(function( data ) {
	    	waitingForReply = false;
	    	element.attr("active", 1);
	    	element.removeClass("deletedTransaction")
	    	trashcanElement = element.find('.restore');
	    	trashcanElement.removeClass('restore');
	    	trashcanElement.addClass('trashCan');
	    	trashcanElement.html("");
			SetcurrentBalance();
	  	});	
	}
}
function SetcurrentBalance(){
	total = 0;
	$(".transactionAmount").each(function() {
		if($(this).parent().attr("active") == 1){
			total += parseInt($(this).html());
		}
	});
	$('#transactionHistoryTotal span').html(total);
}