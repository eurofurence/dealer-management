<?php

namespace App\Controller\Admin;

use App\Entity\TablePackage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TablePackageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TablePackage::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()->setDisabled(),
            TextField::new('name'),
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setStoredAsCents()
            ,
        ];
    }
}
