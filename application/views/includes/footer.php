    <!-- Core Scripts - Include with every page -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/morris/morris.js"></script>
    
    <!-- Form validator -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/additional-methods.js"></script>
    
    <!-- Page-Level Plugin Scripts - Tables -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <?php /*
    <!-- Page-Level Plugin Script for edit-in-place  -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/editable/jquery.dataTables.editable.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/editable/jquery.jeditable.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/editable/jquery.validate.js"></script> 
    */ ?> 

    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?php echo base_url(); ?>assets/js/sb-admin.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
	    $(document).ready(function() {
	        $('#dataTables-acctmanager').dataTable();
	    });
	
		// Javascript to enable link to tab
	    var url = document.location.toString();
	    if (url.match('#')) {
	        $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
	    } 
	
	    // Change hash for page-reload
	    $('.nav-tabs a').on('shown', function (e) {
	        window.location.hash = e.target.hash;
	    })
    </script>
    <?php /*
	<script type="text/javascript">
	    $(document).ready(function () {
	        var oTable = $('#dataTables-admin_').dataTable({
	            "bProcessing": true,
	            "bServerSide": true,
	            "sServerMethod": "GET",
	            "sAjaxSource": '<?php echo base_url(); ?>dashboard/admin_datatable',
	            "bJQueryUI": true,
	            "sPaginationType": "full_numbers",
	            "iDisplayStart ": 20,
	            //"oLanguage": {
	                //"sProcessing": "<img src='<?php //echo base_url(); ?>assets/images/ajax-loader_dark.gif'>"
	           // },
	            "fnInitComplete": function () {
	                //oTable.fnAdjustColumnSizing();
	            },
	            'fnServerData': function (sSource, aoData, fnCallback) {
	                $.ajax
	                ({
	                    'dataType': 'json',
	                    'type': 'POST',
	                    'url': sSource,
	                    'data': aoData,
	                    'success': fnCallback
	                });
	            }
	        });
	    });
	</script>
	*/ ?>
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

</body>

</html>