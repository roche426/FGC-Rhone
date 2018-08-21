<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;


/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    const CHAIRMAN = 1;
    const TREASURER = 2 ;
    const SECRETARY = 3 ;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=25)
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=25)
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=254, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     * @ORM\Column(name="city", type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(name="postal_code", type="string", nullable=true)
     */
    private $postalCode;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(name="additional_address", type="string", nullable=true)
     */
    private $additionalAddress;

    /**
     * @var \DateTime
     * @ORM\Column(name="registration_date", type="date")
     */
    private $registrationDate;

    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = false;

    /**
     * @var bool
     * @ORM\Column(name="is_admin", type="boolean")
     */
    private $isAdmin = false;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=true, unique=true)
     */
    private $token;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="smallint", nullable=true)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_profil", type="string", nullable=true)
     */
    private $pictureProfil;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_at", type="date", nullable=true)
     */
    private $deleteAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="disable_at", type="date", nullable=true)
     */
    private $disableAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Blog", mappedBy="user", cascade={"persist"})
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user", cascade={"persist"})
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="Files", mappedBy="user", cascade={"remove"})
     */
    private $files;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }


    public function getUsername()
    {
        return $this->email;
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
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param int $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $adress
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAdditionalAddress()
    {
        return $this->additionalAddress;
    }

    /**
     * @param string $additionalAddress
     */
    public function setAdditionalAddress($additionalAddress)
    {
        $this->additionalAddress = $additionalAddress;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param \DateTime $registrationDate
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getPictureProfil()
    {
        return $this->pictureProfil;
    }

    /**
     * @param string $pictureProfil
     */
    public function setPictureProfil($pictureProfil)
    {
        $this->pictureProfil = $pictureProfil;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return \DateTime
     */
    public function getDeleteAt()
    {
        return $this->deleteAt;
    }

    /**
     * @param \DateTime $deleteAt
     */
    public function setDeleteAt($deleteAt)
    {
        $this->deleteAt = $deleteAt;
    }

    /**
     * @return \DateTime
     */
    public function getDisableAt()
    {
        return $this->disableAt;
    }

    /**
     * @param \DateTime $disableAt
     */
    public function setDisableAt($disableAt)
    {
        $this->disableAt = $disableAt;
    }



/* ============================================ */

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }


    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        if (!$this->isAdmin()) {
            return array('ROLE_USER');
        }

        return array('ROLE_ADMIN');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,

            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);

    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
}
