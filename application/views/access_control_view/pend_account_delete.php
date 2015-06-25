<?php 
$attr_FormOpen = array('role'=>'form', 'name'=>'delete-form', 'id'=>'delete-form');
$attr_FormSubmit = array('class'=>'btn-lg btn-danger', 'value' =>'Deny', 'type'=>'submit');
?>
<div id="wrapper">
	<!-- /.navbar-static-top -->
	<?php // load dashboard admin menu ?>
	<?php $this->load->view("dashboard_menu"); ?>
	<!-- /.navbar-static-side -->
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<?= $subtitle; ?>
				</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<!-- /.col-lg-6 -->
			<div class="col-lg-6">
				<!-- /.panel-heading -->
				<!-- Tab panes -->
				<div>
					<div class="panel panel-danger" id="profile-pills">
						<?= form_open("".$del_pending_account ."", $attr_FormOpen); ?>
						<div class="panel-danger">
							<div class="panel-heading">
								<h2>
									<i class="fa fa-trash-o fa-fw"></i>
									<?= $this->form_data->first_name. " ".$this->form_data->last_name; ?>
								</h2>
							</div>
							<div class="panel-body">
								<h3>Date Requested</h3>
								<p>
									<?= $this->form_data->date_requested; ?>
								</p>
								<h3>First Name</h3>
								<p>
									<?= $this->form_data->first_name; ?>
								</p>
								<h3>Last Name</h3>
								<p>
									<?= $this->form_data->last_name; ?>
								</p>
								<h3>User Name</h3>
								<p>
									<?= $this->form_data->username; ?>
								</p>
								<h3>Email Address</h3>
								<p>
									<?= $this->form_data->email_address; ?>
								</p>
								<!-- Change this to a button or input when using this as a form -->
								<?= form_submit($attr_FormSubmit); ?>
							</div>
						</div>
						<?= form_close(); ?>
					</div>
				</div>
				<?= $link_back; ?>
			</div>
		</div>
	</div>
	<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->