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
    
    <!-- Password -->
    <script src="<?= base_url('assets/js/password.js'); ?>"></script>
 
    <!-- Confirmation/ToolTip Box  -->
    <script src="<?= base_url('assets/js/bootstrap-confirmation.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap-transition.js'); ?>"></script>
    
    
    <!-- SB Admin Scripts - Include with every page -->
    <script src="<?= base_url('assets/js/sb-admin.js'); ?>"></script>

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
	            "sAjaxSource": '<?= base_url('dashboard/admin_datatable'); ?>',
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
	    $('#add-daas').validate({
	        rules: {
	        	hostname: {
	        		rangelength:[2,50],
	                required: true
	            },
	            instance: {
	                rangelength:[2,50],	
	                alphanumeric:true	                
	            },
	            username: {
	            	rangelength:[4,50],
					required: true
	            },
	            dbpassword: {
		            minlength: 6,
	                required: true,
	            },
	            schema: {
	                alphanumeric:true,	                
	                required: true
	            },
	            dbname: {
	                alphanumeric:true,	                	            
	                required: true
	            },	            
	            dbtable: {
	                alphanumeric:true,		            
	                required: true
	            },
	            dbtable_alias: {
		            rangelength: [5,50],
	                alphanumeric:true,		            
	                required: true
	            },
	            dbport: {
	            	digits: true
	            },
	            dbtable_clmn: {
	                alphanumeric:true,    
		        },
	            dbsid: {
	                required: true   
	            },
	            dbdescription: {
	                required: true,
	                rangelength:[6,500]
	            }	            
	        },
	        
	        // Specify the validation error messages
	        messages: {
	        	hostname: {
					required: "Please enter a valid FQDN/IP address.",
					rangelength: "Please enter a FQDN/IP address between {0} and {1} characters long."
				},
				username: {
					required: "API Username required",
					rangelength: "Please enter a API Username between {0} and {1} characters long."				
					},
				dbpassword: { 
					required: "API Password required",
		        	minlength: "API Password must be at least {0} characters long",
	        	},	 
				schema: { 
					required: "Schema name required",
	        	},
				instance: { 
					required: "Instance value required",
	        	},	        		
				dbname: { 
					required: "Database name required",
	        	},
	            dbtable: {
					required: "Table name required",
	           	},
	        	dbtable_alias: {
					required: "Table alias is required",
	           	},
	            dbport: {
					required: "Port number required",
	           	},
	            dbtable_clmn: {
					required: "DB table column required",
	            },
	            dbsid: {
					required: "DB SID or Service Name required",
	            },
	            dbdescription: {
					required: "Please enter a description",
					rangelength: "Please enter a description between {0} and {1} characters long.",
				},	        		
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
	    	}
		);
	  
	  </script>
    <!-- Page-Level Scripts - Notifications - Use for reference -->
	<script>
	$(function() {
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
		function testString(obj) {			
		    $('#string-result').empty()
		    var dropDown = document.getElementById("connectid");
		    var connectid = dropDown.options[dropDown.selectedIndex].value;
		    $.ajax({
			    type: "POST",
		   	    url: "<?= base_url("daas_control/daas/connection_test") ?>",
		        data: { 'connectid': connectid  },
		        success: function(data){
		        // Parse the returned json data
		        var data;
		        // Use jQuery's each to iterate over the opts value
		        //$.each(opts) {
		        // You will need to alter the below to get the right values from your json object.  Guessing that d.id / d.modelName are columns in your carModels data
		      	$('#string-result').append('<div><pre>' + data + '</pre></div>');
			  	 //});
		 	}
			});
		}

	</script>
	<script>
	$( window ).load(function() {
		daas_toggle();
	});	
		function daas_toggle() {
		    var dbtype = $('#db_type :selected').text();
		    
			if (!$('#db_type :selected').text()){
			    dbtype = $('#db_type').text();			
			}
			$('#add_conn').prop('disabled',false);				
			$('#jq_hostname').hide();
			$('#jq_instance').hide();
			$('#jq_username').hide();
			$('#jq_dbpassword').hide();
			$('#jq_schema').hide();
			$('#jq_dbname').hide();
			$('#jq_dbtable').hide();
			$('#jq_dbtable_alias').hide();
			$('#jq_dbtable_clmn').hide();
			$('#jq_dbport').hide();
			$('#jq_dbsid').hide();			
			$('#jq_dbsname').hide();			
			$('#jq_dbdescription').hide();
			
			
			if(dbtype == "MySQL"){
				$('#jq_hostname').show();
				$('#jq_instance').hide();
				$('#jq_username').show();
				$('#jq_dbpassword').show();
				$('#jq_schema').hide();
				$('#jq_dbname').show();
				$('#jq_dbtable').show();
				$('#jq_dbtable_alias').show();
				$('#jq_dbtable_clmn').show();
				$('#jq_dbport').show();
				$('#jq_dbsid').hide();			
				$('#jq_dbsname').hide();			
				$('#jq_dbdescription').show();					
			}else if(dbtype == "Microsoft SQL"){
				$('#jq_hostname').show();
				$('#jq_instance').show();
				$('#jq_username').show();
				$('#jq_dbpassword').show();
				$('#jq_schema').show();
				$('#jq_dbname').show();
				$('#jq_dbtable').show();
				$('#jq_dbtable_alias').show();
				$('#jq_dbtable_clmn').show();
				$('#jq_dbport').show();
				$('#jq_dbsid').hide();			
				$('#jq_dbsname').hide();			
				$('#jq_dbdescription').show();					
			}else if(dbtype == "Oracle DB"){
				$('#jq_hostname').show();
				$('#jq_instance').hide();
				$('#jq_username').show();
				$('#jq_dbpassword').show();
				$('#jq_schema').show();
				$('#jq_dbname').show();
				$('#jq_dbtable').show();
				$('#jq_dbtable_alias').show();
				$('#jq_dbtable_clmn').show();
				$('#jq_dbport').show();
				$('#jq_dbsid').show();			
				$('#jq_dbsname').show();			
				$('#jq_dbdescription').show();					
			}else if(dbtype == "PostgreSQL"){
				$('#jq_hostname').show();
				$('#jq_instance').hide();
				$('#jq_username').show();
				$('#jq_dbpassword').show();
				$('#jq_schema').show();
				$('#jq_dbname').show();
				$('#jq_dbtable').show();
				$('#jq_dbtable_alias').show();
				$('#jq_dbtable_clmn').show();
				$('#jq_dbport').show();
				$('#jq_dbsid').hide();			
				$('#jq_dbsname').hide();			
				$('#jq_dbdescription').show();					
			}else{
				$('#add_conn').prop('disabled',true);				
			}								
		}
	</script>	
	<script>
	$('#checkAll').click(function(e){
		var table= $(e.target).closest('table');
		$('td input:checkbox',table).attr('checked',e.target.checked);
	});
	</script>
	
	<script>
			// deactivation validation  
		    $('#deactivate-form').validate({
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
</body>

</html>