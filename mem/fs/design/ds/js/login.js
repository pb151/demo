jQuery(document).ready(function() {
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	Login.init();
	Demo.init();
	
});

var Login = function() {

	var handleLogin = function() {

		$('.login-form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			rules: {
				username: {
					required: true
				},
				password: {
					required: true
				}
			},
			invalidHandler: function(event, validator) { //display error alert on form submit
				$('.wrong-credential', $('.login-form')).show();
			},


			highlight: function(element) { // hightlight error inputs
				$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
			},

			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},

			errorPlacement: function(error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},

			submitHandler: function(form) {
				// form.submit(); // form validation success, call ajax form submit
				loadMask({
					flag: true
				});
				$.ajax({
					type: 'POST',
					url: '/ajax.php?action=do_login&nocache=' + new Date().getTime(),
					dataType: 'json',
					data: {
						username : form['username'].value,
						password : form['password'].value
					},
					success: function (obj) {
						loadMask({
							flag: false
						});
						if(obj.success) {
							toastr['success'](obj.message);
							
							window.setTimeout(function () {
								window.location.href = 'overview.php';
							}, 1000);
						} else {
							$('#error-message').text(obj.error_message);
							$('.wrong-credential', $('.login-form')).show();
						}
					}
				});
			}
		});

		$('.login-form input').keypress(function(e) {
			if (e.which == 13) {
				if ($('.login-form').validate().form()) {
					$('.login-form').submit(); //form validation success, call ajax form submit
				}
				return false;
			}
		});
	};
	
	var handleChangePassword = function() {
		$('.change-form').validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",
			rules: {
				old_password: {
					required: true
				},
				new_password: {
					required: true
				},
				confirm_password: {
					equalTo: "#new_password"
				}
			},
			
			invalidHandler: function(event, validator) { //display error alert on form submit
				$('.wrong-credential', $('.change-form')).show();
			},
			
			highlight: function(element) { // hightlight error inputs
				$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
			},
			
			success: function(label) {
				label.closest('.form-group').removeClass('has-error');
				label.remove();
			},
			
			errorPlacement: function(error, element) {
				error.insertAfter(element.closest('.input-icon'));
			},
			
			submitHandler: function(form) {
				// form.submit(); // form validation success, call ajax form submit
				loadMask({
					flag: true
				});
				$.ajax({
					type: 'POST',
					url: '/ajax.php?action=change_password&nocache=' + new Date().getTime(),
					dataType: 'json',
					data: {
						old_password : form['old_password'].value,
						new_password : form['new_password'].value,
						confirm_password : form['confirm_password'].value
					},
					success: function (obj) {
						loadMask({
							flag: false
						});
						if(obj.success) {
							toastr['success'](obj.message);
							
							window.setTimeout(function () {
								window.location.href = 'overview.php';
							}, 1000);
						} else {
							$('.wrong-credential', $('.change-form')).show();
						}
					}
				});
			}
		});
		
		$('.change-form input').keypress(function(e) {
			if (e.which == 13) {
				if ($('.change-form').validate().form()) {
					$('.change-form').submit();
				}
				return false;
			}
		});
	};
	
	return {
		//main function to initiate the module
		init: function() {
			handleLogin();
			handleChangePassword();
		}

	};

}();