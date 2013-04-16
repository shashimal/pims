<?php
class UserGroupService {
    
    public function showUserGroupList() {

        $userGroups =$this->_getUserGroupObject()->showUserGroupList();
        
        return $userGroups;
    
    }

    public function searchUserGroup($searchMode, $searchValue) {

        $userGroups =$this->_getUserGroupObject()->searchUserGroup($searchMode, $searchValue);
        
        return $userGroups;
    }

    public function saveUserGroup(UserGroup $userGroup) {

        if ($userGroup->getUserGroupId() == '') {
            $idGenService =new UniqueIdGenerator();
            
            $idGenService->setEntityTable("UserGroup");
            $userGroup->setUserGroupId($idGenService->getNextID());
        }
        
        $userGroup->saveUserGroup();
    }

    
    
    private function _getUserGroupObject() {

        $objUserGroup = new UserGroup();
        
        return $objUserGroup;
    }
}

?>