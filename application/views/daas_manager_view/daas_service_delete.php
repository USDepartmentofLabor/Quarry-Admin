<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form','id'=>'delete-form');

$attr_FormSubmit = array('class' =>'btn-lg btn-danger', 'value' =>'Delete', 'type'=>'submit');

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
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                           <h2><i class="fa fa-cog fa-fw"></i> <?= $service->db_type; ?></h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li><h3><?= strtoupper($service->daas_dbname); ?> Details</h3></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="daas-service">
                                 <?= form_open("{$del_service}", $attr_FormOpen); ?>                            

	                                <h3>DB Type</h3>
	                                <p><?= $service->db_type; ?></p>
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

	                                <h3>Date Modified</h3>
	                                <p><?= $service->date_modified; ?></p>
                                                  <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close();?> 	                                
                                </div>
                             
                            </div>
                        </div>
                        <!-- /.panel-body -->

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