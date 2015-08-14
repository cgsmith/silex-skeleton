<?php
namespace App\Entities;

/**
 * @Entity
 * @HasLifecycleCallbacks
 */
class User
{
    /** @const ROLE_USER */
    const ROLE_USER = 0;

    /** @const ROLE_SALES */
    const ROLE_SALES = 1;

    /** @const ROLE_ADMIN */
    const ROLE_ADMIN = 2;

    /** @Id @GeneratedValue */
    private $id;

    /** @Column(type="string",nullable=false,unique=true) */
    protected $email;

    /** @Column(type="string",nullable=false) */
    protected $password;

    /** @Column(type="string",nullable=true) */
    protected $passwordReset;

    /** @Column(type="string",nullable=false) */
    protected $country;

    /** @Column(type="integer",nullable=false,options={default=0}) */
    protected $role = 0;

    /** @Column(type="boolean",nullable=false,options={default=0}) */
    protected $active = 0;

    /** @Column(type="datetime",nullable=false) */
    protected $createdDate;

    /** @Column(type="datetime") */
    protected $deletedDate;

    /**
     * @return mixed
     */
    public function getPasswordReset()
    {
        return $this->passwordReset;
    }

    /**
     * @param mixed $passwordReset
     */
    public function setPasswordReset($passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return mixed
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
    }

    /**
     * @param mixed $deletedDate
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deletedDate = $deletedDate;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @PrePersist
     * Sets the created date as the current date time
     */
    public function prePersistCreatedDate() {
        $this->setCreatedDate(new \DateTime());
    }

}