<?php
namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\NewsCategory;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use HK\AdminBundle\Router\Router;

class NewsCategoryType extends MasterFormType
{

    protected $entityClass = NewsCategory::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'news-category.title',
            'attr' => [
                'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                FormHelper::$_DATA_IS_MULTI_LANGUAGES => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'news-category.title-required',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST => '1',
                FormHelper::$_VALIDATE_CLASS_NOT_EXIST . '-error' => 'news-category.title-existing',
                FormHelper::$_DATA_URL => Router::$_REAL_PATH_PREFIX . '/news-category/title-existing',
                FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
            ]
        ]);

        parent::buildForm($builder, $options);
    }
}
