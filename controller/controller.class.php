<?php

class Controller {
    
    public function checkDataNewUser($data){
        
        if(isset($_POST["login"])& isset($_POST["password"]) & isset($_POST["passwordControll"]) & isset($_POST["name"])
                    & isset($_POST["email"])){
            $login = $data["login"];
            $password = $data["password"];
            $passwordControll = $data["passwordControll"];
            $name = $data["name"];
            $email = $data["email"];

            if(($login != "") & ($password == $passwordControll) & ($name != "") & ($email != "")){
                return true;
            }else{
                return false;   
            }
        }else{
            return false;
        }
    }
    
    public function checkDataNewPost($data){
        
        if(isset($_POST["post_name"]) & isset($_POST["text"])){
            $postName = $data["post_name"];
            $postText = $data["text"];

            if(($postName != "") & ($postText != "")){
                return true;
            }else{
                return false;   
            }
        }else{
            return false;
        }
    }
    
    public function checkDataLogin($data){
        
        if(isset($_POST["login"])& isset($_POST["password"])){
            $login = $data["login"];
            $password = $data["password"];

            if(($login != "") || ($password != "")){
                return true;
            }else{
                return false;   
            }
        }else{
            return false;
        }
    }
    
    public function calcReview($allReview, $allPosts){
        
        $i = 0;
        foreach($allReview as $value1){
            $j = 0;
            $mean = 0;
            foreach($value1 as $value){
                if(isset($value["topic"]) && isset($value["quality"])){
                    if(($value["topic"] != NULL) && ($value["quality"] != NULL)){
                        $mean = $mean + ($value["topic"] + $value["quality"]);
                    $j = $j + 2;
                    }   
                }                
            }
            if($mean != 0){
                $mean = $mean / $j;
            }
            $allPosts[$i]["mean"] = $mean;
            $i = $i + 1;
        }
        return $allPosts;
    }
    
    public function upload(){
        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $msg = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $msg = "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $msg = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $msg = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $msg = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $msg = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                $msg = "Sorry, there was an error uploading your file.";
            }
        }
        return $msg;
    }
    
}


?>