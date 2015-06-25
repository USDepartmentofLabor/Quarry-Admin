<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'add-daas');
$dbhostname = array('class'=>'form-control', 'name' => 'hostname', 'id'=>'hostname','required'=>'required','aria-required'=> 'true');
$dbinstance = array('class'=>'form-control', 'name' => 'instance', 'id'=>'instance');
$dbusername = array('class'=>'form-control', 'name' => 'username', 'id'=>'username','required'=>'required','aria-required'=> 'true');
$dbpassword = array('class'=>'form-control', 'type' => 'password', 'name' => 'dbpassword', 'id'=>'dbpassword', 'data-message'=>'Show/hide password','required'=>'required','aria-required'=> 'true');
$dbname = array('class'=>'form-control', 'name' => 'dbname', 'id'=>'dbname','required'=>'required','aria-required'=> 'true');
$dbschema = array('class'=>'form-control', 'name' => 'schema', 'id'=>'schema','required'=>'required','aria-required'=> 'true');
$dbtable = array('class'=>'form-control', 'name' => 'dbtable', 'id'=>'dbtable','required'=>'required','aria-required'=> 'true');
$dbtable_alias = array('class'=>'form-control', 'name' => 'dbtable_alias', 'id'=>'dbtable_alias','required'=>'required','aria-required'=> 'true');
$dbport = array('class'=>'form-control', 'name' => 'dbport', 'id'=>'dbport','aria-required'=> 'true');
$dbtbl_clmn = array('class'=>'form-control', 'name' => 'dbtable_clmn', 'id'=>'dbtable_clmn');
$dbsid = array('class'=>'form-control', 'name' => 'dbsid', 'id'=>'dbsid','required'=>'required','aria-required'=> 'true');
$dbsrvname = array('class'=>'form-control', 'name' => 'dbsname', 'id'=>'dbsname','required'=>'required','aria-required'=> 'true');
$db_descrpt = array('class'=>'form-control', 'name' => 'dbdescription', 'id'=>'dbdescription', 'rows' => '5','required'=>'required','aria-required'=> 'true');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'id'=>'add_conn', 'name'=>'add_conn', 'value' =>'Add Connection', 'type'=>'submit');

