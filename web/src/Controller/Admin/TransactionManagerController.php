<?php


namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class TransactionManagerController extends EasyAdminController
{
    protected function createNewEntity(){
        $result = parent::createNewEntity();
        return $result;
    }

}