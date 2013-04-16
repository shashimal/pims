<?php
/**
 * Class Rights
 * All the functions related to the user rights handled by this class
 * @package    pims
 * @subpackage model
 * @author     Your name here
 */
class Rights extends BaseRights {

    /**
     * Get assigned rights for a user group
     * return an array of rights
     */
    public function getUserRights() {

        try {

            $q = Doctrine_Query::create()
                    ->from('Rights r')
                    ->leftJoin('r.SysModule m')
                    ->where('r.user_group_id = ?', $this->getUserGroupId());

            $rights =$q->fetchArray();

            return $rights;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Show user group's rights list
     * @param $orderField
     * @param $orderBy
     * @return objects of $userGroupRights
     */
    public function showUserGroupRightsList($orderField = 'user_group_id', $orderBy = 'ASC') {

        try {
            
            $q = Doctrine_Query::create()
                    ->from('Rights r')
                    ->leftJoin('r.SysModule m')
                    ->orderBy($orderField .' ' .$orderBy);

            $rights = $q->fetchArray();

            return $rights;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Save user rights
     *
     */
    public function saveUserGroupRights() {

        try {

            $this->save();

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * View rights of a module
     * @return an array of $userGroupRights
     */
    public function viewUserGroupRightsOfModule() {

        try {

            $q = Doctrine_Query::create()
                    ->from('UserGroup u')
                    ->leftJoin('u.Rights r')
                    ->leftJoin('r.SysModule m')
                    ->where('r.user_group_id = ?', $this->getUserGroupId())
                    ->andWhere('r.module_id = ?', $this->getModuleId());

            $userGroupRights =$q->fetchArray();

            return $userGroupRights;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Get an object of user rights
     * @return $userGroupRights
     */
    public function getUserGroupRightsObject() {

        try {

            $userGroupRights = Doctrine::getTable('Rights')
                               ->find(array($this->getUserGroupId(), $this->getModuleId()));

            return $userGroupRights;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Reset user rights
     */
    public function resetUserGroupRights() {

        try {

            $userGroupRights = Doctrine::getTable('Rights')
                               ->findOneByUserGroupId($this->getUserGroupId());

            return $userGroupRights;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
}
