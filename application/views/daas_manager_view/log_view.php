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
                        <div class="panel-heading ">
                        <h2>
                           <i class="fa fa-cog fa-fw"></i> 
                           	Transaction Record <?= $log->id; ?>
                        </h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="connect-test">
					             	<div class="panel-body">
		                                <h3>URI</h3>
		                                <p><?= $log->uri; ?></p>
		                                <h3>Method</h3>
		                                <p><?= $log->method; ?></p>
		                                <h3>Parameters</h3>
		                                <p><?= $log->params; ?></p>
		                                <h3>REST API KEY</h3>
		                                <p><?= $log->api_key; ?></p>
		                                <h3>IP Address</h3>
		                                <p><?= $log->ip_address; ?></p>
		                                <h3>Response Time (ms)</h3>
		                                <p><?= $log->rtime; ?></p>
                            		</div>
					        	</div>
                           	</div>
                        </div>
                        <!-- /.panel-body -->

                    </div>
                    <?= $link_back; ?>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->