<?php

namespace HK\AdminBundle\FormType;

use HK\CoreBundle\Master\MasterFormType;
use Symfony\Component\Form\FormBuilderInterface;
use HK\CoreBundle\Helper\FormHelper;
use HK\CoreBundle\Entity\News;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use HK\CoreBundle\Entity\NewsCategory;
use Doctrine\ORM\EntityRepository;
use HK\AdminBundle\Router\Router;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NewsType extends MasterFormType
{

    protected $entityClass = News::class;

    protected $isMultipart = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cate', EntityType::class, [
            'label' => 'news.cate',
            'class' => NewsCategory::class,
            'multiple' => false,
            'expanded' => false,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('tbl')
                    ->where('tbl.isDeleted = 0')
                    ->orderBy('tbl.displayOrder', 'ASC');
            },
            'choice_label' => function ($em) {
                return $em->getTitle();
            },
            'attr' => array(
                'class' => ''
            )
        ])
            ->add('title', TextType::class, [
                'label' => 'news.title',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '1',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'news.title-required',
                    FormHelper::$_VALIDATE_CLASS_NOT_EXIST => '1',
                    FormHelper::$_VALIDATE_CLASS_NOT_EXIST . '-error' => 'news.title-existing',
                    FormHelper::$_DATA_URL => Router::$_REAL_PATH_PREFIX . '/news/title-existing',
                    FormHelper::$_REF_CLASS => FormHelper::$_FORM_CLASS_EDIT_ID
                ]
            ])
            ->add('showOn', ChoiceType::class, [
                'label' => 'news.show-on',
                'choices' => [
                    'news.show-on-all' => News::$_SHOW_ON_ALL,
                    'news.show-on-app' => News::$_SHOW_ON_APP,
                    'news.show-on-web' => News::$_SHOW_ON_WEB
                ],
                'expanded' => true,
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS
                ],
                'data' =>  News::$_SHOW_ON_ALL
            ])
            ->add('photoUrl', TextType::class, [
                'label' => 'news.photo',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' d-none ' . FormHelper::$_FORM_CLASS_PHOTO_SINGLE,
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'news.photo-required'

                ]
            ])
            ->add('thumbnailUrl', TextType::class, [
                'label' => 'news.photo-thumb',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' d-none ' . FormHelper::$_FORM_CLASS_PHOTO_SINGLE,
                    FormHelper::$_VALIDATE_CLASS_REQUIRED => '1',
                    FormHelper::$_VALIDATE_CLASS_REQUIRED . '-error' => 'news.photo-thumb-required'

                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'news.description',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '1'
                ]
            ])
            ->add('viewmoreUrl', TextType::class, [
                'label' => 'news.view-more',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS,
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'news.content',
                'attr' => [
                    'class' => FormHelper::$_FORM_VALIDATE_CLASS . ' ' . FormHelper::$_FORM_CLASS_EDITOR,
                    FormHelper::$_DATA_IS_MULTI_LANGUAGES => '1'
                ]
            ]);

        parent::buildForm($builder, $options);
    }
}
