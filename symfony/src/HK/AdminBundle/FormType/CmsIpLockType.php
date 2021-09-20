<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\CmsIpLock;

class CmsIpLockType extends MasterFormType
{

    protected $entityClass = CmsIpLock::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ipLocked', TextType::class, [
            'label' => 'lbl.iplock.ip-locked',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'lbl.iplock.ip-locked-required',
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
