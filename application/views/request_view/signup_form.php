<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'signup-form');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id'=>'first_name');
$lastname = array('class'=>'form-control', 'name' => 'last_name', 'id'=>'last_name');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id'=>'email_address','type'=>'email');
$user = array('class'=>'form-control', 'name' => 'username', 'id'=>'username');
$pass = array('class'=>'form-control', 'name' => 'password', 'id'=>'password');
$pass2 = array('class'=>'form-control', 'name' => 'password2', 'id'=>'password2');
$attr_FormSubmit = array('class'=>'btn btn-lg btn-warning btn-block', 'value' =>'Request Access', 'type'=>'submit');

?>
<!-- Signup form for administrative access -->
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-title">
                        	<i class="fa fa-key fa-fw"></i> <?= $subtitle; ?>
                        </h1>
                    </div>
                    <div class="panel-body">
                             <?php echo $this->session->flashdata('data'); ?>
                    
                            <fieldset>
                            	<?= form_open("{$action}", $attr_FormOpen); ?>
                                <div><label for="first_name"><em>Required </em>First Name</label></div>                           	
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($firstname); ?>                                    
                                </div>
                                <div><label for="last_name"><em>Required </em>Last Name</label></div>                                
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($lastname); ?>                                    
                                </div>
                                <div><label for="email_address"><em>Required </em>Email Address</label></div>
                                
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                	<?= form_input($email); ?>                                    
                                </div>                                                            
                                <div><label for="password"><em>Required </em>Enter Password</label></div>
                                
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass); ?>
                                </div>
                                <div><label for="password2"><em>Required </em>Confirm Password</label></div>                               
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                                	<?= form_password($pass2); ?>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= anchor(base_url()."login", 'Back to Login'); ?>
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
