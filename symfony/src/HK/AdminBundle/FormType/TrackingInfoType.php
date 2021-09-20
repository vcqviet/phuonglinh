<?php

namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\TrackingInfo;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HK\CoreBundle\Entity\TrackingInfoCategory;
use Doctrine\ORM\EntityRepository;
use HK\AdminBundle\Router\Router;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TrackingInfoType extends MasterFormType
{

    protected $entityClass = TrackingInfo::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('deviceId', TextType::class, [
            //     'label' => 'tracking-info.device-id',
            //     'attr' => [
            //         'class' => FormHelper::$_FORM_VALIDATE_CLASS,
            //         FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
            //         FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
            //         FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'tracking-info.device-id-required',
            //         // FormHelper::$_VALIDATE_CLASS_NOT_EXIST => '1',
            //         // FormHelper::$_VALIDATE_CLASS_NOT_EXIST . '-error' => 'tracking-info.device-id-existing',
            //         // FormHelper::$_DATA_URL => Router::$_REAL_PATH_PREFIX . '/tracking-info/device-id-existing',
            //         FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
            //     ]
            // ])
            ->add('gender', ChoiceType::class, [
                'label' => 'tracking-info.gender',
                'choices' => [
                    'tracking-info.gender-male' => TrackingInfo::$_GENDER_MALE,
                    'tracking-info.gender-female' => TrackingInfo::$_GENDER_FEMALE
                ],
                'expanded' => true,
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS
                ],
                'data' => TrackingInfo::$_GENDER_MALE
            ])
            ->add('platform', TextType::class, [
                'label' => 'tracking-info.platform',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'tracking-info.platform-required',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID

                ]
            ]);

        parent::buildForm($builder, $options);
    }
}
