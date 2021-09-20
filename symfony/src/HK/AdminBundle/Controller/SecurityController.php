<?php

namespace HK\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use HK\CoreBundle\Entity\CmsUser;
use HK\CoreBundle\Helper\MailHelper;
use Symfony\Contracts\Translation\TranslatorInterface;
use HK\CoreBundle\Helper\StringHelper;
use HK\CoreBundle\Helper\DateTimeHelper;
use HK\AdminBundle\Router\Router;

class SecurityController extends AbstractController
{

    public function adminLogin(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() != null && !empty($this->getUser()->getEmailAddress())) {
            return $this->redirectToRoute('hk_admin_dashboard');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if (!empty($error)) {
            $error = 'err.login-failed';
        }
        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    public function preLogin(Request $req): Response
    {
        if ($this->getUser() != null && !empty($this->getUser()->getEmailAddress())) {
            return $this->redirectToRoute('hk_admin_dashboard');
        }
        return $this->redirectToRoute('hk_admin_login');
    }

    public function adminLogout(Request $req): Response
    {
        return $this->redirectToRoute('hk_admin_logout');
    }

    public function adminForgot(Request $req, \Swift_Mailer $mailer, TranslatorInterface $translator): Response
    {
        $error = '';
        if ($req->isMethod('POST')) {
            $account = $this->getDoctrine()
                ->getRepository(CmsUser::class)
                ->forgotPassword($req->get('emailAddress', ''));
            if ($account != null) {
                $link = $req->getUriForPath('/admin/reset-password') . '/' . $account->getId() . StringHelper::encodeDateTime($account->getRecoverTime());
                $mailer->send(MailHelper::getMessageObject($translator->trans('lbl.login-recover-subject', [
                    '%domain%' => getenv('DOMAIN')
                ]), getenv('NOREPLY_EMAIL'), $account->getEmailAddress(), $this->renderView('admin/mail/loginRecoverHtml.html.twig', [
                    'fullName' => $account->getEmailAddress(),
                    'link' => $link,
                    'domain' => getenv('DOMAIN')
                ]), $this->renderView('admin/mail/loginRecoverText.html.twig', [
                    'fullName' => $account->getEmailAddress(),
                    'link' => $link,
                    'domain' => getenv('DOMAIN')
                ])));
                return $this->render('admin/security/recover.html.twig');
            }
            // account not found
            $error = 'lbl.login-forgot-not-found';
        }
        return $this->render('admin/security/forgot.html.twig', [
            'error' => $error
        ]);
    }

    public function adminReset(Request $req, \Swift_Mailer $mailer, TranslatorInterface $translator, UserPasswordEncoderInterface $encoder): Response
    {
        $error = '';
        $recover = $req->get('recover', '');
        $idLength = strlen($recover) - CmsUser::$lengthEncoded;
        if ($idLength <= 0) {
            return $this->redirectToRoute(Router::$LOGIN);
        }
        $account = $this->getDoctrine()
            ->getRepository(CmsUser::class)
            ->getById(substr($recover, 0, $idLength));
        if ($account == null) {
            return $this->redirectToRoute(Router::$LOGIN);
        }
        $now = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
        $diff = $now->diff($account->getRecoverTime());
        if (($diff->h + $diff->days * 24) > 24) {
            return $this->redirectToRoute(Router::$LOGIN);
        }

        if (!StringHelper::isValidEncode(substr($recover, $idLength), $account->getRecoverTime()->format(DateTimeHelper::$DATE_FORMAT))) {
            return $this->redirectToRoute(Router::$LOGIN);
        }
        if ($req->isMethod('POST')) {
            $password = $req->get('password', '');
            $passwordConfirm = $req->get('passwordConfirm', '');
            if (strlen($password) >= 6 && $password === $passwordConfirm) {
                $passwordEncoded = $encoder->encodePassword($account, $password);
                $newAccount = $this->getDoctrine()
                    ->getRepository(CmsUser::class)
                    ->resetPassword($account->getId(), $passwordEncoded);
                if ($newAccount != null) {
                    $now = new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh'));
                    $link = $req->getUriForPath('/admin/login');
                    $mailer->send(MailHelper::getMessageObject($translator->trans('lbl.login-reset-subject', [
                        '%domain%' => getenv('DOMAIN')
                    ]), getenv('NOREPLY_EMAIL'), $newAccount->getEmailAddress(), $this->renderView('admin/mail/loginResetHtml.html.twig', [
                        'fullName' => $account->getEmailAddress(),
                        'datetime' => $now->format('Y-m-d H:i:s'),
                        'link' => $link,
                        'domain' => getenv('DOMAIN')
                    ]), $this->renderView('admin/mail/loginResetText.html.twig', [
                        'fullName' => $account->getEmailAddress(),
                        'datetime' => $now->format('Y-m-d H:i:s'),
                        'link' => $link,
                        'domain' => getenv('DOMAIN')
                    ])));
                    return $this->render('admin/security/resetSuccess.html.twig');
                }
            }
            // password does not match, length is less than 6
            $error = 'lbl.login-reset-invalid-password';
        }

        return $this->render('admin/security/reset.html.twig', [
            'error' => $error
        ]);
    }
}
