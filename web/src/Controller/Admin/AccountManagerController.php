<?php


namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AccountManagerController extends EasyAdminController
{
    protected function createNewEntity()
    {
        try {
            $result = parent::createNewEntity();
            $result->setLabel('Compte Courant');
            $result->setId(bin2hex(random_bytes(10)));
        } catch (\Exception $e) {
            $result = $e;
        }
        return $result;
    }

}