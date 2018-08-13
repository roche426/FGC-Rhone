<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="shared_files")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SharedFilesRepository")
 */
class SharedFiles
{
    const PUBLIC_ACCESS_FILE = 0;
    const MEMBERS_ACCESS_FILE = 1;
    const BUREAU_MEMBERS_ACCESS_FILE = 2;
    const ADMIN_ACCESS_FILE = 3;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="path_file", type="string")
     */
    private $pathFile;

    /**
     * @var string
     * @ORM\Column(name="name_file", type="string")
     */
    private $nameFile;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="subject", type="string")
     */
    private $subject;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_upload", type="date")
     */
    private $dateUpload;

    /**
     * @var int
     * @ORM\Column(name="file_access", type="smallint")
     */
    private $fileAccess;

    /**
     * @var bool
     * @ORM\Column(name="is_shared", type="boolean")
     */
    private $isShared;


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
    public function getPathFile()
    {
        return $this->pathFile;
    }

    /**
     * @param string $pathFile
     */
    public function setPathFile($pathFile)
    {
        $this->pathFile = $pathFile;
    }

    /**
     * @return string
     */
    public function getNameFile()
    {
        return $this->nameFile;
    }

    /**
     * @param string $nameFile
     */
    public function setNameFile($nameFile)
    {
        $this->nameFile = $nameFile;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpload()
    {
        return $this->dateUpload;
    }

    /**
     * @param \DateTime $dateUpload
     */
    public function setDateUpload($dateUpload)
    {
        $this->dateUpload = $dateUpload;
    }

    /**
     * @return int
     */
    public function getFileAccess()
    {
        return $this->fileAccess;
    }

    /**
     * @param int $fileAccess
     */
    public function setFileAccess($fileAccess)
    {
        $this->fileAccess = $fileAccess;
    }

    /**
     * @return bool
     */
    public function isShared()
    {
        return $this->isShared;
    }

    /**
     * @param bool $isShared
     */
    public function setIsShared($isShared)
    {
        $this->isShared = $isShared;
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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }




}
