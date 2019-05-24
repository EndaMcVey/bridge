<?php

include '../db_connect.php';
session_start();
$UserID=$_SESSION["UserID"];

$id = $_POST['fileID'];
$status = $_POST['status'];
$comments = $_POST['comments'];
$comment_date=date("Y-m-d");
$reviewer= 'false';

//getting the infomation about the file
$sql= "select * from File where file_id= '$id'";
$result = $conn->query($sql);

   if($result->num_rows>0){
     while($row = mysqli_fetch_array($result))
     {
        $title=$row["title"];
        $grade_id=$row["grade_id"];
        $status_id=$row["status_id"];
        $dateUploaded=$row["dateUploaded"];
        $fileType_id=$row["fileType_id"];
        $student_id=$row["student_id"];
     }//while
   }//if

   //Get Reviewer ID

   do {
     $sql= "select reviewer_id from Reviewer where manager_id= '$UserID'";
     $result1 = $conn->query($sql);
        if($result1->num_rows>0){
          $reviewer = 'True';
          while($row = mysqli_fetch_array($result1))
          {
                  //Reviewer is already in Reviewer Table
                  $reviewer_id=$row["reviewer_id"];
            }//while
          }else {
            //reviewer is not in Reviewer Table
            $query5 = "INSERT INTO Reviewer (manager_id, reviewer_type)
            VALUES ('$UserID' , 'Manager')";
            $result = mysqli_query($conn, $query5);
          }//else
        } while ($reviewer == "False");

        //add comment to Comment Table
        $query2 = "INSERT INTO Comment (comment, reviewer_id, file_id, comment_date)
        VALUES ('$comments', '$reviewer_id', '$id','$comment_date')";
        $result2 = mysqli_query($conn, $query2);

        //update file status
        if ($status == "Approve")
        {
          $query3 = "UPDATE File  set status_id= 1 where file_id= $id";
          $result3 = mysqli_query($conn, $query3);
        }else{
          $query4= "UPDATE File set status_id= 2 where file_id= $id ";
          $result4 = mysqli_query($conn, $query4);
        }//else

          header("Location: ../manager.php?Success=1");
      ?>
