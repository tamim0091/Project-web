<?php
class User
{
    // Private properties
    private ?int $id = null;
    private $FullName = null;
    private $Email = null;
    private $Password = null;
    private $PhoneNumber = null;
    private $ConfirmPassword = null;
    private $Gender = null;
    private $Role = null;

    // // Constructor to initialize properties
    // public function __construct($id=null,$FullName, $Email, $Password, $PhoneNumber, $ConfirmPassword, $Gender, $Role="User")
    // {
    //     $this->id=$id;
    //     $this->FullName = $FullName;
    //     $this->Email = $Email;
    //     $this->Password = $Password;
    //     $this->PhoneNumber = $PhoneNumber;
    //     $this->ConfirmPassword = $ConfirmPassword;
    //     $this->Gender = $Gender;
    //     $this->Role = "User";
    // }

    public function __construct($id = null,$FullName, $Email, $Password, $PhoneNumber, $ConfirmPassword, $Gender, $Role = "User")
{
    $this->id = $id;
    $this->FullName = $FullName;
    $this->Email = $Email;
    $this->Password = $Password;
    $this->PhoneNumber = $PhoneNumber;
    $this->ConfirmPassword = $ConfirmPassword;
    $this->Gender = $Gender;
    $this->Role = "User"; // Default value for Role if not provided
}


    // Getter and Setter for FullName
    public function getFullName()
    {
        return $this->FullName;
    }

    public function setFullName($FullName)
    {
        $this->FullName = $FullName;
    }

    // Getter and Setter for Email
    public function getEmail()
    {
        return $this->Email;
    }

    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    // Getter and Setter for Password
    public function getPassword()
    {
        return $this->Password;
    }

    public function setPassword($Password)
    {
        $this->Password = $Password;
    }

    // Getter and Setter for PhoneNumber
    public function getPhoneNumber()
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber($PhoneNumber)
    {
        $this->PhoneNumber = $PhoneNumber;
    }

    // Getter and Setter for ConfirmPassword
    public function getConfirmPassword()
    {
        return $this->ConfirmPassword;
    }

    public function setConfirmPassword($ConfirmPassword)
    {
        $this->ConfirmPassword = $ConfirmPassword;
    }

    // Getter and Setter for Gender
    public function getGender()
    {
        return $this->Gender;
    }

    public function setGender($Gender)
    {
        $this->Gender = $Gender;
    }

    // Getter and Setter for Role
    public function getRole()
    {
        return $this->Role;
    }

    public function setRole($Role)
    {
        $this->Role = $Role;
    }
}
?>
