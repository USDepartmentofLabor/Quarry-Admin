<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'update-user');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name','required'=>'required','aria-required'=> 'true');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name','required'=>'required','aria-required'=> 'true');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address','type'=>'email','required'=>'required','aria-required'=> 'true');
$user = array('class'=>'form-control', 'name' => 'username', 'id'=>'username','required'=>'required','aria-required'=> 'true');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password','required'=>'required','aria-required'=> 'true');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2','required'=>'required','aria-required'=> 'true');
$attr_FormSubmit = array('class'=>'btn-lg btn-success', 'value' =>'Create User', 'type'=>'submit');

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
                <div class="col-lg-12">
           		<?php
           			if (isset($_GET["del_success_message"]) == TRUE) {
           				echo "<div class=\"alert alert-success alert-dismissable\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							Account successfully deactivated.
						</div>";
           			}           		          	
                 ?>                 
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="<?= ($tab == 'users') ? 'active' : ''; ?>"><a href="#users" data-toggle="tab">Users</a>
                                </li>
                                <li class="<?= ($tab == 'add_admin') ? 'active' : ''; ?>"><a href="#add_admin" data-toggle="tab">Add User</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane <?= ($tab == 'users') ? 'active' : ''; ?>" id="users">
                                	<?= br(); ?>
					            	<div><?= $pagination; ?></div>
					            	<?= br(); ?>
					                <div class="col-lg-12">
					                    <div class="panel panel-default">
                        					<div class="panel-heading">
                            					<i class="fa fa-group fa-fw"></i> 
                            					<?= $panel_title; ?>
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
					            	<div><?= $pagination; ?></div>
					                    
					                </div>
					                <!-- /.col-lg-12 -->
                                </div>
                                <div class="tab-pane <?= ($tab == 'add_admin') ? 'active' : ''; ?>" id="add_admin">
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
					                	<?= br(); ?>
				                            	<?= form_open("{$add_admin_process}", $attr_FormOpen); ?>
					                	
					                    <fieldset class="panel panel-primary" >
											<legend class="panel-heading">
					                           <i class="fa fa-user fa-fw"></i>
					                        </legend>
					                        <div class="panel-body">
								              <?php           
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
			                     				if (isset($_GET["success_message"]) == TRUE) {
			                     					echo "<div class=\"alert alert-success alert-dismissable\">
													<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
													Admin successfully added.
													</div>";
			                     				}	
			                     			?>
				                            <?php 
				                            	echo $this->session->flashdata('validation_results');
				                            	//echo $validation_results;
				                            ?>
				                            <p>All fields are required.</p>
												<div><label for="first_name"><em>Required </em>First Name</label></div>					        		                            					                            	
				                                <div class="form-group input-group">
				                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
				                                	<?= form_input($firstname, set_value('first_name')); ?>                                    
				                                </div>
												<div><label for="last_name"><em>Required </em>Last Name</label></div>					        		                            					                                
				                                <div class="form-group input-group">
				                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
				                                	<?= form_input($lastname, set_value('last_name')); ?>                                    
				                                </div>                                                            
												<div><label for="username"><em>Required </em>User Name</label></div>					        		                            					                                					                               
				                                <div class="form-group input-group">
				                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
				                                	<?= form_input($user, set_value('username')); ?>                                    
				                                </div>
												<div><label for="email_address"><em>Required </em>E-mail Address</label></div>					        		                            					                                					                               
				                                <div class="form-group input-group">
				                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
				                                	<?= form_input($email, set_value('email_address')); ?>                                    
				                                </div>				                                  
												<div>
													<label for="password"><em>Required </em>Password</label>
												</div>					        		                            					                                					                               					                                
				                                <div class="form-group input-group">
				                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
				                                	<?= form_password($pass); ?>
				                                </div>
												<div>
													<label for="password2"><em>Required </em>Confirm Password</label>
												</div>					        		                            					                                					                               					                                
				                                <div class="form-group input-group">
				                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
				                                	<?= form_password($pass2); ?>
				                                </div>
												<div>
													<label for="roles"><em>Required </em>Assign Role</label>
												</div>
												<span>To edit this list please go to the <?= $role_back ?> page.</span><br>																				
		                             			<div class="form-group input-group">
	                             					<span class="input-group-addon"><i class="fa fa-group"></i></span>
													<select id="roles"  name="roles" class="form-control"  size = "<?=count($role_list)?>" onChange="change_perms()">
													<? foreach($role_list as $role): ?>
														<option   id= "<?= $role->slug;?>" value="<?= $role->role_id; ?>" <?= isset($role->set) ? 'selected="selected"' : NULL; ?>><?= $role->name; ?></option>
													<? endforeach; ?>
													</select>
		                            			</div>
												<div>
													<em>Required </em>
													<label for="perms" >Allowable permissions by role type </label> <br>
													<span>To edit this list please go to the <?= $perm_back ?> page.</span><br>
												</div>																				 											                            
												<div class="form-group input-group height:100%" >
		                             				<span class="input-group-addon"><i class="fa fa-group"></i></span>
													<select id="perms" name="perms" multiple="multiple" class="form-control" size = "<?=count($perm_list)?>" disabled required>
													<? foreach($perm_list as $perm): ?>
														<option value="<?= $perm->perm_id; ?>" <?= (!empty($perm->set)) ? 'selected="selected"' : NULL; ?>><?= $perm->name; ?></option>
													<? endforeach; ?>
													</select>
		                            			</div>					                                
				                                <!-- Change this to a button or input when using this as a form -->
				                                <?= form_submit($attr_FormSubmit); ?>
				                                <?= br(); ?>
        
				                            </div>
					                </fieldset>				                                <?= form_close(); ?>
					                
					                <?= $link_back; ?>					                        
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
    </div>