<?php

/**
 * auth actions.
 *
 * @package    pims
 * @subpackage auth
 * @author     Shashimal Warakagoda
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class authActions extends sfActions {

    /**
     * Executes index action
     * @param sfRequest $request a request object
     */
    public function executeIndex(sfWebRequest $request) {

        if ($_SESSION['login'] ==true) {
             $this->forward('registration', 'showPatientList');
        } else {
            $this->forward('auth', 'showLoginScreen');
        }
    }

    /**
     * Display the login page for the users
     * @param $request
     */
    public function executeShowLoginScreen(sfWebRequest $request) {

        $session =$this->getUser();
        $this->message =$session->getAttribute('message', '');
        $this->messageType =$session->getAttribute('messageType', '');
    }

    /**
     * Authenticate the user
     * @param $request
     */
    public function executeLogin(sfWebRequest $request) {

        $user =new User();
        $authenticated =false;

        if ($request->isMethod('post')) {

            $user->setUserName($request->getParameter('txtUsername'));
            $user->setUserPassword(md5($request->getParameter('txtPassword')));
            $authenticated =$user->authenticateLogin();


            if ($authenticated) {

                $_SESSION['login'] =true;
                $_SESSION['login_user'] =$user->getUserName();
                $_SESSION['userId'] =$user->getUserId();

                //Get the user rights and modules for the assigned user group
                $rights =new Rights();
                $rights->setUserGroupId($user->getUserGroupId());
                $userRights =$rights->getUserRights();

                $arrModules =array();

                if (isset($arrModules)) {

                    foreach ($userRights as $userRight) {

                        $arrUserRights =array();

                        $arrUserRights['module_id'] =$userRight['module_id'];
                        $arrUserRights['module'] =$userRight['SysModule']['name'];
                        $arrUserRights['add'] =$userRight['adding'];
                        $arrUserRights['edit'] =$userRight['editing'];
                        $arrUserRights['delete'] =$userRight['deleting'];
                        $arrUserRights['view'] =$userRight['viewing'];

                        $arrModules[] =$arrUserRights;
                    }

                    $_SESSION['assignedModules'] =$arrModules;
                }

                $this->redirect('registration/showPatientList');

            } else {
                $this->loginFaild = true;
                $this->getUser()->setFlash('error', 'Invalid login! Please enter valid username and password');
                $this->redirect('auth/showLoginScreen/');
            }
        }else {
            $this->loginFaild = "";
            $this->getUser()->setFlash('error', 'Invalid login! Please enter valid username and password');
            $this->redirect('auth/showLoginScreen/');
        }
    }

    /**
     * Log out the user
     */
    public function executeLogout() {

        unset($_SESSION['login']);
        $this->redirect('auth/index');
    }

    /**
     * Show the change password form
     */
    public function executeShowChangePassword() {

        //Password Changed by Admin
        $rights =$_SESSION['arrRights'];
        $mod =$_SESSION['arrMod'];

        $objUser =new User();

        if ($rights[$mod['Admin']]['edit'] == 1) {

            $this->users = $objUser->getUsers();

        } else {

            //Normal Users
            $objUser->setUserId($_SESSION['userId']);
            $this->users = $objUser->viewUser();

        }

    }

    /**
     * Change the password of users
     * @param $request
     */
    public function executeChangePassword(sfWebRequest $request) {

        if ($request->isMethod('post')) {

            $user = new User();

            $userId = $request->getParameter('cmbUserId');
            $userName = $request->getParameter('txtUserName');
            $password = $request->getParameter('txtOldPassword');
            $user->setUserId($userId);
            $user->setUserName($userName);
            $user->setUserPassword(md5($password));
            $objUser = $user->getUserObjectForChangePassword();            
            if(count($objUser)>0) {
                $objUser[0]->setUserPassword(md5($request->getParameter('txtPassword')));
                $objUser[0]->saveUser();

                $this->getUser()->setFlash('notice', "Password changed successfully");
                $this->redirect('auth/showChangePassword');
            }else {
                 $this->getUser()->setFlash('error', "Invalid user name or old password entered");
                $this->redirect('auth/showChangePassword');
            }
        }
    }

}
