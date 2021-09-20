<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use HK\CoreBundle\Helper\FormHelper;
use HK\AdminBundle\Router\Router;
use HK\CoreBundle\Entity\CmsRole;

class CmsRoleType extends MasterFormType
{

    protected $entityClass = CmsRole::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roleName', TextType::class, [
            'label' => 'lbl.role.role-name',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.role.role-name-required',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST => '1',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST . '-error' => 'lbl.role.role-name-existing',
                FormHelper::$_DATA_URL => Router::$_REAL_PATH_PREFIX . '/admin-role/role-existing',
                FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
