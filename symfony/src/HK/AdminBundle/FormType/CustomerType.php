<?php

namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\Customer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomerType extends MasterFormType
{

    protected $entityClass = Customer::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productModel', TextType::class, [
                'label' => 'customer.product-model',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])->add('fullName', TextType::class, [
                'label' => 'customer.full-name',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])
            ->add('emailAddress', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'customer.phone-number',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])->add('address', TextType::class, [
                'label' => 'customer.address',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])->add('dateOfBirth', TextType::class, [
                'label' => 'customer.birthday',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' ' . FormHelper::$_CLASS_DATETIME_PICKER,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])->add('orderDate', TextType::class, [
                'label' => 'customer.order-date',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '0',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '0',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ]);

        parent::buildForm($builder, $options);
    }
}
