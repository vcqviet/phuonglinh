<?php
namespace HK\AdminBundle\FormType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use HK\CoreBundle\Master\MasterFormType;
use HK\CoreBundle\Entity\CmsUser;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;

class CmsUserChangePasswordType extends MasterFormType
{

    protected $entityClass = CmsUser::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', PasswordType::class, [
            
            'label' => 'lbl.user.password-old',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.user.password-old-error'
            ]
        ])
            ->add('loginPassword', PasswordType::class, [
            'label' => 'lbl.user.password-login',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' rb-password-need-confirmed',
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.user.password-login-required',
                
                FormHelper::$_VALIDATE_CLASS_MIN => '6',
                FormHelper::$_VALIDATE_CLASS_MIN . '-error' => 'lbl.user.password-login-min-error',
                
                FormHelper::$_VALIDATE_CLASS_MAX => '20',
                FormHelper::$_VALIDATE_CLASS_MAX . '-error' => 'lbl.user.password-login-max-error'
            ]
        ])
            ->add('confirmPassword', PasswordType::class, [

            'label' => 'lbl.user.password-confirm',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_CONFIRM => '1',
                FormHelper::$_VALIDATE_CLASS_CONFIRM . '-error' => 'lbl.user.password-confirm-error',
                FormHelper::$_REF_CLASS => 'rb-password-need-confirmed'
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
