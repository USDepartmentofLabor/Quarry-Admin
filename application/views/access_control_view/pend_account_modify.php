<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'update-user');
$hiddenid = array('class'=>'form-control', 'name' => 'user_id', 'id' => 'user_id','type'=>'hidden','required'=>'required','aria-required'=> 'true');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name','required'=>'required','aria-required'=> 'true');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name','required'=>'required','aria-required'=> 'true');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address','type'=>'email','autocorrect'=>'off','autocomplete'=>'off','autocapitalize'=>'off','required'=>'required','aria-required'=> 'true');
$date_requested = array('class'=>'form-control', 'name' => 'date_requested', 'id'=>'date_requested', 'disabled'=>'disabled','required'=>'required','aria-required'=> 'true');
$username = array('class'=>'form-control', 'name' => 'username','id' => 'username','autocorrect'=>'off','autocomplete'=>'off','autocapitalize'=>'off','required'=>'required','aria-required'=> 'true');
$approveRequest = array('class'=>'btn-lg btn-success', 'name' => 'approved','id' => 'approved', 'value' =>'Approve', 'type'=>'submit');
//$DenyRequest = array('class'=>'btn btn-danger', 'name' => 'denied', 'value' =>'Deny Request', 'type'=>'submit');


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
                <? echo $this->session->flashdata('data'); ?>
              <?= form_open("".$action ."", $attr_FormOpen); ?>
                
				<?= form_input($hiddenid, set_value("user_id", $this->form_data->user_id)); ?>     
					<fieldset>
					<div class="panel panel-success">
						<!-- /.panel-heading -->
						<legend class="panel-heading">
							<i class="fa fa-user fa-fw"></i> <?= $this->form_data->first_name. " ".$this->form_data->last_name; ?>
                        	</legend>
						<div class="panel-body">
							<p>All fields are required.</p>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane fade in active" id="profile-pills">

									<div>
										<label for="date_requested">Date Requested</label>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>											
											<?= form_input($date_requested, set_value("date_requested", $this->form_data->date_requested)); ?>                                   
										</div>
									<div>
										<label for="first_name">First Name</label>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($firstname, set_value("first_name", $this->form_data->first_name)); ?>                                
										</div>
									<div>
										<label for="last_name">Last Name</label>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($lastname, set_value("last_name", $this->form_data->last_name)); ?>                                
										</div>
									<div>
										<label for="username">User Name</label>
									</div>

									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($username, set_value("username", $this->form_data->username)); ?>                                  
										</div>
									<div>
										<label for="email_address">E-mail Address</label>
									</div>

									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<?= form_input($email, set_value("email_address", $this->form_data->email_address)); ?>                                   
										</div>
									<div>
										<label for="roles">User Role</label>
									</div>
									<span>To edit this list please go to the <?= $role_back ?> page.</span><br>
								</div>
								<div class="form-group input-group">
									<span class="input-group-addon"><i class="fa fa-group"></i></span>
									<select id="roles" name="roles" class="form-control" required
										size="<?=count($role_list)?>" onChange="change_perms()">
										<? foreach($role_list as $role): ?>
										<option name="<?= $role->name;?>" id="<?= $role->name;?>"
										value="<?= $role->role_id; ?>"
										<?= isset($role->set) ? 'selected="selected"' : NULL; ?>><?= $role->name; ?></option>
										<? endforeach; ?>
									</select>
								</div>
								<div>
									<label for="perms">Allowable permissions by role type</label><br>
									<span>To edit this list please go to the <?= $perm_back ?> page.</span><br>
									<div class="form-group input-group height:100%">
										<span class="input-group-addon"><i class="fa fa-group"></i></span>
										<select id="perms" name="perms" multiple="multiple"
											class="form-control" size="<?=count($perm_list)?>" disabled
											required>
											<? foreach($perm_list as $perm): ?>
											<option value="<?= $perm->perm_id; ?>"
											<?= (!empty($perm->set)) ? 'selected="selected"' : NULL; ?>><?= $perm->name; ?></option>
											<? endforeach; ?>
										</select>
									</div>
								</div>
							</div>

							<!-- Change this to a button or input when using this as a form -->
							<?= form_submit($approveRequest); ?>
							<?= form_close(); ?>
                     	</div>
					</div>
			</fieldset>
			</div>
		</div>		
		
                                
		<?= $link_back; ?>
		
		</div>
	<!-- /.panel-body -->

	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<!-- /#wrapper -->