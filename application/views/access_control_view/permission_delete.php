<?php 
$attr_FormOpen = array('perm'=>'form', 'id'=>'delete-form');
$attr_FormSubmit = array('class'=>'btn-lg btn-danger', 'value' =>'Delete', 'type'=>'submit');
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
                        <div class="panel-heading ">
                           <h2><i class="fa fa-trash-o fa-fw "></i> Permission ID: &nbsp;<?= $perm->perm_id?></h2>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <!-- Tab panes -->
                            <div class="tab-content">
				           		<?= form_open("{$del_perm}", $attr_FormOpen); ?>
								<h3>System Name</h3>					        
                                <p><?= $perm->name; ?></p>	
		                        <h3>System ID</h3>					               
								<p><?=  $perm->slug; ?></p>
		                        <h3>Description</h3>					               		                            
								<p><?=  $perm->description; ?></p>
                             	<?= form_submit($attr_FormSubmit); ?>
                               	<?php form_close();?>
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