<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="files_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FilesRepository")
 */
class Files
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="files")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="id_card", type="string",nullable=true)
     */
    private $idCard;

    /**
     * @var string
     * @ORM\Column(name="files2", type="string",nullable=true)
     */
    private $files2;

    /**
     * @var string
     * @ORM\Column(name="files3", type="string",nullable=true)
     */
    private $files3;

    /**
     * @var string
     * @ORM\Column(name="files4", type="string",nullable=true)
     */
    private $files4;

    /**
     * @var string
     * @ORM\Column(name="files5", type="string",nullable=true)
     */
    private $files5;

    /**
     * @var string
     * @ORM\Column(name="files6", type="string",nullable=true)
     */
    private $files6;



    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

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
     * @return string
     */
    public function getIdCard()
    {
        return $this->idCard;
    }

    /**
     * @param string $idCard
     */
    public function setIdCard($idCard)
    {
        $this->idCard = $idCard;
    }

    /**
     * @return string
     */
    public function getFiles2()
    {
        return $this->files2;
    }

    /**
     * @param string $files2
     */
    public function setFiles2($files2)
    {
        $this->files2 = $files2;
    }

    /**
     * @return string
     */
    public function getFiles3()
    {
        return $this->files3;
    }

    /**
     * @param string $files3
     */
    public function setFiles3($files3)
    {
        $this->files3 = $files3;
    }

    /**
     * @return string
     */
    public function getFiles4()
    {
        return $this->files4;
    }

    /**
     * @param string $files4
     */
    public function setFiles4($files4)
    {
        $this->files4 = $files4;
    }

    /**
     * @return string
     */
    public function getFiles5()
    {
        return $this->files5;
    }

    /**
     * @param string $files5
     */
    public function setFiles5($files5)
    {
        $this->files5 = $files5;
    }

    /**
     * @return string
     */
    public function getFiles6()
    {
        return $this->files6;
    }

    /**
     * @param string $files6
     */
    public function setFiles6($files6)
    {
        $this->files6 = $files6;
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}
