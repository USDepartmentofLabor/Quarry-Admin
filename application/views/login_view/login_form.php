 <?php 
 
 # Set form attributes
 $attr_FormOpen = array('role'=>'form', 'id' => 'login-form');
 $attr_Username	= array('class'=>'form-control', 'name'=>'username', 'id'=>'username');
 $attr_Password	= array('class'=>'form-control', 'name'=>'password', 'id'=> 'password', 'type'=>'password','value'=>'');
 $attr_RememberMe = array('name'=>'remember','id'=>'remember',  'checked'=> TRUE, 'value'=>'Remember Me',);
 $attr_FormSubmit = array('class'=>'btn btn-lg btn-success btn-block', 'value' =>'Login', 'type'=>'submit');
 ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-title"><?= $subtitle; ?></h1>
                    </div>
                    <div class="panel-body">
                    	<?= form_open("{$action}", $attr_FormOpen); ?>
                    	<?php  isset($error)?  print_r($error):'' ?>
                        	<div><label for="username"><em>Required </em>User Name</label></div>                           	                            
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($attr_Username); ?>                                    
                                </div>
                                <div><label for="password"><em>Required </em>Password</label></div>                           	                                
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_password($attr_Password); ?>
                                </div>
                                <div class="checkbox">
                                    <label for="remember">
                                    	<?= form_checkbox($attr_RememberMe); ?> Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= anchor(base_url()."request/", 'Request Access'); ?>
                                <?= br(); ?>
                                <?= anchor(base_url()."request/password_reset", 'Forgot Password'); ?>
                                <?= br(); ?>
                                <?php
                                	if (isset($_GET["PasswordChange"])) {
                                	echo "<div class=\"alert alert-success alert-dismissable\">
										<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
											Your password has been changed
										</div>";
                                	}
                                ?>
                                <?= validation_errors('<p class="error">'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>