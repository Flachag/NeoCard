<?php


namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class UserManagerController extends EasyAdminController
{
    protected function createNewEntity(){
        $result = parent::createNewEntity();
        $result->setPassword(password_hash("password",PASSWORD_BCRYPT));
        $result->setBanned(false);
        return $result;
    }

}