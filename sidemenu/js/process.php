<?php

	include('../../pages.php');
    header("Content-type: text/javascript");

?>
$( document ).ready(function() {

	$("#testsuit").submit(function(event){

		var request;
		var address="<?php echo $processTestSuit; ?>";
		if (request) {
			request.abort();
		}
		// setup some local variables
		var $form = $(this);
		// let's select and cache all the fields
		var $inputs = $form.find("input, select, button, textarea");
		// serialize the data in the form
		var serializedData = $form.serialize();
		
		// let's disable the inputs for the duration of the ajax request
		$inputs.prop("disabled", true);
		
		request = $.ajax({
			url: address,
			type: "POST",
			data: serializedData
		});

		// callback handler that will be called on success
		request.done(function (response, textStatus, jqXHR){
				// log a message to the console
				$("#testoutput").text(response);
				console.log("Hooray, it worked!");
			});
		
		// callback handler that will be called on failure
		request.fail(function (jqXHR, textStatus, errorThrown){
				// log the error to the console
				console.error(
					"The following error occured: "+
					textStatus, errorThrown
					);
			});
		
		// callback handler that will be called regardless
		// if the request failed or succeeded
		request.always(function () {
				// reenable the inputs
				$inputs.prop("disabled", false);
			});
		
		event.preventDefault();

	});

});
