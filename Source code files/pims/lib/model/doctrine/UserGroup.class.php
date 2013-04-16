<?php

/**
 * Class UserGroup
 * All the functions related to the user groups are handled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda 
 */
class UserGroup extends BaseUserGroup {

    /**
     * Show user group list
     * @param $orderField
     * @param $orderBy
     * @return objects of $userGroups
     */
    public function showUserGroupList($orderField = 'user_group_id', $orderBy = 'ASC') {

        try {

            $q = Doctrine_Query::create()
                    ->from('UserGroup');

            $userGroups = $q->execute();

            return $userGroups;
            

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Search an user group object
     * @param $searchMode
     * @param $searchValue
     * @return object of $userGroup
     */
    public function searchUserGroup($searchMode, $searchValue) {

        try {

            $q = Doctrine_Query::create()
                    ->from('UserGroup')
                    ->where("$searchMode = ?", $searchValue);

            $userGroup = $q->execute();

            return $userGroup;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }


    }

    /**
     * Save an user group object
     */
    public function saveUserGroup() {

        try {

            $this->save();

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * View an user group object 
     * @return object of $userGroup
     */
    public function viewUserGroup() {

        try {

            $userGroup = Doctrine::getTable('UserGroup')
                        ->find($this->getUserGroupId());

            return $userGroup;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Get all user groups
     * @return object of $userGroups
     */
    public function getUserGroups() {

        $q = Doctrine_Query::create()
                ->from('UserGroup g')
                ->orderBy('user_group_name ASC');

        $userGroups = $q->execute();

        return  $userGroups ;
    }

    /**
     * Delete user group
     * @param $userGroupList
     * @return unknown_type
     */
    public function deleteUserGroup($userGroupList) {

        try {

            if (is_array($userGroupList)) {

                $q = Doctrine_Query::create()
                        ->delete('UserGroup')
                        ->whereIn('user_group_id', $userGroupList );

                $numDeleted = $q->execute();
            }

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Check existing user groups
     * @return boolean
    */
    public function isUserGroupExist($groupId, $groupName) {

        try {

            $q = Doctrine_Query::create()
                    ->from('UserGroup')
                    ->where('user_group_name = ?', $groupName);

            $userGroup = $q->fetchArray();

            if (is_array($userGroup) && !empty($userGroup)) {

                if ($userGroup) {

                    if ($userGroup[0]['user_group_id'] == $groupId) {
                        return false;
                    }
                }

                return true;
            }

            return false;


        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }
}
