<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('link'),
            TextField::new('imageLink')
        ];

        if ($pageName == Crud::PAGE_NEW || $pageName == Crud::PAGE_EDIT) {
            $imageFile = TextField::new('imageFile')->setFormType(VichImageType::class)->onlyOnForms();
            array_push($fields, $imageFile);
        } 

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $image = ImageField::new('image', 'Image File')->setBasePath('/uploads');
            $author = TextField::new('author');
            array_push($fields, $image);
            array_push($fields, $author);
        } 

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
