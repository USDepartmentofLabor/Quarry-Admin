<?php

 # Set form attributes
 $attr_FormOpen = array('role'=>'form', 'id'=>'forgot-password');
 $attr_Username	= array('class'=>'form-control', 'name'=>'email_address', 'id'=>'email_address');
 $attr_FormSubmit = array('class'=>'btn btn-lg btn-success btn-block', 'value' =>'Reset Password', 'type'=>'submit');

?>
<!-- Request password change -->
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
                    	<?= form_open("".$action."", $attr_FormOpen); ?>
            		    <?= $success; ?>
		                <?= $error; ?>                    	
                               <div><label for="email_address"><em>Required </em>E-mail Address</label></div>
                                <div class="form-group input-group">
                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                	<?= form_input($attr_Username); ?>                                    
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <?= form_submit($attr_FormSubmit); ?>
                                <?= form_close(); ?>
                                <?= br(1); ?>
                                <?= anchor(base_url()."login", 'Back to Login'); ?>
                                <?= br(1); ?>
                                <?= validation_errors('<p class="error">'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
