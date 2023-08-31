<?php
/**
 * Registration form type.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class registration form type.
 */
class RegistrationType extends AbstractType
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * RegistrationType constructor.
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
     * @param FormBuilderInterface $builder Builder
     * @param array                $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nickname',
                TextType::class,
                [
                    'label' => $this->translator->trans('label.nickname'),
                    'attr' => ['max_length' => 255],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => $this->translator->trans('label.email'),
                    'required' => true,
                ]
            )
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                    'label' => $this->translator->trans('label.agree_terms'),
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue(),
                    ],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => $this->translator->trans('label.password'),
                    'mapped' => true,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 6,
                            'max' => 4096,
                        ]),
                    ],
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
        return 'user';
    }
}
