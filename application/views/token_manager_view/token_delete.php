<?php

# Set form attributes
$descr_arry = array('class'=>'form-control', 'rows' => '5','id'=>'view_description','name'=>'view_description', 'value'=>$descr, 'disabled'=>'disabled');
# delete registration
$attr_DelFormOpen = array('role'=>'form', 'id'=>'delete-registration');
$hiddenid = array('class'=>'form-control', 'id'=>'key_id', 'name'=>'key_id', 'type'=>'hidden');
$hiddenkey = array('class'=>'form-control', 'id'=>'token', 'name'=>'token', 'type'=>'hidden');
$attr_DeleteKey = array('class'=>'btn-lg btn-danger', 'value' =>'Delete', 'type'=>'submit');


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
				<ul class="nav nav-tabs">
					<li class="inactive"><?= $link_view ?></li>
					<li class="inactive"><?= $link_edit ?></li>
					<li class="active"><a href="#confirm_delete" data-toggle="tab">Delete</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in" id="token_view">
		                    <?= br(); ?>
		                <!-- /.col-lg-6 -->
		            <?= br(); ?>
		            <!-- /.row -->
					</div>
					<div class="tab-pane fade in" id="token_edit">
					  <?= br(); ?>
					            <!-- /.col-lg-6 -->
					</div>
					<div class="tab-pane fade in active"  id="confirm_delete">
		                    <?= br(); ?>
		                <!-- /.col-lg-6 -->
						<div class="col-lg-6">
							<div class="panel panel-danger">
							<div class="panel-heading">
								<h2>
									<i class="fa fa-user fa-fw"></i> <?= $key_owner; ?>
		                        </h2>
		                  	</div> 
								<!-- /.panel-heading -->
							<div class="panel-body">
								<?= form_open("".$delKeyReg ."", $attr_DelFormOpen); ?>
							
									<div>
										<h3>Key ID</h3>
									</div>
									<div class="form-group input-group">
											<?= $key_id ?>                             
										</div>
									<div>
										<h3>Date Generated</h3>
									</div>
									<div class="form-group input-group">
											<?= $cdate ?>                             
										</div>
									<div>
										<h3>Key Owner</h3>
									</div>
									<div class="form-group input-group">
										<?= $key_owner ?>                             
									</div>
									<div>
										<h3>Key</h3>
									</div>
									<div class="form-group input-group">
										<?= $token ?>                               
										</div>
									<div>
										<h3>Status</h3>
									</div>
									<div class="form-group input-group">
											<?php
											if ($status == "1") {
											} elseif ($status == "0") {
											}
											if ($status == "1") {
												echo 'Enabled';
											}else{
												echo 'Disabled';										
											}
											?>
									 	</div>
									<div>
										<h3>Description</h3>
									</div>
									<div class="form-group input-group">
											<?= print_r($descr_arry['value']); ?><br>
									</div>
								<?= form_submit($attr_DeleteKey); ?>
	                            <?= form_close(); ?>									
							</div>
							<!-- /.panel-body -->
						</div>                   
		               	<?= $link_back; ?>   
		            </div>											            
		            <?= br(); ?>
		            <!-- /.row -->
					</div>
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
<!-- /#wrapper -->
