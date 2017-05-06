<?php 
	require '../includes/loginHeader.php'; 
	isLoggedIn() ? : header('location: ../sign-in.php');
?>
	<div id="page-wrapper">
		<div class="graphs">
			<div class="col_3">
				<div class="col-md-3 widget widget1">
					<div class="r3_counter_box">
						<?php if ($type == 'admin') { ?>
							<i class="fa fa-user greenColor"></i>
							<div class="stats">
								<h5><?= columnCount('account', 'admin')[0] ?></h5>
							  	<div class="grow grow1">
									<p>Administrator</p>
							  	</div>
							</div>
						<?php } elseif ($type == 'technician') { ?>
							<i class="fa fa-exchange"></i>
							<div class="stats">
								<h5><?= taskPropertyClause('pending', $id)[0] ?></h5>
							  	<div class="grow grow1">
									<p>Pending</p>
							  	</div>
							</div>
						<?php } else { ?>
							<i class="fa fa-users"></i>
							<div class="stats">
								<h5><?= columnCount('account', 'technician')[0] ?></h5>
							  	<div class="grow grow1">
									<p>Total</p>
							  	</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-3 widget widget1">
					<div class="r3_counter_box">
						<?php if ($type == 'admin') { ?>
							<i class="fa fa-user-md greenColor"></i>
							<div class="stats">
								<h5><?= columnCount('account', 'technician')[0] ?></h5>
							  	<div class="grow grow1">
									<p>Technician</p>
							  	</div>
							</div>
						<?php } elseif ($type == 'technician') { ?>
							<i class="fa fa-ellipsis-h"></i>
							<div class="stats">
								<h5><?= taskPropertyClause('in progress', $id)[0] ?></h5>
							  	<div class="grow grow1">
									<p>In Progress</p>
							  	</div>
							</div>
						<?php } else { ?>
							<i class="fa fa-users"></i>
							<div class="stats">
								<h5><?= taskPropertyClause('pending', $id)[0] ?></h5>
							  	<div class="grow grow1">
									<p>Pending</p>
							  	</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-3 widget widget1">
					<div class="r3_counter_box">
						<?php if ($type == 'admin') { ?>
							<i class="fa fa-users greenColor"></i>
							<div class="stats">
								<h5><?= columnCount('account', 'user')[0] ?></h5>
							  	<div class="grow grow1">
									<p>User</p>
							  	</div>
							</div>
						<?php } elseif ($type == 'technician') { ?>
							<i class="fa fa-check-square-o"></i>
							<div class="stats">
								<h5><?= taskProperty('complete', $id)[0] ?></h5>
							  	<div class="grow grow1">
									<p>Completed</p>
							  	</div>
							</div>
						<?php } else { ?>
							<i class="fa fa-users"></i>
							<div class="stats">
								<h5><?= taskPropertyClause('in progress', $id)[0] ?></h5>
							  	<div class="grow grow1">
									<p>in progress</p>
							  	</div>
							</div>
						<?php } ?>
					</div>
				</div>
				 <div class="col-md-3 widget">
					<div class="r3_counter_box">
						<?php if ($type == 'admin') { ?>
							<i class="fa fa-tasks greenColor"></i>
							<div class="stats">
								<h5><?= taskProperty('pending')[0] ?></h5>
							  	<div class="grow grow1">
									<p>Submitted</p>
							  	</div>
							</div>
						<?php } elseif ($type == 'technician') { ?>
							<i class="fa fa-tasks greenColor"></i>
							<div class="stats">
								<h5><?= DB::query("SELECT COUNT(*) FROM task WHERE id = :id", 
            							[':id'=> $id])[0][0] ?></h5>
							  	<div class="grow grow1">
									<p>Task</p>
							  	</div>
							</div>
						<?php } else { ?>
							<i class="fa fa-users greenColor"></i>
							<div class="stats">
								<h5><?= taskPropertyClause('completed', $id)[0] ?></h5>
							  	<div class="grow grow1">
									<p>Completed</p>
							  	</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>

			
		</div>
	</div>
<?php require '../includes/loginFooter.php'; ?>