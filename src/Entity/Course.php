<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, inversedBy="courses")
     */
    private $Students;

    /**
     * @ORM\ManyToOne(targetEntity=Professeur::class, inversedBy="courses")
     */
    private $Professeur;

    public function __construct()
    {
        $this->Students = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->Students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->Students->contains($student)) {
            $this->Students[] = $student;
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $this->Students->removeElement($student);

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->Professeur;
    }

    public function setProfesseur(?Professeur $Professeur): self
    {
        $this->Professeur = $Professeur;

        return $this;
    }
}
