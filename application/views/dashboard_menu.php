        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?= $title; ?></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>Profile<i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?= base_url("access_control/admin/account_view/{$this->session->userdata('user_id')}")?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="<?= base_url("access_control/admin/account_manager/?tab=users")?>"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?= base_url(); ?>dashboard/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

        </nav>
		<nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">         
                    <?php if ($this->session->userdata('is_admin')){ ?>          
                    <li>
                        <a href="#"><i class="fa fa-group fa-fw"></i> Account Manager<span class="fa arrow"></span></a>

                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Manage Users <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="<?= base_url("access_control/admin/account_manager/?tab=users"); ?>">User List</a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url("access_control/admin/pending_request"); ?>">Pending Request</a>
                                    </li>                                    
                                    <li>
                                        <a href="<?= base_url("access_control/admin/account_manager#add_admin"); ?>">Create User</a>
                                    </li>
                                </ul>
                                <!-- /.nav-third-level -->
                            </li>
                            <li>
                                <a href="#">Manage Roles <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="<?= base_url("access_control/role/role_manager/?tab=roles"); ?>">Roles</a>
                                    </li>
                                </ul>
                                <!-- /.nav-third-level -->
                            </li>
                            <li>
                                <a href="#">Manage Permissions <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="<?= base_url("access_control/permission/permission_manager/?tab=permissions"); ?>">Permission</a>
                                    </li>
                                </ul>
                                <!-- /.nav-third-level -->
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-key fa-fw"></i> API Key Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= base_url("key_control/key/token_manager/?tab=key_list"); ?>">Manage API Key</a>
                            </li>
                            <li>
                                <a href="<?= base_url("key_control/key/token_manager/?tab=register_key"); ?>">Generate API Key</a>
                            </li>
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>  
                    <?php  } ?>          
                    
                    <li>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Manage Datasets<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?= base_url("daas_control/daas/daas_manager/?tab=add_daas"); ?>">Add Dataset</a>
                            </li>
                            <li>
                                <a href="<?= base_url("daas_control/daas/daas_manager/?tab=daas_list"); ?>">View Dataset</a>
                            </li>
                            <li>
                                <a href="<?= base_url("daas_control/daas/log_table/?tab=log_list"); ?>">View REST Server Logs</a>
                            </li>                            
                        </ul>      
                        <!-- /.nav-second-level -->
                    <li>
                        <?= anchor_popup('apiv2adminui-docs/','<i class="fa fa-book fa-fw"></i> API v2 Documentation',array()) ?>
                    </li>                    
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </nav>