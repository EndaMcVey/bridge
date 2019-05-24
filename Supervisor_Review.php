<?php
//Include External Php Files
include 'db_connect.php';
//starts the session
session_start();
if (isset($_SESSION['Username']))
{
	//saves the Username & User ID Session Variables as a PHP Variables
	$Username= $_SESSION['Username'];
	$UserID=$_SESSION["UserID"];
	$FullName=$_SESSION["Fullname"];
}else{
	//is Username session hasn't been defined- redirect to login page
	header("Location:login.php?");

}//else

//if logout button is pressed it will include the logout script, which will unset the session and
//return the user to the login page
if (isset($_GET['logout'])) {
	include 'Process/logoutProcess.php';
}

$sql= "SELECT * FROM Supervisor WHERE supervisor_id='$UserID'";
$result1 = $conn->query($sql);

	 if($result1->num_rows>0){
		 while($row = mysqli_fetch_array($result1))
		 {
       $SupervisorName=$row["name"];
		 }//while
	 }//if
?>

<!DOCTYPE html>
<html>
<title>Bridge</title>

	<!-- Includes all the necessary links -->
	<?php include 'Includes/links.html';?>

	<body class="w3-light-grey w3-content" style="max-width:1600px" onload=buttonCSS()>

		<!-- Includes Menu sidebar on the left -->
		<?php include 'Includes/sidebar_supervisor.php';?>
		<script>
		function buttonCSS()
		{
		var query = window.location.search.substring(1);
		if (query !='review=1')
		{
			var vars = query.substring(9,10);
			if(vars=='1')
			{
				var element = document.getElementById("Journal");
				element.classList.remove("w3-white");
				element.classList.add("w3-black");
			}
			if(vars=='2')
			{
				var element = document.getElementById("Report");
				element.classList.remove("w3-white");
				element.classList.add("w3-black");
			}
			if(vars=='3')
			{
				var element = document.getElementById("File");
				element.classList.remove("w3-white");
				element.classList.add("w3-black");
			}
		}else
		{
			vars="0";
			var element = document.getElementById("all");
			element.classList.remove("w3-white");
			element.classList.add("w3-black");
		}//else
	}
		var element = document.getElementById("ViewWork");
		element.classList.remove("w3-text-blue");
		var element = document.getElementById("ViewWork");
		element.classList.add("w3-text-blue");

	</script>
		<!-- Overlay effect when opening sidebar on small screens -->
		<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

		<!-- !PAGE CONTENT! -->
		<div class="w3-main" style="margin-left:300px">



			<!-- Includes Header to filter the type of files -->
			<header id="portfolio" >
			<a href="#"><img src="/w3images/avatar_g2.jpg" style="width:65px;" class="w3-circle w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
			<span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
			<div class="w3-container">
				<h2><b><?php echo $SupervisorName;?></b></h2>
				<div class="w3-section w3-bottombar w3-padding-16">
					<a href="supervisor_Review.php?review=1" class="w3-button w3-white" id="all" name='all' onclick= "buttonChange('all')" > <i class="far fa-folder-open w3-margin-right"></i>All</a>
					<a href="supervisor_Review.php?filetype=2&&review=1" class="w3-button w3-white" onclick= "buttonChange('Report')" id="Report" name='Report'> <i class="fas fa-envelope-open-text w3-margin-right"></i>Report</a>
					<a href="supervisor_Review.php?filetype=1&&review=1" id="Journal" name='Journal' class="w3-button w3-white w3-hide-small"><i class="fas fa-book w3-margin-right"></i>Journal</a>
					<a href="supervisor_Review.php?filetype=3&&review=1" onclick= "buttonChange('File')" class="w3-button w3-white w3-hide-small" id="File" name="File"><i class="far fa-file w3-margin-right"></i>File</a>
				</div>
			</div>
		</header>
		<input type="text" id="myInput" onkeyup=tableSearch() size='40' class="w3-left w3-margin w3-white" placeholder="Search for Student No.."  title="Type in a Student No">
		<br><br>

			<!-- Small padding -->
			<div class="w3-third w3-container w3-margin-bottom"></div>

			<?php
			if (isset($_GET["review"]))
			{
				if ($_GET["review"] == 1){
				$review= true;
				$post= "supervisorReview.php";
			}else
			$post= "supervisorPastComments.php";
			}
			?>

			<script>
			function showComment(id) {
				$.post("Process/<?php echo $post; ?>", {
					id:id
				}, function(result) {
					$("#result").html(result);
				});
			}

			//Used the following guide for the search feacture https://www.w3schools.com/howto/howto_js_filter_table.asp
			function tableSearch() {
		  var input, filter, table, tr, td, i, txtValue;
		  input = document.getElementById("myInput");
		  filter = input.value.toUpperCase();
		  table = document.getElementById("table");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[0];
		    if (td) {
		      txtValue = td.textContent || td.innerText;
		      if (txtValue.toUpperCase().indexOf(filter) > -1) {
		        tr[i].style.display = "";
		      } else {
		        tr[i].style.display = "none";
		      }//else
		    }//if
		  }//for
		}//TableSearch
   		    </script>

			<!-- Title row of the table -->
			<table id="table">
	 		<tr>
				<th>Student No.</th>
		 		<th>Title</th>
		 		<th>Date</th>
		 		<th>Status</th>
		 		<th>Grade</th>
				<th>    </th>
		 		<!-- <th>Delete</th> -->
	 		</tr>


	 		<?php

	 		// Query to bring the files

			$sql = "select student_id from Student where supervisor_id = $UserID";
			$result2 = $conn->query($sql);
			while($row = mysqli_fetch_array($result2))
			{
				$student_id=$row["student_id"];



				if ($review == false)
				{
					if (isset($_GET["filetype"])) {

							//css
							echo "<script>
								var element = document.getElementById(\"all\");
								element.classList.remove(\"w3-white\");
								element.classList.add(\"w3-black\");
							</script>";

    					$filetype= $_GET["filetype"];
    					$sql = "select * from File where status_id in (4) and student_id =$student_id and fileType_id = '$filetype'";
    				}else{
    					$sql = "select * from File where status_id in (4) and student_id =$student_id";
    					$result = $conn->query($sql1);
  			}

				}else
				{
					if (isset($_GET["filetype"])) {
    					$filetype= $_GET["filetype"];
    					$sql = "select * from File where status_id in (1) and student_id =$student_id and fileType_id = '$filetype'";
    				}else{
    					$sql = "select * from File where status_id in (1) and student_id =$student_id";
    					$result = $conn->query($sql1);

				}
				}//else


	 			$result = $conn->query($sql);

	 		// Creating list with all the files, path of the zip file and the zip file
	 		$files = [];
	 		$zipFileName="./Files/download.zip";
	 		$zip = new ZipArchive();
	 		if ($zip->open($zipFileName, ZIPARCHIVE::CREATE )!==TRUE) {
	 			exit("cannot open <$zipFileName>\n");
	 		}


	 		// Assign the results of the query
	 		while($file = mysqli_fetch_array($result)) {

	 			$file_id=$file["file_id"];
	 			$title=$file["title"];
	 			$filePath=$file["filePath"];

	 			//Add path of the file to files list
	 			$files[] = $filePath;

	 			$grade_id=$file["grade_id"];
	 			$status_id=$file["status_id"];
	 			$dateUploaded=$file["dateUploaded"];
	 			$fileType_id=$file["fileType_id"];
	 			$student_id=$file["student_id"];

	 			// Query to bring the grades
	 			$sql = "select grade from Grade where grade_id = $grade_id";
	 			$graderesult = $conn->query($sql);
	 			// Assign the results of the query
	 			while($row = mysqli_fetch_array($graderesult))
	 			{
	 				$grade_id=$row["grade"];
				}

				// Query to bring the statuses
				$sql = "select statusName from Status where status_id = $status_id";
				$statusresult = $conn->query($sql);
				// Assign the results of the query
				while($row = mysqli_fetch_array($statusresult)) {
					$status_id=$row["statusName"];
				}

				//Displaying all the results
				echo "<tr>
				<td>$student_id</td>
				<td><a style='color:black;' href='$filePath', download>$title</a></td>
				<td>$dateUploaded</td>
				<td>$status_id</td>
				<td><a style='color:black;' onclick='showComment($file_id);'>$grade_id</a></td>
				<td><a style='color:black;' onclick='showComment($file_id);'><i class='far fa-comment'></i></a></td>


				</tr>\n";
			}
		}
			// <td><a style='color:black;' href='delete.php?id=".$file['file_id']."'><i class='far fa-trash-alt'></i></a></td>

			// Add all filepaths to the zip file
			foreach ($files as $file) {
				$zip->addFile($file);
			}

			// Close zip file
			$zip->close();
			// if (file_exists($filename)) {
			// 	header('Content-Type: application/zip');
			// 	header('Content-Disposition: attachment; filename=$zipFileName');
			// 	header('Content-Length: ' . filesize($zipFileName));

			// 	flush();
			// 	readfile($zipFileName);
		    ////  delete file
			// 	unlink($zipFileName);
			// }
			?>

			</table>


			<div id=result> </div>

			<!-- The Modal -->
			<div id="myModal" class="modal">

				<!-- Modal content -->
				<div class="modal-content text-center">
					<h4>Are you sure you want to delete this file?</h4>
				<br>
				<button class="w3-btn w3-border w3-border-blue w3-round-large" style="width:20%;">Yes</button>
				<button class="w3-btn w3-border w3-border-blue w3-round-large" style="width:20%;" onclick="closeModal(this)">No</button>

				</div>
			</div>
			<div id="id01" class="modal">

				<div class="modal-content animate">
					<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">x</span>

					<center>
						<h4> Success</h4>
						<p> You have successfully reviewed the file <p>
								<button type="button" onclick="document.getElementById('id01').style.display='none'" class="button button1">Close</button>
				</div>
			</div>

			<!-- Includes Download all Button -->
			<?php include 'Includes/download_all_button.html';?>

			<!-- Includes footer at the bottom -->
			<?php include 'Includes/footer.html';?>
			<!-- if (isset($_GET["Success"]))
			{
					// echo "<script>document.getElementById('id01').style.display='block';</script>";
			}//if -->

		<!-- End page content -->
		</div>



		<script>
		 // Script to open and close sidebar
		 function w3_open() {
		 	document.getElementById("mySidebar").style.display = "block";
		 	document.getElementById("myOverlay").style.display = "block";
		 }

		 function w3_close() {
		 	document.getElementById("mySidebar").style.display = "none";
		 	document.getElementById("myOverlay").style.display = "none";
		 }

		 function displayModal(btn) {
			 var modal = document.getElementById('myModal');
			 //Get the button that opens the modal
			 //var btn = document.getElementById("myBtn");

			 modal.style.display = "block";
		 }

		 function closeModal(btn) {
			 //Get the <span> element that closes the modal
			 //var span = document.getElementsByClassName("close")[0];
			 var modal = document.getElementById('myModal');
			 modal.style.display = "none";
		 }
		</script>

	</body>
</html>
