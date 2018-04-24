<?php

/**
 * @author Dimitri Alikakos
 */

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $original_pass = $password;
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, fullname, email, encrypted_password, password, salt, createdOn) VALUES(?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $uuid, $name, $email, $encrypted_password,$original_pass, $salt);
        $stmt->execute();
        $stmt->close();

        // check for successful store

        $stmt = $this->conn->prepare("SELECT fullname,email,createdOn,updated_at,unique_id FROM users WHERE email = ?  ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($name, $email,$created_at,$updated_at,$uid);
        if ($created_at != NULL) {
            /* set values */
            $user['success'] = 1;
            $user['name'] = $name;
            $user['email'] = $email;
            $user["unique_id"] = $uid;
            $user["updated_at"] = $updated_at;
            $user['created_at'] = $created_at;
            $stmt->close();
        }else{
            $user['success'] = 0;
        }

        return $user;

    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $stmt = $this->conn->prepare("SELECT fullname,email,id,updated_at,unique_id FROM users WHERE email = ?  ");

        $stmt->bind_param("s", $email);

        $stmt->execute();
        $stmt->bind_result($name, $email,$created_at,$updated_at,$uid);
        /* fetch values */
        mysqli_stmt_fetch($stmt);
        if ($created_at != NULL) {
            /* set values */
            $user['success'] = 1;
            $user['name'] = $name;
            $user['email'] = $email;
            $user["unique_id"] = $uid;
            $user["updated_at"] = $updated_at;
            $user['created_at'] = $created_at;
            $stmt->close();
        }else{
            $user['success'] = 0;
        }
        return $user;
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
