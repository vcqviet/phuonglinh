<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\MediaFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class MediaFileType extends MasterFormType
{

    protected $entityClass = MediaFile::class;

    protected $isMultipart = true;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class, [
            'label' => false,
            'multiple' => true,
            'attr' => [
                'class' => FormHelper::$_FORM_CLASS_MEDIA_FILE,
                'style' => 'outline: none'
            ]
        ])
            ->setAction('?')
            ->add('path', HiddenType::class, [])
            ->add('Save', ButtonType::class, [
            'label' => false,
            'attr' => [
                'class' => 'btn-info rounded-0 ' . FormHelper::$_FORM_CLASS,
                FormHelper::$_REF_NAME => $builder->getName(),
                FormHelper::$_DATA_URL => $builder->getAction(),
                FormHelper::$_FORM_IS_MULTIPART => $this->isMultipart ? '1' : '0',
                FormHelper::$_CALLBACK_AFTER => 'bmedia_formAfter',
                FormHelper::$_CALLBACK_BEFORE => 'bmedia_formBefore'
            ]
        ]);
    }
}
