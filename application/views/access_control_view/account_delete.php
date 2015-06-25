<?php 
$attr_FormOpen = array('role'=>'form', 'id'=>'delete-form');
$attr_FormSubmit = array('class'=>'btn-lg btn-danger', 'value' =>'Delete', 'type'=>'submit');
$firstname = array('class'=>'form-control', 'name' => 'first_name', 'id' => 'first_name','disabled'=>'disabled');
$lastname = array('class'=>'form-control', 'name' => 'last_name','id' => 'last_name', 'disabled'=>'disabled');
$email = array('class'=>'form-control', 'name' => 'email_address', 'id' => 'email_address','disabled'=>'disabled');
$status = array('class'=>'form-control', 'name' => 'status','id' => 'status', 'disabled'=>'disabled');
$username = array('class'=>'form-control', 'name' => 'username','id' => 'username','disabled'=>'disabled');
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
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                          <h2> <i class="fa fa-trash-o fa-fw"></i> <?= $this->form_data->first_name. " ".$this->form_data->last_name; ?></h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
									<?= form_open("".$del_account ."", $attr_FormOpen); ?>
									
										<h3>First Name</h3>		                        									
										<p>
											<?= $this->form_data->first_name; ?>                                
										</p>
										<h3>Last Name</h3>		                        																			
										<p>
											<?= $this->form_data->last_name ?>                                
										<h3>Status</h3>		                        																													
										<p>
											<?php
											if ($this->form_data->status == "1") {
												echo "User Enabled";
											}else{
												echo "User Disabled";										
											}
											?>
										 </p>
										<h3>User Name</h3>		                        																																							 
										<p>
											<?= $this->form_data->username; ?>										                                 
										</p>
										<h3>Email Address</h3>		                        																																							 
										<p>
											<?= $this->form_data->email_address; ?>                                   
										</p>										
										<h3>Role</h3>		                        																																							 
										<p>
										<?= $role->name ?>	
										</p>																													 											                            
										<!-- Change this to a button or input when using this as a form -->
										<?= form_submit($attr_FormSubmit); ?>
										<?= form_close(); ?>
								<!-- Change this to a button or input when using this as a form -->
                                </div>
        					</div>
        				</div>
        				</div>
                     <?= $link_back; ?>
        				
        		</div>
        	</div>
      </div></div>
        
        <!-- /#page-wrapper -->
    <!-- /#wrapper -->