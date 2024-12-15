<?php
class User
{
    private $id;
    private $FullName;
    private $Email;
    private $Password;
    private $PhoneNumber;
    private $Gender;
    private $Role;

    public function __construct(
        $id = null,
        $FullName = null,
        $Email = null,
        $Password = null,
        $PhoneNumber = null,
        $Gender = null,
        $Role = "User"
    ) {
        $this->id = $id;
        $this->FullName = $FullName;
        $this->Email = $Email;
        $this->Password = $Password;
        $this->PhoneNumber = $PhoneNumber;
        $this->Gender = $Gender;
        $this->Role = $Role;
    }

    public function getId() { return $this->id; }
    public function getFullName() { return $this->FullName; }
    public function setFullName($FullName) { $this->FullName = $FullName; }

    public function getEmail() { return $this->Email; }
    public function setEmail($Email) { $this->Email = $Email; }

    public function getPassword() { return $this->Password; }
    public function setPassword($Password) { $this->Password = $Password; }

    public function getPhoneNumber() { return $this->PhoneNumber; }
    public function setPhoneNumber($PhoneNumber) { $this->PhoneNumber = $PhoneNumber; }

    public function getGender() { return $this->Gender; }
    public function setGender($Gender) { $this->Gender = $Gender; }

    public function getRole() { return $this->Role; }
    public function setRole($Role) { $this->Role = $Role; }
}
