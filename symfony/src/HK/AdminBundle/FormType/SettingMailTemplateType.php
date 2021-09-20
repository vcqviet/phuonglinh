<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use HK\CoreBundle\Entity\SettingMailTemplate;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SettingMailTemplateType extends MasterFormType
{

    protected $entityClass = SettingMailTemplate::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'setting-mail-template.name',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
            ]
        ])->add('email', TextType::class, [
            'label' => 'setting-mail-template.email',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
            ]
        ])->add('copyTo', TextType::class, [
            'label' => 'setting-mail-template.copy-to',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
            ]
        ])->add('subject', TextType::class, [
            'label' => 'setting-mail-template.subject',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'setting-mail-template.subject-required',
            ]
        ]);
        $builder->add('content', TextareaType::class, [
            'label' => 'setting-mail-template.content',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' ' . FormHelper::$_FORM_CLASS_EDITOR,
            ]
        ]);
        $builder->add('contentText', TextareaType::class, [
            'label' => 'setting-mail-template.content-text',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                'rows' => 10 
            ]
        ]);
        parent::buildForm($builder, $options);
    }
}
