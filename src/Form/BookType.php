<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('publicationYear', IntegerType::class, ['label' => 'Год публикации'])
            ->add('isbn', TextType::class, ['label' => 'ISBN'])
            ->add('pageCount', IntegerType::class, ['label' => 'Количество страниц'])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullname',
                'multiple' => true,
                'required' => false,
                'label' => 'Авторы'
            ])
            ->add('cover', FileType::class, [
                'required' => false, 
                'data_class' => null,
                'label' => 'Обложка'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Записать']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
