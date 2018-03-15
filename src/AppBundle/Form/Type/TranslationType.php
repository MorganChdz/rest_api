<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\Lang;
use Symfony\Component\Form\Extension\Core\Type\LangType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code')
                ->add('trans', CollectionType::class, ["required" => true, 'entry_type' => TextType::class, 'allow_add' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Translation',
            'csrf_protection' => false
        ]);
    }
}
