<?php

namespace Netbull\AuthBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Netbull\AuthBundle\Entity\User;

/**
 * Class UserType
 * @package АuthBundle\Form
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class, [
                'label' => 'Потребителсо име'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'              => PasswordType::class,
                'first_options'     => ['label' => 'Парола'],
                'second_options'    => ['label' => 'Повтори паролата'],
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'mapped'        => false,
                'constraints'   => new IsTrue(),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
