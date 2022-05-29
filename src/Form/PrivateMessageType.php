<?php

namespace App\Form;

use App\Entity\PrivateMessages;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\User;
use App\Repository\FollowingRepository;

class PrivateMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['empty_data'];
        $builder
            ->add('receiver', EntityType::class, array(
                'class' => User::class,
                'query_builder' => function($er) use($user){
                    return $er->getFollowingUsers($user);
                },
                'choice_label' => function ($user){
                    return $user->getName()." ".$user->getSurname(). " - ".$user->getNick();
                },
                'label' => 'Para:',
                'attr' => array('class' => "form-control")
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Message',
                'required' => 'required'
            ))
            ->add('image', FileType::class, array(
                'label' => 'Image',
                'required' => false,
                'data_class' => null,
            ))
            ->add('file', FileType::class, array(
                'label' => 'Document',
                'required' => false,
                'data_class' => null
            ))

            #boton de save de los datos de publications
            ->add('Send', SubmitType::class, array(
                'attr'=> array(
                    'class'=>'btn-dark mt-2'
                )))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PrivateMessages::class,
        ]);
    }
}
