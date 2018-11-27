<?php

namespace App\Controller;

use App\Business\CVBusiness;
use App\Entity\CV;
use App\Form\CVType;
use Psr\Log\LoggerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends Controller
{

    private $logger;

    private $cvBusiness;

    /**
     *
     * @Route("/", name="inicio")
     * @param Request $request
     * @param string $step
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inicio(Request $request, $step = "land")
    {
        /**
         * steps: land, iniciado, emailConfirmEnviado, login.
         **/
        $vParams = [];

        $vParams['cpf'] = preg_replace('/[^\d]/', '', $request->get('cpf'));

        $vParams['email'] = $request->get('email');
        $vParams['password'] = $request->get('password');
        $vParams['password2'] = $request->get('password2');
        $vParams['email'] = $request->get('email');
        $vParams['password_login'] = $request->get('password_login');

        $submittedToken = $request->request->get('_csrf_token');
        if ($submittedToken and !$this->isCsrfTokenValid('land', $submittedToken)) {
            $this->addFlash('error', 'Erro de submissão');

        } else {

            if ($request->get('btnIniciar')) {
                // estava no 'land' e clicou em 'Iniciar'
                $this->handleInicio($request, $vParams);
                if (!isset($vParams['cadastroOk'])) {
                    $step = 'land';
                } else if ($vParams['cadastroOk']) {
                    $step = 'login';
                } else {
                    $step = 'iniciado';
                }
            } else if ($request->get('btnNovo')) {
                // estava no 'iniciar' e clicou em 'Novo'
                $this->handleNovo($request, $vParams);
                if ($vParams['cadastroIniciado']) {
                    $this->addFlash('info', 'E-mail enviado. Verifique sua Caixa de Entrada ou o Spam.');
                    $step = 'login';
                } else {
                    $step = 'iniciado';
                }
            } else if ($request->get('btnLogin')) {
                // estava no login e clicou em 'Entrar'
                $this->handleLogin($request, $vParams);
                return $this->redirectToRoute('cv');
            } else if ($request->get('btnEsqueciMinhaSenha')) {
                $this->handleEsqueciSenha($vParams);
                $step = 'login';
            }
        }

        $vParams['step'] = $step;
        return $this->render('landpage.html.twig', $vParams);
    }

    /**
     * Ao digitar o CPF.
     *
     * @param Request $request
     * @param $vParams
     * @return bool
     */
    private function handleInicio(Request $request, &$vParams)
    {
        if (!$this->getCvBusiness()->validaCPF($vParams['cpf'])) {
            $this->addFlash('error', 'CPF inválido');
        } else {
            if (!$request->get('g-recaptcha-response')) {
                $this->addFlash('error', 'Você é um robô?');
            } else {
                $secret = getenv('GOOGLE_RECAPTCHA_SECRET');
                $gRecaptchaResponse = $request->get('g-recaptcha-response');
                $recaptcha = new ReCaptcha($secret);
                $urlSistema = getenv('URL_SISTEMA');
                $resp = $recaptcha->setExpectedHostname($urlSistema)
                    ->verify($gRecaptchaResponse, $request->server->get('REMOTE_ADDR'));
                if (!$resp->isSuccess()) {
//                $errors = $resp->getErrorCodes();
                    $this->addFlash('error', 'Você é um robô ou não??');
                } else {
                    $cadastroOk = $this->getCvBusiness()->checkCadastroOk($vParams['cpf']);
                    $vParams['cadastroOk'] = $cadastroOk;
                }
            }
        }
    }

    /**
     * Ao salvar um cadastro novo.
     *
     * @param Request $request
     * @param $vParams
     * @return void
     */
    private function handleNovo(Request $request, &$vParams)
    {
        if ($vParams['password'] !== $vParams['password2']) {
            $this->addFlash('error', 'As senhas não coincidem.');
        } else {
            try {
                $this->getCvBusiness()->novo($vParams['cpf'], $vParams['email'], $vParams['password']);
                $vParams['cadastroIniciado'] = true;
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }
    }

    /**
     * Ao fazer login.
     *
     * @param Request $request
     * @param $vParams
     * @return bool
     */
    private function handleLogin(Request $request, &$vParams)
    {
        try {
            $r = $this->getCvBusiness()->login($vParams['cpf'], $vParams['password_login']);
            if (!$r) {
                $this->addFlash('error', 'CPF ou senha inválidos');
                return false;
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao tentar efetuar login');
        }
        return true;
    }

    /**
     *
     * @Route("/logout", name="logout")
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $session = $request->hasSession() ? $request->getSession() : new Session();
        $session->clear();
        return $this->redirectToRoute('inicio');
    }

    /**
     *
     * @Route("/esqueciMinhaSenha", name="esqueciMinhaSenha")
     * @param $vParams
     * @return void
     */
    public function handleEsqueciSenha(&$vParams)
    {
        try {
            $this->getCvBusiness()->reenviarSenha($vParams['cpf']);
            $this->addFlash('info', 'Nova senha enviada para seu e-mail.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao gerar nova senha.');
        }
        $vParams['step'] = 'login';
    }


    /**
     *
     * @Route("/confirmEmail", name="confirmEmail")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmEmail(Request $request)
    {
        $cvId = $request->get('cv');
        $uuid = $request->get('uuid');

        try {
            $cv = $this->getCvBusiness()->confirmEmail($cvId, $uuid);
            if (!$cv) {
                $this->addFlash('error', 'Não foi possível confirmar seu e-mail.');
            } else {
                $request->request->set('iniciarLogin', 'true');
                $this->addFlash('info', 'E-mail confirmado com sucesso. Efetua o login...');
                return $this->redirectToRoute('inicio', $request->request->all());
            }
        } catch (\Exception $e) {
            $this->getLogger()->error('Não foi possível confirmar o e-mail');
            $this->getLogger()->error($e->getMessage());
            $this->addFlash('error', 'Não foi possível confirmar seu e-mail.');
        }

        return $this->redirectToRoute('inicio');

    }


    /**
     *
     * @Route("/cv", name="cv")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cv(Request $request)
    {
        $vParams = [];
        $session = $request->hasSession() ? $request->getSession() : new Session();
        $cvId = $session->get('cvId');
        if (!$cvId) {
            return $this->redirectToRoute('inicio');
        }
        $cv = $this->getDoctrine()->getRepository(CV::class)->find($cvId);

        $form = $this->createForm(CVType::class, $cv);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->getCvBusiness()->saveCv($cv);
                    $this->getCvBusiness()->saveFilhos($cv, $request->get('filho'));
                    $this->getCvBusiness()->saveEmpregos($cv, $request->get('emprego'));
                    $this->getDoctrine()->getManager()->refresh($cv);
                    $form = $this->createForm(CVType::class, $cv);
                    $this->addFlash('success', 'Registro salvo com sucesso!');
                } catch (\Throwable $e) {
                    $this->addFlash('error', 'Erro ao salvar!');
                }
            } else {
                $errors = $form->getErrors(true, true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        // Pode ou não ter vindo algo no $parameters. Independentemente disto, só adiciono form e foi-se.
        $vParams['form'] = $form->createView();
        $vParams['foto'] = $cv->getFoto();

        $vParams['dadosFilhosJSON'] = $this->getCvBusiness()->dadosFilhos2JSON($cv);
        $vParams['dadosEmpregosJSON'] = $this->getCvBusiness()->dadosEmpregos2JSON($cv);

        return $this->render('cv.html.twig', $vParams);

    }

    /**
     *
     * @Route("/alterarSenha", name="alterarSenha")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function alterarSenha(Request $request)
    {
        $session = $request->hasSession() ? $request->getSession() : new Session();
        $cvId = $session->get('cvId');
        if (!$cvId) {
            return $this->redirectToRoute('inicio');
        }
        $vParams = [];

        if ($request->get('btnAlterarSenha')) {
            $senhaAtual = $request->request->get('password_login');
            $password = $request->request->get('password');
            $password2 = $request->request->get('password2');
            if ($password !== $password2) {
                $this->addFlash('error', 'As senhas não coincidem.');
            } else {
                $this->getCvBusiness()->alterarSenha($cvId, $senhaAtual, $password);
                $session->clear();
                $this->addFlash('info', 'Senha alterada com sucesso!');
                return $this->redirectToRoute('inicio');
            }
        }

        return $this->render('alterarSenha.html.twig', $vParams);
    }

    /**
     *
     * @Route("/uploadFoto", name="uploadFoto")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function uploadFoto(Request $request)
    {
        $output = ['uploaded' => false];
        if ($request->files->get('file')) {
            $file = $request->files->get('file');
            $session = $request->hasSession() ? $request->getSession() : new Session();
            $cvId = $session->get('cvId');
            if ($cvId) {
                $cv = $this->getDoctrine()->getRepository(CV::class)->find($cvId);
                $cv->setFotoFile($request->files->get('file'));
                $this->getDoctrine()->getManager()->flush();
                $output['uploaded'] = true;
            }
        }
        return new JsonResponse($output);
    }

    /**
     *
     * @Route("/deleteFoto", name="deleteFoto")
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteFoto(Request $request)
    {
        try {
            $session = $request->hasSession() ? $request->getSession() : new Session();
            $cvId = $session->get('cvId');
            if ($cvId) {
                $cv = $this->getDoctrine()->getRepository(CV::class)->find($cvId);
                $this->get('vich_uploader.upload_handler')->remove($cv, 'fotoFile'); // https://github.com/dustin10/VichUploaderBundle/issues/323
                $cv->setUpdated(new \DateTime());
                $cv->setFoto(null);
                $cv->setFotoFile(null);
                // $this->getDoctrine()->getManager()->merge($cv);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('cv');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Ocorreu um erro ao remover a foto.');
        }
    }


    /**
     * @return mixed
     */
    public function getCvBusiness(): CVBusiness
    {
        return $this->cvBusiness;
    }

    /**
     * @required
     * @param mixed $cvBusiness
     */
    public function setCvBusiness(CVBusiness $cvBusiness): void
    {
        $this->cvBusiness = $cvBusiness;
    }

    /**
     * @return mixed
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @required
     * @param mixed $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }


}