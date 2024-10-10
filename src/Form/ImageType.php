<?php

namespace App\Form;
 
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
 
use ItForFree\rusphp\File\MIME;
 
class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         
        $builder
            //->add('title')
            //->add('description')
            ->add('image_file', FileType::class, [
                'label' => 'Image',
 
                // false означает, что нет  ассоциации с каким-то из полей модели (и БД)
                'mapped' => false,
 
                'required' => true,
 
                // поля, не ассоцированные с сущность (энтити), не могут быть валидированы
                // с помощью аннотаций этой сущности, 
                // но можно использовать специальный класс ограничений:
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        //'mimeTypes' => MIME::getAllbyExtentions(['png', 'jpg']),
                        'mimeTypesMessage' => 'Поддерживается только загрузка изображений!',
                    ])
                ],
            ])
            ->add('save', SubmitType::class)
        ;
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}