<?php

namespace App\Controller\Admin;

use App\Entity\Press;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Press::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('magazine'),
            TextField::new('link', 'Magazine Link'),
            TextField::new('imageLink'),
            AssociationField::new('tags'),
            BooleanField::new('isVisible')->onlyOnIndex()
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
        } 

        if ($pageName == Crud::PAGE_DETAIL) {
            $imageAlt = TextField::new('imageAlt', 'Image description');
            array_push($fields, $imageAlt);
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
