    <!-- Core Scripts - Include with every page -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    
    <!-- Form validator -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/additional-methods.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?php echo base_url(); ?>assets/js/sb-admin.js"></script>

	<!-- jQuery forgot password validation -->
	<script>
		// password change validation  
	    $('#forgot-password').validate({
	        rules: {
	            email_address: {
	                required: true,
	                email: true
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
	        	email_address: "Please enter a valid email address"
	        },
	        
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block',
	        errorPlacement: function(error, element) {
	            if(element.parent('.input-group').length) {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        }
	    });
	  
	  </script>
	<!-- jQuery password change validation -->
	<script>
		// password change validation  
	    $('#password-change').validate({
	        rules: {
	            current_password: {
	            	rangelength:[6,16],
	                required: true
	            },
	            password: {
	            	rangelength:[6,16],
	                required: true
	            },
	            password2: {
	            	equalTo: '#password'
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
	             current_password: {
	                required: "Please your current password",
					rangelength: "Please enter a current password between {0} and {1} characters long."
	           	},
	            password: {
	                required: "Please provide a new password",
					rangelength: "Please enter a password between {0} and {1} characters long."
	          	},
	            password2: {
	            	equalTo: "Please enter the same passwords"
	            }
	        },
	        
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block',
	        errorPlacement: function(error, element) {
	            if(element.parent('.input-group').length) {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        }
	    });
	  
	  </script>
	<!-- jQuery forgot password validation -->
	<script>
		// password change validation  
	    $('#forgot-password').validate({
	        rules: {
	            email_address: {
	                required: true,
	                email: true
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
	        	email_address: "Please enter a valid email address"
	        },
	        
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block',
	        errorPlacement: function(error, element) {
	            if(element.parent('.input-group').length) {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        }
	    });
	  
	  </script>
	<!-- jQuery Form Validation code -->
	<script>
		// signup form validation  
	    $('#signup-form').validate({
	        rules: {
	            first_name: {
	            	rangelength:[2,50],
	                required: true
	            },
	            last_name: {
	            	rangelength:[2,50],		               
	                required: true
	            },
	            email_address: {
	                required: true,
	                email: true
	            },
	            username: {
	                required: true,
	                email: true
	            },
	            password: {
	            	rangelength:[6,16],
	                required: true
	            },
	            password2: {
	            	equalTo: '#password'
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
				first_name: {
					required: "Please enter your first name",
					rangelength: "Please enter a first name between {0} and {1} characters long."					
				},
				last_name: {
					required: "Please enter your last name",
					rangelength: "Please enter a last name between {0} and {1} characters long."					
				},
	        	username: "Please enter a valid username",
	        	email_address: "Please enter a valid email address",
	            password: {
	                required: "Please provide a password",
	                minlength: "Your password must be at least {0} characters long"
	            },
	            password2: {
	            	equalTo: "Please enter the same passwords"
	            }
	        },	        
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block',
	        errorPlacement: function(error, element) {
	            if(element.parent('.input-group').length) {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        }
	    });
	  
	  </script>
	<!-- jQuery registration error request -->
	<script>
		// password change validation  
	    $('#registration-error').validate({
	        rules: {
	            contact_name: {
	            	rangelength:[6,100],   
	                required: true
	            },
	            message_area: {
	            	rangelength:[10,250],
		            required: true
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
	        	contact_name: {
					required: "Enter your first and last name",
					rangelength: "Please enter a contact name between {0} and {1} characters long."
					
				},
				message_area: {
					required: "Please give us something to work with",
					minlength: "Message must be at least {0} characters long",
					maxlength: "Message cannot be more than {0} characters long"					
				}
	        },        
	        highlight: function(element) {
	            $(element).closest('.form-group').addClass('has-error');
	        },
	        unhighlight: function(element) {
	            $(element).closest('.form-group').removeClass('has-error');
	        },
	        errorElement: 'span',
	        errorClass: 'help-block',
	        errorPlacement: function(error, element) {
	            if(element.parent('.input-group').length) {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        }
	    });
	  
	  </script>
</body>

</html>