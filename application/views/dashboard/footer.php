 <div class="footer">
<p><?= $version_official_name ?> &copy; 2015</p>
</div>
<!-- Core Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/jquery-1.10.2.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="<?= base_url('assets/js/plugins/morris/raphael-2.1.0.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/plugins/morris/morris.js'); ?>"></script>
    <script src="<?= base_url('assets/js/highcharts.js'); ?>"></script>
    <script src="<?= base_url('assets/js/modules/exporting.js'); ?>"></script>
    
    <!-- Form validator -->
    <script src="<?= base_url('assets/js/jquery.validate.js'); ?>"></script>
    <script src="<?= base_url('assets/js/additional-methods.js'); ?>"></script>
    
    <!-- Confirmation/ToolTip Box  -->
    <script src="<?= base_url('assets/js/bootstrap-confirmation.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap-transition.js'); ?>"></script>
    
    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/sb-admin.js'); ?>"></script>

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
	<!-- jQuery password change validation -->
	<script>
		// password change validation  
	    $('#pending-request').validate({
	        rules: {
	            first_name: {
	            	rangelength:[2,50],
	                required: true
	            },
	            last_name: {
	            	rangelength:[2,50],	            
	                required: true
	            },
	            admin_role: {
					required: true
	            },
	            username: {
	                required: true,
	                email: true
	            },
	            email_address: {
	                required: true,
	                email: true
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
				admin_role: "Select an administrative role",
	        	username: "Please enter a valid email address",
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
    <!-- Page-Level Scripts - Notifications - Use for reference -->
    <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>

    <!-- Page-Level Demo Scripts - Dashboard - Use for reference -->
    <script src="<?= base_url('assets/js/demo/highcharts_live.js'); ?>"></script>
    <!-- Page-Level Demo Scripts - Dashboard - Use for reference -->
    <script src="<?= base_url('assets/js/demo/dashboard-demo.js'); ?>"></script>
</body>

</html>