<?php
namespace HK\AdminBundle\FormType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use HK\CoreBundle\Master\MasterFormType;
use HK\CoreBundle\Entity\CmsUser;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use HK\CoreBundle\Helper\FormHelper;
use HK\AdminBundle\Router\Router;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HK\CoreBundle\Entity\CmsRole;
use Doctrine\ORM\EntityRepository;

class CmsUserType extends MasterFormType
{

    protected $entityClass = CmsUser::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('emailAddress', TextType::class, [

            'label' => 'lbl.user.email-login',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.user.email-login-required',
                FormHelper::$_VALIDATE_CLASS_EMAIL => '1',
                FormHelper::$_VALIDATE_CLASS_EMAIL . '-error' => 'lbl.user.email-login-required',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST => '1',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST . '-error' => 'lbl.user.email-existing',
                FormHelper::$_DATA_URL => Router::$_REAL_PATH_PREFIX . '/admin-user/email-existing',
                FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
            ]
        ])
            ->add('phoneNumber', TextType::class, [
            'label' => 'lbl.user.phone-number',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.user.phone-number-required',
                FormHelper::$_VALIDATE_CLASS_PHONE_NUMBER => '1',
                FormHelper::$_VALIDATE_CLASS_PHONE_NUMBER . '-error' => 'lbl.user.phone-number-required',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST => '1',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST . '-error' => 'lbl.user.phone-number-existing',
                FormHelper::$_DATA_URL => Router::$_REAL_PATH_PREFIX . '/admin-user/phone-number-existing',
                FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
            ]
        ])
            ->add('cmsRoles', EntityType::class, [
            'label' => 'lbl.user.role',
            'class' => CmsRole::class,
            'multiple' => true,
            'expanded' => true,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('tbl')
                    ->where('tbl.isDeleted = 0')
                    ->andWhere('tbl.roleName != :roleAdmin')
                    ->setParameter(':roleAdmin', 'ROOT')
                    ->orderBy('tbl.displayOrder', 'ASC')
                    ->addOrderBy('tbl.roleName', 'ASC');
            },
            'choice_label' => function ($em) {
                return $em->getRoleName();
            },
            'attr' => array(
                'class' => ''
            )
        ])
            ->add('loginPassword', PasswordType::class, [
            'label' => 'lbl.user.password-login',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' rb-password-need-confirmed',
                FormHelper::$_VALIDATE_CLASS_CUSTOM => '1',
                FormHelper::$_VALIDATE_CLASS_CUSTOM_CALLBACK => 'badmin_validateCustom',

                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.user.password-login-required',
                FormHelper::$_VALIDATE_CLASS_MIN . '-error' => 'lbl.user.password-login-min-error',
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
