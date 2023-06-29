<?php
/**
 * Article type.
 */

namespace App\Form\Type;

use App\Entity\Tag;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ArticleType.
 */
class ArticleType extends AbstractType
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Construct new article type object.
     *
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'label.title',
                    'required' => true,
                    'attr' => ['max_length' => 255],
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => [
                        Article::STATUS_DRAFT,
                        Article::STATUS_PUBLISHED,
                    ],
                    'choice_label' => function (int $status): string {
                        return match ($status) {
                            Article::STATUS_DRAFT => $this->translator->trans('label.draft'),
                            Article::STATUS_PUBLISHED => $this->translator->trans('label.published')
                        };
                    },
                ]
            )
            ->add(
                'content',
                CKEditorType::class,
                [
                    'label' => 'label.content',
                    'required' => true,
                    'attr' => ['max_length' => 5000],
                ]
            )
            ->add(
                'tags',
                EntityType::class,
                [
                    'class' => Tag::class,
                    'choice_label' => function ($tag): string {
                        return $tag->getName();
                    },
                    'label' => 'label.tags',
                    'placeholder' => 'label.none',
                    'required' => false,
                    'expanded' => true,
                    'multiple' => true,
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => function ($category): string {
                        return $category->getName();
                    },
                    'label' => 'label.category',
                    'placeholder' => 'label.none',
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                ]
            );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Article::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'article';
    }
}
