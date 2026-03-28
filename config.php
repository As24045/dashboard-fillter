<?php
class Database
{
    private $servername = 'localhost';
    private $username = 'carobar_user';
    private $password = 'ikywj4RW0w0BHSe';
    private $dbname = 'carobar_db';
    private $conn;
    private $uid_prefix = 'EMP';


    // ! ||--------------------------------------------------------------------------------||
    // ! ||                           CREATE A CONNECTION WITH PDO                         ||
    // ! ||--------------------------------------------------------------------------------||
    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->servername};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function test_input($form_data)
    {
        $form_data = trim($form_data);
        $form_data = stripslashes($form_data);
        $form_data = strip_tags($form_data);
        $form_data = htmlspecialchars($form_data, ENT_QUOTES, 'UTF-8');
        $form_data = filter_var($form_data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_HIGH);
        return $form_data;
    }

    public function sql($query)
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            // Handle the exception, log it or throw a custom exception.
            return false;
        }
    }

    public function insert($table_name, $data)
    {
        $table_name = $this->test_input($table_name);
        $columns = array_keys($data);
        $placeholders = ':' . implode(', :', $columns);

        $sql = "INSERT INTO " . $table_name . " (" . implode(",", $columns) . ") VALUES (" . $placeholders . ")";

        try {

            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            foreach ($data as $column => $value) {
                $stmt->bindValue(':' . $column, $value);
            }

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // handle the exception or log the error message
            return false;
        }
    }

    public function update($table, $para = array(), $id)
    {
        $args = array();
        $table = $this->test_input($table);
        foreach ($para as $key => $value) {
            $args[] = "$key = '$value'";
        }
        $sql11 = "UPDATE  $table SET " . implode(',', $args);
        $sql11 .= " WHERE $id";
        try {
            // echo $sql11;
            $stmt = $this->conn->prepare($sql11);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // error_log('Error updating record: ' . $e->getMessage());
            return false;
        }
    }



}


