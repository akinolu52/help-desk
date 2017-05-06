<?php 
	require '../includes/loginHeader.php';
	isLoggedIn() ? : header('location: ../sign-in.php');

	if ($_POST) {
		
		if ($_POST['action'] === 'create task') {
			//($_POST);
			if(!isset($title, $categories, $description)) {
				$message = 'Please enter valid values';
			} elseif (strlen( $title) < 5 || strlen($description) < 10) {
				$message = 'Incorrect Length for title or description';
			} else {

				#handle the categories functionality
				$categoryStr = implode(",", $categories);

				#picture functionality to work on
				$directory = "../uploads/";
				
				if ($_FILES["picture"]["size"] > 0){
					$targetFile = $directory.basename($_FILES["picture"]["name"]);
					$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
					if ($imageFileType != "jpg" || $imageFileType != "png" || $imageFileType != "jpeg") {
						if (!move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFile)) {
							// DB::query("UPDATE account SET picture = :picture WHERE id = :id", []);
							$message = "Uploading Failed";
						} else {
							DB::query("INSERT INTO 
								task (userId, title, description, categories, picture) 
								VALUES (:userId, :title, :description, :categoryStr, :picture)",
							[':userId'=> $id, ':title'=> $title, ':description'=> $description, ':picture'=> $targetFile, ':categoryStr'=> $categoryStr]);
						}
					} else {
						$message = "Invalid Image format";
					}
				}else{
					DB::query("INSERT INTO task (userId, title, categories, description) 
						VALUES (:userId, :title, :categoryStr, :description)", 
					[':userId'=> $id, ':title'=> $title, ':categoryStr'=> $categoryStr, ':description'=> $description]);
				}	
				
				$message = "Your task was successfully created.";

				
			}
		} 

		if ($_POST['action'] === 'assign task') {
			
			extract($_POST); 

			if(!isset($taskId, $technicianId)) {
				$message = 'Please enter valid values';
			} else {

				DB::query("UPDATE task SET technicianId = :technicianId WHERE id = :taskId",
				[':technicianId'=> $technicianId, ':taskId'=> $taskId]);

				$message = "Task has been assigned!";
			}
		}

		if ($_POST['action'] === 'submit progress') {
			extract($_POST); 
			$start = date('Y-m-d');

			if(!isset($stop, $taskId)) {
				echo $message = 'Please enter valid values';
			} elseif($start == $stop) {
				echo $message = 'Start and Stop values must be different';
			} elseif (empty($stop)) {
				echo $message = 'Please enter valid stop value';
			} else {

				DB::query("UPDATE task SET start = :start, stop = :stop, status = :status WHERE id = :taskId",
				[':start'=> $start, ':stop'=> $stop, ':status'=> 'in progress', ':taskId'=> $taskId]);

				$message = "Task status has been changed!";
			}
		}
	}
