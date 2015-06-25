<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'add-daas');
$attr_FormSubmit = array('class'=>'btn btn-primary', 'id'=>'add_conn', 'name'=>'add_conn', 'value' =>'Add Connection', 'type'=>'submit');

# tab view
$tab = (isset($_GET['tab'])) ? $_GET['tab'] : null;
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
				                <?php if (isset($_GET["del_data_success"])) {
		                            echo "<div class=\"alert alert-success alert-dismissable\">
		                            		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
											Dataset successfully deleted.
										</div>";
			                        }
			                     ?>                             
                            <ul class="nav nav-tabs">
                                <li class="<?= ($tab == 'log_list') ? 'active' : ''; ?>"><a href="#log_list" data-toggle="tab">View Logs</a>
                                </li>
                            </ul>
                            <!-- Tab panes -->  
                            <div class="tab-content">
                                <div class="tab-pane <?= ($tab == 'log_list') ? 'active' : ''; ?>" id="log_list">
                                	<?= br(); ?>
					            	<div><?= $pagination; ?></div>
					            	<?= br(); ?>
					                <div class="col-lg-12">
					                    <div class="panel panel-default">
					                        <div class="panel-heading">
					                            <i class="fa fa-cogs fa-fw"></i> <?= $panel_title; ?>
					                        </div>
					                        <!-- /.panel-heading -->
					                        <div class="panel-body">
					                            <div class="table-responsive">
												<?= $table; ?>
					                            </div>
					                            <!-- /.table-responsive -->
					                        </div>					                        
					                        <!-- /.panel-body -->
					                    </div>					                    
					                    <!-- /.panel -->
					                </div>
					            	<div><?= $pagination; ?></div>
					                
					                <!-- /.col-lg-12 -->
                                </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <p>Page rendered in {elapsed_time} seconds</p>
                <!-- end tab view -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->