<?php

require_once './model/Model.class.php';

class ModelMethods extends Model{
	
    /**Return array of all users**/
    public function getAllUsers(){
        
        $query = "SELECT * FROM users ORDER BY position;";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function changePosition($data, $id){
        
        $position = $data["position"];
        
        $query = "UPDATE users SET position = '$position' WHERE id = '$id';";
            
        $statement = $this->connection->prepare($query);
        $statement->execute();
    }
    
    public function addReviewing($data){
           
        $postName = $data["post_name"];
        $query = "SELECT id FROM posts WHERE posts.post_name = '$postName';"; 
        
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        $topic = $data["topic"];
        $quality = $data["quality"];
        
        $idPost = $rows[0]["id"];
        $idUser = $data["id"];
        $query = "UPDATE review SET topic = '$topic', quality = '$quality' WHERE id_post = '$idPost' AND id_user = '$idUser';";
            
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        return $rows;
    }
    
    public function removePost($id){
        
        $query = "DELETE FROM posts WHERE id = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $query = "DELETE FROM review WHERE id_post = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
    }
    
    public function removeUser($id){
        
        $query = "DELETE FROM users WHERE id = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getReviewers(){
        
        $query = "SELECT nick, id FROM users WHERE position = 'Recenzent';";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function getAllReviews(){
        
        $query = "SELECT * FROM posts;";
        
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);    
        $size = sizeof($rows);
        for($i = 0; $i < $size; $i++){
            $idPost = $rows[$i]["id"];
            
            $query = "SELECT review.id id_post, nick, topic, quality FROM review, users WHERE review.id_post = '$idPost' AND review.id_user = users.id; ";

            $statement = $this->connection->prepare($query);
            $statement->execute();

            $rowsOne = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            if(sizeof($rowsOne) == 0){
                $rowsOne["size"] = 0;
                $rowsOne["postId"] = $idPost;
                $array[$i] = $rowsOne;
            }else{
                $rowsOne["size"] = sizeof($rowsOne);
                $rowsOne["postId"] = $idPost;
                $array[$i] = $rowsOne;
            }    
        }
            
        return $array;
    }
    
    public function removeReview($reviewId){
        
        $query = "DELETE FROM review WHERE id = '$reviewId';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUsersReview($id){
        
        $query = "SELECT post_name, nick FROM posts, review, users WHERE review.id_user = '$id' AND review.id_post = posts.id AND posts.id_user = users.id AND review.topic IS NULL;";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function addReview($postName, $reviewer){
        
        $query = "SELECT id FROM users WHERE nick = '$reviewer';";

        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $idUser = $rows[0]["id"];
        
        $query = "SELECT id FROM posts WHERE post_name = '$postName';";

        $statement = $this->connection->prepare($query);
        $statement->execute();

       $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $idPost = $rows[0]["id"];
        
        $query = "INSERT INTO `review`(`id_post`, `id_user`) VALUES ('$idPost','$idUser');";
        
        $this->connection->exec($query);
        return $idPost;
    }
    
    /**Return array with all posts(author, post name, content)**/
    public function getAllPosts(){
        
        $query = "SELECT nick, post_name, content, posts.id FROM posts, users WHERE posts.id_user = users.id;";
        
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    /**Add new post to database**/
    public function addNewPost($data){  
        
        $postName = $data["post_name"];
        $postText = $data["text"];
        $author = $data["author"];
        
        $query = "SELECT id FROM users WHERE nick = '$author';";
       
        
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as &$value) {
            $idAuthor = $value['id'];
        }
        
        $query = "INSERT INTO `posts`(`id_user`, `post_name`, `content`) VALUES ('$idAuthor','$postName','$postText');";
        
        $this->connection->exec($query);
    }
    
    public function deleteUser($id){
        
        $query = "DELETE FROM users WHERE id = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $query = "DELETE FROM posts WHERE id_user = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
    }
    
    /**Add new user to database**/
    public function addNewAuthor($data){
        
        $login = $data["login"];
        $password = $data["password"];
        $name = $data["name"];
        $email = $data["email"];
        
        $query = "INSERT INTO `users`(`position`, `first_name`, `email`, `nick`, `password`) VALUES ('Autor', '$name','$email','$login','$password');";
        
        $this->connection->exec($query);
    }
    
    public function changePassword($password, $id){
        
        $query = "UPDATE users SET password = '$password' WHERE users.id = '$id';";
            
        $statement = $this->connection->prepare($query);
        $statement->execute();
    }
    
    /**Return true, if user's password is ok**/
    public function checkUsersPassword($data){
        
        $login = $data["login"];
        $password = $data["password"];
        $loginDB = "";
        $passwordDB = "";
        
        $query = "SELECT nick, password FROM users WHERE nick = '$login' AND password = '$password';";
        $statement = $this->connection->prepare($query);
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as &$value) {
            $loginDB = $value['nick'];
            $passwordDB = $value['password'];
        }
        
        if(($login == $loginDB) && ($password == $passwordDB)){
            return true;
        }else{
            return false;
        }
    }
    
    /**Return user id**/
    public function getID($login){
        
        $query = "SELECT id FROM users WHERE nick = '$login';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as &$value) {
            $id = $value['id'];
            break;
        }
        
        return $id;
    }
    
    /**Return user position('Uživatel', 'Administrator')**/
    public function getPosition($login){
        
        $query = "SELECT position FROM users WHERE nick = '$login';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rows as &$value) {
            $position = $value['position'];
            if($position == "Administrator"){
                return "Administrator";
            }else if($position == "Autor"){
                return "Autor";
            }else{
                return "Recenzent";
            }
            break;
        }
    }
    
    /**Return user's post(nick, post name, content)**/
    public function getUserPosts($id){
        
        $query = "SELECT nick, post_name, content, posts.id FROM posts, users WHERE posts.id_user = '$id' AND users.id = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function getPost($id){
        
        $query = "SELECT nick, post_name, content FROM posts, users WHERE posts.id = '$id';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
     public function getSinglePost($postName){
        
        $query = "SELECT posts.id, nick, post_name, content FROM posts, users WHERE posts.post_name = '$postName' AND posts.id_user = users.id;";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function getPostId($postName){
        
        $query = "SELECT id FROM posts WHERE post_name = '$postName';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
    
    public function getPostByName($postName, $userId){
        
        $query = "SELECT nick, posts.id, content FROM posts, users WHERE posts.post_name = '$postName' AND posts.id_user = '$userId' AND users.id = '$userId';";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
}


?>