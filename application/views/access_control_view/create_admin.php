<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'signup-form');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name', 'placeholder'=>'First Name');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name', 'placeholder'=>'Last Name');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address', 'placeholder'=>'Email Address');
$user = array('class'=>'form-control', 'name' => 'username', 'id'=>'username', 'placeholder'=>'Email Address');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password', 'placeholder' => 'Password');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2', 'placeholder' => 'Confirm password');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'value' =>'Create Admin', 'type'=>'submit');

?>
<!-- Signup form for administrative access -->
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
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i>
                        </div>
                        <div class="panel-body">
                            <fieldset>
                            	<?= form_open("{$action}", $attr_FormOpen); ?>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($firstname); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($lastname); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                	<?= form_input($email); ?>                                    
                                </div>                                                            
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($user); ?>                                    
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-group"></i></span>
                                    	<select class="form-control">
                                        	<option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                       </select>
                               </div>                                
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass); ?>
                                </div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass2); ?>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= $error; ?>
                            </fieldset>
                        </div>
                        <div class="panel-footer">
                            <i class="fa fa-user fa-fw"></i>
                        </div>
                    </div>
                    <?= $link_back; ?>
                </div>
                <!-- /.col-lg-6 -->
            </div>
            <?= br(); ?>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
