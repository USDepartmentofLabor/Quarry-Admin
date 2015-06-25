<?php
# Set form attributes
$attr_FormOpen = array('role'=>'form','id'=>'role-form');
$hiddenid = array('class'=>'form-control', 'name' => 'role_id', 'type'=>'hidden');
$name = array('class'=>'form-control', 'name' => 'name','id' => 'name','required'=>'required','aria-required'=> 'true');
$slug = array('class'=>'form-control', 'name' => 'slug','id' => 'slug','required'=>'required','aria-required'=> 'true');
$description = array('class'=>'form-control', 'name' => 'description', 'id' => 'description', 'rows'=>'5','required'=>'required','aria-required'=> 'true');
$attr_FormSubmit = array('class'=>'btn-lg btn-success', 'value' =>'Update', 'type'=>'submit');
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
					<div class="panel panel-success">
						<div class="panel-heading">
							<h2><i class="fa fa-edit fa-fw"></i> Role ID: &nbsp;
							<?= $this->form_data->role_id; ?></h2>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<!-- Nav tabs -->
							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane fade in active" id="perm-pill">
									<?php
			                        if (isset($_GET["UpdateSuccess"])) {
			                            echo "<div class=\"alert alert-success alert-dismissable\">
			                            		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
												Role successfully update.
											</div>";
			                        }
			                        echo $this->session->flashdata('data'); 			                        			                       
			                     ?>			                     
									<?= form_open("".$action ."", $attr_FormOpen); ?>
			                    	<fieldset>
									
									<legend>All fields are required.</legend>
									<?= form_input($hiddenid, set_value("role_id", $this->form_data->role_id)); ?>
									<div>
										<label for="name"><em>Required </em>System Name (50
											Maximum Characters)</label>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-pencil">
										</i></span>
										<?= form_input($name, set_value("name", $this->form_data->name)); ?>
									</div>
									<div>
										<label for="slug"><em>Required </em>System ID (50
											Maximum Characters)</label>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
										<?= form_input($slug, set_value("slug", $this->form_data->slug)); ?>
									</div>
									<div>
										<label for="description"><em>Required </em>Description
											(500 Maximum Characters)</label>
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
										<?= form_textarea($description, set_value("description", $this->form_data->description)); ?>
									</div>
									<div>
										<label for="perms"><em>Required </em>Permissions</label>
									</div>
									<div class="form-group input-group height:auto">
										<span class="input-group-addon"><i class="fa fa-group"></i></span>
										<select id="perms" name="perms[]" multiple="multiple"
											class="form-control" size="<?= count($perm_list)?>"
											required>
											<? foreach($perm_list as $perm): ?>
											<option value="<?= $perm->perm_id ?>"
												<?= (!empty($perm->set)) ? 'selected="selected"' : NULL; ?>>
												<?= $perm->name; ?>
											</option>
											<? endforeach; ?>
										</select>
									</div>
									<!-- Change this to a button or input when using this as a form -->
									<?= form_submit($attr_FormSubmit); ?>
									</fieldset>
									<?= form_close(); ?>
									
								</div>
							</div>
							<!-- /.panel-body -->
						</div>
					</div>
			<?= $link_back; ?>
			</div>
		</div>
		<!-- /.row -->
	</div>
<!-- /#page-wrapper -->
	</div>
<!-- /#wrapper -->