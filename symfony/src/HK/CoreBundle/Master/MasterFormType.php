<?php
namespace HK\CoreBundle\Master;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HK\CoreBundle\Helper\FormHelper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MasterFormType extends AbstractType
{

    protected $entityClass = '';

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('?');

        $builder->add('editId', HiddenType::class, [
            'label' => 'lbl.form.edit-id',
            'attr' => [
                'class' => FormHelper::$_FORM_CLASS_EDIT_ID
            ]
        ])->add('Save', ButtonType::class, [
            'label' => 'lbl.form.button-save',
            'attr' => [
                'class' => 'btn-primary float-right ' . FormHelper::$_FORM_CLASS,
                FormHelper::$_REF_NAME => $builder->getName(),
                FormHelper::$_DATA_URL => $builder->getAction(),
                FormHelper::$_FORM_IS_MULTIPART => $this->isMultipart ? '1' : '0',
                FormHelper::$_CALLBACK_AFTER => 'badmin_formAfter'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'csrf_protection' => false,
            'data_class' => $this->entityClass
        ]);
    }
}