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
					<h2><i class="fa fa-user fa-fw"></i>
						<?= $acct->first_name. " ".$acct->last_name; ?></h2>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<!-- Nav tabs -->
						<!-- Tab panes -->
						<div class="tab-content">
							<h3>Date Requested</h3>
							<p><?=  date ( "m/d/Y", strtotime ( $acct->date_requested ) ) ?></p>
							<h3>First Name</h3>
							<p><?= $acct->first_name;?></p>
							<h3>Last Name</h3>
							<p><?= $acct->last_name; ?></p>
							<h3>User Name</h3>
							<p><?= $acct->username; ?></p>
							<h3>E-mail Address</h3>
							<p><?= $acct->email_address; ?></p>
							<!-- /.panel-body -->
						</div>
					</div>
					<!-- /.row -->
				</div>
				<?= $link_back; ?>
				<!-- /#page-wrapper -->
			</div>
		</div>
	</div>
</div>
<!-- /#wrapper -->