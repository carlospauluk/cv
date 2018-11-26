<?php

namespace App\Business;

use App\Entity\CV;
use App\Entity\CVExperProfis;
use App\Entity\CVFilho;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;

class CVBusiness extends BaseBusiness
{

    private $swiftMailer;

    private $container;

    /**
     *
     *
     * @param $cpf
     * @return bool
     */
    public function checkCadastroOk($cpf)
    {
        $cv = $this->getDoctrine()->getRepository(CV::class)->findOneBy(['cpf' => $cpf]);
        if ($cv and $cv->getEmailConfirmado() == 'S') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Salva o CPF, e-mail e a senha criptografada e envia o e-mail para confirmação.
     *
     * @param $cpf
     * @param $email
     * @param $senha
     * @throws \Exception
     */
    public function novo($cpf, $email, $senha)
    {
        try {
            $this->getDoctrine()->getEntityManager()->beginTransaction();
            $cv = $this->getDoctrine()->getRepository(CV::class)->findOneBy(['cpf' => $cpf]);
            if (!$cv) {
                $cv = new CV();
            }
            $cv->setCpf($cpf);
            $cv->setEmail($email);
            $passwordEncoder = new Pbkdf2PasswordEncoder();
            $hashed = $passwordEncoder->encodePassword($senha, $cpf);
            $cv->setSenha($hashed);
            $cv->setInserted(new \DateTime());
            $cv->setUpdated(new \DateTime());
            $cv->setEmailConfirmado('N');
            $cv->setEmailConfirmUUID(md5(uniqid(rand(), true)));
            try {
                $this->getDoctrine()->getEntityManager()->persist($cv);
                $this->getDoctrine()->getEntityManager()->flush();
            } catch (ORMException $e) {
                throw new \Exception('Erro ao salvar novo registro', 0, $e);
            }
            if (!$this->enviarEmailNovo($cv)) {
                throw new \Exception('Não foi possível enviar o e-mail de confirmação.');
            }
            $this->getDoctrine()->getEntityManager()->commit();
        } catch (\Exception $e) {
            $this->getDoctrine()->getEntityManager()->rollback();
            throw new \Exception('Erro ao salvar registro.', 0, $e);
        }
    }

    public function enviarEmailNovo(CV $cv)
    {
        $link = getenv('LINK_CONFIRM_EMAIL') . '?cv=' . $cv->getId() . '&uuid=' . $cv->getEmailConfirmUUID();
        $body = $this->container->get('twig')->render('emailConfirm.html.twig', ['link' => $link]);
        $message = (new \Swift_Message('Confirmação de cadastro.'))
            ->setFrom('mailer@casabonsucesso.com.br', 'Casa Bonsucesso')
            ->setSubject('Confirmação de Cadastro - Cadastro de Currículos')
            ->setTo($cv->getEmail())
            ->setBody($body, 'text/html');
        return $this->getSwiftMailer()->send($message);
    }

    /**
     * @param $cpf
     * @return int
     * @throws \Exception
     */
    public function reenviarSenha($cpf)
    {
        $cv = $this->getDoctrine()->getRepository(CV::class)->findOneBy(['cpf' => $cpf]);
        if (!$cv) {
            throw new \Exception('Cadastro não encontrado.');
        }
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $novaSenhaTemp = implode($pass); //turn the array into a string

        $passwordEncoder = new Pbkdf2PasswordEncoder();
        $hashed = $passwordEncoder->encodePassword($novaSenhaTemp, $cpf);
        $cv->setSenhaTemp($hashed);
        $cv->setUpdated(new \DateTime());
        $this->getDoctrine()->getEntityManager()->flush();


        $body = $this->container->get('twig')->render('emailNovaSenha.html.twig', ['novaSenha' => $novaSenhaTemp]);
        $message = (new \Swift_Message())
            ->setFrom('mailer@casabonsucesso.com.br', 'Casa Bonsucesso')
            ->setSubject('Cadastro de Currículos - Nova Senha')
            ->setTo($cv->getEmail())
            ->setBody($body, 'text/html');
        return $this->getSwiftMailer()->send($message);
    }

    /**
     * @param $cpf
     * @param $password
     * @return bool
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function login($cpf, $password)
    {
        $cv = $this->getDoctrine()->getRepository(CV::class)->findOneBy(['cpf' => $cpf]);
        if (!$cv) {
            throw new \Exception('Cadastro não encontrado.');
        }
        $passwordEncoder = new Pbkdf2PasswordEncoder();
        if ($passwordEncoder->isPasswordValid($cv->getSenha(), $password, $cpf)) {
            // Primeiro testa com a senha normal.
            $session = new Session();
            $session->set('cvId', $cv->getId()); // o cvId no session define o status de logado
            return true;
        } else if ($passwordEncoder->isPasswordValid($cv->getSenhaTemp(), $password, $cpf)) {
            // Se não logar, verifica se entra com a temporária (gerada pelo "Esqueci minha senha").
            $session = new Session();
            $session->set('cvId', $cv->getId());
            $cv->setSenha($cv->getSenhaTemp());
            $cv->setSenhaTemp(null);
            $this->getDoctrine()->getEntityManager()->flush();
            return true;
        } else {
            return false;

        }
    }

    /**
     * Confirma o e-mail do usuário.
     *
     * @param $cvId
     * @param $uuid
     * @return bool
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function confirmEmail($cvId, $uuid)
    {
        $cv = $this->getDoctrine()->getRepository(CV::class)->find($cvId);
        if (!$cv or $cv->getEmailConfirmUUID() != $uuid) {
            return false;
        } else {
            $cv->setEmailConfirmado('S');
            $this->getDoctrine()->getEntityManager()->flush();
            return $cv;
        }
    }

    /**
     * @param $cvId
     * @param $senhaAtual
     * @param $novaSenha
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function alterarSenha($cvId, $senhaAtual, $novaSenha)
    {
        $cv = $this->getDoctrine()->getRepository(CV::class)->find($cvId);
        if (!$cv) {
            throw new \Exception('Cadastro não encontrado.');
        }
        $passwordEncoder = new Pbkdf2PasswordEncoder();
        if (!$passwordEncoder->isPasswordValid($cv->getSenha(), $senhaAtual, $cv->getCpf())) {
            throw new \Exception('Senha atual inválida.');
        } else {
            $novaSenhaHash = $passwordEncoder->encodePassword($novaSenha, $cv->getCpf());
            $cv->setSenha($novaSenhaHash);
            $this->getDoctrine()->getEntityManager()->flush();
        }

    }

    /**
     * Salvar o CV na base.
     *
     * @param CV $cv
     * @return bool
     * @throws \Exception
     */
    public function saveCv(CV $cv)
    {
        try {
            $cv->setUpdated(new \DateTime());
            $this->getDoctrine()->getEntityManager()->merge($cv);
            $this->getDoctrine()->getEntityManager()->flush();
            return true;
        } catch (ORMException $e) {
            // FIXME: melhorar a mensagem.
            throw new \Exception('Erro ao salvar os dados. Por favor, entre em contato com o suporte.');
        }
    }

    /**
     * @param CV $cv
     * @param $arrFilhos
     * @return CV
     * @throws \Exception
     */
    public function saveFilhos(CV $cv, $arrFilhos)
    {
        try {
            $cv->getFilhos()->clear();
            $this->getDoctrine()->getEntityManager()->flush();
            foreach ($arrFilhos as $filho) {
                $cvFilho = new CVFilho();
                $cvFilho->setCv($cv);
                $cvFilho->setInserted(new \DateTime());
                $cvFilho->setUpdated(new \DateTime());
                $cvFilho->setNome($filho['nome']);
                $cvFilho->setDtNascimento(\DateTime::createFromFormat('d/m/Y', $filho['dtNascimento']));
                $cvFilho->setOcupacao($filho['ocupacao']);
                $cvFilho->setObs($filho['obs']);
                $this->getDoctrine()->getEntityManager()->persist($cvFilho);
                $this->getDoctrine()->getEntityManager()->flush();
            }
            $this->getDoctrine()->getEntityManager()->refresh($cv);
            return $cv;
        } catch (ORMException $e) {
            throw new \Exception('Erro ao salvar dados dos filhos.');
        }
    }

    /**
     *
     * @param CV $cv
     * @return false|string
     */
    public function dadosFilhos2JSON(CV $cv)
    {
        $dadosFilhosJSON = [];
        if ($cv and $cv->getFilhos()) {
            foreach ($cv->getFilhos() as $filho) {
                $d['nome'] = $filho->getNome();
                $d['dtNascimento'] = $filho->getDtNascimento()->format('Y-m-d');
                $d['ocupacao'] = $filho->getOcupacao();
                $d['obs'] = $filho->getObs();
                $dadosFilhosJSON[] = $d;
            }
        }
        return json_encode($dadosFilhosJSON);
    }

    /**
     * @param CV $cv
     * @param $arrFilhos
     * @return CV
     * @throws \Exception
     */
    public function saveEmpregos(CV $cv, $arrEmpregos)
    {
        try {
            $cv->getExperProfis()->clear();
            $this->getDoctrine()->getEntityManager()->flush();
            foreach ($arrEmpregos as $emprego) {
                if ($emprego['nomeEmpresa']) {
                    $cvExperProfiss = new CVExperProfis();
                    $cvExperProfiss->setCv($cv);
                    $cvExperProfiss->setInserted(new \DateTime());
                    $cvExperProfiss->setUpdated(new \DateTime());
                    $cvExperProfiss->setNomeEmpresa($emprego['nomeEmpresa']);
                    $cvExperProfiss->setLocalEmpresa($emprego['localEmpresa']);
                    $cvExperProfiss->setNomeSuperior($emprego['nomeSuperior']);
                    $cvExperProfiss->setCargo($emprego['cargo']);
                    $cvExperProfiss->setHorario($emprego['horario']);
                    $cvExperProfiss->setAdmissao($emprego['admissao'] ? \DateTime::createFromFormat('d/m/Y', $emprego['admissao']) : null);
                    $cvExperProfiss->setDemissao($emprego['demissao'] ? \DateTime::createFromFormat('d/m/Y', $emprego['demissao']) : null);
                    $cvExperProfiss->setUltimoSalario(isset($emprego['ultimoSalario']) ? (new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL))->parse($emprego['ultimoSalario']) : null);
                    $cvExperProfiss->setBeneficios($emprego['beneficios']);
                    $cvExperProfiss->setMotivoDesligamento($emprego['motivoDesligamento']);
                    $this->getDoctrine()->getEntityManager()->persist($cvExperProfiss);
                    $this->getDoctrine()->getEntityManager()->flush();
                }
            }
            $this->getDoctrine()->getEntityManager()->refresh($cv);
            return $cv;
        } catch (ORMException $e) {
            throw new \Exception('Erro ao salvar dados dos filhos.');
        }
    }

