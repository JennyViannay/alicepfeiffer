<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('imageLink'),
            TextField::new('imageAlt'),
            TextEditorField::new('content')->setFormType(CKEditorType::class),
            AssociationField::new('tags'),
            AssociationField::new('likes')->onlyOnIndex(),
            AssociationField::new('lang'),
            IntegerField::new('readingTime', 'Reading time :')
        ];

        if ($pageName == Crud::PAGE_NEW || $pageName == Crud::PAGE_EDIT) {
            $imageFile = TextField::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms();
            $imageAlt = TextField::new('imageAlt', 'Image description improve SEO');
            array_push($fields, $imageFile);
            array_push($fields, $imageAlt);
        }

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $image = ImageField::new('image', 'Image File')->setBasePath('/uploads');
            array_push($fields, $image);
            array_push($fields, DateField::new('createdAt'));
        }

        if ($pageName == Crud::PAGE_DETAIL) {
            $imageAlt = TextField::new('imageAlt', 'Image description');
            $tags = CollectionField::new('tags')
            ->setTemplatePath('admin/tags.html.twig');
            array_push($fields, $tags);
            array_push($fields, $imageAlt);
        } 

        array_push($fields, BooleanField::new('isVisible')->onlyOnIndex());

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return
            $actions
            ->add(Crud::PAGE_INDEX, 'detail')
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pen')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel(false);
            });
    }
}
