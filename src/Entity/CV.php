<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Version;

/**
 * CV.
 * Registra os CVs.
 *
 * @ORM\Table(name="cv", uniqueConstraints={@ORM\UniqueConstraint(name="UK_cv_cpf", columns={"cpf"})})
 * @ORM\Entity(repositoryClass="App\Repository\CVRepository")
 */
class CV
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
     * @var string
     *
     * @ORM\Column(name="cargos_pretendidos", type="string", length=300, nullable=false)
     */
    private $cargosPretendidos;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="cpf", type="string", length=11, nullable=false, options={"fixed"=true})
     */
    private $cpf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_nascimento", type="date", nullable=false)
     */
    private $dtNascimento;

    /**
     * @var int
     *
     * @ORM\Column(name="naturalidade_id", type="bigint", nullable=false)
     */
    private $naturalidadeId;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_atual_logr", type="string", length=300, nullable=false)
     */
    private $enderecoAtualLogr;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_atual_numero", type="string", length=6, nullable=false)
     */
    private $enderecoAtualNumero;

    /**
     * @var string|null
     *
     * @ORM\Column(name="endereco_atual_compl", type="string", length=50, nullable=true)
     */
    private $enderecoAtualCompl;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_atual_bairro", type="string", length=300, nullable=false)
     */
    private $enderecoAtualBairro;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_atual_cidade", type="string", length=300, nullable=false)
     */
    private $enderecoAtualCidade;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_atual_uf", type="string", length=2, nullable=false, options={"fixed"=true})
     */
    private $enderecoAtualUf;

    /**
     * @var string
     *
     * @ORM\Column(name="endereco_atual_tempo_resid", type="string", length=50, nullable=false)
     */
    private $enderecoAtualTempoResid;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone1", type="string", length=20, nullable=false)
     */
    private $telefone1;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone1_tipo", type="string", length=50, nullable=false)
     */
    private $telefone1Tipo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone2", type="string", length=20, nullable=true)
     */
    private $telefone2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone2_tipo", type="string", length=50, nullable=true)
     */
    private $telefone2Tipo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone3", type="string", length=20, nullable=true)
     */
    private $telefone3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone3_tipo", type="string", length=50, nullable=true)
     */
    private $telefone3Tipo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone4", type="string", length=20, nullable=true)
     */
    private $telefone4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone4_tipo", type="string", length=50, nullable=true)
     */
    private $telefone4Tipo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone5", type="string", length=20, nullable=true)
     */
    private $telefone5;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telefone5_tipo", type="string", length=50, nullable=true)
     */
    private $telefone5Tipo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_civil", type="string", length=2, nullable=false, options={"fixed"=true})
     */
    private $estadoCivil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conjuge_nome", type="string", length=100, nullable=true)
     */
    private $conjugeNome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conjuge_estado_trabalho", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $conjugeEstadoTrabalho;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conjuge_profissao", type="string", length=100, nullable=true)
     */
    private $conjugeProfissao;

    /**
     * @var string
     *
     * @ORM\Column(name="tem_filhos", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $temFilhos;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pai_nome", type="string", length=100, nullable=true)
     */
    private $paiNome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pai_profissao", type="string", length=100, nullable=true)
     */
    private $paiProfissao;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pai_estado_trabalho", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $paiEstadoTrabalho;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mae_nome", type="string", length=100, nullable=true)
     */
    private $maeNome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mae_profissao", type="string", length=100, nullable=true)
     */
    private $maeProfissao;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mae_estado_trabalho", type="string", length=2, nullable=true, options={"fixed"=true})
     */
    private $maeEstadoTrabalho;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia1_nome", type="string", length=100, nullable=true)
     */
    private $referencia1Nome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia1_relacao", type="string", length=100, nullable=true)
     */
    private $referencia1Relacao;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia1_telefone1", type="string", length=50, nullable=true)
     */
    private $referencia1Telefone1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia1_telefone2", type="string", length=50, nullable=true)
     */
    private $referencia1Telefone2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia2_nome", type="string", length=100, nullable=true)
     */
    private $referencia2Nome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia2_relacao", type="string", length=100, nullable=true)
     */
    private $referencia2Relacao;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia2_telefone1", type="string", length=50, nullable=true)
     */
    private $referencia2Telefone1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="referencia2_telefone2", type="string", length=50, nullable=true)
     */
    private $referencia2Telefone2;

    /**
     * @var string
     *
     * @ORM\Column(name="ensino_fundamental_status", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $ensinoFundamentalStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ensino_fundamental_local", type="string", length=50, nullable=true)
     */
    private $ensinoFundamentalLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="ensino_medio_status", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $ensinoMedioStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ensino_medio_local", type="string", length=50, nullable=true)
     */
    private $ensinoMedioLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="ensino_superior_status", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $ensinoSuperiorStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ensino_superior_local", type="string", length=50, nullable=true)
     */
    private $ensinoSuperiorLocal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ensino_demais_obs", type="string", length=3000, nullable=true)
     */
    private $ensinoDemaisObs;

    /**
     * @var int|null
     *
     * @ORM\Column(name="conhece_a_empresa_tempo", type="integer", nullable=true)
     */
    private $conheceAEmpresaTempo;

    /**
     * @var string
     *
     * @ORM\Column(name="eh_nosso_cliente", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $ehNossoCliente;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parente1_ficha_crediario_nome", type="string", length=100, nullable=true)
     */
    private $parente1FichaCrediarioNome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parente2_ficha_crediario_nome", type="string", length=100, nullable=true)
     */
    private $parente2FichaCrediarioNome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conhecido1_trabalhou_na_empresa", type="string", length=100, nullable=true)
     */
    private $conhecido1TrabalhouNaEmpresa;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conhecido2_trabalhou_na_empresa", type="string", length=100, nullable=true)
     */
    private $conhecido2TrabalhouNaEmpresa;

    /**
     * @var string|null
     *
     * @ORM\Column(name="motivos_quer_trabalhar_aqui", type="string", length=3000, nullable=true)
     */
    private $motivosQuerTrabalharAqui;

    /**
     * @var string|null
     *
     * @ORM\Column(name="senha", type="string", length=200, nullable=true)
     */
    private $senha;

    /**
     *
     * @var CVExperProfis[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="CVExperProfis",
     *      mappedBy="cv",
     *      orphanRemoval=true
     * )
     */
    private $experProfis;

    /**
     *
     * @var CVFilho[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="CVFilho",
     *      mappedBy="cv",
     *      orphanRemoval=true
     * )
     */
    private $filhos;

    /**
     * @var string
     *
     * @ORM\Column(name="email_confirmado", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $emailConfirmado;

    /**
     * @var string
     *
     * @ORM\Column(name="email_confirm_uuid", type="string", length=45, nullable=false)
     */
    private $emailConfirmUUID;


    public function __construct()
    {
        $this->experProfis = new ArrayCollection();
        $this->filhos = new ArrayCollection();
    }

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
     * @return string
     */
    public function getCargosPretendidos(): string
    {
        return $this->cargosPretendidos;
    }

    /**
     * @param string $cargosPretendidos
     */
    public function setCargosPretendidos(string $cargosPretendidos): void
    {
        $this->cargosPretendidos = $cargosPretendidos;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return DateTime
     */
    public function getDtNascimento(): DateTime
    {
        return $this->dtNascimento;
    }

    /**
     * @param DateTime $dtNascimento
     */
    public function setDtNascimento(DateTime $dtNascimento): void
    {
        $this->dtNascimento = $dtNascimento;
    }

    /**
     * @return int
     */
    public function getNaturalidadeId(): int
    {
        return $this->naturalidadeId;
    }

    /**
     * @param int $naturalidadeId
     */
    public function setNaturalidadeId(int $naturalidadeId): void
    {
        $this->naturalidadeId = $naturalidadeId;
    }

    /**
     * @return string
     */
    public function getEnderecoAtualLogr(): string
    {
        return $this->enderecoAtualLogr;
    }

    /**
     * @param string $enderecoAtualLogr
     */
    public function setEnderecoAtualLogr(string $enderecoAtualLogr): void
    {
        $this->enderecoAtualLogr = $enderecoAtualLogr;
    }

    /**
     * @return string
     */
    public function getEnderecoAtualNumero(): string
    {
        return $this->enderecoAtualNumero;
    }

    /**
     * @param string $enderecoAtualNumero
     */
    public function setEnderecoAtualNumero(string $enderecoAtualNumero): void
    {
        $this->enderecoAtualNumero = $enderecoAtualNumero;
    }

    /**
     * @return null|string
     */
    public function getEnderecoAtualCompl(): ?string
    {
        return $this->enderecoAtualCompl;
    }

    /**
     * @param null|string $enderecoAtualCompl
     */
    public function setEnderecoAtualCompl(?string $enderecoAtualCompl): void
    {
        $this->enderecoAtualCompl = $enderecoAtualCompl;
    }

    /**
     * @return string
     */
    public function getEnderecoAtualBairro(): string
    {
        return $this->enderecoAtualBairro;
    }

    /**
     * @param string $enderecoAtualBairro
     */
    public function setEnderecoAtualBairro(string $enderecoAtualBairro): void
    {
        $this->enderecoAtualBairro = $enderecoAtualBairro;
    }

    /**
     * @return string
     */
    public function getEnderecoAtualCidade(): string
    {
        return $this->enderecoAtualCidade;
    }

    /**
     * @param string $enderecoAtualCidade
     */
    public function setEnderecoAtualCidade(string $enderecoAtualCidade): void
    {
        $this->enderecoAtualCidade = $enderecoAtualCidade;
    }

    /**
     * @return string
     */
    public function getEnderecoAtualUf(): string
    {
        return $this->enderecoAtualUf;
    }

    /**
     * @param string $enderecoAtualUf
     */
    public function setEnderecoAtualUf(string $enderecoAtualUf): void
    {
        $this->enderecoAtualUf = $enderecoAtualUf;
    }

    /**
     * @return string
     */
    public function getEnderecoAtualTempoResid(): string
    {
        return $this->enderecoAtualTempoResid;
    }

    /**
     * @param string $enderecoAtualTempoResid
     */
    public function setEnderecoAtualTempoResid(string $enderecoAtualTempoResid): void
    {
        $this->enderecoAtualTempoResid = $enderecoAtualTempoResid;
    }

    /**
     * @return string
     */
    public function getTelefone1(): string
    {
        return $this->telefone1;
    }

    /**
     * @param string $telefone1
     */
    public function setTelefone1(string $telefone1): void
    {
        $this->telefone1 = $telefone1;
    }

    /**
     * @return string
     */
    public function getTelefone1Tipo(): string
    {
        return $this->telefone1Tipo;
    }

    /**
     * @param string $telefone1Tipo
     */
    public function setTelefone1Tipo(string $telefone1Tipo): void
    {
        $this->telefone1Tipo = $telefone1Tipo;
    }

    /**
     * @return null|string
     */
    public function getTelefone2(): ?string
    {
        return $this->telefone2;
    }

    /**
     * @param null|string $telefone2
     */
    public function setTelefone2(?string $telefone2): void
    {
        $this->telefone2 = $telefone2;
    }

    /**
     * @return null|string
     */
    public function getTelefone2Tipo(): ?string
    {
        return $this->telefone2Tipo;
    }

    /**
     * @param null|string $telefone2Tipo
     */
    public function setTelefone2Tipo(?string $telefone2Tipo): void
    {
        $this->telefone2Tipo = $telefone2Tipo;
    }

    /**
     * @return null|string
     */
    public function getTelefone3(): ?string
    {
        return $this->telefone3;
    }

    /**
     * @param null|string $telefone3
     */
    public function setTelefone3(?string $telefone3): void
    {
        $this->telefone3 = $telefone3;
    }

    /**
     * @return null|string
     */
    public function getTelefone3Tipo(): ?string
    {
        return $this->telefone3Tipo;
    }

    /**
     * @param null|string $telefone3Tipo
     */
    public function setTelefone3Tipo(?string $telefone3Tipo): void
    {
        $this->telefone3Tipo = $telefone3Tipo;
    }

    /**
     * @return null|string
     */
    public function getTelefone4(): ?string
    {
        return $this->telefone4;
    }

    /**
     * @param null|string $telefone4
     */
    public function setTelefone4(?string $telefone4): void
    {
        $this->telefone4 = $telefone4;
    }

    /**
     * @return null|string
     */
    public function getTelefone4Tipo(): ?string
    {
        return $this->telefone4Tipo;
    }

    /**
     * @param null|string $telefone4Tipo
     */
    public function setTelefone4Tipo(?string $telefone4Tipo): void
    {
        $this->telefone4Tipo = $telefone4Tipo;
    }

    /**
     * @return null|string
     */
    public function getTelefone5(): ?string
    {
        return $this->telefone5;
    }

    /**
     * @param null|string $telefone5
     */
    public function setTelefone5(?string $telefone5): void
    {
        $this->telefone5 = $telefone5;
    }

    /**
     * @return null|string
     */
    public function getTelefone5Tipo(): ?string
    {
        return $this->telefone5Tipo;
    }

    /**
     * @param null|string $telefone5Tipo
     */
    public function setTelefone5Tipo(?string $telefone5Tipo): void
    {
        $this->telefone5Tipo = $telefone5Tipo;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEstadoCivil(): string
    {
        return $this->estadoCivil;
    }

    /**
     * @param string $estadoCivil
     */
    public function setEstadoCivil(string $estadoCivil): void
    {
        $this->estadoCivil = $estadoCivil;
    }

    /**
     * @return null|string
     */
    public function getConjugeNome(): ?string
    {
        return $this->conjugeNome;
    }

    /**
     * @param null|string $conjugeNome
     */
    public function setConjugeNome(?string $conjugeNome): void
    {
        $this->conjugeNome = $conjugeNome;
    }

    /**
     * @return null|string
     */
    public function getConjugeEstadoTrabalho(): ?string
    {
        return $this->conjugeEstadoTrabalho;
    }

    /**
     * @param null|string $conjugeEstadoTrabalho
     */
    public function setConjugeEstadoTrabalho(?string $conjugeEstadoTrabalho): void
    {
        $this->conjugeEstadoTrabalho = $conjugeEstadoTrabalho;
    }

    /**
     * @return null|string
     */
    public function getConjugeProfissao(): ?string
    {
        return $this->conjugeProfissao;
    }

    /**
     * @param null|string $conjugeProfissao
     */
    public function setConjugeProfissao(?string $conjugeProfissao): void
    {
        $this->conjugeProfissao = $conjugeProfissao;
    }

    /**
     * @return string
     */
    public function getTemFilhos(): string
    {
        return $this->temFilhos;
    }

    /**
     * @param string $temFilhos
     */
    public function setTemFilhos(string $temFilhos): void
    {
        $this->temFilhos = $temFilhos;
    }

    /**
     *
     * @return Collection|CVFilho[]
     */
    public function getFilhos(): Collection
    {
        return $this->filhos;
    }

    /**
     * @return null|string
     */
    public function getPaiNome(): ?string
    {
        return $this->paiNome;
    }

    /**
     * @param null|string $paiNome
     */
    public function setPaiNome(?string $paiNome): void
    {
        $this->paiNome = $paiNome;
    }

    /**
     * @return null|string
     */
    public function getPaiProfissao(): ?string
    {
        return $this->paiProfissao;
    }

    /**
     * @param null|string $paiProfissao
     */
    public function setPaiProfissao(?string $paiProfissao): void
    {
        $this->paiProfissao = $paiProfissao;
    }

    /**
     * @return null|string
     */
    public function getPaiEstadoTrabalho(): ?string
    {
        return $this->paiEstadoTrabalho;
    }

    /**
     * @param null|string $paiEstadoTrabalho
     */
    public function setPaiEstadoTrabalho(?string $paiEstadoTrabalho): void
    {
        $this->paiEstadoTrabalho = $paiEstadoTrabalho;
    }

    /**
     * @return null|string
     */
    public function getMaeNome(): ?string
    {
        return $this->maeNome;
    }

    /**
     * @param null|string $maeNome
     */
    public function setMaeNome(?string $maeNome): void
    {
        $this->maeNome = $maeNome;
    }

    /**
     * @return null|string
     */
    public function getMaeProfissao(): ?string
    {
        return $this->maeProfissao;
    }

    /**
     * @param null|string $maeProfissao
     */
    public function setMaeProfissao(?string $maeProfissao): void
    {
        $this->maeProfissao = $maeProfissao;
    }

    /**
     * @return null|string
     */
    public function getMaeEstadoTrabalho(): ?string
    {
        return $this->maeEstadoTrabalho;
    }

    /**
     * @param null|string $maeEstadoTrabalho
     */
    public function setMaeEstadoTrabalho(?string $maeEstadoTrabalho): void
    {
        $this->maeEstadoTrabalho = $maeEstadoTrabalho;
    }

    /**
     * @return null|string
     */
    public function getReferencia1Nome(): ?string
    {
        return $this->referencia1Nome;
    }

    /**
     * @param null|string $referencia1Nome
     */
    public function setReferencia1Nome(?string $referencia1Nome): void
    {
        $this->referencia1Nome = $referencia1Nome;
    }

    /**
     * @return null|string
     */
    public function getReferencia1Relacao(): ?string
    {
        return $this->referencia1Relacao;
    }

    /**
     * @param null|string $referencia1Relacao
     */
    public function setReferencia1Relacao(?string $referencia1Relacao): void
    {
        $this->referencia1Relacao = $referencia1Relacao;
    }

    /**
     * @return null|string
     */
    public function getReferencia1Telefone1(): ?string
    {
        return $this->referencia1Telefone1;
    }

    /**
     * @param null|string $referencia1Telefone1
     */
    public function setReferencia1Telefone1(?string $referencia1Telefone1): void
    {
        $this->referencia1Telefone1 = $referencia1Telefone1;
    }

    /**
     * @return null|string
     */
    public function getReferencia1Telefone2(): ?string
    {
        return $this->referencia1Telefone2;
    }

    /**
     * @param null|string $referencia1Telefone2
     */
    public function setReferencia1Telefone2(?string $referencia1Telefone2): void
    {
        $this->referencia1Telefone2 = $referencia1Telefone2;
    }

    /**
     * @return null|string
     */
    public function getReferencia2Nome(): ?string
    {
        return $this->referencia2Nome;
    }

    /**
     * @param null|string $referencia2Nome
     */
    public function setReferencia2Nome(?string $referencia2Nome): void
    {
        $this->referencia2Nome = $referencia2Nome;
    }

    /**
     * @return null|string
     */
    public function getReferencia2Relacao(): ?string
    {
        return $this->referencia2Relacao;
    }

    /**
     * @param null|string $referencia2Relacao
     */
    public function setReferencia2Relacao(?string $referencia2Relacao): void
    {
        $this->referencia2Relacao = $referencia2Relacao;
    }

    /**
     * @return null|string
     */
    public function getReferencia2Telefone1(): ?string
    {
        return $this->referencia2Telefone1;
    }

    /**
     * @param null|string $referencia2Telefone1
     */
    public function setReferencia2Telefone1(?string $referencia2Telefone1): void
    {
        $this->referencia2Telefone1 = $referencia2Telefone1;
    }

    /**
     * @return null|string
     */
    public function getReferencia2Telefone2(): ?string
    {
        return $this->referencia2Telefone2;
    }

    /**
     * @param null|string $referencia2Telefone2
     */
    public function setReferencia2Telefone2(?string $referencia2Telefone2): void
    {
        $this->referencia2Telefone2 = $referencia2Telefone2;
    }

    /**
     * @return string
     */
    public function getEnsinoFundamentalStatus(): string
    {
        return $this->ensinoFundamentalStatus;
    }

    /**
     * @param string $ensinoFundamentalStatus
     */
    public function setEnsinoFundamentalStatus(string $ensinoFundamentalStatus): void
    {
        $this->ensinoFundamentalStatus = $ensinoFundamentalStatus;
    }

    /**
     * @return null|string
     */
    public function getEnsinoFundamentalLocal(): ?string
    {
        return $this->ensinoFundamentalLocal;
    }

    /**
     * @param null|string $ensinoFundamentalLocal
     */
    public function setEnsinoFundamentalLocal(?string $ensinoFundamentalLocal): void
    {
        $this->ensinoFundamentalLocal = $ensinoFundamentalLocal;
    }

    /**
     * @return string
     */
    public function getEnsinoMedioStatus(): string
    {
        return $this->ensinoMedioStatus;
    }

    /**
     * @param string $ensinoMedioStatus
     */
    public function setEnsinoMedioStatus(string $ensinoMedioStatus): void
    {
        $this->ensinoMedioStatus = $ensinoMedioStatus;
    }

    /**
     * @return null|string
     */
    public function getEnsinoMedioLocal(): ?string
    {
        return $this->ensinoMedioLocal;
    }

    /**
     * @param null|string $ensinoMedioLocal
     */
    public function setEnsinoMedioLocal(?string $ensinoMedioLocal): void
    {
        $this->ensinoMedioLocal = $ensinoMedioLocal;
    }

    /**
     * @return string
     */
    public function getEnsinoSuperiorStatus(): string
    {
        return $this->ensinoSuperiorStatus;
    }

    /**
     * @param string $ensinoSuperiorStatus
     */
    public function setEnsinoSuperiorStatus(string $ensinoSuperiorStatus): void
    {
        $this->ensinoSuperiorStatus = $ensinoSuperiorStatus;
    }

    /**
     * @return null|string
     */
    public function getEnsinoSuperiorLocal(): ?string
    {
        return $this->ensinoSuperiorLocal;
    }

    /**
     * @param null|string $ensinoSuperiorLocal
     */
    public function setEnsinoSuperiorLocal(?string $ensinoSuperiorLocal): void
    {
        $this->ensinoSuperiorLocal = $ensinoSuperiorLocal;
    }

    /**
     * @return null|string
     */
    public function getEnsinoDemaisObs(): ?string
    {
        return $this->ensinoDemaisObs;
    }

    /**
     * @param null|string $ensinoDemaisObs
     */
    public function setEnsinoDemaisObs(?string $ensinoDemaisObs): void
    {
        $this->ensinoDemaisObs = $ensinoDemaisObs;
    }

    /**
     * @return int|null
     */
    public function getConheceAEmpresaTempo(): ?int
    {
        return $this->conheceAEmpresaTempo;
    }

    /**
     * @param int|null $conheceAEmpresaTempo
     */
    public function setConheceAEmpresaTempo(?int $conheceAEmpresaTempo): void
    {
        $this->conheceAEmpresaTempo = $conheceAEmpresaTempo;
    }

    /**
     * @return string
     */
    public function getEhNossoCliente(): string
    {
        return $this->ehNossoCliente;
    }

    /**
     * @param string $ehNossoCliente
     */
    public function setEhNossoCliente(string $ehNossoCliente): void
    {
        $this->ehNossoCliente = $ehNossoCliente;
    }

    /**
     * @return null|string
     */
    public function getParente1FichaCrediarioNome(): ?string
    {
        return $this->parente1FichaCrediarioNome;
    }

    /**
     * @param null|string $parente1FichaCrediarioNome
     */
    public function setParente1FichaCrediarioNome(?string $parente1FichaCrediarioNome): void
    {
        $this->parente1FichaCrediarioNome = $parente1FichaCrediarioNome;
    }

    /**
     * @return null|string
     */
    public function getParente2FichaCrediarioNome(): ?string
    {
        return $this->parente2FichaCrediarioNome;
    }

    /**
     * @param null|string $parente2FichaCrediarioNome
     */
    public function setParente2FichaCrediarioNome(?string $parente2FichaCrediarioNome): void
    {
        $this->parente2FichaCrediarioNome = $parente2FichaCrediarioNome;
    }

    /**
     * @return null|string
     */
    public function getConhecido1TrabalhouNaEmpresa(): ?string
    {
        return $this->conhecido1TrabalhouNaEmpresa;
    }

    /**
     * @param null|string $conhecido1TrabalhouNaEmpresa
     */
    public function setConhecido1TrabalhouNaEmpresa(?string $conhecido1TrabalhouNaEmpresa): void
    {
        $this->conhecido1TrabalhouNaEmpresa = $conhecido1TrabalhouNaEmpresa;
    }

    /**
     * @return null|string
     */
    public function getConhecido2TrabalhouNaEmpresa(): ?string
    {
        return $this->conhecido2TrabalhouNaEmpresa;
    }

    /**
     * @param null|string $conhecido2TrabalhouNaEmpresa
     */
    public function setConhecido2TrabalhouNaEmpresa(?string $conhecido2TrabalhouNaEmpresa): void
    {
        $this->conhecido2TrabalhouNaEmpresa = $conhecido2TrabalhouNaEmpresa;
    }

    /**
     * @return null|string
     */
    public function getMotivosQuerTrabalharAqui(): ?string
    {
        return $this->motivosQuerTrabalharAqui;
    }

    /**
     * @param null|string $motivosQuerTrabalharAqui
     */
    public function setMotivosQuerTrabalharAqui(?string $motivosQuerTrabalharAqui): void
    {
        $this->motivosQuerTrabalharAqui = $motivosQuerTrabalharAqui;
    }

    /**
     * @return null|string
     */
    public function getSenha(): ?string
    {
        return $this->senha;
    }

    /**
     * @param null|string $senha
     */
    public function setSenha(?string $senha): void
    {
        $this->senha = $senha;
    }

    /**
     *
     * @return Collection|CVExperProfis[]
     */
    public function getExperProfis(): Collection
    {
        return $this->experProfis;
    }

    /**
     * @return string
     */
    public function getEmailConfirmado(): string
    {
        return $this->emailConfirmado;
    }

    /**
     * @param string $emailConfirmado
     */
    public function setEmailConfirmado(string $emailConfirmado): void
    {
        $this->emailConfirmado = $emailConfirmado;
    }

    /**
     * @return string
     */
    public function getEmailConfirmUUID(): string
    {
        return $this->emailConfirmUUID;
    }

    /**
     * @param string $emailConfirmUUID
     */
    public function setEmailConfirmUUID(string $emailConfirmUUID): void
    {
        $this->emailConfirmUUID = $emailConfirmUUID;
    }


}
