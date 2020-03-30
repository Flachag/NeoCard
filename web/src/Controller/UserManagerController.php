<?php


namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class UserManagerController extends EasyAdminController
{
    protected function createNewEntity(){
        $result = parent::createNewEntity();
        $result->setPassword(password_hash("password",PASSWORD_BCRYPT));
        $result->setBanned(false);
        dump($result);
        return $result;
    }

}