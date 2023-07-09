<?php
/**
 * Edit profile form type.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EditProfileType.
 */
class EditProfileType extends AbstractType
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * ChangePasswordType constructor.
     *
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Builds form.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nickname',
                TextType::class,
                [
                    'label' => $this->translator->trans('label.change_nickname'),
                    'attr' => ['max_length' => 255],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => $this->translator->trans('label.change_email'),
                    'attr' => ['max_length' => 255],
                ]
            );
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver Options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    /**
     * Get block prefix.
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'edit_profile';
    }
}
