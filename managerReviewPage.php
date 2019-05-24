<?php
session_start();
//Include External Php Files
include 'db_connect.php';
//starts the session
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
$sql= "SELECT * FROM Manager WHERE manager_id='$UserID'";
$result1 = $conn->query($sql);

	 if($result1->num_rows>0){
		 while($row = mysqli_fetch_array($result1))
		 {
       $ManagerName=$row["name"];
		 }//while
	 }//if

 ?>

<!DOCTYPE html>
<html>
<title>Bridge</title>

<?php include 'Includes/links.html';?>
<script>
 var element = document.getElementById("ManagerReview");
 element.classList.remove("w3-text-blue");
 var element = document.getElementById("Manager");
 element.classList.remove("w3-text-blue");
 var element = document.getElementById("ManagerReview");
 element.classList.add("w3-text-blue");
</script>


<body class="w3-light-grey w3-content" style="max-width:1600px">

	<!-- Sidebar/menu -->
	<?php include "Includes/sidebar_reviewer.php"?>

	<!-- Overlay effect when opening sidebar on small screens -->
	<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

	<!-- !PAGE CONTENT! -->
	<div class="w3-main" style="margin-left:300px">

		<header id="portfolio" >
		<a href="#"><img src="/w3images/avatar_g2.jpg" style="width:65px;" class="w3-circle w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
		<span class="w3-button w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
		<div class="w3-container">
			<h2><b><?php echo $ManagerName;?></b></h2>
			<div class="w3-section w3-bottombar w3-padding-16">
				<a href="manager.php?" class="w3-button w3-black"> <i class="far fa-folder-open w3-margin-right"></i>All</a>
				<a href="manager.php?filetype=2" class="w3-button w3-white"><i class="fas fa-envelope-open-text w3-margin-right"></i>Report</a>
				<a href="manager.php?filetype=1" class="w3-button w3-white w3-hide-small"><i class="fas fa-book w3-margin-right"></i>Journal</a>
				<a href="manager.php?filetype=3" class="w3-button w3-white w3-hide-small"><i class="far fa-file w3-margin-right"></i>File</a>
			</div>
		</div>
	</header>


		<table id="table">
			<tr>
				<th>Title</th>
				<th>Date</th>
				<th>Status</th>
				<th>Grade</th>
				<th>Actions </th>
			</tr>


				<?php
	//Include External Php Files
	if (isset($_GET["review"]))
	{
		if ($_GET["review"] == 1)
	{
		$review= true;
		$post= "managerApproval.php";
	}
}else {
	$post= "managerReview.php";
}

echo $post;
?>
<script>
	//this script collects the id from the table and posts it to PHP inorder to
//get the comments

function showComments(id)
{

			$.post("Process/managerApproval.php", {
					id:id
				}, function(result) {
					$("#result").html(result);
				});
	}
</script>
<?php

	$sql = "select student_id from Student where manager_id = $UserID";
	$result2 = $conn->query($sql);
	while($row = mysqli_fetch_array($result2))
	{
		$student_id=$row["student_id"];


			$sql = "select * from File where status_id =3 and student_id = $student_id";


				$result = $conn->query($sql);
	while($row = mysqli_fetch_array($result))
	{
		$file_id=$row["file_id"];
		$title=$row["title"];
		$filePath=$row["filePath"];
		$grade_id=$row["grade_id"];
		$status_id=$row["status_id"];
		$dateUploaded=$row["dateUploaded"];
		$fileType_id=$row["fileType_id"];
		$student_id=$row["student_id"];

		$sql = "select grade from Grade where grade_id = $grade_id";
		$graderesult = $conn->query($sql);
		while($row = mysqli_fetch_array($graderesult))
		{
			$grade_id=$row["grade"];
		}//grade

		$sql = "select statusName from Status where status_id = $status_id";
		$statusresult = $conn->query($sql);
		while($row = mysqli_fetch_array($statusresult))
		{
			$status_id=$row["statusName"];
		}//stauts

		 echo "<tr><td style='display:none;'>$file_id</td>
		  <td><a href='$filePath' style='color:black;', download>$title</a></td>
		 <td>$dateUploaded</td><td>$status_id</td><td>$grade_id</td>
		 <td><a style='color:black;' onclick='showComments($file_id);'><i class='far fa-comment'></i></a></td>
		</tr>\n";
	}//while
}
	?>
		</table>



		<div id=result> </div>

		<?php include 'Includes/download_all_button.html';?>
		<div id="id01" class="modal">

			<div class="modal-content animate">
				<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">x</span>

				<center>
					<h4> Success</h4>
					<p> You have successfully reviewed the file <p>
							<button type="button" onclick="document.getElementById('id01').style.display='none'" class="button button1">Close</button>
			</div>
		</div>

		<?php include 'Includes/footer.html';

		if (isset($_GET["Success"]))
		{
			if ($_GET["Success"]!=15)
				echo "<script>document.getElementById('id01').style.display='block';</script>";
		}//if
		 ?>
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
	</script>


</body>

</html>
