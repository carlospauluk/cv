<?php

namespace App\Business;

use App\Entity\CV;
use Doctrine\ORM\ORMException;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            try {
                $this->getDoctrine()->getEntityManager()->persist($cv);
                $this->getDoctrine()->getEntityManager()->flush();;
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
        $link = getenv('LINK_CONFIRM_EMAIL') . $cv->getId();
        $body = $this->container->get('twig')->render('emailConfirm.html.twig', ['link' => $link]);
        $message = (new \Swift_Message('Confirmação de cadastro.'))
            ->setFrom('mailer@casabonsucesso.com.br', 'Casa Bonsucesso')
            ->setSubject('Confirmação de Cadastro - Cadastro de Currículos')
            ->setTo($cv->getEmail())
            ->setBody($body,'text/html')
        ;
        return $this->getSwiftMailer()->send($message);
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