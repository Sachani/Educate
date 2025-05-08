<?php

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require '../includes/scripts/connection.php';  
  session_start();
  if(isset($_SESSION['educat_logedin_user_id']) && (trim ($_SESSION['educat_logedin_user_id']) !== '')){
    $user_id = $_SESSION['educat_logedin_user_id'];
        $query = "SELECT * FROM user_master WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        $userdata = mysqli_fetch_assoc($result);
        $user_role = $userdata["role"];
        if($user_role != 1 && $user_role != 2){
            header("Location: ../404.php");
        }
  }else{
      header("Location: ../sign-in.php");
  }


  
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      $id = $_POST['courseId'];
      $title = $_POST['course_title'];
      $language = $_POST['course_langauge'];
      $price = $_POST['course_price'];
      $category = $_POST['course_category'];
      $desc = $_POST['course_description'];


      if(!empty($title)){
          $sql = "UPDATE `course_master` SET `course_name`='$title'WHERE `course_id`= $id";
          mysqli_query($conn,$sql);
          $_SESSION["educat_success_message"] = "course details updated.";
          header("location: course-edit.php?courseId=$id");
      }
      if(!empty($language)){
        $sql = "UPDATE `course_master` SET `course_language`='$language' WHERE `course_id`= $id";
        mysqli_query($conn,$sql);
        $_SESSION["educat_success_message"] = "course details updated.";
        header("location: course-edit.php?courseId=$id");
      }
      if(!empty($price)){
        $sql = "UPDATE `course_master` SET `course_price`='$price' WHERE `course_id`= $id";
        mysqli_query($conn,$sql);
        $_SESSION["educat_success_message"] = "course details updated.";
        header("location: course-edit.php?courseId=$id");
      }
      if(!empty($category)){
        $sql = "UPDATE `course_master` SET `course_category`='$category' WHERE `course_id`= $id";
        mysqli_query($conn,$sql);
        $_SESSION["educat_success_message"] = "course details updated.";
        header("location: course-edit.php?courseId=$id");
      }
      if(!empty($desc)){
        $sql = "UPDATE `course_master` SET `course_description`='$desc' WHERE `course_id`= $id";
        mysqli_query($conn,$sql);
        $_SESSION["educat_success_message"] = "course details updated.";
        header("location: course-edit.php?courseId=$id");
      }

      // update image
      if (!empty($_FILES["upload_course_photo"]["name"])) {
        $target_dir = "course/uploads/course_featured_image/";
        $file_name = uniqid() . "_" . basename($_FILES["upload_course_photo"]["name"]);
        $target_path = $target_dir . $file_name;
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png");
        $max_file_size = 2 * 1024 * 1024; // 2 MB
      if (in_array($file_extension, $allowed_extensions) && $_FILES["upload_course_photo"]["size"] <= $max_file_size) {
       
            // If course information added successfully, update the image path
            if (move_uploaded_file($_FILES["upload_course_photo"]["tmp_name"], "../" . $target_path)) {
              
                $updateQuery = "UPDATE `course_master` SET `course_image`='$target_path' WHERE `course_id`='$id'";
                mysqli_query($conn, $updateQuery);

                $_SESSION["educat_success_message"] = "Success! Course is created.";
                header("location: course-edit.php?courseId=$id");
            } else {
                // If image upload fails, delete the previously added course information
                $deleteQuery = "DELETE FROM `course_master` WHERE `course_id`='$course_id'";
                mysqli_query($conn, $deleteQuery);

                $_SESSION["educat_error_message"] = "Failed to upload the file.";
                header("location: course-edit.php?courseId=$id");
            }
        } 
    } else {
        $_SESSION["educat_error_message"] = "Invalid file type or size. Please upload a JPG, JPEG, or PNG file (up to 2 MB).";
        header("location: course-edit.php?courseId=$id");
    }}

?>