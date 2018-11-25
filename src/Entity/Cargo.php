<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cargo.
 *
 * Registra os tipos de cargos da empresa.
 *
 * @ORM\Table(name="cargo", uniqueConstraints={@ORM\UniqueConstraint(name="K_cargos", columns={"cargo"})})
 * @ORM\Entity(repositoryClass="App\Repository\CargoRepository")
 */
class Cargo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted", type="datetime", nullable=false)
     */
    private $inserted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cargo", type="string", length=100, nullable=true)
     */
    private $cargo;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getInserted(): DateTime
    {
        return $this->inserted;
    }

    /**
     * @param DateTime $inserted
     */
    public function setInserted(DateTime $inserted): void
    {
        $this->inserted = $inserted;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     */
    public function setUpdated(DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return null|string
     */
    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    /**
     * @param null|string $cargo
     */
    public function setCargo(?string $cargo): void
    {
        $this->cargo = $cargo;
    }


}
