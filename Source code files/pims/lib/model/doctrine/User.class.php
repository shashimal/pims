<?php
/**
 * Class User
 * All the functions related to the users are handled by this class
 * @package    pims
 * @subpackage model
 * @author     Your name here 
 */
class User extends BaseUser {

    /**
     * Authenticate the user
     * @return boolean
     */
    public function authenticateLogin() {

        $authenticated =false;

        $q = Doctrine_Query::create()
                ->select('*')
                ->from('User u')
                ->where('u.user_name = ?', $this->getUserName())
                ->andWhere('u.user_password = ?', $this->getUserPassword())
                ->andWhere('u.status != ?', "Disabled");

        $users = $q->execute();

        if (count($users) === 1) {

            list($user) =$users;
            $authenticated =($user->getUserName() === $user->getUserName());
            $this->setUserId($user->getUserId());
            $this->setUserGroupId($user->getUserGroupId());
            $this->setUserGroup($user->getUserGroup());
            $this->setStatus($user->getStatus());

        }

        return $authenticated;
    }

    /**
     * Show users list
     * @param $orderField
     * @param $orderBy
     * @return Objects of $users
     */
    public function showUserList($orderField = 'user_id', $orderBy = 'ASC') {

        try {

            $q = Doctrine_Query::create()
                    ->from('User u')
                    ->leftJoin('u.UserGroup g ON u.user_group_id = g.user_group_id');

            $users = $q->fetchArray();

            return $users;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Search an user object
     * @param $searchMode
     * @param $searchValue
     * @return object of $user
     */
    public function searchUser($searchMode, $searchValue) {

        try {
            $q = Doctrine_Query::create()
                    ->from('User')
                    ->where("$searchMode = ?", $searchValue);

            $user = $q->execute();

            return $user;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }


    }

    /**
     * View an user object
     * @return object of $user
     */
    public function viewUser() {

        try {

            $q = Doctrine_Query::create()
                    ->from('User u')
                    ->where('u.user_id = ?', $this->getUserId());

            $uesr = $q->fetchArray();

            return $uesr;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Save an user object
     */

    public  function saveUser() {

        try {

            $this->save();

        }catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Get an object of user
     * @return $user
     */
    public function getUserObject() {

        try {

            $user = Doctrine::getTable('User')
                    ->find($this->getUserId());

            return $user;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

 	/**
     * Get an object of user
     * @return $user
     */
    public function getUserObjectForChangePassword() {

        try {

            $q = Doctrine_Query::create()
                            ->from('User')
                            ->where('user_id = ?', $this->getUserId())
                            ->andWhere('user_name  = ?', $this->getUserName())
                            ->andWhere('user_password  = ?', $this->getUserPassword());

                  $user =     $q->execute();
            return $user;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
    
    /**
     * Get all users
     * @return object of $user
     */
    public function getUsers() {

        try {
            
            $q = Doctrine_Query::create()
                    ->from('User ')
                    ->orderBy('user_name ASC');

            $users = $q->fetchArray();

            return  $users ;

        }catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }

    }

    /**
     * Delete user
     * @param $userList
     * @return unknown_type
     */
    public function deleteUser($userList) {

        try {

            if (is_array($userList)) {

                $q = Doctrine_Query::create()
                        ->delete('User')
                        ->whereIn('user_id', $userList );

                $numDeleted = $q->execute();
            }

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Check an existing user
     * @return boolean
     */
    public function isUserExist($userId, $userName) {

        try {

            $q = Doctrine_Query::create()
                    ->from('User')
                    ->where("user_name = ?", $userName);

            $user = $q->fetchArray();

            if (is_array($user) && !empty($user)) {

                if ($user) {

                    if ($user[0]['user_id'] == $userId) {
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

    /**
     * View an user object
     * @return object of $user
     */
    public function UserT() {

        try {

            $q = Doctrine_Query::create()
                    ->from('User u');


            $uesr = $q->fetchArray();

            return $uesr;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }
}
