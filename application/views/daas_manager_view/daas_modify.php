<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'add-daas');
$hiddenid = array('class'=>'form-control', 'name' => 'db_id','id' => 'db_id', 'type'=>'hidden');
$hidden_db_type = array('class'=>'form-control', 'name' => 'rdbms','id' => 'rdbms', 'type'=>'hidden');
$dbhostname = array('class'=>'form-control', 'name' => 'hostname', 'id'=>'hostname', 'required'=>'required');
$dbinstance = array('class'=>'form-control', 'name' => 'instance', 'id'=>'instance');
$dbusername = array('class'=>'form-control', 'name' => 'username', 'id'=>'username','required'=>'required');
$dbpassword = array('class'=>'form-control', 'type' => 'password', 'name' => 'dbpassword', 'id'=>'dbpassword', 'data-message'=>'Show/hide password', 'required'=>'required');
$dbname = array('class'=>'form-control', 'name' => 'dbname', 'id'=>'dbname', 'required'=>'required');
$dbschema = array('class'=>'form-control', 'name' => 'schema', 'id'=>'schema');
$dbtable = array('class'=>'form-control', 'name' => 'dbtable', 'id'=>'dbtable', 'required'=>'required');
$dbtable_alias = array('class'=>'form-control', 'name' => 'dbtable_alias', 'id'=>'dbtable_alias', 'required'=>'required');
$dbport = array('class'=>'form-control', 'name' => 'dbport', 'id'=>'dbport');
$dbtbl_clmn = array('class'=>'form-control', 'name' => 'dbtable_clmn', 'id'=>'dbtable_clmn');
$dbsid = array('class'=>'form-control', 'name' => 'dbsid', 'id'=>'dbsid');
$dbsrvname = array('class'=>'form-control', 'name' => 'dbsname', 'id'=>'dbsname');
$db_descrpt = array('class'=>'form-control', 'name' => 'dbdescription', 'id'=>'dbdescription', 'rows' => '5', 'required'=>'required');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Add Connection', 'type'=>'submit');


//$pass = array('class'=>'form-control', 'name' => 'password', 'id' => 'password','required'=>'required','aria-required'=> 'true');
//$pass2 = array('class'=>'form-control', 'name' => 'password2','id' => 'password2','required'=>'required','aria-required'=> 'true');
$attr_FormSubmit = array('class'=>'btn-lg btn-success', 'value' =>'Update', 'type'=>'submit');
?>
	<div id="wrapper">
        <!-- /.navbar-static-top -->
		<?php // load dashboard admin menu ?>
		<?php $this->load->view("dashboard_menu"); ?>
        <!-- /.navbar-static-side -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?= $subtitle; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                           <h2><i class="fa fa-cloud fa-fw"></i> Dataset: &nbsp;<?= ucfirst($daas_obj->dbname); ?></h2>
                        </div>
                        <!-- /.panel-heading -->
	                        <div class="panel-body">
                                <?php
                                	if (isset($_GET["success"]) == TRUE)
									{
                                		echo "<div class=\"alert alert-success alert-dismissable\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
											Update success. " .anchor("".base_url("daas_control/daas/daas_view/{$this->form_data->daas_id}#connect-test"), "Get sample data..."). "
										</div>";
                                		
                                		echo "<div class=\"alert alert-success alert-dismissable\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
											DB Connection: {$daas_obj->hostname} OK
										</div>";
                                	} 	  
                                	echo $this->session->flashdata('data');                               	                                	
                                ?>					                        
                            	<?= form_open("{$action}", $attr_FormOpen); ?>
                            	<?= form_input($hiddenid, set_value("db_id", $this->form_data->daas_id)); ?>
                            	<?= form_input($hidden_db_type, set_value("rdbms", $daas_obj->db_id)); ?>
                            
                            <fieldset>     	
                            	<h3> DB Type: </h3><p id ="db_type"  onLoad="daas_toggle()" ><?= $dbtype_name ?></p>
				             	<div id = "jq_hostname" >
									<label for ="hostname"><em>Required </em>FQDN/IP</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbhostname, set_value("hostname", $daas_obj->hostname)); ?>                                    
	                                </div>
                                </div>
                                <div id = "jq_instance" >
                                	<label for ="instance">DB Instance (MSSQL)</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbinstance, set_value("instance", $daas_obj->instance)); ?>                                    
	                                </div>
                                </div>
                                <div id = "jq_username" >
                                	<label for ="username"><em>Required </em>DB Username</label>				                                
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbusername, set_value("username", $daas_obj->username)); ?>                                    
	                                </div>
                                </div>
                                <div id = "jq_dbpassword" >
                                	<label for ="dbpassword"><em>Required </em>DB Password</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbpassword, set_value("dbpassword", $daas_obj->daas_passwd)); ?>                                    
	                                </div>
	                          	</div>      
                                <div id = "jq_schema" >
                                	<label for ="schema">DB Schema</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbschema, set_value("dbschema", $daas_obj->schema)); ?>                                    
	                                </div>
	                           </div>     
                                <div id = "jq_dbname" >
                                	<label for ="dbname"><em>Required </em>DB Name</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbname, set_value("dbname", $daas_obj->dbname)); ?>                                    
	                                </div>
	                           	</div>     
                                <div id = "jq_dbtable">
                                	<label for ="dbtable"><em>Required </em>DB Table</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbtable, set_value("dbtable", $daas_obj->dbtable)); ?>                                    
	                                </div>
	                          	</div>
                                <div id = "jq_dbtable_alias" >
                                	<label for ="dbtable_alias"><em>Required </em>DB Table Alias</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	
	                                	<?php 
	                                		if(!empty($daas_obj->dbtable_alias)){
	                                		echo form_input($dbtable_alias, set_value("dbtable_alias", $daas_obj->dbtable_alias)); 
	                                		}else{
											echo form_input($dbtable_alias, set_value("dbtable_alias", ''));
											}
	                                	?>                                    
	                                </div>
	                          	</div>	                          	      
                                <div id = "jq_dbtable_clmn" >
                                	<label for ="dbtable_clmn">DB Columns</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbtbl_clmn, set_value("dbtable_clmn", $daas_obj->dbtable_clmn)); ?>                                    
	                                </div>
	                           	</div>     
                                <div id = "jq_dbport" >
                                	<label for ="dbport">DB Port</label>				                                
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?= form_input($dbport, set_value("dbport", $daas_obj->dbport)); ?>                                    
	                                </div>
	                         	</div>       
                               
                                <div id = "jq_dbsid" >
                                	<label for ="dbsid">SID or Service Name</label>
	                                <div class="form-group input-group">
	                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
	                                	<?php 
	                                	if(!$daas_obj->dbsid){
	                                		print_r(form_input($dbsid, set_value("dbsid", $daas_obj->dbsname)));                                    
	                                	}else{
	                                		print_r(form_input($dbsid, set_value("dbsid", $daas_obj->dbsid)));
	                                	}                                    
	                                	?>
	                                </div>
	                          	</div>      

		                          	<div>
		                          		<label for="dbdescription">Description</label>
                                        <div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-comment"></i></span>
                                            <?= form_textarea($db_descrpt, set_value("dbdescription", $daas_obj->dbdescription)); ?>
                                        </div>
                                  	</div>      				                                		
                                </fieldset>      
                                  				                                				                                				                                
		                          	<!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= validation_errors(); ?>
                                <?= br(); ?>
                                <?= $error; ?>
	                        </div>
	                       
                        <!-- /.panel-body -->
                    </div>
               	<?= $link_back; ?>
                    
               	</div>               
            </div>				                            
            <!-- /.row -->
            </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->