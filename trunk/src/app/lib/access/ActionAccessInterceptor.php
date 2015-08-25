<?php
/**
 * Zood Framework
 *
 * @category   Zood
 * @package    Zood
 * @copyright  Copyright (c) 2009 ZoodPHP Org. (http://zoodphp.ahdong.com)
 * @since      Sep 5, 2009
 * @version    SVN: $$Id$$
 */

require_once dirname(__FILE__) . '/Authentication.php';

class ActionAccessInterceptor extends Zood_Controller_Action_Interceptor_Abstract
{
    public function before()
    {
        //return;
        $request = $this->getAction()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        $resModule = ucfirst($module);
        $resController = $resModule.'_'.ucfirst($controller);
        $resAction = $resController.'_'.ucfirst($action);
    
        $aclFile = ZOODPP_APP.'/access/acl.php';
        if (file_exists($aclFile)) {
            include $aclFile;
        }
        $aclFile = ZOODPP_APP.'/access/acl.'.strtolower($module[0]).substr($module,1).'.php';
        if (file_exists($aclFile)) {
            include $aclFile;
        }
        
        if (isset($ACL_IGNORE[$resModule]) && (in_array($resAction,$ACL_IGNORE[$resModule]) || in_array($resController,$ACL_IGNORE[$resModule]) || in_array($resModule,$ACL_IGNORE[$resModule]))) {
            //Permission check ignored, do nothing
        } else if (isset($ACL_LOGIN[$resModule]) && (in_array($resAction,$ACL_LOGIN[$resModule]) || in_array($resController,$ACL_LOGIN[$resModule]) || in_array($resModule,$ACL_LOGIN[$resModule]))) {
            //Only login is required, check whether user has logged in or not
            $isLoggedIn = Authentication::getInstance()->isLoggedIn();
            if (!$isLoggedIn) {
                exit('You are not allowed to access this action. <a href="/login/">Click here to Login!</a> ');
            }
        } else {
            $permissionNeeded = array();
            if (isset($ACL[$resModule]) && !empty($ACL[$resModule])) {
                $permissionNeeded += $ACL[$resModule];
            }
            if (isset($ACL[$resController]) && !empty($ACL[$resController])) {
                $permissionNeeded += $ACL[$resController];
            }
            if (isset($ACL[$resAction]) && !empty($ACL[$resAction])) {
                $permissionNeeded += $ACL[$resAction];
            }
            
            if (!empty($permissionNeeded)) {
                //@todo 检查相应权限
            }
        }
    }

    public function after()
    {
        //do nothing;
    }
}
