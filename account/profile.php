<?php 

	require '../includes/loginHeader.php'; 
	isLoggedIn() ? : header('location: ../sign-in.php');

?>
	<div id="page-wrapper">
		<div class="graphs">
			<div class="col_3">
				<div class="col-md-12 widget widget1">
					<div class="r3_counter_box">
						<i class="fa"><img src="<?= $picture ? $picture : "../images/1.jpg" ?>" style="width:80px" class=""></i>
						<div class="stats">
						  <h5><?= ucfirst($type) ?></h5>
						  <div class="grow" style="margin-left: 170px">
							<p>Role</p>
						  </div>
						</div>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>

			<div class=" switches">
			   	<div class="col-md-6 email-list1">
			   		<h3>Personal Information</h3>
			   		<ul class="collection">
			   			<li class="collection-item avatar email-unread">
			   				<h4><i class="fa fa-user margin-5"></i> Full Name</h4>
			   				<p><?= ucfirst($firstname." ".$lastname) ?></p>
			   			  	<div class="clearfix"> </div>
			   			</li>
			   			<li class="collection-item avatar email-unread">
			   				<h4><i class="fa fa-mobile-phone margin-5"></i> Mobile</h4>
			   				<p>+(234) <?= $mobile ?> </p>
			   			  	<div class="clearfix"> </div>
			   			</li>
			   			<li class="collection-item avatar email-unread">
			   				<h4><i class="fa fa-envelope-o margin-5"></i> Email</h4>
			   				<p><?= ucfirst($email) ?> </p>
			   			  	<div class="clearfix"> </div>
			   			</li>
			   			<li class="collection-item avatar email-unread">
			   				<h4><i class="fa fa-location-arrow margin-5"></i> Location</h4>
			   				<p><?= ucfirst($location) ?> </p>
			   			  	<div class="clearfix"> </div>
			   			</li>
			   		</ul>
			   	</div>
			   	<div class="col-md-6 inbox_right">
			   		<h3>Biography</h3>
			   		<ul class="collection">
			   			<p>
			   				<?= $bio ?> 
			   			</p>
			   		  	<div class="clearfix"> </div>
			   		</ul>
			   		<?php if ($type == 'technician') { ?>
			   		   	<div class=" switches">
			   			   	<div class="col-md-12 email-list1">
			   			   		<h3>Skills </h3>
			   			   		<ul class="collection">
			   			   			<li class="collection-item">
			   				   			<div class="bar">
			   			   					<p><?= $skill ?></p>
			   			   				</div>
			   			   				<div class="progress progress-striped active">
			   			   					<div class="bar yellow" style="width:90%;"></div>
			   			   				</div>
			   			   			</li>
			   			   		</ul>
			   			   	</div>
			   			   <div class="clearfix"> </div>
			   		   	</div>
			   		<?php } ?>
			   	</div>
			   <div class="clearfix"> </div>
		   	</div>
		   	
		</div>			
<?php require '../includes/loginFooter.php'; ?>