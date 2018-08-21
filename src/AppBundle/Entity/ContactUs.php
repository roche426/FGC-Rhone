<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="contact_us")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactUsRepository")
 */
class ContactUs
{

    const CONTACT_MEMBERS_ACCES = 0;
    const CONTACT_VISITORS = 1;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string")
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="register", type="string", nullable=true)
     */
    private $register;

    /**
     * @var string
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var int
     * @ORM\Column(name="subject", type="smallint")
     */
    private $subject;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="response", type="text", nullable=true)
     */
    private $response;

    /**
     * @var \DateTime
     * @ORM\Column(name="is_treated", type="date", nullable=true)
     */
    private $isTreated;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * @param string $register
     */
    public function setRegister($register)
    {
        $this->register = $register;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param bool $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }


    /**
     * @return \DateTime
     */
    public function isTreated()
    {
        return $this->isTreated;
    }

    /**
     * @param \DateTime $isTreated
     */
    public function setIsTreated($isTreated)
    {
        $this->isTreated = $isTreated;
    }


}
