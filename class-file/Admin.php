<?php
class Admin
{
    /**
     * Constructor: Initializes the database connection.
     */
    public function __construct() {}

    /**
     * Insert the admin record into tbl_user.
     * 
     * @return bool|string Returns true on success, or an error message on failure.
     */
    public function insertAdmin()
    {
        include_once "User.php";
        $user = new User();
        $user->email = "admin@admin";
        $user->password = "password";
        $user->user_type = "admin";
        $user->full_name = "Admin";

        $user->insert();
        echo "Admin record inserted successfully.";
        return true;
    }
}
?>

<!-- end of the file Admin.php -->