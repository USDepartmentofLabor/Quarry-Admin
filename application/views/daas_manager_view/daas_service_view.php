<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'disable-form');
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
                    <div class="panel panel-primary">
                        <div class="panel-heading ">
                        <h2>
                           <i class="fa fa-cog fa-fw"></i> 
                           	<?= $service->db_type; ?>
                        </h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li><a href="#connect-test" data-toggle="tab">JSON Test</a></li>
                                <li><a href="#daas-service" data-toggle="tab"><?= strtoupper($service->daas_dbname); ?> Details</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="connect-test">
					             	<div class="panel-body">
					                		<h3><i class="fa fa-cog fa-fw"></i> Connection Test - (Default JSON)</h3>
				                            	<p>For the sake of system performance, a limited record will be returned...<br></p>
				                            	
									            <div><label for ='connectid'>Select Datasource</label></div>
				                            	<div class="form-group input-group">
												<span class="input-group-addon"><i class="fa fa-cog"></i></span>
													<select name="connectid" id="connectid" class="form-control" onchange="testString(this)">
														<option value="">Select Datasource</option>													
														<?php foreach($string_array as $row) : ?>
														<option value="<?= $row->daas_id ?>" <?= set_select("db_type", "{$row->daas_id}", ( !empty($data) &&
																$data == "{$row->daas_id}" ? TRUE : FALSE )); ?>><?= $row->db_type; ?> 
																&rightarrow; <?= $row->daas_method; ?> &rightarrow;<?= $row->daas_dbname; ?> 
														</option>
														<?php endforeach; ?>
													</select>
												</div>
											<div class="form-group input-group" id="string-result"></div>
					             	</div>
                                </div>
                                <div class="tab-pane fade" id="daas-service">
	                            
	                                <h3>DB Type</h3>
	                                <p> <?= $service->db_type; ?></p>
	                            
	                                <h3>DB Host</h3>
	                                <p><?= $service->daas_host; ?></p>
	                            
	                                <h3>DB User</h3>
	                                <p><?= $service->daas_user; ?></p>
	                            
	                                <h3>DB Password</h3>
	                                <p><?= $service->daas_passwd; ?></p>
	                            
	                                <h3>DB Name</h3>
	                                <p><?= $service->daas_dbname; ?></p>
	                            
	                                <h3>DB Table</h3>
	                                <p><?= $service->daas_table; ?></p>
	                               	<h3>DB Table Alias</h3>
	                                <p><?= $service->daas_table_alias; ?></p>
	                            
	                                <h3>DB Port</h3>
	                                <p><?= !empty($service->daas_port)? $service->daas_port : 'NA'; ?></p>
	                                
	                                <h3>Column Name</h3>
	                                <p><?= !empty($service->daas_action_clmn)? $service->daas_action_clmn : 'NA'; ?></p>
	                            
	                            	<?php if ($service->db_type == "Oracle DB") { ?>
		                                <h3>DB SID (Oracle)</h3>
	                              		<p><?= !empty($service->daas_sid)? $service->daas_sid : 'NA'; ?></p>
	                              				                            
		                                <h3>DB Service Name (Oracle)</h3>
	                              		<p><?= !empty($service->daas_sname)? $service->daas_sname : 'NA'; ?></p>   
	                            	<?php } ?>
	                            
	                                <h3>Date Created</h3>
	                                <p><?= $service->date_created; ?></p>
	                                                        
	                                <h3>Description</h3>
	                                <p><?= $description->dbdescription; ?></p>
	                             	                            
	                                <h3>Date Modified</h3>
	                                <p><?= $service->date_modified; ?></p>
	                             		                            	                                                        
	                                <h3>REST URL</h3>
	                                <p><a href="<?= $service->daas_access_link.$service->daas_table_alias ?>" title="Get <?= $service->daas_access_link.$service->daas_table_alias ?> list" target='_blank'><?= $service->daas_access_link.$service->daas_table_alias ?></a></p>
	                                                             		                            	                            
	                                <h3>JSON Test</h3>
	                                <p><a href="<?= base_url('daas_control/daas/rest_client_request/'.$service->daas_id.'/?format=json'); ?>" title="Get <?= $service->daas_table ?> list" target='_blank'><?= $service->daas_dbname ?></a></p>
	                              
	                                <h3>XML Test</h3>
	                                <p><a href="<?= base_url('daas_control/daas/rest_client_request/'.$service->daas_id.'/?format=xml'); ?>" title="Get <?= $service->daas_table ?> list" target='_blank'><?= $service->daas_dbname ?></a></p>
	                                                     	                            		                   	                                                                                  
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <?= strtoupper($service->daas_dbname); ?>
                        </div>
                    </div>
                    <?= $link_back; ?>
                </div>
            </div>
            <?= br(); ?>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->