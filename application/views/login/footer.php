    <!-- Core Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/jquery-1.10.2.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
    
    <!-- Form validator -->
    <script src="<?= base_url('assets/js/jquery.validate.js'); ?>"></script>
    <script src="<?= base_url('assets/js/additional-methods.js'); ?>"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/sb-admin.js'); ?>"></script>

	<!-- jQuery Form Validation code -->
	<script>
		// login form validation  
	    $('#login-form').validate({
	        rules: {
	            username: {
	                required: true
	         	},
	            password: {
	                minlength: 6,
	                maxlength: 16,
	                required: true
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
	        	username: "Please enter a valid username",
	            password: {
	                required: "Please provide a password",
	                minlength: "Your password must be at least {0} characters long"
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
	<!-- jQuery password change validation -->
	<script>
		// password change validation  
	    $('#password-change').validate({
	        rules: {
	            current_password: {
	                minlength: 6,
	                maxlength: 16,
	                required: true
	            },
	            password: {
	                minlength: 6,
	                maxlength: 16,
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
	                minlength: "Your password must be at least {0} characters long"
	            },
	            password: {
	                required: "Please provide a new password",
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
</body>

</html>