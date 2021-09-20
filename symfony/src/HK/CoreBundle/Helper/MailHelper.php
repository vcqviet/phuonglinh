<?php
namespace HK\CoreBundle\Helper;

use Doctrine\ORM\EntityManager;
use HK\CoreBundle\Entity\SettingWebsite;
use HK\CoreBundle\Entity\SettingMailTemplate;

class MailHelper
{

    public static function getMessageObject($subject, $from, $to, $html, $text = '', $cc = [], $bcc = [])
    {
        $messageObj = new \Swift_Message();
        $messageObj->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');
        foreach ($cc as $c) {
            if (empty($c)) {
                continue;
            }
            $messageObj->addCc($c);
        }
        foreach ($bcc as $c) {
            if (empty($c)) {
                continue;
            }
            $messageObj->addBcc($c);
        }
        return $messageObj;
    }

    private static $instance = null;

    private $entityManager = null;

    public static function instance(EntityManager $entityManager = null): self
    {
        if (self::$instance === null) {
            self::$instance = new MailHelper($entityManager);
        }
        return self::$instance;
    }

    public function __construct(EntityManager $entityManager = null)
    {
        $this->entityManager = $entityManager;
    }

    public function sendmail($subject, $contentHtml, $contentText, $email)
    {
        $repo = $this->entityManager->getRepository(SettingWebsite::class);

        $host = getenv('MAIL_SMTP') == 'gmail' ? 'smtp.gmail.com' : getenv('MAIL_HOST');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_HOST))) {
            $host = $repo->getValue(SettingWebsite::$_KEY_SMTP_HOST);
        }
        $port = getenv('MAIL_PORT');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_PORT))) {
            $port = $repo->getValue(SettingWebsite::$_KEY_SMTP_PORT);
        }
        $encrypt = getenv('MAIL_ENCRYPTION');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_TYPE))) {
            $encrypt = $repo->getValue(SettingWebsite::$_KEY_SMTP_TYPE);
        }
        $transport = new \Swift_SmtpTransport($host, intval($port), $encrypt);

        $user = getenv('MAIL_USER_NAME');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_USER))) {
            $user = $repo->getValue(SettingWebsite::$_KEY_SMTP_USER);
        }
        $password = getenv('MAIL_USER_PASSWORD');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_PASSWORD))) {
            $password = $repo->getValue(SettingWebsite::$_KEY_SMTP_PASSWORD);
        }
        $transport->setUsername($user)->setPassword($password);
        $mailer = new \Swift_Mailer($transport);

        $fromName = $repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_FROM_NAME);

        $fromEmail = getenv('NOREPLY_EMAIL');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_FROM_EMAIL))) {
            $fromEmail = $repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_FROM_EMAIL);
        }

        $bcc = $repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_BCC);
        $bcc = explode(';', $bcc);
        $mailer->send(self::getMessageObject($subject, [
            $fromEmail => $fromName
        ], $email, $contentHtml, $contentText, [], $bcc));
        return true;
    }

    public function sendmailWithTemplate($key, $data = [], $email)
    {
        $template = $this->entityManager->getRepository(SettingMailTemplate::class)->getByNameKey($key);
        if ($template == null) {
            return false;
        }

        $repo = $this->entityManager->getRepository(SettingWebsite::class);

        $host = getenv('MAIL_SMTP') == 'gmail' ? 'smtp.gmail.com' : getenv('MAIL_HOST');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_HOST))) {
            $host = $repo->getValue(SettingWebsite::$_KEY_SMTP_HOST);
        }
        $port = getenv('MAIL_PORT');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_PORT))) {
            $port = $repo->getValue(SettingWebsite::$_KEY_SMTP_PORT);
        }
        $encrypt = getenv('MAIL_ENCRYPTION');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_TYPE))) {
            $encrypt = $repo->getValue(SettingWebsite::$_KEY_SMTP_TYPE);
        }
        $transport = new \Swift_SmtpTransport($host, intval($port), $encrypt);

        $user = getenv('MAIL_USER_NAME');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_USER))) {
            $user = $repo->getValue(SettingWebsite::$_KEY_SMTP_USER);
        }
        $password = getenv('MAIL_USER_PASSWORD');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SMTP_PASSWORD))) {
            $password = $repo->getValue(SettingWebsite::$_KEY_SMTP_PASSWORD);
        }
        $transport->setUsername($user)->setPassword($password);
        $mailer = new \Swift_Mailer($transport);

        $header = $repo->getValue(SettingWebsite::$_KEY_CLIENT_EMAIL_HEADER);
        $footer = $repo->getValue(SettingWebsite::$_KEY_CLIENT_EMAIL_FOOTER);
        $signature = $repo->getValue(SettingWebsite::$_KEY_GLOBAL_EMAIL_SIGNATURE);
        $fromName = $repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_FROM_NAME);
        if (! empty($template->getName())) {
            $fromName = $template->getName();
        }
        $fromEmail = getenv('NOREPLY_EMAIL');
        if (! empty($repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_FROM_EMAIL))) {
            $fromEmail = $repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_FROM_EMAIL);
        }
        if (! empty($template->getEmail())) {
            $fromEmail = $template->getEmail();
        }

        $bcc = $repo->getValue(SettingWebsite::$_KEY_SYSTEM_EMAIL_BCC);
        if (! empty($template->getCopyTo())) {
            $bcc .= ';' . $template->getCopyTo();
        }
        $bcc = explode(';', $bcc);
        $contentHtml = $header . StringHelper::replaceTemplate($data, $template->getContent()) . $signature . $footer;
        $contentText = StringHelper::replaceTemplate($data, $template->getContentText()) . "\n" . $signature;
        $title = StringHelper::replaceTemplate($data, $template->getSubject());

        $mailer->send(self::getMessageObject($title, [
            $fromEmail => $fromName
        ], $email, $contentHtml, $contentText, [], $bcc));
        return true;
    }
}