# tab view
$tab = (isset($_GET['tab'])) ? $_GET['tab'] : null;

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
            <!-- start tab view for access control list -->
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
			                <?php if (isset($_GET["del_data_success"])) {
	                            echo "<div class=\"alert alert-success alert-dismissable\">
	                            		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
										Dataset successfully deleted.
									</div>";
		                        }
		                     ?>                             
                            <ul class="nav nav-tabs">
                                <li class="<?= ($tab == 'daas_list') ? 'active' : ''; ?>"><a href="#daas_list" data-toggle="tab">Connection Strings</a>
                                </li>
                                <li class="<?= ($tab == 'add_daas') ? 'active' : ''; ?>"><a href="#add_daas" data-toggle="tab">Add Connection String</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->  
                            <div class="tab-content">
                                <div class="tab-pane <?= ($tab == 'daas_list') ? 'active' : ''; ?>" id="daas_list">
                                	<?= br(); ?>
					            	<div><?= $pagination; ?></div>
					            	<?= br(); ?>
					                <div class="col-lg-12">
					                    <div class="panel panel-default">
					                        <div class="panel-heading">
					                            <i class="fa fa-cogs fa-fw"></i> <?= $panel_title; ?>
					                        </div>
					                        <!-- /.panel-heading -->
					                        <div class="panel-body">
					                            <div class="table-responsive">
												<?= $table; ?>
					                            </div>
					                            <!-- /.table-responsive -->
					                        </div>					                        
					                        <!-- /.panel-body -->
					                    </div>					                    
					                    <!-- /.panel -->
					                </div>
					            	<div><?= $pagination; ?></div>
					                
					                <!-- /.col-lg-12 -->
                                </div>
                                <div class="tab-pane <?= ($tab == 'add_daas') ? 'active' : ''; ?>" id="add_daas">
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
					                	<?= br(); ?>
					                	
					                    <div class="panel panel-primary">
											<div class="panel-heading">
					                           <i class="fa fa-cogs fa-fw"></i>
					                        </div>
					                        <div class="panel-body">
													<? echo $this->session->flashdata('validation_errors'); ?>
																     									
												<legend>Enter Data Connection Strings</legend>
													
					                            	<?= form_open("{$add_daas_process}", $attr_FormOpen); ?>
					                            	<fieldset>
					                            	
					                            	<div><label for="db_type">Select Database Type</label></div>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
					                                	<select id ="db_type" class="form-control" name="db_type" onChange="daas_toggle(this)">
					                                			<option value="">Select DB Type</option>
															<?php foreach($dbtype as $row) : ?>																  
															   <option id="<?= $row->db_slug; ?>" value="<?= $row->db_id; ?>"><?= $row->db_type ?></option> 
															<?php endforeach; ?>
														</select>                                 
					                                </div>
					                                <div id = "jq_hostname" >
					                                	<label for="hostname"><em>Required </em>FQDN/IP Address</label>					                                
					                                	<div class="form-group input-group">
					                                		<span class="input-group-addon"><i class="fa fa-cog"></i></span>
					                                		<?= form_input($dbhostname, set_value("hostname")); ?>                                    
					                                	</div>
						                          	</div>
					                                <div id = "jq_instance" ><label for="instance">Instance (MSSQL)</label>
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbinstance, set_value("instance")); ?>                                    
						                                </div>
						                             </div>   
					                                <div id = "jq_username" ><label for="username">Username</label>					                                
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbusername, set_value("username")); ?>                                    
						                                </div>
						                            </div>    
					                                <div id = "jq_dbpassword" ><label for="dbpassword">API Password</label>
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbpassword, set_value("dbpassword")); ?>                                    
						                                </div>
						                          	</div>      
					                                <div id = "jq_schema" ><label for="schema">Schema e.g.: public, dbo</label>
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbschema, set_value("schema")); ?>                                    
						                                </div>
						                          	</div>      
					                                <div id = "jq_dbname" ><label for="dbname">DB Name</label>
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbname, set_value("dbname")); ?>                                    
						                                </div>
						                           	</div>     
					                                <div id = "jq_dbtable"><label for="dbtable">DB Table</label>
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbtable, set_value("dbtable")); ?>                                    
						                                </div>
						                            </div>   
					                                <div id = "jq_dbtable_alias" ><label for="dbtable_alias">DB Table Alias</label>
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbtable_alias, set_value("dbtable_alias")); ?>                                    
						                                </div>
						                            </div>						                             
					                                <div id = "jq_dbtable_clmn"><label for="dbtable_clmn">DB Table Column</label>	
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbtbl_clmn, set_value("dbtable_clmn")); ?>                                    
						                                </div>
					                                </div>
					                                <div id = "jq_dbport" ><label for="dbport">DB Port</label>					                             					                                
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbport, set_value("dbport")); ?>                                    
						                                </div>
						                          	</div>      
					                                <div id = "jq_dbsid" ><label for="dbsid">DB SID or Service Name</label>					                             
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-cog"></i></span>
						                                	<?= form_input($dbsid, set_value("dbsid")); ?>                                    
						                                </div>
						                          	</div>      				    
					                                <div id = "jq_dbdescription" ><label for="dbdescription">Description (500 Maximum Characters)</label>
				                                        <div class="form-group input-group">
															<span class="input-group-addon"><i class="fa fa-comment"></i></span>
				                                            <?= form_textarea($db_descrpt, set_value("dbdescription")); ?>
				                                        </div>		
			                                       </div> 			                                		                                					                                				                                				                                
					                                <!-- Change this to a button or input when using this as a form -->
					                                <?= form_submit($attr_FormSubmit); ?>
													</fieldset>					                                
					                                
					                                <?= form_close(); ?>
					                                <?= br(); ?>
					                                <?php
					                                	if (isset($_GET["service_registered"]) == TRUE) {
					                                		echo "<div class=\"alert alert-success alert-dismissable\">
															<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
																Service successfully added. " .anchor("".base_url("daas_control/daas/daas_manager#daas_list"), "Get sample data..."). "
															</div>";
					                                		
					                                		echo "<div class=\"alert alert-success alert-dismissable\">
					                                		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
					                                		DB Connection: {$_GET['dbhost']} OK
					                                		</div>";
					                                	} 
					                                	
					                                	if (isset($_GET["service_error"]) == TRUE) {
															echo "<div class=\"alert alert-danger alert-dismissable\">
															<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
																Error, connection already exist...
															</div>";
					                                	}
					                                ?>
					                        </div>
					                    </div>
					                    <?= $link_back; ?>
					                </div>
					                <!-- /.col-lg-4 -->							
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <p>Page rendered in {elapsed_time} seconds</p>
                <!-- end tab view -->
            </div>
            <!-- /.row -->
        <!-- /#page-wrapper -->
      	</div>
    <!-- /#wrapper -->