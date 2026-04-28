<?php
class Session {
    public function __construct() {
        if(session_status() == PHP_SESSION_NONE) { 
            session_start(); 
            }
    }
    public function set($key, $value) { 
        $_SESSION[$key] = $value; 
        }

    public function remove($key) {
        return $_SESSION[$key] ?? null;
    }
    public function regenerate() {
        session_regenerate_id(true);
    }
    public function get($key) { 
        return $_SESSION[$key] ?? null; 
        }
    public function destroy() { 
        session_destroy(); 
        }
}
?>