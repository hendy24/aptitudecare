<?php

class PageControllerAdmin extends PageControllerAdminBase {
    public function init() {

            parent::init();
            Authentication::disallow();
            
            smarty()->assign("actions_nav", array(
                    "/?page=admin&action=roles" => "Assign roles for notifications"
            ));

    }
    
    public function roles() {
        
        // get facility from URL
        $facility = new CMS_Facility(input()->facility);
        smarty()->assignByRef("facility", $facility);
        
        
        
    }
    
    public function submitRoles() {
        $setrole = input()->setrole;
        
        $facility = new CMS_Facility(input()->facility);
        if ($facility->valid()) {
            $users = $facility->getUsers();
            foreach ($users as $user) {
                $user->clearRoles($facility);
            }
            
            
            if (is_array($setrole)) {
                foreach ($setrole as $user_id => $roles) {
                    $user = new CMS_Site_User($user_id);
                    foreach ($roles as $role_id => $ignore) {
                        $role = new CMS_Role($role_id);
                        $user->setRole($role, $facility);
                    }
                }
            }
        }
        $this->redirect(SITE_URL . "/?page=admin&action=roles&facility={$facility->pubid}");
    }
    
}