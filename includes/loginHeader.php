<?php 

	session_start();
	require 'function.php'; 
	require 'DB.php'; 
	isLoggedIn() ? : header('location: ../sign-in.php');

	extract($getProfile = getProfile($_SESSION['account_id'], $_SESSION['account_type']));
	
?>


<!DOCTYPE HTML>
<html>
	<head>
		<title><?= ucfirst($firstname) ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<link href="../css/bootstrap.min.css" rel='stylesheet' type='text/css' />
		<link href="../css/style.css" rel='stylesheet' type='text/css' />
		<link href="../css/font-awesome.css" rel="stylesheet"> 
		<link rel="stylesheet" href="../css/icon-font.min.css" type='text/css' />
		<script src="../js/Chart.js"></script>
		<link href="../css/animate.css" rel="stylesheet" type="text/css" media="all">
		<script src="../js/wow.min.js"></script>
		<script>
			new WOW().init();
		</script>
		<script src="../js/jquery-1.10.2.min.js"></script>
		<script src="../js/app.js"></script>
	</head> 
   
 <body class="sticky-header left-side-collapsed"  onload="initMap()">
    <section>
    
		<div class="left-side sticky-left-side">

			<div class="logo">
				<h1><a href="index.php">Reinox <span><?= ucfirst($type) ?></span></a></h1>
			</div>
			<div class="logo-icon text-center">
				<a href="index.php"><i class="fa fa-home"></i> </a>
			</div>
			<div class="left-side-inner">
				<ul class="nav nav-pills nav-stacked custom-nav">
					<li class="active">
						<a href="index.php">
							<i class="fa fa-dashboard"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<?php if ($type == 'admin') { ?>
					<li class="menu-list">
						<a href="#"><i class="fa fa-plus-square"></i>
						<span>Create</span></a>
						<ul class="sub-menu-list">
							<li><a href="create.php?account=technician">Technician</a> </li>
							<li><a href="create.php?account=admin">Admin</a></li>
						</ul>
					</li>
					<?php } ?>
					<li><a href="profile.php"><i class="fa fa-user"></i> <span>Profile</span></a></li>
					<li><a href="task.php"><i class="fa fa-tasks"></i> <span>Tasks</span></a></li>              
					<li class="menu-list"><a href="#"><i class="fa fa-envelope"></i> <span>MailBox</span></a>
						<ul class="sub-menu-list">
							<li><a href="inbox.html">Inbox</a> </li>
							<li><a href="compose-mail.html">Compose Mail</a></li>
						</ul>
					</li>
					<li><a href="settings.php"><i class="fa fa-cog"></i> <span>Logout</span></a></li>
					<li><a href="signout.php"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>
				</ul>
			</div>
		</div>
		
		<div class="main-content">
			<div class="header-section">
				<a class="toggle-btn menu-collapsed"><i class="fa fa-bars"></i></a>
				<div class="menu-right">
					<div class="user-panel-top">  	
						<div class="profile_details_left">
							<ul class="nofitications-dropdown">
								<?php if ($type == 'technician') { ?>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge blue">3</span></a>
									<ul class="dropdown-menu">
										<li>
											<div class="notification_header">
												<h3>You have 3 task to complete</h3>
											</div>
										</li>
										<li>
											<a href="#">
												<div class="user_img"><img src="../images/1.png" alt=""></div>
										   		<div class="notification_desc">
													<p>Lorem ipsum dolor sit amet</p>
													<p><span>1 hour ago</span></p>
												</div>
										  		<div class="clearfix"></div>	
										 	</a>
										</li>
										<div class="notification_bottom">
											<a href="#">See all notification</a>
										</div>
									</ul>	
								</li>	
								<?php } ?>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"></i><span class="badge blue1"><?= taskProperty('pending')[0] ?></span></a>
										<ul class="dropdown-menu">
											<li>
												<div class="notification_header">
													<?php if ($type == 'admin') { ?>
													<h3>You have <?= taskProperty('pending')[0] ?> task not assigned</h3>
													<?php } elseif ($type == 'technician') { ?>
													<!-- get all task that neds completion -->
													<?php } else{ ?>
													<!--  -->
													<?php } ?>
												</div>
											</li>
											<?php 
											$pendingTask = DB::query("SELECT * FROM task WHERE status = :status",[':status'=> 'pending']);
											if (!empty($pendingTask)) {
												foreach ($pendingTask as $pending) {
											?>
												<li><a href="#">
													<div class="task-info">
														<span class="task-desc"><?= $pending['title'] ?></span><span class="percentage">20%</span>
														<div class="clearfix"></div>	
													</div>
													<div class="progress progress-striped active">
														<div class="bar yellow" style="width:20%;"></div>
													</div>
												</a></li>
											<?php } } ?>
											<li>
												<div class="notification_bottom">
													<a href="task.php">See all pending task</a>
												</div> 
											</li>
										</ul>
								</li>		   							   		
								<div class="clearfix"></div>	
							</ul>
						</div>
						<div class="profile_details">		
							<ul>
								<li class="dropdown profile_details_drop">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<div class="profile_img">
											<span style="background:url('<?= $picture ? $picture : "../images/1.jpg" ?>" ') no-repeat center"> </span> 
											 <div class="user-name">
												<p><?= ucfirst($firstname) ?><span><?= ucfirst($type) ?></span></p>
											 </div>
											 <i class="fa fa-chevron-down"></i>
											 <i class="fa fa-chevron-up"></i>
											<div class="clearfix"></div>	
										</div>	
									</a>
									<ul class="dropdown-menu drp-mnu">
										<li> <a href="settings.php"><i class="fa fa-cog"></i> Settings</a> </li> 
										<li> <a href="profile.php"><i class="fa fa-user"></i>Profile</a> </li> 
										<li> <a href="../sign-out.php"><i class="fa fa-sign-out"></i> Logout</a> </li>
									</ul>
								</li>
								<div class="clearfix"> </div>
							</ul>
						</div>		
						<div class="social_icons">
							
						</div>            	
						<div class="clearfix"></div>
					</div>
				  </div>
			</div>
		