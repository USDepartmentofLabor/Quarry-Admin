<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'registration-error');
$contact_name = array('class'=>'form-control', 'name' => 'contact_name', 'id'=>'contact_name','autocorrect'=>'off','autocomplete'=>'off','autocapitalize'=>'off');
$email = array('class'=>'form-control', 'name' => 'email_address', 'type'=>'hidden');
$message = array('class' => 'form-control', 'name' => 'message_area', 'id' => 'message', 'rows' => '5', 'cols'=> '40', 'value'=>'Explain what happened exactly when you tried to register...');
$attr_FormSubmit = array('class'=>'btn btn-lg btn-warning btn-block', 'value' =>'Request Assistance', 'type'=>'submit');

if (isset($_GET["requestor"])) {
	$name = $_GET["requestor"];
	$req_email = $_GET["email"];
} else {
	$name = $requestor;
	$req_email = $email_add;
}
?>
<!-- Signup form for administrative access -->
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                        	<i class="fa fa-comment fa-fw"></i> <?= $subtitle; ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                            <fieldset>
                            	<?= form_open("{$action}", $attr_FormOpen); ?>
                                <div class="form-group input-group">
                                	<div><label for="contact_name"><em>Required </em>Contact Name</label></div>
                                	<span class="input-group-addon"><i class="fa fa-user"></i></span>
                                	<?= form_input($contact_name, set_value("contact_name", $name)); ?>
                                	<br>
                                	<div><label for="email_address"><em>Required </em>Contact Name</label></div>
                                	<?= form_input($email, set_value("email_address", $req_email)); ?>                                 
                                </div>
                                <div><label for="message_area"><em>Required </em>Contact Name</label></div>
                                
                                <div class="form-group ">
                                	<?= form_textarea($message); ?>                                  
                                </div>                                                            
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(); ?>
                                <?= $error; ?>
                                <?= $success; ?>
                                <?= anchor(base_url()."login", 'Back to Login'); ?>
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>