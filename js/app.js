/*var action = function (action) {
	alert(action);
	if (action === 'accept') {
		//change to in progress 

		//get time
	} else {
		// remove this line from the feed

		// set it as rejected on the database
	}
	return false;
}*/


var actionDelete = function(type, id){
	
	if (type && id) {
		
		data = "action=delete&type="+type+"&id="+id;
		element = '.admin_'+id;
		alert(data);
		alert(element);

	    $.ajax({
            type:'POST',
            url:'../includes/actions.php',
            data:data,
            success: function(data){
            	//alert(data);
                $(element).fadeOut().remove();
                
            }
        });
	}
	
}

/*on blur add processing symbol
check if the value is ok or check if it is in the dbase if OK then display ok on right with success mark*/

var check = function (input, check, id) {
	//check if empty
	// alert('here');
	/*if (input != '' && input.length > 2) {
		//show processing symbol
		$('#ico'+id).css({'display': 'none'});
		$('#processing'+id).css({'display': 'block'}).animate(5);
		//for regular expression checks
		if(check == 'no_db'){
			//check here
			$("#div"+id).removeClass('has-error')
			$('#processing'+id).css({'display': 'none'});
			$('#ico'+id).css({'display': 'block'});
		} else if (check == "password"){
			//check for password
			pass = $("#pass");
			console.log(pass);
			console.log($('#em'))
			retype = $("#retype").value;

			alert('p - '+pass)
			alert('r - '+retype)
			// $("#div"+id).addClass('has-error') : "";
		} else {
			//check the database
		}
	} else {
		$("#div"+id).addClass('has-error')
		//display error
	}
	*/
}

/*var create = function(accountType){
	alert(accountType);

	
		$.ajax({
		    type: 'POST',
		    url: 'contact_mail.php',
		    cache: false,
		    data: $(".contact_form").serialize(),
		    success: function (data) {
		        if (data == "error") {
		            $('.success_box').hide();
		            $('.error_box').show();
		        }
		        else {
		            $('#name').val('');
		            $('#email').val('');
		            $('#message').val('');
		            $('#website').val('');
		            $('.error_box').hide();
		            $('.success_box').show();
		        }
		    }
		});
	
}*/

//var check = (input) => alert(input);