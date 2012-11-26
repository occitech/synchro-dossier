<?php

$dashboardUrl = array(
	'admin' => true,
	'plugin' => 'settings',
	'controller' => 'settings',
	'action' => 'dashboard',
);
?>
<header class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<span class="hidden-phone">
			<?php echo $this->Html->link(Configure::read('Site.title'), $dashboardUrl, array('class' => 'brand')); ?>
			</span>
			<span class="hidden-desktop hidden-tablet">
			<?php echo $this->Html->link(__('Dashboard'), $dashboardUrl, array('class' => 'brand')); ?>
			</span>
			<div class="nav-collapse collapse" style="height: 0px; ">
				<ul class="nav">
					<li>
						<?php echo $this->Html->link(__('Visit website'), '/', array('target' => '_blank')); ?>
					</li>
				</ul>
				<ul class="nav pull-right">
					<li>
						<a href="#">
							<?php echo sprintf(__("You are logged in as: %s"), $this->Session->read('Auth.User.username')); ?>
						</a>
					</li>
					<li>
						<?php echo $this->Html->link(__("Log out"), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout')); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
