<?php
/**
 * Admin actions.
 * All the functions of the admin module are handled by this class.
 * @package    pims
 * @subpackage admin
 * @author     Shashimal Warakagoda
 */
class adminActions extends sfActions {

    /**
     * Display the default page of admin module
     * @return next auto increment ID
     */
    public function executeIndex(sfWebRequest $request) {

    }

    /**
     * List user groups
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowUserGroupList(sfWebRequest $request) {

        $userGroup = new UserGroup();
        $this->userGroups = $userGroup->showUserGroupList();
    
    }

    /**
     * Show user group form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddUserGroup(sfWebRequest $request) {

    }

    /**
     * View user group
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewUserGroup(sfWebRequest $request) {

        $userGroup = new UserGroup();
        $userGroup->setUserGroupId($request->getParameter('id'));
        $this->group = $userGroup->viewUserGroup();
    }

    /**
     * Save user group object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveUserGroup(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $idGenService = new UniqueIdGenerator();
            $idGenService->setEntityTable('UserGroup');
            
            $userGroup = new UserGroup();
            
            if ($userGroup->isUserGroupExist($idGenService->getNextID(false), $request->getParameter('txtName'))) {
                
                $this->getUser()->setFlash('error', 'User group is already existing');
                $this->redirect('admin/showUserGroupList/');
            
            } else {
                
                $userGroup->setUserGroupName($request->getParameter('txtName'));
                $userGroup->setDescription($request->getParameter('txtDescription'));
                $userGroupId = $idGenService->getNextID();
                $userGroup->setUserGroupId($userGroupId);
                $userGroup->saveUserGroup();
                
                $this->getUser()->setFlash('notice', 'Record saved successfully');
                $this->redirect('admin/addUserGroupRights?id=' .$userGroupId);
            
            }
        }
    }

    /**
     * Update user group object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdateUserGroup(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $userGroup = new UserGroup();
            $userGroup->setUserGroupId($request->getParameter('id'));
            
            if ($userGroup->isUserGroupExist($request->getParameter('id'), $request->getParameter('txtName'))) {
                
                $this->getUser()->setFlash('error', 'User group is already existing');
                $this->redirect('admin/showUserGroupList/');
            
            } else {
                
                $this->group = $userGroup->viewUserGroup();
                $this->group->setUserGroupName($request->getParameter('txtName'));
                $this->group->setDescription($request->getParameter('txtDescription'));
                $this->group->saveUserGroup();
                
                $this->getUser()->setFlash('notice', 'Record saved successfully');
                $this->redirect('admin/showUserGroupList/');
            }
        
        }
    }

    /**
     * Delete user group
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteUserGroup(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $userGroup = new UserGroup();
            $userGroup->deleteUserGroup($request->getParameter('chkLocID'));
            $this->getUser()->setFlash('notice', 'Successfully deleted the record');
        
        } else {
            
            $this->getUser()->setFlash('error', 'Select at least one record to delete');
        }
        
        $this->redirect('admin/showUserGroupList/');
    }

    /**
     * Show assigned user rights form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddUserGroupRights(sfWebRequest $request) {

        $userGroup = new UserGroup();
        $userGroup->setUserGroupId($request->getParameter('id'));
        $this->group = $userGroup->viewUserGroup();
        
        $rights = new Rights();
        $rights->setUserGroupId($this->group->getUserGroupId());
        $this->assignedModuleRights = $rights->getUserRights();
        
        $sysModule = new SysModule();
        $this->moduleList = $sysModule->getModuleList($this->assignedModuleRights);
    
    }

    /**
     * Save user group rights object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveUserGroupRights(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $userGroupId = $request->getParameter('id');
            
            $rights = new Rights();
            $rights->setUserGroupId($request->getParameter('id'));
            $rights->setModuleId($request->getParameter('cmbModuleID'));
            $rights->setAdding($request->getParameter('chkAdd'));
            $rights->setEditing($request->getParameter('chkEdit'));
            $rights->setDeleting($request->getParameter('chkDelete'));
            $rights->setViewing($request->getParameter('chkView'));            
            $rights->saveUserGroupRights();
            
            $this->getUser()->setFlash('notice', "Record saved successfully");
            $this->redirect('admin/addUserGroupRights?id=' .$userGroupId);
        
        }
    }

    /**
     * View user group rights
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewUserGroupRights(sfWebRequest $request) {

        $rights = new Rights();        
        $rights->setUserGroupId($request->getParameter('gid'));
        $rights->setModuleId($request->getParameter('mid'));
        $this->groupRights = $rights->viewUserGroupRightsOfModule();
    
    }

    /**
     * Update user rights Object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdateUserGroupRights(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $userGroupId = $request->getParameter('txtUserGroupID');
            
            $rights = new Rights();          
            $rights->setUserGroupId($userGroupId);
            $rights->setModuleId($request->getParameter('txtModId'));
            
            $this->groupRights = $rights->getUserGroupRightsObject();            
            $this->groupRights->setAdding($request->getParameter('chkAdd'));
            $this->groupRights->setEditing($request->getParameter('chkEdit'));
            $this->groupRights->setDeleting($request->getParameter('chkDelete'));
            $this->groupRights->setViewing($request->getParameter('chkView'));            
            $this->groupRights->saveUserGroupRights();
            
            $this->getUser()->setFlash('notice', "Record saved successfully");
            $this->redirect('admin/addUserGroupRights?id=' .$userGroupId);
        }
    
    }

    /**
     * Reset user rights object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeResetUserGroupRights(sfWebRequest $request) {

        $rights = new Rights();
        
        $userGroupId = $request->getParameter('txtUserGroupId');
        $rights->setUserGroupId($userGroupId);
        
        $arrayModules = $request->getParameter('module_id');
        
        foreach ($arrayModules as $module) {
            
            $rights->setModuleId($module);
            $this->groupRights = $rights->getUserGroupRightsObject();
            $this->groupRights->setAdding($request->getParameter('chkAdd'));
            $this->groupRights->setEditing($request->getParameter('chkEdit'));
            $this->groupRights->setDeleting($request->getParameter('chkDelete'));
            $this->groupRights->setViewing($request->getParameter('chkView'));
            $this->groupRights->saveUserGroupRights();
        }
        
        $this->getUser()->setFlash('notice', "Records reset successfully");
        $this->redirect('admin/addUserGroupRights?id=' .$userGroupId);
    }

    /**
     * List users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowUserList(sfWebRequest $request) {

        $user = new User();
        $this->users = $user->showUserList();
    }

    /**
     * Show user form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddUser(sfWebRequest $request) {

        $userGroups = new UserGroup();
        $this->groups = $userGroups->getUserGroups();
    }

    /**
     * Save user object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveUser(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $idGenService = new UniqueIdGenerator();
            $idGenService->setEntityTable("User");
            
            $user = new User();
            
            if ($user->isUserExist($idGenService->getNextID(false), $request->getParameter('txtUserName'))) {
                
                $this->getUser()->setFlash('error', "User name is already existing");
                $this->redirect('admin/showUserList/');
            
            } else {
                
                $userId = $idGenService->getNextID();
                $user->setUserId($userId);
                $user->setUserName($request->getParameter('txtUserName'));
                $user->setUserPassword(md5($request->getParameter('txtPassword')));
                $user->setIsAdmin('No');
                $user->setStatus($request->getParameter('cmbUserStatus'));
                $user->setUserGroupId($request->getParameter('cmbUserGroup'));
                $user->saveUser();
            }
            
            $this->getUser()->setFlash('notice', "Record saved successfully");
            $this->redirect('admin/showUserList');
        }
    }

    /**
     * View user object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewUser(sfWebRequest $request) {

        $objUser = new User();
        $objUser->setUserId($request->getParameter('id'));
        $this->user = $objUser->viewUser();
        $userGroups = new UserGroup();
        $this->groups = $userGroups->getUserGroups();
    
    }

    /**
     * Update an user object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdateUser(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $user = new User();
            
            if ($user->isUserExist($request->getParameter('txtUserId'), $request->getParameter('txtUserName'))) {
                $this->getUser()->setFlash('error', "User name is already existing");
                $this->redirect('admin/showUserList/');
            }
            
            $userId = $request->getParameter('txtUserId');
            $user->setUserId($userId);
            $objUser = $user->getUserObject();
            $objUser->setUserName($request->getParameter('txtUserName'));
            $objUser->setUserPassword(md5($request->getParameter('txtPassword')));
            $objUser->setIsAdmin('No');
            $objUser->setStatus($request->getParameter('cmbUserStatus'));
            $objUser->setUserGroupId($request->getParameter('cmbUserGroup'));
            $objUser->saveUser();
            
            $this->getUser()->setFlash('notice', "Record saved successfully");
            $this->redirect('admin/showUserList');
        }
    }

    /**
     * Delete an user object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteUser(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $user = new User();            
            $user->deleteUser($request->getParameter('chkLocID'));
            $this->getUser()->setFlash('notice', 'Successfully deleted the record');
        
        } else {
            
            $this->getUser()->setFlash('error', 'Select at least one record to delete');
        }
        
        $this->redirect('admin/showUserList/');
    }

}
