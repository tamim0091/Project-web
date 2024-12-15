<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../Model/user.php';

class UserController
{
    public function listUsers()
    {
        $sql = "SELECT * FROM users";
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all users as an associative array
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function addUser($user)
    {
        $sql = "INSERT INTO users (FullName, Email, Password, PhoneNumber, Gender, Role, created_at, updated_at) 
                VALUES (:FullName, :Email, :Password, :PhoneNumber, :Gender, :Role, NOW(), NOW())";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'FullName'    => $user->getFullName(),
                'Email'       => $user->getEmail(),
                'Password'    => $user->getPassword(),
                'PhoneNumber' => $user->getPhoneNumber(),
                'Gender'      => $user->getGender(),
                'Role'        => $user->getRole()
            ]);
    
            return true;
        } catch (Exception $e) {
            echo 'Error in addUser: ' . $e->getMessage();
            return false;
        }
    }
    

    public function updateUser($user, $id)
    {
        $sql = "UPDATE users SET 
                    FullName = :FullName,
                    Email = :Email,
                    Password = :Password,
                    PhoneNumber = :PhoneNumber,
                    Gender = :Gender,
                    Role = :Role
                WHERE id = :id";

        $db = config::getConnexion();
        try {
            $password = $user->getPassword();
            if (empty($password)) {
                $currentUser = $this->showUser($id);
                $password = $currentUser['Password'];
            }

            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'FullName' => $user->getFullName(),
                'Email' => $user->getEmail(),
                'Password' => $password,
                'PhoneNumber' => $user->getPhoneNumber(),
                'Gender' => $user->getGender(),
                'Role' => $user->getRole()
            ]);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function showUser($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function searchUsers($name, $email)
    {
        $sql = "SELECT * FROM users WHERE FullName LIKE :name OR Email LIKE :email";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':name', "%$name%");
            $query->bindValue(':email', "%$email%");
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function makeAdmin($id)
    {
        $sql = "UPDATE users SET Role = 'Admin' WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // Demote a user to User
    public function makeUser($id)
    {
        $sql = "UPDATE users SET Role = 'User' WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
    

    public function login($identifier, $password, $useEmail = false)
    {
        $column = $useEmail ? 'Email' : 'Username';
        $sql = "SELECT * FROM users WHERE $column = :identifier LIMIT 1";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':identifier', $identifier, PDO::PARAM_STR);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['Password'])) {
                return $user;
            } else {
                return null;
            }
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    
    public function setRememberMeToken($userId, $token)
    {
        $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([':token' => $token, ':id' => $userId]);
    }
    
    public function getUserByToken($token)
    {
        $sql = "SELECT * FROM users WHERE remember_token = :token LIMIT 1";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([':token' => $token]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
 
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE Email = :email LIMIT 1";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function setResetToken($userId, $token, $expires)
    {
        $sql = "UPDATE users SET password_reset_token = :token, password_reset_expires = :expires WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            ':token' => $token,
            ':expires' => $expires,
            ':id' => $userId
        ]);
    }

    public function getUserByResetToken($token)
    {
        $sql = "SELECT * FROM users WHERE password_reset_token = :token LIMIT 1";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':token', $token, PDO::PARAM_STR);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($userId, $newPassword)
    {
        $sql = "UPDATE users SET Password = :password WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([
            ':password' => $newPassword,
            ':id' => $userId
        ]);
    }

    public function clearResetToken($userId)
    {
        $sql = "UPDATE users SET password_reset_token = NULL, password_reset_expires = NULL WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([':id' => $userId]);
    }

    
}
