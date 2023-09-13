<?php

    class User extends UsersModel {
        private $username;
        private $password;
        private $image;

        public function setRegistrationDetails($username, $password, $image) {
            $this->username = $username;
            $this->password = $password;
            $this->image = $image;
        }

        public function setImage($image) {
            $this->image = $image;
        }

        public function setUsername($username) {
            $this->username = $username;
        }

        private function getImageName() {
            return $this->image['name'];
        }

        private function getUserPostImage() {
            $general_folder_path = "../uploads/".$this->username."/posts/";

            if(!file_exists($general_folder_path)) {
                mkdir($general_folder_path);
            }
            

            $fileDestination = $general_folder_path."/".$this->getImageName();

            return $fileDestination;
        }

        private function getUserImageLocation() {
            $general_folder_path = "../uploads/";

            $specific_folder_path = $general_folder_path.$this->username;

            if(!file_exists($specific_folder_path)) {
                mkdir($specific_folder_path);
            }
            
            $fileDestination = $specific_folder_path."/".$this->getImageName();

            return $fileDestination;
        }

        public function emptyField() {
            if(empty($this->username) || empty($this->password)) {
                return true;
            } else {
                return false;
            }
        }

        public function registerUser() {
            $isUserNameFree = $this->isUsernameAvailable($this->username);

            if(!$isUserNameFree) {
                echo "Username taken!";
                //header("Location: ../index.php?register=username_error");
                exit();
            }

            $isSaved = $this->saveUser($this->username, $this->password, $this->getImageName());

            if(!$isSaved || !move_uploaded_file($this->image['tmp_name'], $this->getUserImageLocation())) {
                //header("Location: ../index.php?register=error");
                echo "Error with our servers";
                exit();
            }

            echo "You are registered, you can now login in";
            //header("Location:  ../index.php?register=success");
            exit();
        }

        public function loginUser() {
            $result = $this->login($this->username, $this->password);

            if ($result == "success") {
                echo "Login Successful*";
            } else {
                echo "Incorrect Credentials*";
            }
        }

        public function getUserData($id) {
            return $this->getCurrentUserData($id);
        }

        public function uploadPost($comment, $userid) {

            $isSaved = $this->savePost($userid, $this->getImageName(), $comment);
            
            if(!$isSaved || !move_uploaded_file($this->image['tmp_name'], $this->getUserPostImage())) {
                //header("Location: ../index.php?register=error");
                return "Error with our servers";
            }
            return"Your Post as been uploaded";
        }

        public function getUserUniquePosts($id) {
            return $this->getUserPosts($id);
        }


        public function deletePost($id) {
            $isDeleted = $this->removeUserPost($id);
            $isCommentRemoved = $this->deleteComments($id);

            if ($isDeleted && $isCommentRemoved) {
                header("Location: ../pages/user_profile.php?delete=success");
                exit();
            } 

            header("Location: ../pages/user_profile.php?delete=failed");
            exit();
        }

        public function getAllSavedPosts() {
            return $this->getAllPosts();
        }

        public function getNumberLikes($post_id) {
            return $this->getLikesCount($post_id);
        }

        public function isHeart($post_id, $user_id) {
            return $this->isLiked($user_id, $post_id);
        }

        public function likesHandler($post_id, $user_id) {
            $isHearted = $this->isLiked($user_id, $post_id);

            if ($isHearted == 'false') {
                $this->addNewLike($user_id, $post_id);
            } elseif ($isHearted == 0) {
                $this->updateLike($user_id, $post_id, 1);
            } elseif ($isHearted == 1) {
                $this->updateLike($user_id, $post_id, 0);
            }
        }

        public function is_following($user_id, $following) {
            return $this->isFollowing($user_id, $following);
        }

        public function followHandler($user_id, $following) {
            $isFollowing = $this->isFollowing($user_id, $following);

            if ($isFollowing == 'false') {
                $this->addNewFollow($user_id, $following);
                return 'Unfollow';
            } elseif ($isFollowing == 0) { 
                $this->updateFollowing($user_id, $following, 1);
                return 'Unfollow';
            } elseif ($isFollowing == 1) {
                $this->updateFollowing($user_id, $following, 0);
                return 'Follow';
            }
        }

        public function addComment($user_id, $post_id, $comment) {
            if ($this->postComment($post_id, $comment, $user_id)) {
                $userData = $this->getCurrentUserData($user_id);
                $data = array("username"=>$userData['username'], "image"=>$userData['profile_img'], "userId"=>$user_id);
                return $data;
            }
           
            return 'false';  
        }

        public function getCommentsForPost($post_id) {
            return $this->getAllComments($post_id);
        }

        public function getRandomUsers() {
            return $this->getFiveUsers();
        }

        public function findUser($username) {
            return $this->searchUser($username);
        }

        public function returnNumberOfFollowers($user_id) {
            return $this->countFollowers($user_id);
        }

        public function returnNumberOfFollowing($user_id) {
            return $this->countFollowing($user_id);
        }

    }