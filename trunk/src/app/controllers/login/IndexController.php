<?php
Zood_Loader::loadClass('Zood_Controller_Action');

class IndexController extends Zood_Controller_Action
{
    /**
     * Login form
     *
     * @return string
     */
    public function indexAction()
    {
        $this->addResult(self::RESULT_SUCCESS, 'php', 'login/index.php');
        $this->setData('u',$this->getU());
        return self::RESULT_SUCCESS;
    }

    /**
     * Login
     *
     * @return string
     */
    public function loginAction()
    {
        $success = false;
        $error = '';
        require_once ZOODPP_APP . '/models/UserModel.php';

        $username = $this->getParam('username');
        $password = $this->getParam('password');
        $persistent = $this->getParam('persistent');
        $persistent = $persistent ? 1 : 0;

        $user = UserModel::getUserByUsername($username);
        if (empty($user)) {
            $error = 'User is not existent';
        } else if ($user['password'] != $password) {
            $error = 'Incorrect password';
        } else {
            $success = true;
        }

        if ($success) {
            require_once ZOODPP_APP . '/models/SessionModel.php';
            $sessionid = session_id();
            $csession = SessionModel::getSessionBySessionid($sessionid);
            $session = array('sessionid'=>$sessionid,'userid'=>$user['userid'],'username'=>$user['username'],'persistent'=>$persistent,'ip'=>Zood_Util::clientIP(),'user_agent'=>$_SERVER['HTTP_USER_AGENT']);
            if ($csession) {
                SessionModel::updateSession($session,$sessionid);
            } else {
                SessionModel::addSession($session);
            }
            if ($persistent) {
                setcookie('ZOODSID',$sessionid,time()+36000*24*30,'/');
            } else {
                setcookie('ZOODSID',$sessionid,null,'/');
            }
            $_SESSION['ZOODSID'] = $sessionid;

            $u = $this->getU();
            header('Location: '.$u);
            exit;
        } else {
            $this->addResult(self::RESULT_SUCCESS, 'php', 'login/index.php');
            $this->setData($this->getUserParams());
            $this->setData('error', $error);
            $this->setData('u',$this->getU());
            return self::RESULT_SUCCESS;
        }
    }

    private function getU()
    {
        $u = $this->getParam('u');
        if (empty($u)) {
            $u = Zood_Util::getReferer();
        }
        if (empty($u)) {
            $u = '/';
        }

        return $u;
    }

    /**
     * Logout
     *
     * @return unknown_type
     */
    public function logoutAction()
    {
        require_once ZOODPP_APP . '/models/SessionModel.php';
        $sessionid = isset($_SESSION['ZOODSID']) ? $_SESSION['ZOODSID'] : (isset($_COOKIE['ZOODSID']) ? $_COOKIE['ZOODSID'] : session_id());
        $csession = SessionModel::getSessionBySessionid($sessionid);
        if ($csession) {
            SessionModel::deleteSession($sessionid);
        }
        $_SESSION['ZOODSID'] = null;
        setcookie('ZOODSID',null,time()-3600000000,'/');
        echo "Logout successfully!";
    }
}
// End ^ LF ^ UTF-8
