 <div class="footer">
<p><?= $version_official_name ?> &copy; 2015</p>
</div>
    <!-- Core Scripts - Include with every page -->
    <script src="<?= base_url(); ?>assets/js/jquery-1.10.2.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    
    <!-- Form validator -->
    <script src="<?= base_url(); ?>assets/js/jquery.validate.js"></script>
    <script src="<?= base_url(); ?>assets/js/additional-methods.js"></script>
 
    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/sb-admin.js'); ?>"></script> 
        
    <!-- Page-Level Plugin Scripts - Tables -->
    <?php /*
    <script src="<?= base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    
    <!-- Page-Level Plugin Script for edit-in-place  -->
    <script src="<?= base_url(); ?>assets/js/plugins/editable/jquery.dataTables.editable.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/editable/bootstrap-confirmation.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/editable/jquery.validate.js"></script> 
    */ ?> 
    <!-- Confirmation/ToolTip Box  -->
    <script src="<?= base_url(); ?>assets/js/bootstrap-confirmation.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap-transition.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <?php /*
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
	<script type="text/javascript">
	    $(document).ready(function () {
	        var oTable = $('#dataTables-admin_').dataTable({
	            "bProcessing": true,
	            "bServerSide": true,
	            "sServerMethod": "GET",
	            "sAjaxSource": '<?= base_url(); ?>dashboard/admin_datatable',
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
	            password: {
	                required: "Please provide a new password",
					rangelength: "Please enter a password between {0} and {1} characters long.",
	            },
	            password2: {
	            	equalTo: "Passwords do not match."
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
		// New Pending and User form validation  
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
	                minlength: 2,   
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
	    
		// For udpating Pending and User form validation  
		
	    $('#update-user').validate({
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
	            	rangelength:[2,50],
	                  
	                required: true
	            },
	            roles:{
	                required: true
		        },    
	            email_address: {
	                required: true,
	                email: true
	            },
	            password: {
	            	rangelength:[6,16],
	                required: true
	            },
	            password2: {
		            required: true,
	            	equalTo: '#password'
	            }	            
	        },	        
	        // Specify the validation error messages
	        messages: {
				first_name: {
					required: "Please enter first name",
					rangelength: "Please enter a first name between {0} and {1} characters long."
					
				},
				last_name: {
					required: "Please enter last name",
					rangelength: "Please enter a last name between {0} and {1} characters long."
					
				},
	            password: {
	                required: "Please provide a new password",
	                minlength: "Your password must be at least {0} characters long"
	            },
	            password2: {
	            	required: "Password must be confirmed",		            
	            	equalTo: "Passwords must match"	            	
	            },
	            roles: {
	                required: "A user role must be selected."
			   	},
				admin_role: "Select an administrative role",
	        	username: "Please enter a valid username",
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
	
		// For udpating Role and Permission form validation  
		
	    $('#role-form').validate({
	        rules: {
	            name: {
	            	rangelength:[3,50],
	                required: true
	            },
	            slug: {
	            	rangelength:[3,50],
	                required: true
	            },
	            description: {
	            	rangelength:[3,500],	            
					required: true
	            },
	            'perms[]':{
					required: true
		      	}
	        },	        
	        // Specify the validation error messages
	        messages: {
	        	name: {
					required: "Please enter a system name",
					rangelength: "Please enter a system name between {0} and {1} characters long."				
				},
				slug: {
					required: "Please enter a system ID",
					rangelength: "Please enter a system ID between {0} and {1} characters long."
				},
				description: {
					required: "Please enter a description",
					rangelength: "Please enter a description between {0} and {1} characters long."
				},
				'perms[]': {
					required: "A role must be assigned at least 1 permission."
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
    </script>
	<script>
	$(function() {
		$('#nav-tabs li:eq(1) a').tab('show')
	  // Javascript to enable link to tab
	  var url = document.location.toString();
	  if (url.match('#')) {
	    $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
	  }
	
	  // Change hash for page-reload
	  $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
	    window.location.hash = e.target.hash;
	  });
	});
	</script>
	<script>
	function like(placeholder) {
        $.ajax({
            url: $(placeholder).attr('rel'),
            type: "GET",
            success:function(){
                alert("done");
            },
            error:function (){
                alert("testing error");
            }
        });
        return false;
	}
	</script>
	<script type="text/javascript">
	 // Perm control
	function change_perms(){		
	 	//alert($("#roles option:selected").val());	
		var rid = $("#roles option:selected").val();
			
	 	$.ajax({
	 		type: 'POST',
	 		url: '<?= base_url("access_control/admin/roles_select_ui/?rid="); ?>'+ rid, //We are going to make the request to the method "list_dropdown" in the match controller
	 		data:'role='+ rid, //POST parameter to be sent with the role id
	 		success: function(resp) { //When the request is successfully completed, this function will be executed
	 		//Activate and fill in the matches list
	 		$('#perms').attr({'disabled':true,'multiple':'multiple','class':'form-control'}).html(resp); //With the ".html()" method we include the html code returned by AJAX into the matches list
			// Resize the input select after filtering.	
	 		 $("#perms").attr('size',$('#perms option').size());
	 		}
	 });		 
 	}
</script> 

</body>

</html>