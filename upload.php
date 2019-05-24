<?php

session_start();
$UserID=$_SESSION["UserID"];

$target_dir = "/homepages/5/d760206299/htdocs/upload/$UserID/";
include 'db_connect.php';

//check if directory for the student exists
if (file_exists($target_dir)) {

} else {
    //directory doesn't exist, make the directory
    mkdir($target_dir);
}//else

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image

// Check if file already exists
if (file_exists($target_file)) {
    header("Location: ../student_Upload.php?success=2");
    $uploadOk = 1;
}//if


$type= $_POST["type"];
$FileTitle= $_POST["FileTitle"];
$dateUploaded=date("Y-m-d");

if($type == "Journal")
{
$fileType_id= 1;
}elseif($type == "Report")
{
  $fileType_id=2;
}else{
  $fileType_id=3;
}//else

$FilePath =substr($target_file, 30);
$query5 = "INSERT INTO File (filePath, title, status_id, dateUploaded, fileType_id, student_id)
VALUES ('$FilePath', '$FileTitle','3','$dateUploaded','$fileType_id','$UserID')";
$result = mysqli_query($conn, $query5);


/* Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}*/
/*Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}*/
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    header("Location: ../student_Upload.php?success=0");
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        header("Location: ../student.php?success=0");
    } else {
        header("Location: ../student.php?success=0");
    }//else
}//else
?>
