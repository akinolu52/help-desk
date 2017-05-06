<?php 
	
	require '../includes/loginHeader.php'; 
	isLoggedIn() ? : header('location: ../sign-in.php');

	if ($_POST) {
		extract($_POST); 
		if ($_POST['action'] === 'register admin' || $_POST['action'] === 'register technician') {
			
			if(!isset($firstname, $lastname, $email, $password, $mobile, $location, $bio)) {
				$message = 'Please enter valid values';
			} elseif (strlen( $firstname) < 4 || strlen($lastname) < 4 || strlen($password) < 4) {
				$message = 'Incorrect Length for firstname or Lastname or Password';
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) ) {
				$message = 'Invalid Email format';
			} else {

				$checkEmail = DB::query("SELECT id FROM account WHERE email = :email", [':email'=> $email]);

				if (!empty($checkEmail)) {
					$message = "This email already exists";
				} else { 
					
					$_POST['action'] === 'register admin' ? $type = "admin" : $type = "technician";

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
								account (type, firstname, lastname, email, password, skill, mobile, location, bio, picture) 
								VALUES (:type, :firstname, :lastname, :email, :password, :skill, :mobile, :location, :bio, :picture)",
								[':type'=> $type, ':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':password'=> sha1($password), ':skill'=> $skill, ':mobile'=> $mobile, ':location'=> $location, ':bio'=> $bio, ':picture'=> $targetFile]);
								} else {
								DB::query("INSERT INTO 
								account (type, firstname, lastname, email, password, mobile, location, bio, picture) 
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
<link rel="stylesheet" type="text/css" href="../css/select2.css">
	<div id="page-wrapper">
		<div class="graphs">
			<h3 style="text-align: center;">Register <?php ($_GET['account'] == 'technician') ? print 'Technicians' : print 'Admin' ; ?> Here</h3>
			<!-- <form role="form" method="post" class="form-horizontal" onsubmit="create($_GET['account'])"> -->
			<form role="form" method="post" class="form-horizontal" action="" enctype="multipart/form-data">
				<!-- Firstname -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div1">							
							<span class="input-group-addon ">
								<i class="fa fa-user" id="ico1"></i> 
								<i class="fa fa-fw fa-spin fa-spinner" id="processing1" style="display: none"></i>
							</span>
							<input type="text" class="input-lg form-control1" name="firstname" onblur="check(this.value, 'no_db', '1')" placeholder="First Name" required/>
							<span id="err1"></span>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- Lastname -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div2">							
							<span class="input-group-addon">
								<i class="fa fa-user" id="ico"></i>
								<i class="fa fa-fw fa-spin fa-spinner" id="processing2" style="display: none"></i>
							</span>
							<input type="text" class="input-lg form-control1" name="lastname" onblur="check(this.value, 'no_db', '2')" placeholder="Last Name" required/>
							<span id="err2"></span>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>    
				<!-- Email -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div3">							
							<span class="input-group-addon">
								<i class="fa fa-envelope-o" id="ico3"></i>
								<i class="fa fa-fw fa-spin fa-spinner" id="processing3" style="display: none"></i>
							</span>
							<input type="email" id="em" class="input-lg form-control1" name="email" onblur="check(this.value, '_db', '3')" placeholder="Email" required/>
							<span id="err3"></span>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<!-- Password -->
				<div class="form-group">
					<label class="col-md-2 control-label"></label>
					<div class="col-md-10">
						<div class="input-group in-grp1" id="div4">							
							<span class="input-group-addon">
								<i class="fa fa-key" id="ico4"></i>
								<i class="fa fa-fw fa-spin fa-spinner" id="processing4" style="display: none"></i>
							</span>
							<input type="password" class="input-lg form-control1" name="password" onblur="check(this.value, 'password', '4')" id="pass" placeholder="Password" required/>
							<span id="err4"></span>
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
				<!-- Skill Set -->
				<?php 
					if($_GET['account'] == 'technician') { ?>
						<div class="form-group">
							<label class="col-md-2 control-label"></label>
							<div class="col-md-10">
								<div class="input-group in-grp1 " id="div5">							
									<span class="input-group-addon">
										<i class="fa fa-" id="ico5"></i>
										<i class="fa fa-fw fa-spin fa-spinner" id="processing5" style="display: none"></i>
									</span>
									<select name="skill" id="selector1" class="form-control1 js-example-basic-multiple" multiple="multiple" title="Select skill" required>
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
				<?php } ?>
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
				<div class="row" style="text-align: center;">
					<div class="col-sm-8 col-sm-offset-2">
						<button class="btn-success btn" name="action" 
						value="<?php ($_GET['account'] === 'technician') ? print 'register technician' : print 'register admin' ; ?>">Submit</button>
					</div>
					<div class="clearfix"> </div>
				</div>
			</form>
		</div>
	</div>
<?php require '../includes/loginFooter.php'; ?>
<script type="text/javascript" src="../js/select2.full.js"></script>
<script type="text/javascript">
	$(".js-example-basic-multiple").select2({
		maximumSelectionLength: 1
	});
</script>
        