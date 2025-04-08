<?php
    class UsersModel extends Dbh {

        protected function saveUser($username, $password, $imagePath) {
            $sql = "INSERT INTO user_tbl (username,password,profile_img) VALUES (?,?,?)";
            $stmt = $this->dbconnect()->prepare($sql);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sss", $username, $hashed_password, $imagePath);

            if($stmt->execute()) {
                $stmt->close();
                $this->dbconnect()->close();
                return true;
            } else {
                echo $stmt->error;
                exit();
            }
        }

        protected function isUsernameAvailable($username) {
            $sql = "SELECT username from user_tbl where username=?";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("s",$username);

            if($stmt->execute()) {
                $result = $stmt->get_result();

                if($result->num_rows > 0) {
                    $stmt->close();
                    $this->dbconnect()->close();
                    return false;
                } else {
                    $stmt->close();
                    $this->dbconnect()->close();
                    return true;
                }
            } else {
                echo $stmt->error;
                exit();
            }
            
        }

        protected function login($username, $password) {
            $sql = "SELECT * FROM user_tbl WHERE username=?;";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    if (password_verify($password, $row['password'])) {
                        session_start();
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['id'] = $row['user_id'];
                        $stmt->close();
                        $this->dbconnect()->close();
                        return "success";
                        
                    } else {
                        $stmt->close();
                        $this->dbconnect()->close();
                        return "incorrect";
                    }
                } else {
                    return "incorrect";
                }
            }

            return "incorrect";
        }

        protected function getCurrentUserData($id) {
            $sql = "SELECT * FROM user_tbl where user_id=?;";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $stmt->close();
                $this->dbconnect()->close();
                return $data;
            } else {
                var_dump($stmt->error);
            }
        }

        protected function savePost($user_id, $imageName, $comment) {
            $sql = "INSERT INTO post_tbl (user_id, post_img) VALUES (?,?);";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("is", $user_id, $imageName);

            if($stmt->execute()) {
                $post_id = $stmt->insert_id;
                $stmt->close();
                $this->dbconnect()->close();
                $this->initialComment($post_id, $comment, $user_id);
                return true;
            } else {
                echo $stmt->error;
                exit();
            }
        }

        protected function initialComment($post_id, $comment, $user_id) {
            $sql = "INSERT INTO comment_tbl (post_id, user_id, comment) VALUES (?,?,?) LIMIT 1;";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("iis", $post_id, $user_id, $comment);

            if(!$stmt->execute()) {
               var_dump($stmt->error);
            }
            return true;
        }

        protected function postComment($post_id, $comment, $user_id) {
            $sql = "INSERT INTO comment_tbl (post_id, user_id, comment) VALUES (?,?,?);";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("iis", $post_id, $user_id, $comment);

            if(!$stmt->execute()) {
               var_dump($stmt->error);
               return false;
            }
            return true;
        }

        //Fetch all the comments for a specific post
        protected function getAllComments($post_id) {
            $sql = "SELECT * FROM comment_tbl WHERE post_id=?";

            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('i', $post_id);

            $data = array();

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                
                while($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                }
                $stmt->close();
                $this->dbconnect()->close();
                return $data;
            } else {
                var_dump($stmt->error);
            }
        }

        protected function getUserPosts($id) {
            $sql = "SELECT * FROM post_tbl WHERE user_id=?";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param("i", $id);
            $data = array();

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                }
                $stmt->close();
                $this->dbconnect()->close();
                return $data;
            } else {
                var_dump($stmt->error);
            }
        }

        protected function removeUserPost($id) {
            $sql = "DELETE FROM post_tbl WHERE post_id=?";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('i', $id);

            if ($stmt->execute()) {
                $stmt->close();
                $this->dbconnect()->close();
                return true;
            }
            return false;
        }

        //A function that gets executed when user deletes a post; All the comments related to that post, this function will remove them
        protected function deleteComments($id) {
            $sql = "DELETE FROM comment_tbl WHERE post_id=?";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('i', $id);

            if ($stmt->execute()) {
                $stmt->close();
                $this->dbconnect()->close();
                return true;
            }

            return false;
        }

        protected function getAllPosts() {
            $sql = "SELECT * FROM post_tbl ORDER BY post_id DESC;";
            $tableonedata = array();

            $stmt = $this->dbconnect()->prepare($sql);

            if ($stmt->execute()) {
                $tabeloneresult = $stmt->get_result();
                while ($row = $tabeloneresult->fetch_assoc()) {
                    array_push($tableonedata, $row);
                }
                array_push($tableonedata);
                $stmt->close();
                $this->dbconnect()->close();
                return $tableonedata;
            } else {
                var_dump($stmt->error);
            }
        }

        protected function getLikesCount($post_id) {
            $sql = "SELECT COUNT(post_id) AS postLikes FROM likes WHERE post_id=? AND liked=1"; // Receive likes that are true

            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('i', $post_id);

            if($stmt->execute()) {
                $result = $stmt->get_result();
                $count = $result->fetch_assoc();
                $stmt->close();
                $this->dbconnect()->close();
                return $count;
                
            } else {
                var_dump($stmt->error);
            }
        }

        //This function serves to tell if a user has liked the post before
        protected function isLiked($user_id, $post_id) {
            $sql = "SELECT liked FROM likes where user_id=? and post_id=? LIMIT 1;";

            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('ii', $user_id, $post_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $liked = $result->fetch_assoc();
                    $stmt->close();
                    $this->dbconnect()->close();
                    return $liked['liked']; // value is 1 or 0

                } else {
                    $stmt->close();
                    $this->dbconnect()->close();
                    return 'false';
                }

            }
        }

        protected function addNewLike($user_id, $post_id) {
            $sql = "INSERT INTO likes (`post_id`, `user_id`, `liked`) VALUES (?,?,?);";

            $stmt = $this->dbconnect()->prepare($sql);
            $value = 1;
            $stmt->bind_param('iii', $post_id, $user_id, $value);

            if (!$stmt->execute()) {
                var_dump($stmt->error);
                return false;
            }

            $stmt->close();
            $this->dbconnect()->close();
            return true;
        }

        protected function updateLike($user_id, $post_id, $updatedValue) {
            $sql = "UPDATE likes SET liked=? WHERE user_id=? AND post_id=?";
            
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('iii', $updatedValue, $user_id, $post_id);

            if (!$stmt->execute()) {
                var_dump($stmt->error);
            }
            $stmt->close();
            $this->dbconnect()->close();

            return true;
        }

        
        protected function countFollowing($user_id) {
            //Count the number of followers a user has
            $sql = "SELECT COUNT(id) AS numberOfFollowing FROM followers_tbl WHERE user_id=? AND following=1;";

            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('i', $user_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $count = $result->fetch_assoc();
                $stmt->close();
                $this->dbconnect()->close();
                return $count;
                
            } else {
                var_dump($stmt->error);
            }
        }

        protected function countFollowers($user_id) {
            //Count the number of users the logged in user is following
            $sql = "SELECT COUNT(id) AS numberOfFollowers FROM followers_tbl WHERE followers_id=? AND following=1;";

            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('i', $user_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $count = $result->fetch_assoc();
                $stmt->close();
                $this->dbconnect()->close();
                return $count;
                
            } else {
                var_dump($stmt->error);
            }

        }

        protected function isFollowing($user_id, $following) {
            $sql = "SELECT following FROM followers_tbl WHERE user_id=? AND followers_id=?;";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('ii', $user_id, $following);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $following = $result->fetch_assoc();
                    $stmt->close();
                    $this->dbconnect()->close();
                    return $following['following'];
                } else {
                    $stmt->close();
                    $this->dbconnect()->close();
                    return 'false';
                }
            } else {
                var_dump($stmt->error);
            }
        }

        protected function addNewFollow($user_id, $following) {
            $sql = "INSERT INTO followers_tbl (user_id, followers_id, following) VALUES (?,?,?);";
            $stmt = $this->dbconnect()->prepare($sql);
            $follow = 1;
            $stmt->bind_param('iii', $user_id, $following, $follow);

            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

        protected function updateFollowing($user_id, $following, $updatedValue) {
            $sql = "UPDATE followers_tbl SET following=? WHERE user_id=? AND followers_id=?;";
            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('iii', $updatedValue, $user_id, $following);

            if ($stmt->execute()) {
                return true;
            }

            return false;
        }

        protected function getFiveUsers() {
            $sql = "SELECT `username`, `user_id`, `profile_img` FROM user_tbl ORDER BY RAND() LIMIT 4";
            $stmt = $this->dbconnect()->prepare($sql);

            $data = array();
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                }

                $stmt->close();
                $this->dbconnect()->close();

                return $data;
            } else {
                var_dump($stmt->error);
            }
        }

        protected function searchUser($username) {
            $name = "%$username%"; 
            $sql = "SELECT `user_id`, `username`, `profile_img` FROM user_tbl WHERE username LIKE ?;";

            $stmt = $this->dbconnect()->prepare($sql);
            $stmt->bind_param('s',$name);

            $data = array();

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                }

                $stmt->close();
                $this->dbconnect()->close();

                return $data;
            } else {
                var_dump($stmt->error);
                $stmt->close();
                $this->dbconnect()->close();
            }
        }


    }