<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\AboutPage;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AboutPageType extends MasterFormType
{

    protected $entityClass = AboutPage::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class, [
            'label' => ' ',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' ' . FormHelper::$_FORM_CLASS_EDITOR,
                FormHelper::$_DATA_IS_MULTI_LANGUAGES => '1'
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
