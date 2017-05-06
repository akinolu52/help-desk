<?php 
	require '../includes/loginHeader.php';
	isLoggedIn() ? : header('location: ../sign-in.php');
	if (isset($_POST)) { 
		 
		if ($_POST['action'] === 'register admin') {
			extract($_POST);
			if(!isset($firstname, $lastname, $mobile, $location, $bio)) {
				$message = 'Please enter valid values';
			} elseif (strlen($firstname) < 4 || strlen($lastname) < 4 || strlen($password) < 4) {
				$message = 'Incorrect Length for firstname or Lastname or Password';
			} else {

				$chk = DB::query("SELECT id FROM account WHERE id = :id",[':id'=> $id]);

				if (empty($chk)) {
					$message = "This admin does not already";
				} else { 
					
					#picture functionality to work on
					$directory = "../uploads/";
					extract($_FILES)[0];
					
					if (!empty($_FILES["picture"]["name"]) || ($_FILES["picture"]["size"] > 0) ) {
						if (getimagesize($_FILES["picture"]["tmp_name"])){
							$targetFile = $directory.basename($_FILES["picture"]["name"]);
							$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
							if ($imageFileType != "jpg" || $imageFileType != "png" || $imageFileType != "jpeg") {
								if (!move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFile)) {
									$message = "Uploading Failed";
								} else {
									if (isset($skill)) {
									DB::query("INSERT INTO 
									account (firstname, lastname, skill, mobile, location, bio, picture) 
									VALUES (:firstname, :lastname, :skill, :mobile, :location, :bio, :picture)",
									[':firstname'=> $firstname, ':lastname'=> $lastname, ':skill'=> $skill, ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio, ':picture'=> $targetFile]);
									} else {
									DB::query("INSERT INTO 
									account (firstname, lastname, email, password, mobile, location, bio, picture) 
									VALUES (:type, :firstname, :lastname, :email, :password, :mobile, :location, :bio, :picture)",
									[':type'=> $type, ':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':password'=> sha1($password), ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio, ':picture'=> $targetFile]);
									}
								}
							} else {
								$message = "Invalid Image format";
							}
						}
					} else {
						if (isset($skill)) { 

						DB::query("INSERT INTO 
						account (type, firstname, lastname, email, password, skill, mobile, location, bio) 
						VALUES (:type, :firstname, :lastname, :email, :password, :skill, :mobile, :location, :bio)",
						[':type'=> $type, ':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':password'=> sha1($password), ':skill'=> $skill, ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio]);

						} else {

						DB::query("INSERT INTO 
						account (type, firstname, lastname, email, password, mobile, location, bio) 
						VALUES (:type, :firstname, :lastname, :email, :password, :mobile, :location, :bio)",
						[':type'=> $type, ':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':password'=> sha1($password), ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio]);

						}
					  }

					}
			}
		}
	}
?>
	<div id="page-wrapper">
		<div class="graphs">
			<div class="grid_3 grid_5">
			<?php
				if ($type == 'admin') { ?>
				<h4>View</h4>
				<div class="but_list">
					<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
						<ul id="myTab" class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
							<a href="#admin" id="admin-tab" role="tab" data-toggle="tab" aria-controls="admin" aria-expanded="true">Administrators</a>
							</li>
						  	<li role="presentation">
						  		<a href="#tech" role="tab" id="tech-tab" data-toggle="tab" aria-controls="tech">Technicians</a>
						  	</li>
						  	<li role="presentation">
						  		<a href="#user" role="tab" id="user-tab" data-toggle="tab" aria-controls="user">Users</a>
						  	</li>
						</ul>

						<div id="myTabContent" class="tab-content">
							<!-- to view all admin you can edit your account -->
							<div role="tabpanel" class="tab-pane fade in active" id="admin" aria-labelledby="admin-tab">
								<div class="xs tabls">
									<div class="bs-example4" data-example-id="contextual-table">
									<?php 
									$admins =DB::query("SELECT * FROM account WHERE type = :type",[':type'=> 'admin']);
									if (empty($admins)) {
									  	echo "No result...";
									} else { ?>
										<table class="table table-striped" id="adminList">
										 	<thead>
												<tr>
													<th>#</th>
												  	<th>Full Name</th>
													<th>Email</th>
												  	<th>Mobile</th>
												  	<th>Action</th>
												</tr>
										  	</thead>
										  	<tbody>
											<?php
											 	$c = 0;
												foreach ($admins as $admin) {
												$fullName = $admin['firstname']." ".$admin['lastname'];
												$adminId = $admin['id'];
											?>
												<tr class="admin_<?= $adminId ?>">
													<th scope="row"><?= ++$c; ?></th>
												  	<td><?= $fullName ?></td>
													<td>
													<?php $admin['email'] ? print $admin['email'] : print ' - ' ; ?>
													</td>
													<td>
													<?php $admin['mobile'] ? print $admin['mobile'] : print ' - ' ; ?>
													</td>
													<td>
													<?php 
													if ($adminId == $id) { ?>
													<i class="fa fa-edit fa-1x" data-target="#editAdmin<?= $admin['id'] ?>" data-toggle="modal" style="cursor: pointer;"></i>
													<?php } else { ?> 
													<i class="fa fa-times fa-1x" 
													onclick="actionDelete('admin', <?= $adminId ?>)" id="ico1" style="cursor: pointer;"></i>
													<?php } ?>
													</td>
												</tr>
										 	<div class="modal fade" id="editAdmin<?= $adminId ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
					 			  			<div class="modal-dialog" role="document">
						 		  				<div class="modal-content">
											 	<div class="modal-header">
											 		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  						<h4 class="modal-title">Edit Profile</h4>
											 	</div>
											 	<div class="modal-body">
											 	<form method="post" action="" role="form" class="form-horizontal form-group">
											 	<input type="hidden" name="id" value="<?= $adminId ?>">
											 		<div class="form-group">
													 	<div class="control-group">
															<label for="input-tags">Firstname:</label>
															<input type="text" class="input-lg form-control1" name="firstname" placeholder="First Name" required/>
														</div>
													</div>
											 		<div class="form-group">
												 		<label for="message-text" class="control-label">Lastname:</label>
												 		<input type="text" class="input-lg form-control1" name="lastname" placeholder="Last Name" required/>
											 		</div>
											 		<div class="form-group">
												 		<label for="message-text" class="control-label">Phone number:</label>
												 		<input type="tel" class="input-lg form-control1" name="mobile" placeholder="Mobile Number"/>
											 		</div>
											 		<div class="form-group">
												 		<label for="message-text" class="control-label">Location:</label>
												 		<select name="location" id="selector1" class="form-control1" name="location" title="Select Location">
												 			<option value="VI Lagos">VI, Lagos</option>
												 			<option value="Ajah Lagos">Ajah, Lagos</option>
												 			<option value="Magodo Lagos">Magodo, Lagos</option>
												 			<option value="Ikeja Lagos">Ikeja, Lagos</option>
												 		</select>
											 		</div> 
											 		<div class="form-group">
												 		<label for="message-text" class="control-label">Bio:</label>
												 		<textarea name="bio" cols="50" rows="6" placeholder="Bio" class="form-control1"></textarea>s
											 		</div>
											 		<div class="form-group">
												 		<label for="message-text" class="control-label">Picure:</label>
												 		<input type="file" class="input-lg form-control1" name="picture"/>
											 		</div> 
											 		<div class="modal-footer">
											 		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											 		<!-- <button type="button" class="btn btn-primary" value="assign task" name="action">Assign</button>-->
 											 		<button class="btn-success btn" name="action" 
													value="assign task">Submit</button>
											 		</div>
											 	</div>
											 	</form>
											 	</div>
											</div>
									<?php } } ?>
											</tbody>
										</table>
									</div>
						 		</div>
						  	</div>		

							<!-- view all the technicians and delete -->
							<div role="tabpanel" class="tab-pane fade" id="tech" aria-labelledby="tech-tab">
								<div class="xs tabls">											
									<div class="bs-example4" data-example-id="contextual-table">
									<?php 
									$technicians =DB::query("SELECT * FROM account WHERE type = :type",[':type'=> 'technician']);
									if (empty($technicians)) {
									  	echo "No result...";
									} else { ?>
										<table class="table table-striped">
										 	<thead>
												<tr>
													<th>#</th>
												  	<th>Full Name</th>
													<th>Email</th>
												  	<th>Mobile</th>
												  	<th>Action</th>
												</tr>
										  	</thead>
										  	<tbody>
											<?php
											 	$c = 0;
												foreach ($technicians as $technician) {
												$fullName = $technician['firstname']." ".$technician['lastname'];
												$techId = $technician['id'];
											?>
												<tr>
													<th scope="row"><?= ++$c; ?></th>
													<td><?= $fullName ?></td>
													<td><?= $technician['email'] ?></td>
													<td><?= $technician['mobile'] ?></td>
													<td>
													
													<i class="fa fa-times fa-1x" id="ico1" style="cursor: pointer;" onclick="actionDelete('technician', <?= $techId ?>)"></i>
													</td>
												</tr>
									<?php } } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<!-- to view all users and delete -->
							<div role="tabpanel" class="tab-pane fade" id="user" aria-labelledby="user-tab">
								<div class="xs tabls">
									<div class="bs-example4" data-example-id="contextual-table">
									<?php 
									$users =DB::query("SELECT * FROM account WHERE type = :type",[':type'=> 'user']);
									if (empty($users)) {
									  	echo "No result...";
									} else { ?>
										<table class="table table-striped">
										 	<thead>
												<tr>
													<th>#</th>
												  	<th>Full Name</th>
													<th>Email</th>
												  	<th>Mobile</th>
												  	<th>Action</th>
												</tr>
										  	</thead>
										  	<tbody>
											<?php
											 	$c = 0;
												foreach ($users as $user) {
												$fullName = $user['firstname']." ".$user['lastname'];
												$userId = $user['id'];	
											?>
												<tr>
													<th scope="row"><?= ++$c; ?></th>
												  	<td><?= $fullName ?></td>
													<td><?= $user['email'] ?></td>
													<td><?= $user['mobile'] ?></td>
													<td>
													<i class="fa fa-times fa-1x" style="cursor: pointer;" onclick="actionDelete('user', <?= $userId ?>)" id="ico1"></i>
													</td>
												</tr>
									<?php } } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>	
					</div>
				</div>
				<?php } elseif ($type == 'technician') { ?>
					# code...
				<?php } else { ?>
				<h4>Profile</h4>
				<div class="but_list">
					<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
						<ul id="myTab" class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
							<a href="#admin" id="admin-tab" role="tab" data-toggle="tab" aria-controls="admin" aria-expanded="true">About</a>
							</li>
						</ul>

						<div id="myTabContent" class="tab-content">
							<!-- to view and can edit your account -->
							<div role="tabpanel" class="tab-pane fade in active" id="admin" aria-labelledby="admin-tab">
								<div class="xs tabls">
									<div class="bs-example4" data-example-id="contextual-table">
									<?php 
									$userr =DB::query("SELECT * FROM account WHERE id = :id",[':id'=> $id]);
									if (empty($userr)) {
									  	echo "No result...";
									} else { ?>
										<table class="table table-striped" id="adminList">
										 	<thead>
												<tr>
													<th>#</th>
												  	<th>Full -Name</th>
													<th>Email</th>
												  	<th>Mobile</th>
												  	<th>Action</th>
												</tr>
										  	</thead>
										  	<tbody>
											<?php
											 	$c = 0;
												foreach ($userr as $user) {
												$fullName = $user['firstname']." ".$user['lastname'];
												$userId = $user['id'];
											?>
												<tr class="admin_<?= $adminId ?>">
													<th scope="row"><?= ++$c; ?></th>
												  	<td><?= $fullName ?></td>
													<td>
													<?php $user['email'] ? print $user['email'] : print ' - ' ; ?>
													</td>
													<td>
													<?php $user['mobile'] ? print $user['mobile'] : print ' - ' ; ?>
													</td>
													<td>
													<i class="fa fa-edit fa-1x" id="ico1" style="cursor: pointer;"></i>
													<i class="fa fa-times fa-1x" 
													onclick="actionDelete('user', <?= $userId ?>)" id="ico1" style="cursor: pointer;"></i>
													</td>
												</tr>
									<?php } } ?>
											</tbody>
										</table>
									</div>
						 		</div>
						  	</div>		

							<!-- view all the technicians and delete -->
							<div role="tabpanel" class="tab-pane fade" id="tech" aria-labelledby="tech-tab">
								<div class="xs tabls">											
									<div class="bs-example4" data-example-id="contextual-table">
									<?php 
									$technicians =DB::query("SELECT * FROM account WHERE type = :type",[':type'=> 'technician']);
									if (empty($technicians)) {
									  	echo "No result...";
									} else { ?>
										<table class="table table-striped">
										 	<thead>
												<tr>
													<th>#</th>
												  	<th>Full Name</th>
													<th>Email</th>
												  	<th>Mobile</th>
												  	<th>Action</th>
												</tr>
										  	</thead>
										  	<tbody>
											<?php
											 	$c = 0;
												foreach ($technicians as $technician) {
												$fullName = $technician['firstname']." ".$technician['lastname'];
												$techId = $technician['id'];
											?>
												<tr>
													<th scope="row"><?= ++$c; ?></th>
													<td><?= $fullName ?></td>
													<td><?= $technician['email'] ?></td>
													<td><?= $technician['mobile'] ?></td>
													<td>
													
													<i class="fa fa-times fa-1x" id="ico1" style="cursor: pointer;" onclick="actionDelete('technician', <?= $techId ?>)"></i>
													</td>
												</tr>
									<?php } } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<!-- to view all users and delete -->
							<div role="tabpanel" class="tab-pane fade" id="user" aria-labelledby="user-tab">
								<div class="xs tabls">
									<div class="bs-example4" data-example-id="contextual-table">
									<?php 
									$users =DB::query("SELECT * FROM account WHERE type = :type",[':type'=> 'user']);
									if (empty($users)) {
									  	echo "No result...";
									} else { ?>
										<table class="table table-striped">
										 	<thead>
												<tr>
													<th>#</th>
												  	<th>Full Name</th>
													<th>Email</th>
												  	<th>Mobile</th>
												  	<th>Action</th>
												</tr>
										  	</thead>
										  	<tbody>
											<?php
											 	$c = 0;
												foreach ($users as $user) {
												$fullName = $user['firstname']." ".$user['lastname'];
												$userId = $user['id'];	
											?>
												<tr>
													<th scope="row"><?= ++$c; ?></th>
												  	<td><?= $fullName ?></td>
													<td><?= $user['email'] ?></td>
													<td><?= $user['mobile'] ?></td>
													<td>
													<i class="fa fa-times fa-1x" style="cursor: pointer;" onclick="actionDelete('user', <?= $userId ?>)" id="ico1"></i>
													</td>
												</tr>
									<?php } } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>	
					</div>
				</div>
				<?php } ?>
			
				
					<!-- show all admin -->
					<!-- show all technician so that you can delete -->
					<!-- show all user so that you can delete -->
					
						
					   	
					
			</div>
		</div>
	</div>
<?php require '../includes/loginFooter.php'; ?>