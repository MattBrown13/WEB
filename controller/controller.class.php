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
    
}


?>