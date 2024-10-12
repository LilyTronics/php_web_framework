<?php

/*
 * Model for handling sessions.
 */


class ModelSession {

    static public function setData($key, $data) {
        $_SESSION[$key] = $data;
    }


    static public function getData($key, $default="") {
        $value = $default;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
        }
        return $value;
    }


    static public function clearData($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }


    static public function destroySession() {
        // Clear all cookies for this session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                    );
        }
        session_destroy();
        session_start();
    }

}

?>
