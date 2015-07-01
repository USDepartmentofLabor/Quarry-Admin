<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form','id'=>'update-user');
$hiddenid = array('class'=>'form-control', 'id' => 'user_id', 'name' => 'user_id', 'type'=>'hidden');
$firstname = array('class'=>'form-control','id' =>'first_name', 'name' => 'first_name','required'=>'required','aria-required'=> 'true');
$lastname = array('class'=>'form-control', 'id' => 'last_name','name' => 'last_name','required'=>'required','aria-required'=> 'true');
$email = array('class'=>'form-control', 'id' => 'email_address','name' => 'email_address','type'=>'email','required'=>'required','aria-required'=> 'true');

// prohibit admin from changing username if the logged in user is the same as the admin...  
if ($this->form_data->username === $this->session->userdata("username")) {
	$username = array('class'=>'form-control', 'name' => 'username', 'id' => 'username','disabled'=>'disabled');
} else {
	$username = array('class'=>'form-control', 'name' => 'username','id' => 'username','required'=>'required','aria-required'=> 'true');
}
$attr_FormSubmit = array('class'=>'btn-lg btn-success', 'value' =>'Update', 'type'=>'submit');
# Set form attributes for password change
$attr_FormOpen_passwd = array('role'=>'form','name'=>'password-change','id'=>'password-change');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password','required'=>'required','aria-required'=> 'true');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2','required'=>'required','aria-required'=> 'true');
$attr_FormSubmit_passwd = array('class'=>'btn-lg btn-success', 'value' =>'Change Password', 'type'=>'submit');
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
                           <h2><i class="fa fa-user fa-fw"></i> <?= $this->form_data->first_name. " ".$this->form_data->last_name; ?></h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Edit Profile</a>
                                </li>
                                <li><a href="#password-pills" data-toggle="tab">Change Password</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
								<br>
                                <?= form_open("".$action ."", $attr_FormOpen); ?>
                                <?= form_input($hiddenid, set_value("user_id", $this->form_data->user_id)); ?>     
									<fieldset>
									<legend>All fields are required.</legend>
				                        <? echo $this->session->flashdata('data');    
		                     				if (isset($_GET["UpdateSuccess"]) == TRUE) {
		                     					echo "<div class=\"alert alert-success alert-dismissable\">
												<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
												Admin successfully updated.
												</div>";
		                     				}	
		                     			?>	
										<div><label for ="first_name"><em>Required </em>First Name</label></div>		                        									
				                        <div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($firstname, set_value("first_name", $this->form_data->first_name)); ?>                                
										</div>
										<div><label for ="last_name"><em>Required </em>Last Name</label></div>		                        																		
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($lastname, set_value("last_name", $this->form_data->last_name)); ?>                                
										</div>
										<div><label for ="status">Status</label></div>
										<div class="form-group input-group">
											<?php
											if ($this->form_data->status == "1") {
												echo "<span class=\"input-group-addon\"><i class=\"fa fa-unlock\"></i></span>";
											} elseif ($this->form_data->status == "0") {
												echo "<span class=\"input-group-addon\"><i class=\"fa fa-lock\"></i></span>";
											}
											?>
											<select class="form-control" name="status" id="status">
											<?php 
											if ($this->form_data->status == "1") {
												echo "<option value=\"{$this->form_data->status}\">User Active</option>";
												echo "<option value=\"0\">User Disable</option>";
											} elseif ($this->form_data->status == "0") {
												echo "<option value=\"{$this->form_data->status}\">User Disabled</option>";
												echo "<option value=\"1\">User Enable</option>";
											}                                    
											?>
											</select>
										 </div>
										 <div><label for="username"><em>Required </em>User Name</label></div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($username, set_value("username", $this->form_data->username)); ?>                                 
										</div>
										<div><label for="email_address" ><em>Required </em>Email Address</label></div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<?= form_input($email, set_value("email_address", $this->form_data->email_address)); ?>                                   
										</div>										
										<div><label for ="roles"><em>Required </em>User Role </label><br>
										<span>To edit this list please go to the <?= $role_back ?> page.</span><br></div>
										<div class="form-group input-group">
			                             	<span class="input-group-addon"><i class="fa fa-group"></i></span>
												<select id ="roles" name ="roles"   class="form-control"  required size = "<?=count($role_list)?>" onChange="change_perms()">
													<? foreach($role_list as $role): ?>
													<option id="<?= $role->slug;?>" value="<?= $role->role_id; ?>" <?= ($role->set) ? 'selected="selected"' : NULL; ?> ><?= $role->name; ?></option>
													<? endforeach; ?>
												</select>
			                            </div>			                            
										<div><label for ="perms"><em>Required </em>Allowable permissions by role type. </label><br>
										<span>To edit this list please go to the <?= $perm_back ?> page.</span><br></div>																				 											                            
			                             <div class="form-group input-group height:100%" >
			                             	<span class="input-group-addon"><i class="fa fa-group"></i></span>
												<select id="perms" name="perms[]" multiple="multiple" class="form-control"   size = "<?=count($perm_list)?>" disabled>
													<? foreach($perm_list as $perm): ?>
													<option value="<?= $perm->perm_id; ?> " <?= (!empty($perm->set)) ? 'selected="selected"' : NULL; ?> ><?= $perm->name; ?></option>
													<? endforeach; ?>
												</select>
			                            </div>			                            
										<!-- Change this to a button or input when using this as a form -->
									</fieldset>
										<?= form_submit($attr_FormSubmit); ?>
										<?= form_close(); ?>									
                                </div>
                                <div class="tab-pane fade" id="password-pills">
                                <br>
                                 	<? echo $this->session->flashdata('data'); ?>			     	
		                            <fieldset>
		                            
		                                <?php if (isset($_GET["PasswordChangeError"])) { ?>
		                                	<div class="alert alert-danger alert-dismissable">
			                                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										 		Password change failed
											</div>
		                                <?php } elseif (isset($_GET["PasswordChangeSuccess"])) { ?>
		                                	<div class="alert alert-success alert-dismissable">
			                                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										 		Password changed
											</div>			                                
		                                <?php } ?>
		                            	<legend>All fields are required.</legend>
		                              	<?= form_open("{$action_passwd_chg}", $attr_FormOpen_passwd); ?>
		                            	<div><label for="password" ><em>Required </em> New password</label></div>
		                                <div class="form-group input-group">
		                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
		                                	<?= form_password($pass); ?>
		                                </div>
										<div><label for="password2"><em>Required </em>Confirm password</label></div>		                                
		                                <div class="form-group input-group">
		                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
		                                	<?= form_password($pass2); ?>
		                                </div>
		                                <!-- Change this to a button or input when using this as a form -->
		                                <?= form_submit($attr_FormSubmit_passwd); ?>
		                                <?= form_close(); ?>
				            		    <?= br(); ?>
				                	    <?= form_error("password"); ?> 
				                        <?= form_error("password2"); ?>
				                        <?= br(); ?>
		                            </fieldset>
                                </div>
                            </div>
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