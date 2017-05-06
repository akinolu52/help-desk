<?php
	require "DB.php";

	if (isset($_POST)) {
		
		#delete either an admin user or a technician
		if ($_POST['action'] === 'delete') {
			
			extract($_POST); 
			print_r($_POST);

			if(isset($type, $id)) {
				if ($type == 'technician') {
					# check if am assigned to a job
					$technicianTask = DB::query("SELECT * FROM task WHERE technicianId = :id",
								[':id'=> $id]);
					if (!empty($query)) {
						foreach ($technicianTask as $techTask) {
							DB::query("UPDATE task SET technicianId = :technicianId * WHERE id = :id",
										[':technicianId'=> 0, ':id'=> $techTask['id']]);
						}
						
					}
					# remove me from the job & set it to 0


				}
				$result = DB::query("DELETE FROM account WHERE type = :type AND id = :id",
									[':type'=> $type, ':id'=> $id]);

			}
		}

	}

?>