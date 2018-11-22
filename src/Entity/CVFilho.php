<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * CVFilho.
 *
 * Registra os filhos para o CV.
 *
 * @ORM\Table(name="cv_filho", uniqueConstraints={@ORM\UniqueConstraint(name="K_cv_filho_01", columns={"cv_id", "nome"})})
 * @ORM\Entity
 */
class CVFilho
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
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\CV",inversedBy="filhos")
     * @ORM\JoinColumn(name="cv_id", nullable=false)
     *
     * @var $cv CV
     */
    private $cv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nome", type="string", length=100, nullable=true)
     */
    private $nome;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dt_nascimento", type="datetime", nullable=true)
     */
    private $dtNascimento;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ocupacao", type="string", length=100, nullable=true)
     */
    private $ocupacao;

    /**
     * @var string|null
     *
     * @ORM\Column(name="obs", type="string", length=3000, nullable=true)
     */
    private $obs;

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
     * @return CV
     */
    public function getCv(): CV
    {
        return $this->cv;
    }

    /**
     * @param CV $cv
     */
    public function setCv(CV $cv): void
    {
        $this->cv = $cv;
    }

    /**
     * @return null|string
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param null|string $nome
     */
    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return DateTime|null
     */
    public function getDtNascimento(): ?DateTime
    {
        return $this->dtNascimento;
    }

    /**
     * @param DateTime|null $dtNascimento
     */
    public function setDtNascimento(?DateTime $dtNascimento): void
    {
        $this->dtNascimento = $dtNascimento;
    }

    /**
     * @return null|string
     */
    public function getOcupacao(): ?string
    {
        return $this->ocupacao;
    }

    /**
     * @param null|string $ocupacao
     */
    public function setOcupacao(?string $ocupacao): void
    {
        $this->ocupacao = $ocupacao;
    }

    /**
     * @return null|string
     */
    public function getObs(): ?string
    {
        return $this->obs;
    }

    /**
     * @param null|string $obs
     */
    public function setObs(?string $obs): void
    {
        $this->obs = $obs;
    }


}
