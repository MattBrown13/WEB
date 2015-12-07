<?php
    require_once("model/dbSettings.php");
    require_once("model/ModelMethods.class.php");
    require_once("libs/Twig/Autoloader.php");
    require_once("controller/controller.class.php");

     if (!function_exists("printr")){
                function printr($val)
                {
                    echo "<hr><pre>";
                    print_r($val);
                    echo "</pre><hr>";
                }
    }

    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem("view/templates");
    $twig = new Twig_Environment($loader);

    $model = new ModelMethods();
    $controller = new Controller();

    $template = $twig->loadTemplate("default.twig");
    $msg = "";
    
    // If isnot set page
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }else{
        $page = "mainPage";
    }
    
    // Logout
    session_start();

    if(isset($_GET["logout"]) || !isset($_SESSION["login"])){
        $_SESSION["login"] = "Nepřihlášen";	
        $_SESSION["id"] = "-1";	
        $_SESSION["position"] = "Návštěvník";
    }

    $template_params = array();
    $menu = "adminMenu";

    switch ($_SESSION["position"]){
        case "Administrator":
            $menu = "adminMenu";
            switch ($page){
                case "mainPage":
                    $page = "mainPage";
                    break;
                case "userSettings";
                    $page = "userSettings";
                    break;
                case "allPosts":
                    if(isset($_GET["action"]) && ($_GET["action"] == "delete")){
                        $postId = $model->getPostId($_GET["postName"]);
                        $model->removePost($postId[0]["id"]);
                    }
                    $page = "allPosts";
                    if(isset($_GET["postId"])){
                        $model->removePost($_GET["postId"]);
                    }
                    $allPosts = $model->getAllPosts();
                    $allReview = $model->getAllReviews();
                    $allPosts = $controller->calcReview($allReview, $allPosts);
                    $template_params["allPosts"] = $allPosts;
                    break;
                case "singlePost":
                    $page = "singlePost";
                    $postName = $_GET["postName"];
                    $rows = $model->getSinglePost($postName);
                    $postId = $rows[0]["id"];
                    $postAuthor = $rows[0]["nick"];
                    $postContent = $rows[0]["content"];
                    $template_params["postName"] = $postName;
                    $template_params["postContent"] = $postContent;
                    $template_params["postAuthor"] = $postAuthor;
                    $template_params["file"] = $rows[0]["file"];
                    break;
                case "setReview":
                    $page = "setReview";
                    if(isset($_GET["reviewId"])){
                        $model->removeReview($_GET["reviewId"]);
                        $page= "setReview";
                    }
                    $template_params["allPosts"] = $model->getAllPosts();
                    $template_params["allUsers"] = $model->getReviewers();
                    $template_params["allReviews"] = $model->getAllReviews();
                    if(isset($_POST["postName"])){
                        $model->addReview($_POST["postName"], $_POST["recenzent"]);
                        $template_params["allPosts"] = $model->getAllPosts();
                        $template_params["allUsers"] = $model->getReviewers();
                        $template_params["allReviews"] = $model->getAllReviews();
                    }
                    break;
                case "allUsers":
                    $page = "allUsers";
                    if(isset($_GET["userId"])){
                        $model->removeUser($_GET["userId"]);
                    }
                    if(isset($_POST["position"])){
                        $id = $model->getID($_POST["nick"]);
                        $model->changePosition($_POST, $id);
                    }
                    $template_params["allUsers"] = $model->getAllUsers();
                    $template_params["positions"][0] = "Administrator";
                    $template_params["positions"][1] = "Autor";
                    $template_params["positions"][2] = "Recenzent";
                    break;
                default:
                    $page = "404";
                    break;
            }
            break;
        case "Autor":
            $menu = "authorMenu";
            switch ($page){
                case "mainPage":
                    $page = "mainPage";
                    break;
                case "userSettings";
                    $page = "userSettings";
                    break;
                case "newPost":
                    if($_SESSION["login"] == "Nepřihlášen"){
                        $page = "login";
                    }else{
                        $page = "newPost";
                        if(isset($_POST["post_name"])){
                            if($controller->checkDataNewPost($_POST)){
                                $_POST["author"] = $_SESSION["login"];
                                $_POST["fileToUpload"] = $_FILES["fileToUpload"]["name"];
                                $model->addNewPost($_POST);
                                $template_params["userPosts"] = $model->getUserPosts($_SESSION["id"]);
                                $page = "userPosts";
                                $msg = $controller->upload();
                            }else{
                                $msg = "Hodnoty nejsou správně vzplněny";
                            }   
                        }
                    }
                    break;
                case "allPosts":
                    $page = "allPosts";
                    $allPosts = $model->getAllPosts();
                    $allReview = $model->getAllReviews();
                    $allPosts = $controller->calcReview($allReview, $allPosts);
                    $template_params["allPosts"] = $allPosts;
                    break;
                case "userPosts":
                    if($_SESSION["login"] == "Nepřihlášen"){
                        $page = "login";
                    }else{
                        $page = "userPosts";
                        $template_params["userPosts"] = $model->getUserPosts($_SESSION["id"]);
                    }
                    break;
                case "userPost":
                    $page = "userPost";
                    if(isset($_GET["action"]) && ($_GET["action"] == "delete")){
                        $rows = $model->getPostByName($_GET["postName"], $_SESSION["id"]);
                        $model->removePost($rows[0]["id"]);
                        $page = "userPosts";
                        $template_params["userPosts"] = $model->getUserPosts($_SESSION["id"]);
                    }else{
                        $postName = $_GET["postName"];
                        $rows = $model->getPostByName($postName, $_SESSION["id"]);
                        $postId = $rows[0]["id"];
                        $postAuthor = $rows[0]["nick"];
                        $postContent = $rows[0]["content"];
                        $template_params["postName"] = $postName;
                        $template_params["postContent"] = $postContent;
                        $template_params["postId"] = $postId;
                        $template_params["postAuthor"] = $postAuthor;
                        $template_params["file"] = $rows[0]["file"];
                    }
                    break;
                case "singlePost":
                    $page = "singlePost";
                    $postName = $_GET["postName"];
                    $rows = $model->getSinglePost($postName);
                    $postId = $rows[0]["id"];
                    $postAuthor = $rows[0]["nick"];
                    $postContent = $rows[0]["content"];
                    $template_params["postName"] = $postName;
                    $template_params["postContent"] = $postContent;
                    $template_params["postAuthor"] = $postAuthor;
                    $template_params["file"] = $rows[0]["file"];
                    break;
                default:
                    $page = "404";
                    break;
            }
            break;
        case "Recenzent":
            $menu = "reviewMenu";
            switch ($page){
                case "mainPage":
                    $page = "mainPage";
                    break;
                case "userSettings";
                    $page = "userSettings";
                    if(isset($_POST["oldPassword"])){
                        $data["login"] = $_SESSION["login"];
                        $data["password"] = $_POST["oldPassword"];
                        if($model->checkUsersPassword($data)){
                            if($_POST["newPassword"] == $_POST["passwordControll"]){
                                $model->changePassword($_POST["newPassword"], $_SESSION["id"]);
                                $msg = "Heslo změněno.";
                            }else{
                                $msg = "Heslo nezměněno!";
                            }
                        }else{
                            $msg = "Heslo nezměněno!";
                        }
                    }
                    if(isset($_POST["deleteUser"])){
                        $model->deleteUser($_SESSION["id"]);
                        $_SESSION["login"] = "Nepřihlášen";	
                        $_SESSION["id"] = "-1";	
                        $_SESSION["position"] = "Návštěvník";
                        $page = "mainPage";
                        $menu = "unloginMenu";
                    }
                    
                    break;
                case "allPosts":
                    $page = "allPosts";
                    $allPosts = $model->getAllPosts();
                    $allReview = $model->getAllReviews();
                    $allPosts = $controller->calcReview($allReview, $allPosts);
                    $template_params["allPosts"] = $allPosts;
                    break;
                case "reviewPosts":
                    $page = "reviewPosts";
                    $data = $model->getUsersReview($_SESSION["id"]);
                    $template_params["allPosts"] = $data;
                    break;
                case "singlePost":
                    $page = "singlePost";
                    $postName = $_GET["postName"];
                    $rows = $model->getSinglePost($postName);
                    $postId = $rows[0]["id"];
                    $postAuthor = $rows[0]["nick"];
                    $postContent = $rows[0]["content"];
                    $template_params["postName"] = $postName;
                    $template_params["postContent"] = $postContent;
                    $template_params["postAuthor"] = $postAuthor;
                    $template_params["file"] = $rows[0]["file"];
                    break;
                case "reviewPost":
                    $page = "reviewPost";
                    $postName = $_GET["postName"];
                    $rows = $model->getSinglePost($postName);
                    $postId = $rows[0]["id"];
                    $postAuthor = $rows[0]["nick"];
                    $postContent = $rows[0]["content"];
                    $template_params["postName"] = $postName;
                    $template_params["postContent"] = $postContent;
                    $template_params["postAuthor"] = $postAuthor;
                    $template_params["file"] = $rows[0]["file"];
                    if(isset($_POST["post_name"])){
                        $_POST["id"] = $_SESSION["id"];
                        $data = $model->addReviewing($_POST)[0];
                        $data = $model->getUsersReview($_SESSION["id"]);
                        $template_params["allPosts"] = $data;
                        $page = "reviewPosts";
                    }
                    break;
                default:
                    $page = "404";
                    break;
            }
            break;
        case "Návštěvník":
            $menu = "unloginMenu";
             switch ($page){
                case "mainPage":
                     break;
                case "login":
                    $page = "login";
                    if($controller->checkDataLogin($_POST)){
                        if($model->checkUsersPassword($_POST)){
                            $login = $_POST["login"];
                            $id = $model->getID($login);
                            $position = $model->getPosition($login);
                            $_SESSION["login"] = $login;	
                            $_SESSION["id"] = $id;	
                            $_SESSION["position"] = $position;
                            $page = "mainPage";
                            switch ($_SESSION["position"]){
                                case "Administrator":
                                    $menu = "adminMenu";
                                    break;
                                case "Autor":
                                    $menu = "authorMenu";
                                    break;
                                case "Recenzent":
                                    $menu = "reviewMenu";  
                                    break;
                                case "Návštěvník":
                                    break;
                            }
                        }else{
                            $msg = "Špatné jméno nebo heslo!";
                        }
                    }
                    break;
                case "newUser":
                    $page = "newUser";
                    if(isset($_POST["login"])){
                        if($controller->checkDataNewUser($_POST)){
                            $model->addNewAuthor($_POST);
                            $login = $_POST["login"];
                            $id = $model->getID($login);
                            $position = $model->getPosition($login);
                            $_SESSION["login"] = $login;	
                            $_SESSION["id"] = $id;	
                            $_SESSION["position"] = $position;
                            $page = "mainPage";
                            $menu = "authorMenu";
                        }else{
                            $msg = "Špatné vyplněné údaje!";
                        }
                    }
                    break;
                default:
                    $page = "404";
                    break;
            }
            break;
    }
  
    $template_params["user"] = $_SESSION["login"];
    $template_params["position"] = $_SESSION["position"];
    $template_params["msg"] = $msg;
    $template_params["menu"] = $menu . ".twig";
    $template_params["page"] = $page . ".twig";

	echo $template->render($template_params);
?>