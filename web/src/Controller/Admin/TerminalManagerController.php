<?php


namespace App\Controller\Admin;


use App\Entity\Account;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class TerminalManagerController extends EasyAdminController
{
    public function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $manager = $this->getDoctrine()->getRepository(Account::class);

        /**
         * @var QueryBuilder
         */
        $result = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
        $admin = count(array_intersect($this->getUser()->getRoles(),["ROLE_ADMIN"]));
        if (method_exists($entityClass, 'getIdaccount') && $admin == 0){
            $account = $manager->findOneBy(["iduser" => $this->getUser()->getId()])->getId();
            $result->andWhere("entity.idaccount = $account");
        }
        return $result;
    }

    public function createNewEntity()
    {
        $result = parent::createNewEntity();
        $admin = count(array_intersect($this->getUser()->getRoles(),["ROLE_ADMIN"]));
        if($admin == 0){
            $manager = $this->getDoctrine()->getRepository(Account::class);
            $account = $manager->findOneBy(["iduser" => $this->getUser()->getId()])->getId();
            $result->setIdAccount($account);
        }
        return $result;
    }
}