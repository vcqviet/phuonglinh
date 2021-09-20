<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\AboutPage;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AboutPagePdfType extends MasterFormType
{

    protected $entityClass = AboutPage::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextType::class, [
            'label' => 'PDF file',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' d-none1 ' . FormHelper::$_FORM_CLASS_PHOTO_SINGLE,
                'readonly' => 'readonly'
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
