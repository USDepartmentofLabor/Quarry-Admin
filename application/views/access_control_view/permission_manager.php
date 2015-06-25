<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'drole-form');
$name = array('class'=>'form-control', 'name' => 'name', 'id'=>'name');
$slug = array('class'=>'form-control', 'name' => 'slug', 'id'=>'slug');
$description = array('class'=>'form-control', 'name' => 'description', 'id'=>'description','rows'=>'5');
$attr_FormSubmit = array('class'=>'btn-lg btn-success', 'value' =>'Create Permission', 'type'=>'submit');

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
                <div class="col-lg-12" id="perm_pill">
	                <?php
                        if (isset($_GET["del_success_message"])) {
                            echo "<div class=\"alert alert-success alert-dismissable\">
									<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									Permission successfully deleted. 
								</div>";
                        } 
                        if (isset($_GET["success_message"])) {
                            echo "<div class=\"alert alert-success alert-dismissable\">
                            		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
									Permission successfully added.
								</div>";
                        }
                     ?>                           
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="<?= ($tab == 'permissions') ? 'active' : ''; ?>" ><a href="#permissions" data-toggle="tab">Permissions</a>
                                </li>
                                <li class="<?= ($tab == 'add_permission') ? 'active' : ''; ?>"><a href="#add_permission" data-toggle="tab">Add Permission</a>
                                </li>
                            </ul>                    	
                           <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane <?= ($tab == 'permissions') ? 'active' : ''; ?>" id="permissions">
					            	<div><?= $pagination; ?></div>
					            	<?= br(); ?>
					                <div class="col-lg-12">					                
					                    <div class="panel panel-default">
                        					<div class="panel-heading">
                            					<i class="fa fa-group fa-fw"></i> <?= $panel_title; ?>
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
                                <div class="tab-pane <?= ($tab == 'add_permission') ? 'active' : ''; ?>" id="add_permission">	
                                <?= br(); ?>
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
										<div class="panel panel-primary">
											<div class="panel-heading">
					                           <i class="fa fa-plus fa-fw"></i>
					                        </div>
					                        	<?= br(); ?>
					                        <div class="panel-body">	
                							<? echo $this->session->flashdata('data'); ?>
					                        	<p>All fields are required.</p>
					                    		<fieldset>
		                            				<legend><!-- Empty Legend needed to pass format standards  --></legend>
					                            	<div><label for="name"><em>Required </em> System Name (50 Maximum Characters)</label></div>		                        														                            
					                            	<?= form_open("{$add_perm}", $attr_FormOpen); ?>
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-group"></i></span>
					                                	<?= form_input($name,set_value('name')); ?>                                    
					                                </div>
					                            	<div><label for="slug"><em>Required </em>System ID (50 Maximum Characters)</label></div>		                        														                               
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-group"></i></span>
					                                	<?= form_input($slug,set_value('slug')); ?>                                    
					                                </div>
					                            	<div><label for="description"><em>Required </em>Description (500 Maximum Characters)</label></div>		                        														                               					                                
					                                <div class="form-group input-group">
					                                	<span class="input-group-addon"><i class="fa fa-group"></i></span>
					                                	<?= form_textarea($description,set_value('description')); ?>                                    
					                                </div>                 
						                                                                           
					                                <!-- Change this to a button or input when using this as a form -->
					                                <?= form_submit($attr_FormSubmit); ?>
					                                <?= form_close(); ?>
					                                <?= br(); ?>
					                            </fieldset>
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
                </div>
                
                <!-- end tab view -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->