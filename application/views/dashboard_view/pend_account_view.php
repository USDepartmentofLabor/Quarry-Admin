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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                           <i class="fa fa-user fa-fw"></i> <?= $acct->first_name. " ".$acct->last_name; ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Profile</a>
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
		                            <h4 class="<?= $class; ?>"><i class="fa fa-user fa-fw"></i> <?= $acct->first_name. " ".$acct->last_name; ?></h4>
		                            <address>
		                                <strong>Username</strong>
		                                <br><?= $acct->username; ?>
		                            </address>		                            
		                            <address>
		                                <strong>Account Status</strong>
		                                <br><?= strtoupper($acct->status)=="1"? "Disabled":"Pending"; ?>
		                                <br><strong>Request Date</strong>
		                                <br><?= date('m/d/Y', strtotime($acct->date_requested)); ?>
		                            </address>
		                            <address>
		                                <strong>Email Address</strong>
		                                <br>
		                                <a href="mailto:<?= $acct->email_address; ?>"><?= $acct->email_address; ?></a>
		                            </address>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <?= $acct->first_name. " ".$acct->last_name; ?>
                        </div>
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