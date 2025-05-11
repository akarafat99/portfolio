<?php
class SessionManager
{
    public function __construct()
    {
        $this->start();
    }

    /**
     * Start the session if it's not already started.
     */
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Check if a session is already active.
     * 
     * @return bool True if a session is active, false otherwise.
     */
    public function isSessionActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Set a session variable (supports objects and other types).
     * 
     * @param string $key The key for the session variable.
     * @param mixed $value The value to store in the session.
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable (returns null if not found).
     * 
     * @param string $key The key for the session variable.
     * @return mixed|null The value of the session variable or null if not set.
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Check if a session variable exists.
     * 
     * @param string $key The key for the session variable.
     * @return bool True if the variable exists, false otherwise.
     */
    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a specific session variable.
     * 
     * @param string $key The key for the session variable to remove.
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the entire session.
     */
    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}


?>

<!-- END -->