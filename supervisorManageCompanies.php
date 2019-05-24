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

	 $sql = "SELECT DISTINCT name, name from Company";
		$resultcompany2 = $conn->query($sql);
?>

<script>
function section(section) {
		var i;
		var count = document.getElementsByClassName('section');
		var active = section +'Tabs';

		for (i = 0; i < count.length; i++) {
			 count[i].style.display = 'none';
		}
		document.getElementById('GeneralTabs').classList.remove('active');
		document.getElementById('FamilyTabs').classList.remove('active');
		document.getElementById(section).style.display = 'block';
		document.getElementById(active).className = 'active';
}
</script>

<!DOCTYPE html>
<html>
<title>Bridge</title>

	<!-- Includes all the necessary links -->
	<?php include 'Includes/links.html';?>
	<!--Use the following guide for the PDF creater https://stackoverflow.com/questions/23035858/export-html-table-to-pdf-using-jspdf -->
	<script src="js/jspdf.js"></script>
	<script src="js/jquery-2.1.3.js"></script>
	<script src="js/pdfFromHTML.js"></script>

	<body class="w3-light-grey w3-content" style="max-width:1600px">

		<!-- Includes Menu sidebar on the left -->
		<?php include 'Includes/sidebar_supervisor.php';?>
		<script>
		var element = document.getElementById("ViewWork");
		element.classList.remove("w3-text-blue");
		var element = document.getElementById("Review");
		element.classList.remove("w3-text-blue");
		var element = document.getElementById("ManageCompanies");
		element.classList.remove("w3-text-blue");
		var element = document.getElementById("ManageStudents");
		element.classList.remove("w3-text-blue");
		var element = document.getElementById("ManageCompanies");
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
					<button class= "w3-button w3-white" onclick="document.getElementById('NewCompany').style.display='block'"><i class="fas fa-business-time" "w3-margin-right"></i>   New Company</button>
					<button class= "w3-button w3-white" onclick="HTMLtoPDF()"><i class="fas fa-file-pdf-o w3-margin-right"></i>PDF</button>
					<button class= "w3-button w3-white" onclick="exportTableToCSV('Students.csv')"><i class="fas fa-table w3-margin-right"></i>CSV</button>
				</div>
			</div>
		</header>
		<input type="text" id="myInput" onkeyup=tableSearch() size='40' class="w3-left w3-margin w3-white" placeholder="Search for Company"  title="Type in a Student No">
			<br><br>

			<!-- Small padding -->
			<div class="w3-third w3-container w3-margin-bottom"></div>

			<?php
			if (isset($_GET["review"]))
			{
				if ($_GET["review"] == 1){
				$review= true;
				$post= "supervisorReview.php";
				}
			}else {
			$post= "supervisorPastComments.php";
			}
			?>

			<script>
			function showComment(id) {
				$.post("Process/studentCard", {
					id:id
				}, function(result) {
					$("#result").html(result);
				});
			}



			//I used a Guide for the csv export
			// https://www.fdmdigital.co.uk/formidable-forms-front-end-csv/
			function downloadCSV(csv, filename) {
			    var csvFile;
			    var downloadLink;

			    // CSV file
			    csvFile = new Blob([csv], {type: "text/csv"});

			    // Download link
			    downloadLink = document.createElement("a");

			    // File name
			    downloadLink.download = filename;

			    // Create a link to the file
			    downloadLink.href = window.URL.createObjectURL(csvFile);

			    // Hide download link
			    downloadLink.style.display = "none";

			    // Add the link to DOM
			    document.body.appendChild(downloadLink);

			    // Click download link
			    downloadLink.click();
			}

			function exportTableToCSV(filename) {
			    var csv = [];
			    var rows = document.querySelectorAll("table tr");

			    for (var i = 0; i < rows.length; i++) {
			        var row = [], cols = rows[i].querySelectorAll("td, th");

			        for (var j = 0; j < cols.length; j++)
			            row.push(cols[j].innerText);

			        csv.push(row.join(","));
			    }

			    // Download CSV file
			    downloadCSV(csv.join("\n"), filename);
			}
   		    </script>
					<div id='NewCompany' class='modal'>
					<form class='modal-content animate' action='Process/newManager.php' method='post'>
						<span onclick='document.getElementById("NewCompany").style.display="none"' class='close' title='Close Modal'>x</span>

					<div>
					<center>	<h2>New Company</h2></center>
						</div>
						<ul class='nav nav-tabs'>
							<li id= 'GeneralTabs' class='active'><a onClick= 'section("General")' >Company Details</a></li>
							<li id= 'FamilyTabs'><a onClick= 'section("Family")' >Manager Details</a></li>
						</ul>
						<br>
						<div id='General' class='section'>
							 <center>

								 <br>
							 <label><input type='text' id='companyName' name='companyName' class='form-control' size='20' placeholder="Company Name" required></label><br>
							 <label><input type='text' id='addressLine1' name='addressLine1' class='form-control' size='30' placeholder="Address-Line 1 " required></label><br>
							 <label><input type='text' id='addressLine2' name='addressLine2' class='form-control' size='30' placeholder="Address-Line 2 " required></label><br>
							 <label><input type='text' id='town' name='town' class='form-control' size='20' placeholder="Town" required></label><br>
							 <label><input type='text' id='country' name='country' class='form-control' size='20' placeholder="country" required></label><br>
							 <label><input type='text' id='postcode' name='postcode' class='form-control' size='20' placeholder="country" required></label><br>
				 </div>

						<div id ='Family' class='section' style='display:none'>

							<center>
								<div class="profile-pic">

									<a class="img" >

										<div class="edit"><i class="fas fa-pencil-alt fa-fw w3-margin-right"></i></div>
										<img src="/Images/Dummy_Profile_Pic.png" ; style="width:150px;  height: 150px;" />
									</a>
									<br>
								</div>
								<br>

							<label><input type='text' id='Manageremail' name='Manageremail' class='form-control' size='30' placeholder="Manager E-mail Address" required></label><br>
							<label><input type='text' id='ManagerName' name='ManagerName' class='form-control' size='20' placeholder="Manager Name" required></label><br>

							<label><input type='submit' id='submit' name='submit' class='form-control' size='20'><br></label>


					</div>
				</form>
				</div>



			<!-- Title row of the table -->
			<div = id="HTMLtoPDF">
			<table id="table">
	 		<tr>
				<th>Business Name</th>
		 		<th>Manager Name</th>
		 		<th>Number of Students</th>
	

		 		<!-- <th>Delete</th> -->
	 		</tr>


	 		<?php

	 		// Query to bring the files

			//Include External Php Files

			$sql = "select * from Manager";
			$result2 = $conn->query($sql);
			while($row = mysqli_fetch_array($result2))
			{
				$manager_id=$row["manager_id"];
				$managerName=$row["name"];
        $managerEmail=$row["email"];
        $company=$row["company_id"];
        $managerPhoto=$row["photo_path"];


	 		//Find Company
				$sql = "SELECT name from Company where company_id =$company";
				$resultcompany = $conn->query($sql);
				while($row = mysqli_fetch_array($resultcompany))
				{
					$companyName=$row["name"];
				}//JournalAverage

				$sql = "SELECT count(name) from Student where company_id =$company";
				$resultcompany = $conn->query($sql);
				while($row = mysqli_fetch_array($resultcompany))
				{
					$studentCount=$row["count(name)"];
				}//JournalAverage



				//Displaying all the results
				echo "<tr>
				<td>$companyName</td>
				<td>$managerName</td>
				<td>$studentCount</td>
				</tr>\n";
			}

			// <td><a style='color:black;' href='delete.php?id=".$file['file_id']."'><i class='far fa-trash-alt'></i></a></td>

			// Add all filepaths to the zip file

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
		</div>


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
			<div id="AddedStudent" class="modal">

				<div class="modal-content animate">
					<span onclick="document.getElementById('AddedStudent').style.display='none'" class="close" title="Close Modal">x</span>

					<center>
						<h4> Success</h4>
						<p> You have successfully created a new account for the Company. The Manager has been sent an e-mail containing their login information<p>
								<button type="button" onclick="document.getElementById('AddedStudent').style.display='none'" class="button button1">Close</button>
				</div>
			</div>
			<div id="SuccessEmail" class="modal">

				<div class="modal-content animate">
					<span onclick="document.getElementById('SuccessEmail').style.display='none'" class="close" title="Close Modal">x</span>

					<center>
						<h4> Success</h4>
						<p> You have successfully sent the file request to the student<p>
								<button type="button" onclick="document.getElementById('SuccessEmail').style.display='none'" class="button button1">Close</button>
				</div>
			</div>
			<div id="NotUnique" class="modal">

				<div class="modal-content animate">
					<span onclick="document.getElementById('NotUnique').style.display='none'" class="close" title="Close Modal">x</span>

					<center>
						<h4> Error</h4>
						<p> The Company and/or Manager you are trying to create already has an account for the system, please try again.<p>
								<button type="button" onclick="document.getElementById('NotUnique').style.display='none'" class="button button1">Close</button>
				</div>
			</div>
			<div id="NoCompany" class="modal">

				<div class="modal-content animate">
					<span onclick="document.getElementById('NoCompany').style.display='none'" class="close" title="Close Modal">x</span>

					<center>
						<h4> Error</h4>
						<p> The Company and/or Manager you have entered does not exist on the system. Please create the company first by using the 'Manage Companies' section before creating a new Student<p>
								<button type="button" onclick="document.getElementById('NoCompany').style.display='none'" class="button button1">Close</button>
				</div>
			</div>

		<!--New Students feacture -->
			<!-- Includes footer at the bottom -->
			<?php include 'Includes/footer.html';


			if (isset($_GET["fail"]))
			{
				if($_GET["fail"]== 0)
				{
					  echo "<script>document.getElementById('AddedStudent').style.display='block';</script>";
				}elseif($_GET["fail"]== 1)
				{
					echo "<script>document.getElementById('NotUnique').style.display='block';</script>";
				}elseif($_GET["fail"]== 2)
				{
					echo "<script>document.getElementById('NoCompany').style.display='block';</script>";
				}elseif($_GET["fail"]== 10)
			{
				echo "<script>document.getElementById('SuccessEmail').style.display='block';</script>";
			}
			}//isSet
			?>

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



	</body>
</html>
