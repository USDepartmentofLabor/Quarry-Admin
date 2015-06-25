
 <div class="footer">
<p><?= $version_official_name ?> &copy; 2015</p>
</div>
    <!-- Core Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/jquery-1.10.2.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
 
    <!-- Form validator -->
    <script src="<?= base_url('assets/js/jquery.validate.js'); ?>"></script>
    <script src="<?= base_url('assets/js/additional-methods.js'); ?>"></script>
       
    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/sb-admin.js'); ?>"></script> 
	<!-- jQuery password change validation -->
	<script>
		// password change validation  
	    $('#register-key').validate({
	        rules: {
	            name: {
	            	rangelength:[5,50],
	                required: true
	            },
	            email_address: {
	                required: true,
	                email: true
	            },
	            description: {
	                maxlength: 1000,
	                required: true
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
	        	name: {
					required: "Please enter an API Key Name",
					rangelength: "Please enter an API key name between {0} and {1} characters long."
					
				},
				description: {
					required: "Please enter a brief description about this API key",
					maxlength: "Your description cannot be more than {1000} characters long"
				},
				email_address: "Please enter a valid E-mail Address"
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
	<script>
		// password change validation  
	    $('#search-form').validate({
	        rules: {
	            searchopt: {
	            	rangelength:[2,50],
	                required: true
	            },
	            searchcat: {
	                required: true
	            },
	            description: {
	            	rangelength:[2,250],		            
	                required: true
	            }
	        },
	        
	        // Specify the validation error messages
	        messages: {
				searchcat: "Please select a search category",
				searchcond: "Please select a search condition",		        
	        	searchopt: {
					required: "Please enter a Token, API Key Name, IP or Email",
					rangelength: "Please enter a search criteria between {0} and {1} characters long."				
				},
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