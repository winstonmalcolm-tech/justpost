<?php 
    session_start();
    require '../classes/model/dbh.model.php';
    require '../classes/model/databaseMethods.model.php';
    require '../classes/controller/user.controller.php';

    $styleShow = "";
    $styleHide = "";

    if (!isset($_GET['user_id'])) {
        header("Location: ../index.php");
        exit();  
    } 

    $user_id = $_GET['user_id'];

    if(isset($_SESSION['id'])) {
        $styleShow = "style='display: flex;'";
        $styleHide = "style='display: none;'";

        $loggedInuser = new User();
        $loggedInUserInfo = $loggedInuser->getUserData($_SESSION['id']);

        $username = $_SESSION['username'];

    } else {
        $styleShow = "style='display: none;'";
        $styleHide = "style='display: flex;'";
    }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/79de2b1b63.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="../style.css">
    <title>Events</title>
  </head>
  <body>
    <?php 
        $user = new User();
        $userInfo = $user->getUserData($user_id);
    ?>
    <nav>
        <div class="index__logo">
            <h1>Just Post🔥</h1>
        </div>
        <div class="index__search_bar">
            <input type="text" placeholder="Search" onkeyup="searchUser(this.value)" class="inputsearchbar">
            <i class="fa-solid fa-magnifying-glass searchicon" onclick="showInputbar()"></i>
            <div class="index__prediction_box">
                <dl>
                   
                </dl>
            </div>
        </div>
        <div class="index__user_handle" <?php echo $styleShow ?>>
            <img data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="index__profile_img" src="../uploads/<?php echo $loggedInUserInfo['username']."/".$loggedInUserInfo ['profile_img']; ?>" alt="">
            <div class="dropdown-menu index__picture_dropdown">
                <!-- Dropdown menu links -->
                <ul>
                    <li><?php echo "Hello " .$username?></li>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="./user_profile.php">My Profile</a></li>
                    <li><a href="../includes/userForms.include.php?logout=true">Log Out</a></li>
                </ul> 
            </div>
        </div>

        <div class="index__not_signed_in_handle" <?php echo $styleHide; ?>>
            <button class="index__new_post_button" data-toggle="modal" data-target="#loginModal">Login</button>
             <!-- Modal -->
            <div class="modal fade"  id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Login</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" class="index__login_form">
                                <input type="text" placeholder="Username" name="login_username" id="login_username" class="index__form_input_field" autocomplete="off">
                                <input type="password" placeholder="Password" name="login_password" id="login_password" class="index__form_input_field" autocomplete="off">
                                <button type="submit" id="index__login_button">Login</button>
                                <div class="spinner-border" id="login_spinner" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </form>
                            <p id="index__error_message2" style="color: crimson;"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Modal -->
            <button class="index__new_post_button" data-toggle="modal" data-target="#registerModal">Register</button>
            <!-- Modal -->
            <div class="modal fade"  id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Register</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="index__register_form" class="index__register_form" enctype='multipart/form-data'>
                                <div class="index__profile_pic_register">
                                    <img src="../img/noprofilepic.jpg" alt="" id="index__profile_photo">
                                    <input type="file" name="profile_pic" id="file">
                                    <label for="file" id="uploadBtn">Choose Photo</label>
                                </div>
                                <input type="text" placeholder="Username" id="username" name="username" class="index__form_input_field" autocomplete="off">
                                <input type="password" placeholder="Password" id="password" name="password" class="index__form_input_field" autocomplete="off">
                                <button type="submit" name="register_submit" id="index_register_button">Register</button>
                                <div class="spinner-border" id="register_spinner" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </form>
                            <p id="index__error_message" style="color: crimson;"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- End of Modal -->
        </div>
    </nav>
    <hr>
    <main class="user_profile__main">
        <div class="user_details__header">
            <div class="user_details__image_name">
                <div class="user_details__profile_img">
                    <img src="../uploads/<?php echo $userInfo['username'].'/'.$userInfo['profile_img']?>" alt="">
                </div>
                <span><h4><?php echo $userInfo['username'] ?></h4></span>
            </div>
            <div class="user_details__following_info">
                <h4>Followers: <?php echo $user->returnNumberOfFollowers($user_id)['numberOfFollowers']; ?></h4>
                <h4>Following: <?php echo $user->returnNumberOfFollowing($user_id)['numberOfFollowing']; ?></h4>
            </div>
        </div>
        <hr>

        <section class="user_details__post_display">
        <?php 
                $currentUser = new User();
                $currentUserData = $currentUser->getUserData($user_id);
                $userPosts = $currentUser->getUserUniquePosts($user_id);
                for($i=0; $i<count($userPosts); $i++) {
                    $numberOfLikes = $currentUser->getNumberLikes($userPosts[$i]['post_id']);
            ?>
            <div class="user_post__post_card">
                <div class="index__post_card_header">
                    <div class="index__post_card_header_img_name">
                        <img src="../uploads/<?php echo $currentUserData['username']."/".$currentUserData['profile_img']; ?>" alt="">
                        <span><?php echo $currentUserData['username']?></span> 
                    </div>
                    
                </div>
                <div class="user_profile__post_card_img">
                    <img src="../uploads/<?php echo $currentUserData['username']."/posts/".$userPosts[$i]['post_img']; ?>" alt="">
                </div>
                <div class="index__post_card_like_comment">
                    <div class="user_profile__likes">
                        <!-- <i class="fa-solid fa-heart " style="color:#ec3030;"></i> -->
                        <form class="index__likes_form" action="" method="post">
                            <input type="hidden" class="likes_post_id" value="<?php echo $userPosts[$i]['post_id']; ?>">
                            <input type="hidden" class="likes_user_id" value="<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : '';?>">
                            <?php
                                if (!isset($_SESSION['id'])) { 
                                    echo '<button type="submit" class="like_button"><i class="fa-solid fa-heart index__heart_icon"></i></button>';
                                } else {
                                    $isLiked = $currentUser->isHeart($userPosts[$i]['post_id'], $_SESSION['id']);
                                    if ($isLiked == 1) {
                                        echo '<button type="submit" class="like_button"><i class="fa-solid fa-heart index__heart_icon heart"></i></button>';
                                    } else {
                                        echo '<button type="submit" class="like_button"><i class="fa-solid fa-heart index__heart_icon"></i></button>';
                                    }
                                }
                            ?> 
                            <span class="likedisplay"><?php echo $numberOfLikes['postLikes']; ?></span>
                        </form>
                        
                    </div>
                    <div onclick="comment(<?php echo $currentUserData['username'].'_'.$userPosts[$i]['post_id'];?>)">
                        <i class="fa-solid fa-comment" data-toggle="modal" data-target="#<?php echo $currentUserData['username'].'_'.$userPosts[$i]['post_id'];?>"></i>
                    </div>
                    <!-- Modal start -->
                    <div class="modal animate__animated animate__zoomIn" id="<?php echo $currentUserData['username'].'_'.$userPosts[$i]['post_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body comment_container">
                                    <div class="all_comments">
                                        <dl class="user_comment">
                                        <?php 
                                            $comments = $currentUser->getCommentsForPost($userPosts[$i]['post_id']);

                                            foreach($comments as $comment) {
                                                $poster = $currentUser->getUserData($comment['user_id']);
                                                echo "
                                                    <dt><img src='../uploads/".$poster['username']."/".$poster['profile_img']."' alt='userimg'> " . $poster['username'] . "</dt>
                                                    <dd>&emsp; " . $comment['comment'] . "</dd>
                                                ";
                                            }

                                            ?>
                                        </dl>
                                    </div>
                                    <form class="comment_form">
                                        <input type="hidden" name="" class="post_id" value="<?php echo $userPosts[$i]['post_id'];?>">
                                        <input type="hidden" name="" class="user_id" value="<?php echo (isset($_SESSION['id'])) ? $_SESSION['id'] : '';?>">
                                        <textarea name="" class="comment_textarea" placeholder="Enter comment"></textarea>
                                        <button type="button"><i class="fa-regular fa-paper-plane"></i></button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Modal -->
                </div>
            </div>
            <?php 
                }
            ?>
        </section>

    </main>
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script>
        const socket = io('https://justpostserver.onrender.com/')
    </script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../js/postComment.js"></script>
    <script src="../js/registerAjaxForm.js"></script>
    <script src="../js/loginAjaxForm.js"></script>
    <script src="../js/search.js"></script>
    <script src="../js/likePost.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        const queryString = window.location.search
        const urlParams = new URLSearchParams(queryString)
        const result = urlParams.get('delete')
        
        if (result == "success") {
            Toastify({
                text: "Post deleted",
                duration: 3000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                  background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
                onClick: function(){} // Callback after click
              }).showToast();    
        } else if (result == "failed") {
            Toastify({
                text: "Post not deleted",
                duration: 3000,
                close: true,
                gravity: "bottom", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                  background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
                onClick: function(){} // Callback after click
              }).showToast();
        }
    </script>

</body>