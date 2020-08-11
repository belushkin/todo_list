<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Carbon\Carbon;

/**
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"task:read", "task:item:get"}}
 *          }, 
 *          "put"
 *      },
 *      normalizationContext={"groups"={"task:read"}, "datetime_format" = "Y-m-d H:i:s"},
 *      denormalizationContext={"groups"={"task:write"}},
 *      shortName="tasks",
 *      attributes={
 *          "pagination_items_per_page"=10
 *      }
 * )
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"isPublished"})
 * @ApiFilter(SearchFilter::class, properties={"title": "partial", "description": "partial"})
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"task:read", "task:write", "user:read"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"task:read", "task:write", "user:read"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min=2,
     *  max=50,
     *  maxMessage="Describe your task in 50 characters"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"task:read", "task:write", "user:read"})
     * @Assert\NotBlank()
     * @Assert\Type("\DateTimeInterface")
     */
    private $time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"task:read", "task:write"})
     */
    private $owner;

    
    public function __construct(string $title = null, string $description = null, \DateTime $time = null) {
        $this->title = $title;
        $this->description = $description;
        $this->time = $time;
        $this->createdAt = new \DateTimeImmutable();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * 
     * How long ago task was created
     * 
     * @Groups({"task:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
    
    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
