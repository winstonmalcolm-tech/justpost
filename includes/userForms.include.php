<?php 
    require '../classes/model/dbh.model.php';
    require '../classes/model/databaseMethods.model.php';
    require '../classes/controller/user.controller.php';

    if (isset($_POST["register_submit"])) {
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        $profile_image = $_FILES['profile_pic'];

        $user = new User();
        $user->setRegistrationDetails($username, $password, $profile_image);
        if($user->emptyField()) {
            echo "Please fill in all fields*";
            exit();
        }

        $user->registerUser();

    } elseif (isset($_POST["login_submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $user = new User();
        $user->setRegistrationDetails($username, $password, null);
        
        if($user->emptyField()) {
            echo "Please fill in all fields*";
        } else {
            $user->loginUser();
        }

    } elseif (isset($_POST['new_post_button'])) {
        $comment = $_POST['comment'];
        $image = $_FILES['image'];
        $userid = $_POST['id'];
        $username = $_POST['username'];

        $user = new User();
        $user->setImage($image);
        $user->setUsername($username);
        echo $user->uploadPost($comment, $userid);
        

    } elseif (isset($_POST['remove_post'])) {
        $post_id = $_POST['post_id'];
        $user = new User();
        $user->deletePost($post_id);

    } elseif (isset($_POST['likes_button'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        $user = new User();
        $user->likesHandler($post_id, $user_id);
        echo json_encode($user->getNumberLikes($post_id));
    } elseif (isset($_POST['follow_btn'])) {
        $user_post_id = $_POST['user_post_id'];
        $user_id = $_POST['user_id'];

        $user = new User();
        echo $user->followHandler($user_id, $user_post_id);

    } elseif (isset($_POST['comment_btn'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        $comment = $_POST['comment'];

        $user = new User();
        $response = $user->addComment($user_id, $post_id, $comment);

        if ($response == 'false') {
            echo 'Error';
        } else {
            echo json_encode($response);
        }

    }elseif (isset($_GET['logout'])) {
        session_start();
        unset($_SESSION['id']);
        unset($_SESSION['username']);
        session_destroy();
        header("Location: ../index.php");
    } elseif (isset($_GET['q'])) {
        $username = $_GET['q'];

        $user = new User();
        echo json_encode($user->findUser($username));
    }