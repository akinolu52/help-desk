<?php 

	require "includes/header.php"; 

	if ($_POST) {
		echo "<pre>";
		extract($_POST); 
		if ($_POST['action'] === 'register') {
			//print_r($_POST);
			
			if(!isset($firstname, $lastname, $email, $password, $mobile, $location, $bio)) {
				$message = 'Please enter valid values';
			} elseif (strlen( $firstname) < 4 || strlen($lastname) < 4 || strlen($password) < 4) {
				$message = 'Incorrect Length for Username or Lastname or Password';
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) ) {
				$message = 'Invalid Email format';
			} else {
				

				$checkEmail = DB::query("SELECT id FROM account WHERE email = :email", 
										[':email'=> $email]);
				
				if ($checkEmail) {
					echo $message = "This email already exists";
				} else {

					#picture functionality to work on
					$directory = "uploads/";
					//print_r($_FILES);

					if (getimagesize($_FILES["picture"]["tmp_name"])){
						$targetFile = $directory.basename($_FILES["picture"]["name"]);
						$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
						if ($imageFileType != "jpg" || $imageFileType != "png" || $imageFileType != "jpeg") {
							if (!move_uploaded_file($_FILES["picture"]["tmp_name"], $targetFile)) {
								$message = "Uploading Failed";
							} else {

								DB::query("INSERT INTO 
								account (type, firstname, lastname, email, password, mobile, location, bio, picture) 
								VALUES (:type, :firstname, :lastname, :email, :password, :mobile, :location, :bio, :picture)",
								[':type'=> 'user', ':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':password'=> sha1($password), ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio, ':picture'=> $targetFile]);

								$check = DB::query("SELECT type, id FROM account WHERE email = :email AND password = :password" , [':email'=> $email, ':password'=> sha1($password)])[0];
								
								// print_r($check);

								loggedInAccount($check['id'], $check['type']);
								
								// header('Location: user');
								header('Location: account');

							}
						} else {
							$message = "Invalid Image format";
						}
					}else{

						DB::query("INSERT INTO 
						account (type, firstname, lastname, email, password, mobile, location, bio) 
						VALUES (:type, :firstname, :lastname, :email, :password, :mobile, :location, :bio)",
						[':type'=> 'user', ':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':password'=> sha1($password), ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio]);
						
						$check = DB::query("SELECT type, id FROM account WHERE email = :email AND password = :password" , [':email'=> $email, ':password'=> sha1($password)])[0];

						//print_r($check);
						
						loggedInAccount($check['id'], $check['type']);

						header('Location: account');
						
						// header('Location: user');

					}
				}
			}
		}
	}
?>
	<div id="page-wrapper" class="sign-in-wrapper">
		<div class="graphs">
			<h3>User Register Here</h3>
			<form role="form" method="post" class="form-horizontal" enctype="multipart/form-data" style="border: 2px solid red">
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1">							
							<span class="input-group-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="input-lg form-control1" name="firstname" placeholder="First Name" required/>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1">							
							<span class="input-group-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="text" class="input-lg form-control1" name="lastname" placeholder="Last Name" required/>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1">							
							<span class="input-group-addon">
								<i class="fa fa-envelope-o"></i>
							</span>
							<input type="email" class="input-lg form-control1" name="email" placeholder="Email" required/>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1">							
							<span class="input-group-addon">
								<i class="fa fa-key"></i>
							</span>
							<input type="password" class="input-lg form-control1" name="password" placeholder="Password" required/>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- Mobile -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div5">							
							<span class="input-group-addon">
								<i class="fa fa-phone" id="ico5"></i>
								<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
							</span>
							<input type="tel" class="input-lg form-control1" name="mobile" onblur="check(this.value, 'password', '5')" id="retype" placeholder="Mobile Number"/>
							<span id="err5"></span>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- Location -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div5">							
							<span class="input-group-addon">
								<i class="fa fa-location-arrow" id="ico5"></i>
								<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
							</span>
							<select name="location" id="selector1" class="form-control1" name="location" title="Select Location">
								<option value="VI Lagos">VI, Lagos</option>
								<option value="Ajah Lagos">Ajah, Lagos</option>
								<option value="Magodo Lagos">Magodo, Lagos</option>
								<option value="Ikeja Lagos">Ikeja, Lagos</option>
							</select>
							<span id="err5"></span>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- Bio -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div5">							
							<!-- <span class="input-group-addon">
								<i class="fa fa-upload" id="ico5"></i>
								<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
							</span> -->
							<textarea name="bio" id="txtarea1" cols="50" rows="6" placeholder="Bio" class="form-control1"></textarea>
							<span id="err5"></span>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- Pic -->
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
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<button class="btn-success btn" name="action" value="register">Submit</button>
					</div>
					<div class="clearfix"> </div>
				</div>
			</form>
		</div>
	</div>
<?php require "includes/footer.php"; ?>	