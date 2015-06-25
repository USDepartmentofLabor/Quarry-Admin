<?php

# Set form attributes
$attr_FormOpen = array('role'=>'form', 'id'=>'register-key');
$name_arry  = array('class'=>'form-control', 'id'=>'name', 'name'=>'name','required'=>'required','aria-required'=> 'true');
$email_arry = array('class'=>'form-control', 'id'=>'email_address', 'name'=>'email_address','required'=>'required','aria-required'=> 'true');
$descr_arry = array('class'=>'form-control', 'id'=>'description','name'=>'description','rows' => '5','required'=>'required','aria-required'=> 'true');
$attr_FormSubmit = array('class'=>'btn-lg btn-primary', 'value' =>'Generate Key', 'type'=>'submit');

# Search form attributes
$attr_SearchFormOpen = array('role'=>'form', 'class'=>'form-inline', 'id'=>'search-form');
$searchinput_arry  = array('class'=>'form-control', 'id'=>'searchval', 'name'=>'searchval', 'placeholder'=>'Search for: Token, Key Owner, IP or Email&hellip;');
$searchdrpdwn_arry = array('class'=>'form-control', 'id'=>'searchcond');

$searchCat = array('key'=>'Token', 'ip_addresses'=>'IP Address', 'name'=>'Key Owner', 'status'=>'Status', 'email_addr'=>'Email');
$searchCond = array('eq' => '=', 'gte' => '&gt;=', 'lte' => '&lt;=', 'lt'=>'&lt;');
$attr_FormSearch = array('class'=>'btn btn-primary', 'value' =>'Go', 'type'=>'submit');
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
                                <li class="<?= ($tab == 'key_list') ? 'active' : ''; ?>"><a href="#key_list" data-toggle="tab">API Key List</a></li>
                                <li class="<?= ($tab == 'register_key') ? 'active' : ''; ?>"><a href="#register_key" data-toggle="tab">Register New Key</a></li>
                            </ul>
                            <!-- Tab panes -->  
                            <div class="tab-content">
                                <div class="tab-pane <?= ($tab == 'key_list') ? 'active' : ''; ?>" id="key_list">
                                	<?= br(); ?>
					                <div class="col-lg-12">
																	                
					                    <div class="panel panel-default">
					                        <div class="panel-heading">
					                            <i class="fa fa-key fa-fw"></i> <?= $panel_title; ?>
					                        </div>
					                        <!-- /.panel-heading -->
					                        <div class="panel-body">	               
										<?php if (isset($_GET["status"]) == 'del_success') : ?>
					                	<?= br(); ?>
				                    	<div class="alert alert-success alert-dismissable">
				                    		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Token successfully deleted.
				                    	</div>		                        		                 
				                     <?php endif; ?>					                        
												<?= form_open($actionSearch, $attr_SearchFormOpen); ?>
												    <div class="row">
												        <div class="col-xs-4">
												        	<div><label for="searchcat">Search for</label></div>
															<div  class="input-group">
																<span class="input-group-addon"><i class="fa fa-cog"></i></span>
																	<select id = "searchcat" name = "searchcat"  class="form-control">
																		<option value="">Search category</option>
																		<?php foreach($searchCat as $dbcolumn => $catviewList) : ?>
																		<option value="<?= $dbcolumn; ?>"><?= $catviewList ?></option> 
																		<?php endforeach; ?>
																	</select>                                 
															</div>
												        </div>
												        <div class="col-xs-4">
												        	<div><label for="searchcond">Search conditions</label></div>
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-cog"></i></span>
																<?= form_dropdown('searchcond', $searchCond, '', 'id="searchcond" class="form-control"') ?>                               
															</div>
												        </div>
												        <div class="col-xs-4">
												        	<div><label for="searchval">Search value</label></div>
												            <div class="input-group">
												                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
												                <?= form_input($searchinput_arry, set_value('searchval')) ?>
												            <span class="input-group-btn">
												                <?= form_submit($attr_FormSearch); ?>
												            </span>                
												            </div>
												        </div>
												    </div>
													<?= form_close(); ?>
					                        	<?php if (strlen($pagination)): ?>
										         <div><?= $pagination; ?></div>
										        <?php endif; ?>
										        <?php if (!strlen($pagination)): ?>
										        <?= br(); ?>
										        <?php endif; ?>
					                            <div class="table-responsive">
													<?= $table; ?>
					                            </div>
					                        	<?php if (strlen($pagination)): ?>
										         <div><?= $pagination; ?></div>
										        <?php endif; ?>
					                        <?= $reset; ?>
										        </div>
					                        <!-- /.panel-body -->
					                    </div>
					                    <!-- /.panel -->
					                </div>
					                <!-- /.col-lg-12 -->
                                </div>
                                <div class="tab-pane <?= ($tab == 'register_key') ? 'active' : ''; ?>" id="register_key">
					                <!-- /.col-lg-4 -->
					                <div class="col-lg-4">
					                	<?= br(); ?>
										<?php 
										if (isset($_GET["success_message"]) == TRUE) {
											echo "<div class=\"alert alert-success alert-dismissable\">
													<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
													API key successfully added.
													</div>";
										}										
										?>    	
					                	<?= br(); ?>
					                	
					                    <div class="panel panel-primary">
											<div class="panel-heading">
					                          <i class="fa fa-key fa-fw"></i>
					                        </div>
					                        <div class="panel-body">
					                        	<?= form_open("".$actionregKey ."", $attr_FormOpen); ?>
					                			<?= $this->session->flashdata('validation_errors'); ?>   
					                <fieldset class="bg-info">
					                			
														<legend>All fields are required.</legend>
					                			
														<div><label for="name">API Key Name</label></div>		                        																												
														<div class="form-group input-group">
														<span class="input-group-addon"><i class="fa fa-user"></i></span>
															<?= form_input($name_arry, set_value('name')); ?>                                
														</div>
														<div><label for="email_address">Assigned E-mail Address</label></div>					        		                            					                                					                               
						                                <div class="form-group input-group">
						                                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						                                	<?= form_input($email_arry, set_value('email_address')); ?>                                    
						                                </div> 																                        																												
														<div><label for="description">Description</label></div>		                        																																						
														 <div class="form-group input-group">
															<span class="input-group-addon"><i class="fa fa-comment"></i></span>
															<?= form_textarea($descr_arry, set_value('description')); ?><br>											                                 
														</div>
					                    			</fieldset>
														
													<!-- Change this to a button or input when using this as a form -->
					                                <?= form_submit($attr_FormSubmit); ?>
					                                <?= br(); ?>
												<?= form_close(); ?>
					                        </div>
					                    </div>													
                     					<?= $link_back; ?>
					                </div>
					                
					                <!-- /.col-lg-4 -->							
                                </div>
                                
                            </div>	
                            </div>
                        <!-- /.panel-body -->
                        </div>
                    <!-- /.panel -->
                   <p>Page rendered in {elapsed_time} seconds</p>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->