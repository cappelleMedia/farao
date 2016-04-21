<?php

class ErrorController {
    
    public function goToErrorPage($ex) {
        $nextPage = $this->errLog($ex);
        if (!$nextPage) {
            $nextPage = 'error_pages/server_error.php';
        }
        require_once(dirname(__FILE__) . '/../../pages/' . $nextPage);
    }
    
    public function errLog($error) {
        ErrorLogger::logError($error);
        return $this->toErrorPage($error->getMessage(), 'server');
    }
    
    public function toErrorPage($message, $errorType) {
        if(!empty($message)){
        $_GET['extraMessage'] = $message;
        } else {
            $_GET['extraMessage'] = 'Internal problems';
        }
        $nextPage = 'error_pages/';
        if ($errorType === 'server') {
            $nextPage .= 'server_error.php';
        } else {
            $nextPage .= 'page_not_found_error.php';
        }
        return $nextPage;
    }
}
