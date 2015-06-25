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
                	<?= $message; ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                           <h2>User</h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><h3><?= $acct->first_name. " ".$acct->last_name; ?></h3>
                                </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
                                	<?php
                                		if ($acct->status == "1") {
                                			$class = "text-success";
                                		} elseif ($acct->status == "0") {
                                			$class = "text-danger";
                                		}                                	
                                	?>
		                            <div>
		                                <h4>Username</h4>
		                                <p><?= $acct->username; ?></p>
		                                <h4>Account Status</h4>
		                                <p><?= strtoupper($acct->status)=="1"? "Active":"Disabled"; ?></p>
		                                <h4>User Since</h4>
		                                <p><?= date('m/d/Y', strtotime($acct->date_created)); ?></p>
		                                <h4>Email Address</h4>
		                                <p><a href="mailto:<?= $acct->email_address; ?>"><?= $acct->email_address; ?></a></p>
		                            </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <?= $link_back; ?>
                </div>
            </div>
            <?= br(); ?>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->