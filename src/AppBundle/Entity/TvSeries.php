<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Tv Series
 * @author youssef
 *
 * @ORM\Entity
 */
class TvSeries
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
     * @var String
     * @ORM\Column(nullable=true)
     */
    private $author;
    /**
     * @var DateTime
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $releasedAt;
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
     * @ORM\OneToMany(targetEntity="Episode" , mappedBy="tvSerie")
     */
    private $episodes;


/************************************ getter et setter ************************************/

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
     * @return String
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param String $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return DateTime
     */
    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    /**
     * @param DateTime $releasedAt
     */
    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;
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
     * Constructor
     */
    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    /**
     * Add episode
     *
     * @param  $episode
     *
     * @return TvSeries
     */
    public function addEpisode(Episode $episode)
    {
        $this->episodes[] = $episode;
        $episode->setTvSerie($this);
        return $this;
    }

    /**
     * Remove episode
     *
     * @param $episode
     */
    public function removeEpisode(Episode $episode)
    {
        $this->episodes->removeElement($episode);
    }

    /**
     * Get episodes
     *
     * @return Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }
}
