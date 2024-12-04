<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../Model/user.php';


class UserController
{
    // List all users
    public function listUsers()
    {
        $sql = "SELECT * FROM users"; // Assuming the table name is 'users'
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql); // Execute the query
            return $liste; // Return the list of users
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Delete a user by ID
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id"; // Assuming the primary key is 'id'
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id); // Bind the ID parameter

        try {
            $req->execute(); // Execute the query
        // Redirect to the same page
        header('Location:voyage-master\view\elegant\index.php');
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Add a new user
    public function addUser($user)
    {
        $sql = "INSERT INTO users (FullName, Username, Email, Password, PhoneNumber, ConfirmPassword, Gender, Role) 
            VALUES (:FullName, :Username, :Email, :Password, :PhoneNumber, :ConfirmPassword, :Gender, :Role)";
        $db = config::getConnexion();
        try {
            // Prepare and execute the insert query
            $query = $db->prepare($sql);
            $query->execute([
                'FullName' => $user->getFullName(),
                'Username' => $user->getUsername(), 
                'Email' => $user->getEmail(),
                'Password' => $user->getPassword(), // Hash the password
                'PhoneNumber' => $user->getPhoneNumber(),
                'ConfirmPassword' => $user->getConfirmPassword(),
                'Gender' => $user->getGender(),
                'Role' => $user->getRole()
            ]);

                    // Redirect to the same page
                    header('Location: /voyage-master/view/elegant/index.php');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // public function updateUser($user, $user_id) {
    //     try {
    //         $query = "UPDATE users SET
    //                     FullName = :FullName,
    //                     Email = :Email,
    //                     PhoneNumber = :PhoneNumber,
    //                     Password = :Password,
    //                     Gender = :Gender
    //                   WHERE id = :id";

    //         $stmt = $this->db->prepare($query);

    //         $stmt->bindParam(':FullName', $user->FullName, PDO::PARAM_STR);
    //         $stmt->bindParam(':Email', $user->Email, PDO::PARAM_STR);
    //         $stmt->bindParam(':PhoneNumber', $user->PhoneNumber, PDO::PARAM_STR);
    //         $stmt->bindParam(':Password', $user->Password, PDO::PARAM_STR);
    //         $stmt->bindParam(':Gender', $user->Gender, PDO::PARAM_STR);
    //         $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    //         $stmt->execute();

    //         // If rows are affected, return true
    //         return $stmt->rowCount() > 0;
    //     } catch (PDOException $e) {
    //         die("Error updating user: " . $e->getMessage());
    //     }
    // }
    public function updateUser($user, $id)
    {
        $sql = "UPDATE users SET 
                FullName = :FullName,
                Email = :Email,
                Password = :Password,
                PhoneNumber = :PhoneNumber,
                ConfirmPassword = :ConfirmPassword,
                Gender = :Gender,
                Role = :Role
                WHERE id = :id";
    
        $db = config::getConnexion();
        try {
            // Hash password if it's updated
            $password = $user->getPassword();
            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
            }
    
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'FullName' => $user->getFullName(),
                'Email' => $user->getEmail(),
                'Password' => $password,
                'PhoneNumber' => $user->getPhoneNumber(),
                'ConfirmPassword' => $user->getConfirmPassword(),
                'Gender' => $user->getGender(),
                'Role' => $user->getRole()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    
    

    // Show a specific user by ID
    public function showUser($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id"; // Fetch user by ID
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();

            $user = $query->fetch();
            return $user; // Return the user data
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Optional: Search for users based on specific parameters
    public function searchUsers($name, $email)
    {
        $sql = "SELECT * FROM users WHERE FullName LIKE :name OR Email LIKE :email";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':name', "%$name%");
            $query->bindValue(':email', "%$email%");
            $query->execute();
            
            $users = $query->fetchAll();
            return $users; // Return search results
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    
    // Get a list of all roles from the 'users' table (optional)
    public function listRoles()
    {
        $sql = "SELECT DISTINCT Role FROM users"; // Query for distinct roles
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql); // Execute the query
            return $liste; // Return the list of roles
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

     // Make a user an admin
     public function makeAdmin($id)
     {
         $sql = "UPDATE users SET Role = 'Admin' WHERE id = :id"; // Update user's role to 'Admin'
         $db = config::getConnexion();
         try {
             $query = $db->prepare($sql);
             $query->bindValue(':id', $id);
             $query->execute();
         } catch (Exception $e) {
             die('Error: ' . $e->getMessage());
         }
     }
 
     // Make an admin a regular user
     public function makeUser($id)
     {
         $sql = "UPDATE users SET Role = 'User' WHERE id = :id"; // Update user's role to 'User'
         $db = config::getConnexion();
         try {
             $query = $db->prepare($sql);
             $query->bindValue(':id', $id);
             $query->execute();
         } catch (Exception $e) {
             die('Error: ' . $e->getMessage());
         }
     }
}
?>
