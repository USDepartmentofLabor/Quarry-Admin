<?php

# Set form attributes
$cdate_arry = array('class'=>'form-control', 'id'=>'view_date_created','name'=>'view_date_created', 'disabled'=>'disabled');
$name_arry = array('class'=>'form-control', 'id'=>'view_name','name'=>'view_name', 'disabled'=>'disabled');
$key_arry = array('class'=>'form-control',  'id'=>'view_key','name'=>'view_key','disabled'=>'disabled');
$status_arry = array('class'=>'form-control', 'id'=>'view_status','name'=>'view_status','disabled'=>'disabled');
$descr_arry = array('class'=>'form-control', 'rows' => '5','id'=>'view_description','name'=>'view_description', 'value'=>$descr, 'disabled'=>'disabled');
# delete registration
$attr_DelFormOpen = array('role'=>'form', 'id'=>'delete-registration');
$hiddenid = array('class'=>'form-control', 'id'=>'key_id', 'name'=>'key_id', 'type'=>'hidden');
$hiddenkey = array('class'=>'form-control', 'id'=>'token', 'name'=>'token', 'type'=>'hidden');
$attr_DeleteKey = array('class'=>'btn-lg btn-danger', 'value' =>'Delete', 'type'=>'submit');

# edit version
$attr_EditFormOpen = array('role'=>'form', 'id'=>'register-key');
$cdate_edtarry = array('class'=>'form-control', 'id'=>'date_created', 'name'=>'date_created','disabled'=>'disabled');
$name_edtarry = array('class'=>'form-control','id'=>'name', 'name'=>'name','required'=>'required','aria-required'=> 'true','required'=>'required','aria-required'=> 'true');
$key_edtarry = array('class'=>'form-control', 'id'=>'key','name'=>'key', 'disabled'=>'disabled');
$status_edtarry = array('class'=>'form-control', 'id'=>'status','name'=>'status', 'disabled'=>'disabled');
$descr_edtarry = array('class'=>'form-control', 'rows' => '5','id'=>'description', 'name'=>'description', 'value'=>$descr,'required'=>'required','aria-required'=> 'true');
$attr_editKey = array('class'=>'btn-lg btn-success', 'value' =>'Update', 'type'=>'submit');

# tab view
$tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'null';
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
			<!-- /.panel-heading -->
		
			<div class="panel-body">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs">
					<li class="<?= ($tab == 'token_view') ? 'active' : ''; ?>">
					<a href="#token_view" data-toggle="tab">View</a></li>
					<li class="<?= ($tab == 'token_edit') ? 'active' : ''; ?>">
					<a href="#token_edit" data-toggle="tab">Edit</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div
						class="tab-pane fade in <?= ($tab == 'token_view') ? 'active' : ''; ?>" id="token_view">
		                <?= br(); ?>
		                <!-- /.col-lg-6 -->
						<div class="col-lg-6">
							<div class="panel panel-success">
								<div class="panel-heading">
									<h2><i class="fa fa-user fa-fw"></i><?= $key_owner; ?></h2>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									<h3>Date Generated</h3>
									<p><?= $cdate ?></p>                             
									<h3>API Key Name</h3>
									<p><?= $key_owner ?></p>                                
									<h3>API Key</h3>
									<p><?= $token ?></p>                                
									<h3>Status</h3>
									<?php
									if ($status == "1") {
										echo "<p>Key Enabled</p>";
									} else {
										echo "<p>Key Disabled <br><br></p>";
									}
									?>
									<h3>Description</h3>
									<?= $descr_arry['value'] ?>
									<!-- /.panel-body -->
							</div>
						</div>                 
		               	<?= $link_back; ?>   
		            </div>
					<?= br(); ?>
		            <!-- /.row -->
					</div>

					<div class="tab-pane fade in <?= ($tab == 'token_edit') ? 'active' : ''; ?>" id="token_edit">
					  <?= br(); ?>
					   <!-- /.col-lg-6 -->
					<?php 
						if (isset($_GET["UpdateSuccess"]) == TRUE) {
			            	echo "<div class=\"alert alert-success alert-dismissable\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							Token Updated.
							</div>";
			         	}
			         ?>						  
							<div class="col-lg-6">
					<?= form_open("".$editKeyReg ."", $attr_EditFormOpen); ?>
							
								<div class="panel panel-warning">
									<div class="panel-heading">
										<h2><i class="fa fa-user fa-fw"></i> <?= $key_owner; ?></h2>
					            </div>
									<!-- /.panel-heading -->
									<div class="panel-body">
										<? echo $this->session->flashdata('validation_errors'); ?>
										<fieldset class="bg-info">					
										<legend>All fields are required.</legend>
										
										<div>
											<label for="date_created">Date Generated</label>
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<?= form_input($cdate_edtarry, $cdate); ?>                             
										</div>
										<div>
											<label for="name">API Key Name</label>
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<?= form_input($name_edtarry, $key_owner); ?>                                
										</div>
										<div>
											<h3>Key</h3>
										</div>
										<div class="form-group input-group">
				                     		<?= $token; ?>
										</div>
										<div>
											<label for="status">Status</label>
										</div>
										<div class="form-group input-group">
											<?php
											if ($status == "1") {
												echo "<span class=\"input-group-addon\"><i class=\"fa fa-unlock\"></i></span>";
											} elseif ($status == "0") {
												echo "<span class=\"input-group-addon\"><i class=\"fa fa-lock\"></i></span>";
											}
											if ($status == "1") {
												echo form_input ( $status_edtarry, set_value ( "status", "Key Enabled" ) );
											} else {
												echo form_input ( $status_edtarry, set_value ( "status", "Key Disabled" ) );
											}
											?>
									 	</div>
										<div>
											<label for="description">Description</label>
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon"><i class="fa fa-comment"></i></span>
											<?= form_textarea($descr_edtarry); ?><br>
										</div>
									</fieldset>
										
									<?= form_submit($attr_editKey); ?>
									</div>
									<!-- /.panel-body -->
									
								</div>     
																						<?= form_close()?>
								              
					               	<?= $link_back; ?>   
					            </div>
			            
			            <?= br(); ?>
			            <!-- /.col-lg-6 -->
					</div>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
			<!-- end tab view -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->

