<?php
/*PDO create connection to database
* use prepared statement
* Bind Values
* Return rows and results
*/

class Database {
    private $host = DB_HOST; 
    private $user = DB_USER;
    private $password = DB_PASS;  
    private $dbname = DB_NAME;

    private $databasehandler;
    private $statement;
    private $error;

    public function __construct(){
        $dsn = 'mysql:host='. $this->host . ';dbname='. $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        //PDO instance 
        try{
            $this->databasehandler = new PDO($dsn, $this->user, $this->password, $options);
        } 
            catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
        }
    }

    //Prepare statements
    public function query($sql){
        $this->statement = $this->databasehandler->prepare($sql);
    }

    // Bind Values
    public function bind($param, $value, $type = null){
        if(is_null(type)){
            switch(true){
                case is_int($value): 
                $type = PDO::PARAM_INT;
                break;
                case is_bool($value): 
                $type = PDO::PARAM_BOOL;
                break;
                case is_null($value): 
                $type = PDO::PARAM_NULL;
                break;
                default:
                $type = PDO::PARAM_STR;
            }
        }

        $this->statement->bindValue($param, $value, $type);
    }

    //execute prepared statement
    public function execute(){
        return $this->statement->execute();
    }

    // Get results set as array of objects
    public function resultSet(){
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    // Get results set as single object
    public function resultSingle(){
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount(){
        return $this->statement->rowCount();
    }
}
?>