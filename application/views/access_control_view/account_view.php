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
				<div class="panel panel-warning">
					<div class="panel-heading">
						<h2>
							<i class="fa fa-user fa-fw"></i>
							<?= $acct->first_name. " ".$acct->last_name; ?>
						</h2>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<!-- Nav tabs -->

						<!-- Tab panes -->
						<div class="tab-content">
							<div class="tab-pane fade in active" id="profile-pills">
								<h3>Date Created</h3>
								<p>
									<?= $acct->date_created; ?>
								</p>
								<h3>First Name</h3>
								<p>
									<?= $acct->first_name; ?>
								</p>
								<h3>Last Name</h3>
								<p>
									<?= $acct->last_name; ?>
								</p>
								<h3>Status</h3>
								<p>
									<?php
									if ($acct->status == "1") {
										echo "User Enabled";
									}else{
										echo "User Disabled";										
									}
									?>
								</p>
								<h3>User Name</h3>
								<p>
									<?= $acct->username; ?>
									<br>
								</p>
								<h3>Email Address</h3>
								<p>
									<?= $acct->email_address; ?>
								</p>
								<h3>Role</h3>
								<p>
									<? foreach($user->roles as $role){
									 echo $role->name."<br>";
									 } 
									?>
								</p>

								<h3>Permissions</h3>
								<p class="form-group input-group height:100%">
									<? foreach($perm_list as $perm){ 
										echo $perm->name."<br>";
										}
									?>
								</p>
							</div>
						</div>
						<!-- /.panel-body -->
					</div>
				</div>
				<?= $link_back; ?>
			</div>
			<?= br(); ?>
			<!-- /.row -->
		</div>
	</div>
	<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->