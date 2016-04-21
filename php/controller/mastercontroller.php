<?php

class MasterController {       

    protected function serviceSession() {
        $this->startSession();
        if (isset($_SESSION['sysAdmin'])) {
            $this->_faraoAdmin = $_SESSION['sysAdmin'];
        } else {
            $this->_faraoAdmin = new FaraoAdminService($this->_configs);
            $_SESSION['sysAdmin'] = $this->_faraoAdmin;
        }
        session_write_close();
    }

    protected function checkUserActivity() {
        $this->startSession();
        session_write_close();
        return isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800);
        // request 30 minates ago
    }

    protected function updateUserActivity() {
        $this->startSession();
        $_SESSION['LAST_ACTIVITY'] = time();
        session_write_close();
    }

    protected function isLoggedOn() {
        $this->startSession();
        session_write_close();
        return isset($_SESSION['current_user']);
    }

    protected function setSessionAttr($key, $val) {
        $this->startSession();
        $_SESSION[$key] = $val;
        session_write_close();
    }

    protected function getSessionAttr($key) {
        $this->startSession();
        session_write_close();
        return $_SESSION[$key];
    }

    protected function startSession() {
        //FIXME CLOSE WRITE WEHN NOT USING
        if (session_status() == PHP_SESSION_NONE) {
            session_name('farao_session');
            session_set_cookie_params(0);
            session_start();
        }
    }

}