?>
<link rel="stylesheet" type="text/css" href="../css/select2.css">
	<div id="page-wrapper">
		<div class="graphs">
			<!-- admin would view all or a single task and assign task to technicians -->
			<!-- they should be able to view based on pending, completed or work in progress - task  -->
			<?php if ($type == 'admin') { ?>	
				
				<div class="grid_3 grid_5">
					<!-- Show all task -->
					<!-- Show all pending task -->
					<!-- Show all work in progress task -->
					<!-- Show all completed task -->
					<h3>Task</h3>
						<div class="but_list">
							<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
								<ul id="myTab" class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active">
								  		<a href="#all" id="all-tab" role="tab" data-toggle="tab" aria-controls="all" aria-expanded="true">All</a>
								  	</li>
								  	<li role="presentation">
								  		<a href="#pending" role="tab" id="pending-tab" data-toggle="tab" aria-controls="pending">Pending</a>
								  	</li>
								  	<li role="presentation">
								  		<a href="#in-progress" role="tab" id="in-progress-tab" data-toggle="tab" aria-controls="in-progress">In Progress</a>
								  	</li>
								  	<li role="presentation">
								  		<a href="#completed" role="tab" id="completed-tab" data-toggle="tab" aria-controls="completed">Completed</a>
								  	</li>
								</ul>
								<!-- to view all task -->
								<div id="myTabContent" class="tab-content">
									<div role="tabpanel" class="tab-pane fade in active" id="all" aria-labelledby="all-tab">
								 		<div class="xs tabls">
								 			<div class="bs-example4" data-example-id="contextual-table">
									 			<?php
									 			$tasks = DB::query("SELECT * FROM task");
									 			//echo "<pre>";	#print_r($tasks);
									 			/*foreach ($tasks as $key) {
									 				$key['id'];
									 			}*/
									 			if (empty($tasks)) {
									 			  	echo "No result...";
									 			} else { ?>

									 			<table class="table table-striped">
									 				<thead>
									 					<tr class="warning">
									 				  		<th>#</th>
									 				  		<th>Image</th>
									 				  		<th>Title</th>
									 				  		<th>Status</th>
									 				  		<th>User - Full Name</th>
									 				  		<th>Technician - Full Name</th>
									 					</tr>
									 			  	</thead>
									 			  	<tbody>
									 			<?php
									 			$c = 0; 
									 			extract($tasks);
									 			/*echo "<pre>";*/
										 		foreach ($tasks as $task) {
										 			//echo $task['id'];
											 		$userWhoPost = getProfile($task['userId'], "user");
											 		/*echo $task['technicianId']."--<br>";
											 		print_r($userWhoPost);*/
											 		if ($task['technicianId'] == '0') {
										  				$technicianAssigned = "Not assigned";
							 			  			} else {
							 			  				$techAss = DB::query("SELECT firstname,lastname FROM account WHERE id = :id AND type = :type", [':id'=> $id, ':type'=> $type,])[0];
							 			  				$technicianAssigned =  $techAss['firstname']." ".$techAss['lastname'];
							 			  				/*$technicianAss = getProfile($task['technicianId'], "technician");*/
							 			  				/*$technicianAssigned =  $technicianAss['firstname']." ".$technicianAss['lastname'];*/	
											 		}
										 		?>
											<tr class="modal-grids" style="cursor: pointer;" title="view <?= $task['title'] ?> task" data-toggle="modal" data-target="#adminView<?= $task['id'] ?>" data-whatever="@mdo">
									 			<th scope="row"><?= ++$c ?></th>
									 			<td>
									 			<?php
									 			if ($task['picture']) { ?>
									 				<img src="<?= $task['picture']  ?>" width="70">
									 			<?php } else { ?>
									 					<i class="fa fa-tasks fa-3x" id="ico1"></i> 
									 			<?php } ?>
									 			</td>
									 			<td><?= $task['title'] ?></td>
									 			<td><?= $task['status'] ?></td>
									 			<td><?= ucfirst($userWhoPost['firstname']." ".$userWhoPost['lastname']) ?></td>
									 			<td><?= ucfirst($technicianAssigned) ?></td>
									 			</tr>
									 			<div class="modal fade" id="adminView<?= $task['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
									 				<div class="modal-dialog" role="document">
									 					<div class="modal-content">
									 						<div class="modal-header">
									 							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 							<h4 class="modal-title" id="exampleModalLabel">adminView<?= $task['id'] ?></h4>
									 						</div>
									 						<div class="modal-body">
									 							<form method="post" action="">
									 								<div class="form-group">
									 									<label for="recipient-name" class="control-label">Recipient:</label>
									 									<input type="text" class="form-control" id="recipient-name">
									 								</div>
									 								<div class="form-group">
									 									<label for="message-text" class="control-label">Message:</label>
									 									<textarea class="form-control" id="message-text"></textarea>
									 								</div>
									 							</form>
									 						</div>
									 						<div class="modal-footer">
									 							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									 							<button type="button" class="btn btn-primary">Send message</button>
									 						</div>
									 					</div>
									 				</div>
									 			</div>
									 		<?php } } ?>
									 	 	</tbody>
										</table>
						 			</div>
						 		</div>
						  	</div>

							<!-- view all the pending task -->
							<div role="tabpanel" class="tab-pane fade" id="pending" aria-labelledby="pending-tab">
								<div class="xs tabls">											
									<div class="bs-example4 modal-grids" data-example-id="contextual-table">
									<?php 
									$tasks = DB::query("SELECT * FROM task WHERE status = :status", [':status'=> 'pending']);
									extract($tasks);
									if (empty($tasks)) {
										echo "No result...";
									} else { ?>
									<table class="table table-striped">
										<thead>
											<tr class="warning">
											 	<th>#</th>
											  	<th>Image</th>
												<th>Title</th>
												<th>Category</th>
												<th>Description</th>
												<th>With</th>
											</tr>
									  	</thead>
									  	<tbody>
									 	<?php
									 	$c = 0;
										foreach ($tasks as $task) {
										$userWhoPost = getProfile($task['userId'], "user");
							 			?>
									 	<tr>
											<th scope="row"><?= ++$c ?></th>
					 						<td>
								 			<?php
									 		if ($task['picture']) { ?>
									 			<img src="<?= $task['picture']  ?>" width="70">
									 		<?php } else { ?>
											 	<i class="fa fa-tasks fa-3x" id="ico1"></i> 
									 		<?php } ?>
									 		</td>
											<td><?= $task['title'] ?></td>
					 						<td><?= $task['categories'] ?></td>
									 		<td><?= $task['description'] ?></td>
											<td>
									 		<?php #ucfirst($technicianAssigned)
									 		if ($task['technicianId'] == '0') { ?>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#task<?= $task['id'] ?>" data-whatever="@mdo">Asssign</button>
										 	<div class="modal fade" id="task<?= $task['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
					 			  			<div class="modal-dialog" role="document">
						 		  				<div class="modal-content">
											 	<div class="modal-header">
											 		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  						<h4 class="modal-title" id="exampleModalLabel">Assign Technician to -- <?= $task['title'] ?></h4>
											 	</div>
											 	<div class="modal-body">
											 	<form method="post" action="" role="form" class="form-horizontal">
											 		<div class="form-group">
											 		<div class="control-group">
														<label for="input-tags">Tag:</label>
														<input type="text" class="form-control1" id="input-tags" disabled value="<?= $task['categories'] ?>">
														<input type="hidden" value="<?= $task['id'] ?>" name="taskId">
													</div>
											 		</div>
											 		<div class="form-group">
											 		<label for="message-text" class="control-label">Technician list:</label>
											 		<select id="selector1" required class="form-control1" name="technicianId">
											 		<?php 
											 		$technicians = DB::query("SELECT * FROM account WHERE type = :type AND skill = :skill", [':type'=> 'technician', ':skill'=> $task['categories']]);
											 		if (empty($technicians)) { ?>
											 			<option value="">--No competent technician--</option>
											 		<?php } else {
											 			foreach ($technicians as $technician) { ?>
											 			<option value="<?= $technician['id'] ?>"><?= $technician['firstname']." ".$technician['lastname'] ?></option>
											 		<?php } } ?>
											 		</select>
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
										</div>
										<?php } else {
										$techAss = DB::query("SELECT firstname,lastname FROM account WHERE id = :id AND type = :type", [':id'=> $id, ':type'=> $type,])[0];
										print "Awaiting approval <br>".$techAss['firstname']." ".$techAss['lastname'];
										} ?>
												</td>
											</tr>
										<?php } } ?>
										</tbody>
									</table>
									</div>
								</div>
							</div>

									<!-- to view all work in progress task -->
									<div role="tabpanel" class="tab-pane fade" id="in-progress" aria-labelledby="in-progress-tab">
										<div class="xs tabls">
											<div class="bs-example4" data-example-id="contextual-table">
											<?php 
											$tasks = DB::query("SELECT * FROM task WHERE status = :status", [':status'=> 'in progress']);
											extract($tasks); 
											if (empty($tasks) ) {
												echo "No result...";
											} else { ?>
											<table class="table table-striped">
												<thead>
													<tr class="warning">
												  		<th>#</th>
														<th>Image</th>
														<th>Title</th>
														<th>Category</th>
														<th>Description</th>
														<th>With</th>
													</tr>
												</thead>
												<tbody>
									 			<?php
										 			foreach ($tasks as $task) {
											 		$userWhoPost = getProfile($task['userId'], "user");
											 		if ($task['technicianId'] == '0') {
											 			$technicianAssigned = "Not assigned";
											 		} else {
											 		  	$technicianAss = getProfile($task['technicianId'], "technician");
											 		  	$technicianAssigned =  $technicianAss['firstname']." ".$technicianAss['lastname'];	
											 		}
										 		?>
									 				<tr>
									 					<th scope="row"><?= ++$c ?></th>
									 					<td>
									 					<?php
									 						if ($task['picture']) { ?>
									 						  	<img src="<?= $task['picture']  ?>" width="70">
									 					<?php } else { ?>
									 						 	<i class="fa fa-tasks fa-3x" id="ico1"></i> 
									 					<?php } ?>
									 					</td>
									 					<td><?= $task['title'] ?></td>
									 					<!-- <td><?= $task['description'] ?></td> -->
									 					<td><?= $task['status'] ?></td>
									 					<td><?= ucfirst($userWhoPost['firstname']." ".$userWhoPost['lastname']) ?></td>
									 					<td><?= ucfirst($technicianAssigned) ?></td>
									 				</tr>
									 			<?php } } ?>
									 			</tbody>
											</table>
										</div>
										</div>
						  			</div>	


								  		<!-- to view all completed task -->
								  		<div role="tabpanel" class="tab-pane fade" id="completed" aria-labelledby="completed-tab">
								  			<div class="xs tabls">
												<div class="bs-example4" data-example-id="contextual-table">
												<?php 
												$tasks = DB::query("SELECT * FROM task WHERE status = :status", [':status'=> 'completed']);
												extract($tasks);
												if (empty($tasks)) {
													echo "No result...";
												} else { ?>
												<table class="table table-striped">
													<thead>
														<tr class="warning">
														 	<th>#</th>
														  	<th>Image</th>
														  	<th>Title</th>
														  	<th>Category</th>
														  	<th>Description</th>
														  	<th>With</th>
														</tr>
												  	</thead>
												  	<tbody>
									 			  		<?php
										 			  	foreach ($tasks as $task) {
											 				$userWhoPost = getProfile($task['userId'], "user");
											 				if ($task['technicianId'] == '0') {
											 			  		$technicianAssigned = "Not assigned";
											 			  	} else {
											 			  		$technicianAss = getProfile($task['technicianId'], "technician");
											 			  		$technicianAssigned =  $technicianAss['firstname']." "
											 					  	.$technicianAss['lastname'];	
											 			  	}
										 			  		?>
									 					<tr>
									 						<th scope="row"><?= ++$c ?></th>
									 						<td>
									 						<?php
									 							if ($task['picture']) { ?>
									 							  	<img src="<?= $task['picture']  ?>" width="70">
									 						<?php } else { ?>
									 							 	<i class="fa fa-tasks fa-3x" id="ico1"></i> 
									 						<?php } ?>
									 						</td>
									 						<td><?= $task['title'] ?></td>
									 						<!-- <td><?= $task['description'] ?></td> -->
									 						<td><?= $task['status'] ?></td>
									 						<td><?= ucfirst($userWhoPost['firstname']." ".$userWhoPost['lastname']) ?></td>
									 						<td><?= ucfirst($technicianAssigned) ?></td>
									 					</tr>
									 					<?php } } ?>
									 			  	</tbody>
												</table>												</div>
											</div>
								  		</div>
									</div>
					   		</div>
					   	</div>
					
				</div>

			<!-- technicians would view tasks assigned to them and then click on it to show they are working on it or not they can also accept or reject the task -->
			<?php } elseif ($type == 'technician') { ?>
				
				<div class="grid_3 grid_5">
					<h3>Task</h3>
					<div class="but_list">
							<div class="xs tabls">
								<div class="bs-example4" data-example-id="contextual-table">
								  	<?php 
									$tasks = DB::query("SELECT * FROM task WHERE technicianId = :technicianId", [':technicianId'=> $id]);
									extract($tasks);
									if (empty($tasks)) {
										echo "No result...";
									} else { ?>
									<table class="table table-striped">
										<thead>
											<tr class="warning">
											  	<th>#</th>
											  	<th>Image</th>
											  	<th>Title</th>
											  	<th>Category</th>
											  	<th>Description</th>
											  	<th>Action</th>
											</tr>
									  	</thead>
									  	<tbody>
						 			  		<?php
						 			  		$c = 0;
							 			  	foreach ($tasks as $task) {
								 				$userWhoPost = getProfile($task['userId'], "user");
								 				if ($task['technicianId'] == '0') {
								 			  		$technicianAssigned = "Not assigned";
								 			  	} else {
								 			  		$technicianAss = getProfile($task['technicianId'], "technician");
								 			  		$technicianAssigned =  $technicianAss['firstname']." "
								 					  	.$technicianAss['lastname'];	
								 			  	}
							 			  		?>
						 					<tr style="cursor: pointer;" id="<?= ++$c ?>">
						 						<th scope="row"><?= $c ?></th>
						 						<td>
						 						<?php
						 							if ($task['picture']) { ?>
						 							  	<img src="<?= $task['picture']  ?>" width="70">
						 						<?php } else { ?>
						 							 	<i class="fa fa-tasks fa-3x" id="ico1"></i> 
						 						<?php } ?>
						 						</td>
						 						<td><?= $task['title'] ?></td>
						 						<td><?= $task['categories'] ?></td>
						 						<td><?= $task['description'] ?></td>
						 						<td>
						 							<?php 
						 							if ($task['status'] == 'pending') { ?>
						 								<i class="fa fa-check" class="modal-grids" title="change action" data-toggle="modal" data-target="#change<?= $task['id'] ?>" data-whatever="@mdo" title="Accept"></i>
						 								<i class="fa fa-times" onclick="var ret = confirm('Are you sure you want to reject this task?'); if (ret == true) { alert('removing...') } else { alert('we would remove') }" value="reject"></i>
						 							<?php } elseif ($task['status'] == 'in progress') {
						 								echo 'To Complete: <b>'.$task['stop'].'</b>';
						 							} else {
						 								echo 'Completed';
						 							}
						 							
						 							?>
						 							
						 						</td>
						 					</tr>
						 					<div class="modal fade" id="change<?= $task['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
						 						<div class="modal-dialog" role="document">
						 							<div class="modal-content">
						 								<div class="modal-header">
						 									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						 									<h4 class="modal-title" id="exampleModalLabel">Set Time for <?= $task['title'] ?></h4>
						 								</div>
						 								<form method="post" role="form">
							 								<div class="modal-body">
							 									<div class="form-group">
							 										<label for="recipient-name" class="control-label"><h4>Status:</h4></label>
							 										<input type="text" name="status" value="in progress" readonly class="form-control">
							 									</div>
							 									<div class="form-group"><br>
							 										<label for="message-text" class="control-label"><h4>Time to complete:</h4></label><br>
							 										<label for="message-text" class="control-label">Start Time:</label>
							 										<input type="text" value="<?= date('m-d-Y') ?>"  class="form-control" readonly style="width: 50%">
							 										<label for="message-text" class="control-label">Stop Time:</label>
							 										<input type="date" name="stop" style="width: 50%" class="form-control">
							 										<input type="hidden" name="taskId" value="<?= $task['id'] ?>">
							 									</div>
							 								</div>
							 								<div class="modal-footer">
							 									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							 									<button class="btn-success btn" name="action" 
							 									value="submit progress">Submit</button>
							 								</div>
						 								</form>
						 							</div>
						 						</div>
						 					</div>
						 					<?php } } ?>
						 			  	</tbody>
									</table>													
								  	</tbody>
								</table>
								</div>
							</div>
			   		</div>
			  	</div>

			<!-- USER -->
			<!-- users would create task and view task status and view all or view one my submitted task also -->	
			<?php } else { ?>

					<div class="grid_3 grid_5">
						<h3>Task</h3>
						<div class="but_list">
							<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
								<ul id="myTab" class="nav nav-tabs" role="tablist">
							  		<li role="presentation" class="active">
							  			<!-- to create a task -->
							  			<a href="#create" id="create-tab" role="tab" data-toggle="tab" aria-controls="create" aria-expanded="true">Create</a>
							  		</li>
							  		<li role="presentation">
							  			<!-- to view all the task  -->
							  			<a href="#view" role="tab" id="view-tab" data-toggle="tab" aria-controls="view">View</a>
							  		</li>
							  		</li>
								</ul>
								<div id="myTabContent" class="tab-content">
									<!-- create a task -->
							  		<div role="tabpanel" class="tab-pane fade in active" id="create" aria-labelledby="create-tab">
										<form method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
											<!-- Task Title -->
											<div class="form-group">
												<label class="col-md-2 control-label"></label>
												<div class="col-md-10">
													<div class="input-group in-grp1" id="div1">							
														<span class="input-group-addon ">
															<i class="fa fa-user" id="ico1"></i> 
															<i class="fa fa-fw fa-spin fa-spinner" id="processing1" style="display: none"></i>
														</span>
														<input type="text" class="input-lg form-control1" name="title" placeholder="Title" required/>
														<span id="err1"></span>
													</div>
												</div>
												<div class="clearfix"> </div>
											</div>
											<!-- category -->
											<div class="form-group">
												<label class="col-md-2 control-label"></label>
												<div class="col-md-10">
													<div class="input-group in-grp1 " id="div5">
														<span class="input-group-addon">
															<i class="fa fa-" id="ico5"></i>
															<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
														</span>
														<select name="categories[]" id="selector1" class="form-control1 js-example-basic-multiple" multiple="multiple" title="Select Category" required>
															<option value="System troubleshooting">System troubleshooting</option>
															<option value="System Design">System Design</option>
															<option value="General Hardware">General Hardware</option>
															<option value="System Casing">System Casing</option>
															<option value="OS Installation">OS Installation</option>
															<option value="Software Installation">Software Installation</option>
															<option value="Speaker Repair">Speaker Repair</option>
															<option value="Screen Repair">Screen Repair</option>
															<option value="Charger Repair">Charger Repair</option>
															<option value="Button Repair">Button Repair</option>
															<option value="Bios Setup">Bios Setup</option>
															<option value="System Recovery">System Recovery</option>
														</select>
														<span id="err5"></span>
													</div>
												</div>
												<div class="clearfix"> </div>
											</div>
											<!-- description -->
											<div class="form-group">
												<label class="col-md-2 control-label"></label>
												<div class="col-md-10">
													<div class="input-group in-grp1" id="div5">							
														<!-- <span class="input-group-addon">
															<i class="fa fa-upload" id="ico5"></i>
															<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
														</span> -->
														<textarea name="description" id="txtarea1" style="height:100px" placeholder="description" class="form-control1"></textarea>
														<span id="err5"></span>
													</div>
												</div>
												<div class="clearfix"> </div>
											</div>
											<!-- image -->
											<div class="form-group">
												<label class="col-md-2 control-label"></label>
												<div class="col-md-10">
													<div class="input-group in-grp1" id="div5">							
														<span class="input-group-addon">
															<i class="fa fa-upload" id="ico5"></i>
															<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
														</span>
														<input type="file" class="input-lg form-control1" name="picture"/>
														<span id="err5"></span>
													</div>
												</div>
												<div class="clearfix"> </div>
											</div>
											<div class="row" style="text-align: center;">
												<div class="col-sm-8 col-sm-offset-2">
													<button class="btn-success btn" name="action" 
													value="create task">Submit</button>
												</div>
												<div class="clearfix"> </div>
											</div>
										</form>
							  		</div>
							  		<!-- view all the task or a single task -->
							  		<div role="tabpanel" class="tab-pane fade" id="view" aria-labelledby="view-tab">
										
										<div class="xs tabls">
											<div class="bs-example4" data-example-id="contextual-table">
											  	<?php 
												$tasks = DB::query("SELECT * FROM task WHERE userId = :userId", [':userId'=> $id]);
												extract($tasks);
												if (empty($tasks)) {
													echo "No result...";
												} else { ?>
												<table class="table table-striped">
													<thead>
														<tr class="warning">
														  	<th>#</th>
														  	<th>Image</th>
														  	<th>Title</th>
														  	<th>Category</th>
														  	<th>Description</th>
														  	<th>Status</th>
														</tr>
												  	</thead>
												  	<tbody>
									 			  		<?php
									 			  		$c = 0;
										 			  	foreach ($tasks as $task) {
											 				$userWhoPost = getProfile($task['userId'], "user");
											 				if ($task['technicianId'] == '0') {
											 			  		$technicianAssigned = "Not assigned";
											 			  	} else {
											 			  		$technicianAss = getProfile($task['technicianId'], "technician");
											 			  		$technicianAssigned =  $technicianAss['firstname']." "
											 					  	.$technicianAss['lastname'];	
											 			  	}
										 			  		?>
									 					<tr class="modal-grids" style="cursor: pointer;">
									 						<th scope="row"><?= ++$c ?></th>
									 						<td>
									 						<?php
									 							if ($task['picture']) { ?>
									 							  	<img src="<?= $task['picture']  ?>" width="70">
									 						<?php } else { ?>
									 							 	<i class="fa fa-tasks fa-3x" id="ico1"></i> 
									 						<?php } ?>
									 						</td>
									 						<td><?= $task['title'] ?></td>
									 						<!-- <td><?= $task['description'] ?></td> -->
									 						<td><?= $task['status'] ?></td>
									 						<td><?= ucfirst($userWhoPost['firstname']." ".$userWhoPost['lastname']) ?></td>
									 						<td><?= ucfirst($technicianAssigned) ?></td>
									 					</tr>
									 					<?php } } ?>
									 			  	</tbody>
												</table>													
											  	</tbody>
											</table>
											</div>
										</div>
									</div>
								</div>
				   			</div>
				   		</div>
				  	</div>

			<?php } ?>
			
		</div>
	</div>
<?php require '../includes/loginFooter.php'; ?>
<script type="text/javascript" src="../js/select2.full.js"></script>
<script type="text/javascript">
	$(".js-example-basic-multiple").select2();
	$('#select-beast-disabled').selectize({
		create: true,
		sortField: {field: 'text'}
	});
	$('#input-tags').selectize({
		persist: false,
		createOnBlur: true,
		create: true
	});
</script>