<?php

namespace App\Controller;

use App\Business\CVBusiness;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends Controller
{

    private $logger;

    private $cvBusiness;

    /**
     *
     * @Route("/", name="inicio")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inicio(Request $request)
    {
        $vParams = [];
        $vParams['iniciado'] = false;
        $vParams['cadastroOk'] = false;
        $vParams['emailConfirmEnviado'] = false;

        if ($request->get('btnIniciar')) {
            return $this->handleInicio($request, $vParams);
        } else if ($request->get('btnLogin')) {
            return $this->handleLogin($request, $vParams);
        } else if ($request->get('btnEsqueciSenha')) {
            return $this->handleEsqueciSenha($request, $vParams);
        } else if ($request->get('btnNovo')) {
            return $this->handleNovo($request, $vParams);
        } else {
            return $this->render('landpage.html.twig', $vParams);
        }
    }

    /**
     * Ao digitar o CPF.
     *
     * @param Request $request
     * @param $vParams
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function handleInicio(Request $request, $vParams) {
        $vParams['cpf'] = preg_replace('/[^\d]/','', $request->get('cpf'));

        if (!$request->get('g-recaptcha-response')) {
            $this->addFlash('error', 'Você é um robô?');
        } else {
            $secret = getenv('GOOGLE_RECAPTCHA_SECRET');
            $gRecaptchaResponse = $request->get('g-recaptcha-response');
            $recaptcha = new ReCaptcha($secret);
            $resp = $recaptcha->setExpectedHostname('dev.cv.casabonsucesso.com.br')
                ->verify($gRecaptchaResponse, $request->server->get('REMOTE_ADDR'));
            if (!$resp->isSuccess()) {
//                $errors = $resp->getErrorCodes();
                $this->addFlash('error', 'Você é um robô ou não??');
            } else {
                $cadastroOk = $this->getCvBusiness()->checkCadastroOk($vParams['cpf']);
                $vParams['cadastroOk'] = $cadastroOk;
                $vParams['iniciado'] = true;
            }
        }
        return $this->render('landpage.html.twig', $vParams);
    }

    /**
     * Ao fazer login.
     *
     * @param Request $request
     */
    private function handleLogin(Request $request) {

    }

    /**
     * Ao clicar em 'Esqueci minha senha'.
     * @param Request $request
     */
    private function handleEsqueciSenha(Request $request) {

    }

    /**
     * Ao salvar um cadastro novo.
     *
     * @param Request $request
     * @param $vParams
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function handleNovo(Request $request, $vParams) {
        $vParams['iniciado'] = true;
        $cpf = preg_replace('/[^\d]/','', $request->get('cpf'));
        $email = $request->get('email');
        $senha = $request->get('password');

        $vParams['cpf'] = $cpf;
        $vParams['email'] = $email;
        if ($request->get('password') !== $request->get('password2')) {
            $this->addFlash('error', 'As senhas não coincidem.');
            return $this->render('landpage.html.twig', $vParams);
        } else {
            try {
                $this->getCvBusiness()->novo($cpf, $email, $senha);
                $vParams['emailConfirmEnviado'] = true;
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->render('landpage.html.twig', $vParams);
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


}