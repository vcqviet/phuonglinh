<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\HomeSlider;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomeSliderType extends MasterFormType
{

    protected $entityClass = HomeSlider::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('photoUrl', TextType::class, [
            'label' => 'home-slider.photo',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' d-none ' . FormHelper::$_FORM_CLASS_PHOTO_SINGLE,
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'home-slider.photo-required'
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
