<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Episode
 *
 * @ORM\Table(name="entity_episode")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Entity\EpisodeRepository")
 */
class Episode
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;
    /**
     * @var String
     * @ORM\Column(type="string",nullable=false)
     */
    private $name;
    /**
     * @var int
     * @ORM\Column(nullable=false)
     */
    private $episodeNumber;
    /**
     * @var DateTime
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $datePublish;
    /**
     * @var String
     * @ORM\Column(type="string",nullable=false)
     */
    private $description;
    /**
     * @var String
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 800,
     *     minHeight = 100,
     *     maxHeight = 800,
     *     allowLandscape = true,
     *     allowPortrait = true
     *     )
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="TvSeries" , inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tvSerie;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getEpisodeNumber()
    {
        return $this->episodeNumber;
    }

    /**
     * @param int $episodeNumber
     */
    public function setEpisodeNumber($episodeNumber)
    {
        $this->episodeNumber = $episodeNumber;
    }

    /**
     * @return DateTime
     */
    public function getDatePublish()
    {
        return $this->datePublish;
    }

    /**
     * @param DateTime $datePublish
     */
    public function setDatePublish($datePublish)
    {
        $this->datePublish = $datePublish;
    }

    /**
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param String $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return String
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param String $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getTvSerie()
    {
        return $this->tvSerie;
    }

    /**
     * @param mixed $tvSerie
     */
    public function setTvSerie($tvSerie)
    {
        $this->tvSerie = $tvSerie;
    }
}

