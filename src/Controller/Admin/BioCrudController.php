<?php

namespace App\Controller\Admin;

use App\Entity\Bio;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class BioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bio::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextEditorField::new('content', 'Content :'),
            TextField::new('imageLink'),
        ];

        if ($pageName == Crud::PAGE_NEW || $pageName == Crud::PAGE_EDIT) {
            $imageFile = TextField::new('imageFile')->setFormType(VichImageType::class)->setLabel('Cover File')->onlyOnForms();
            array_push($fields, $imageFile);
        } 

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $image = ImageField::new('image', 'Image File')->setBasePath('/uploads');
            array_push($fields, $image);
        } 

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return 
        $actions
        ->remove(Crud::PAGE_INDEX, 'delete')
        ->remove(Crud::PAGE_INDEX, 'new')
        ->add(Crud::PAGE_INDEX, 'detail')
        ->remove(Crud::PAGE_DETAIL, 'delete')
        ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fa fa-pen')->setLabel(false);
        })
        ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
            return $action->setIcon('fa fa-eye')->setLabel(false);
        });
    }
}
