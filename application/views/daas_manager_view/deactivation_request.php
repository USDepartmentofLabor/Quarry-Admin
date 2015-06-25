<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'deactivate-form');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address', 'placeholder'=>'Email Address','required'=>'required');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Deactivate Account', 'type'=>'submit');

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
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-group fa-fw"></i> <?= $panel_title; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#add_admin" data-toggle="tab">Deactivate User</a>
                                </li>
                            </ul>
                          		<?php 
                          			if (isset($_GET["del_success_message"]) == TRUE) {
                                		echo "<div class=\"alert alert-success alert-dismissable\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
											Account successfully deactivated.
										</div>";
                                	} 
					               ?> 
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="add_admin">
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
					                	<?= br(); ?>
					                    <div class="panel panel-primary">
											<div class="panel-heading">
					                           <i class="fa fa-user fa-fw"></i>
					                        </div>
					                        <div class="panel-body">
					                            <fieldset>
					                            	<?= form_open("{$deactivate}", $attr_FormOpen); ?>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					                                	<?= form_input($email); ?>                                    
					                                </div>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					                                	<select name="status" class="form-control" required="required">
					                                		<option value="">Select Status</option>
					                                		<option value="1">Enable</option>
					                                		<option value="0">Disable</option>
					                                	</select>                                    
					                                </div>                                                           
					                                <!-- Change this to a button or input when using this as a form -->
					                                <?= form_submit($attr_FormSubmit); ?>
					                                <?= form_close(); ?>
					                                <?= br(); ?>
					                                <?= $error; ?>
					                                <?php
					                                	if (isset($_GET["success_message"]) == TRUE) {
					                                		echo "<div class=\"alert alert-success alert-dismissable\">
															<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
																admin successfully added. " .anchor("".base_url()."access_control/admin/account_update/{$_GET["user"]}", "Assign role(s)"). "
															</div>";
					                                	} 
					                                	
					                                	if (isset($_GET["admin_error_message"])) {
															echo "<div class=\"alert alert-danger alert-dismissable\">
															<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
																Error, user already exist...
															</div>";
					                                	}
					                                	
					                                	if (isset($_GET["reg_error_message"])) {
					                                		echo "<div class=\"alert alert-danger alert-dismissable\">
															<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
																Error, user already requested access. " .anchor("".base_url()."access_control/admin/pending_request", "Pending Request"). "
															</div>";
					                                	}
					                                ?>
					                            </fieldset>
					                        </div>
					                    </div>
					                </div>
					                <!-- /.col-lg-4 -->									
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- end tab view -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->