<?php

	require "includes/header.php";

	if ($_POST) {
		extract($_POST);
		if ($_POST['action'] === 'login') {

			if(!isset($email, $password)) {
				$message = 'Please enter valid values';
			} else {
				#check if it is a valid email
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				    $message = "Invalid email format";
				}else{
					#encrypt password
					$password = sha1($password);

					$check = DB::query("SELECT type, id FROM account WHERE email = :email AND password = :password" ,
							[':email'=> $email, ':password'=> $password]);
					if (!$check) {
						$message = "Incorrect details";
					} else {
						// require_once 'includes/function.php';
						#create a session storage for the person
						loggedInAccount($check[0]['id'], $check[0]['type']);

						header('Location: account');
					}
				}
			}

		}
	}

?>
	<div id="page-wrapper" class="sign-in-wrapper">
		<div class="graphs">
			<div class="sign-in-form">
				<div class="sign-in-form-top">
					<p><span>Login</p>
				</div>
				<div class="signin">
					<form method="post">
						<div class="form-group has-feedback">
							<div class="input-group input-group1 in-grp1" id="div1">
								<span class="input-group-addon ">
									<i class="fa fa-envelope-o padding-5" id="ico1"></i>
								</span>
								<input type="email" name="email" class="input-lg form-control1" placeholder="Email" required/>
								<span id="err1"></span>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="form-group">
						    <div class="form-group has-feedback">
								<div class="input-group input-group1 in-grp1" id="div1">
							   	 	<span class="input-group-addon">
							   	 		<i class="fa fa-lock padding-5" id="ico1"></i>
							   	 	</span>
							   	  	<input type="password" required name="password" placeholder="Password" class="form-control1">
							   	</div>
						    </div>
							<div class="clearfix"> </div>
						</div>
						<button type="submit" value="login" name="action" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php require "includes/footer.php"; ?>

<?php if (isset($message)) { ?>
	<script type="text/javascript">
		alert('<?= $message; ?>');
	</script>
<?php } ?>
