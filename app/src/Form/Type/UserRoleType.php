<?php
/**
 * User role form type.
 */

namespace App\Form\Type;

use App\Entity\User;
use App\Entity\Enum\UserRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class UserRoleType.
 */
class UserRoleType extends AbstractType
{
    /**
     * Builds form.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    UserRole::ROLE_USER->label() => UserRole::ROLE_USER->value,
                    UserRole::ROLE_ADMIN->label() => UserRole::ROLE_ADMIN->value,
                ],
            ]);
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
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
        return 'user_role';
    }
}
