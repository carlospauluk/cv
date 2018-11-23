<?php

namespace App\Controller;

use App\Business\CVBusiness;
use App\Entity\CV;
use Psr\Log\LoggerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
                $step = 'emailConfirmEnviado';
            } else {
                $step = 'iniciado';
            }
        } else if ($request->get('btnLogin')) {
            // estava no login e clicou em 'Entrar'
            if ($this->handleLogin($request, $vParams)) {
                $this->redirectToRoute('form', ['cv' => null]);
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
     * @param $params
     */
    private function handleLogin(Request $request, &$params)
    {


    }

    /**
     *
     * @Route("/esqueciMinhaSenha", name="esqueciMinhaSenha")
     * @param Request $request
     * @param $cpf
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleEsqueciSenha(Request $request)
    {
        try {
            $vParams['cpf'] = preg_replace('/[^\d]/', '', $request->get('cpf'));
            $this->getCvBusiness()->reenviarSenha($vParams['cpf']);
            $this->addFlash('info', 'Nova senha enviada para seu e-mail.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erro ao gerar nova senha.');
        }
        $vParams['step'] = 'login';

        return $this->render('landpage.html.twig', $vParams);
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
     * @Route("/form/{cv}", name="form", requirements={"cv"="\d+"})
     * @ParamConverter("cv", class="App\Entity\CV", options={"mapping": {"cv": "id"}})
     * @param Request $request
     * @param CV|null $cv
     * @return void
     */
    public function form(Request $request, CV $cv)
    {

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