    /**
     *
     * @param CV $cv
     * @return false|string
     */
    public function dadosEmpregos2JSON(CV $cv)
    {
        $dadosEmpregosJSON = [];
        if ($cv and $cv->getExperProfis()) {
            foreach ($cv->getExperProfis() as $emprego) {
                $d['nomeEmpresa'] = $emprego->getNomeEmpresa();
                $d['localEmpresa'] = $emprego->getLocalEmpresa();
                $d['nomeSuperior'] = $emprego->getNomeSuperior();
                $d['horario'] = $emprego->getHorario();
                $d['cargo'] = $emprego->getCargo();
                $d['admissao'] = $emprego->getAdmissao() instanceof \DateTime ? $emprego->getAdmissao()->format('d/m/Y') : '';
                $d['demissao'] = $emprego->getDemissao() instanceof \DateTime ? $emprego->getDemissao()->format('d/m/Y') : '';
                $d['ultimoSalario'] = $emprego->getUltimoSalario();
                $d['beneficios'] = $emprego->getBeneficios();
                $d['motivoDesligamento'] = $emprego->getMotivoDesligamento();
                $dadosEmpregosJSON[] = $d;
            }
        }
        return json_encode($dadosEmpregosJSON);
    }

    /**
     * @return mixed
     */
    public function getSwiftMailer(): Swift_Mailer
    {
        return $this->swiftMailer;
    }

    /**
     * @required
     * @param mixed $swiftMailer
     */
    public function setSwiftMailer(Swift_Mailer $swiftMailer): void
    {
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * @return mixed
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @required
     * @param mixed $container
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }


}