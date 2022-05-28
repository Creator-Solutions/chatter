<?php

require '../conn.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");
class Auth
{

    private static $dbHelper;
        
    private static $SQL;
    private static $stmt;
    private static $row;
    private static Array $response;

    private static $encodedData;
    private static $decodedData;

    private static string $Message;
    private static string $email;
    private static string $password;   
    
     /**
     * Class Constructor
     * 
     * 
     * @return Void
     */

    public function __construct() 
    {
        self::$dbHelper = new DatabaseHelper;
        self::$dbHelper::init();

        self::$encodedData = file_get_contents("php://input");
        self::$decodedData = json_decode(self::$encodedData, true);

        if (isset(self::$decodedData))
        {
            if (self::$decodedData['Type'] == 'Login')
            {
                self::authenticate(self::$dbHelper::$conn);
            }else {
                self::create(self::$dbHelper::$conn);
            }           
        }
    }

    /**
     * Handles User Authentication
     * 
     * 
     * @return JSON
     */

    public static function authenticate($conn)
    {
        if ($conn)
        {

            self::$email = isset(self::$decodedData) ? self::$decodedData['Email'] : "";
            self::$password = isset(self::$decodedData)  ? self::$decodedData['Password'] : "";

            try
            {
                self::$SQL = "SELECT * FROM users WHERE Email=?";
                self::$stmt = $conn->prepare(self::$SQL);
                self::$stmt->bindValue(1, self::$email, PDO::PARAM_STR);
                self::$stmt->execute();

                self::$row = self::$stmt->fetch(PDO::FETCH_ASSOC);

                if (isset(self::$row['UUID']))
                {
                    if (password_verify(self::$password, self::$row['Password']))
                    {
                        self::$Message = "Authenticated";
                        self::$response[] = array('Message' => self::$Message, 'Name' => self::$row['FullName'], 'UUID' => self::$row['UUID'], 'Token' => self::Generate_Token());
                                                                     
                    }else 
                    {
                        self::$Message = "Incorrect Password";
                        self::$response[] = array('Message' => self::$Message);                      
                    }
                }else 
                {                   
                    self::$Message = "Account Not Found";
                    self::$response[] = array('Message' => self::$Message);
                }

            }catch (Exception $ex)
            {
                self::$Message = $ex;
                self::$response[] = array('Message' => self::$Message);
            }           
        }  

        echo json_encode(self::$response);
    }


     /**
     * Handles User Account Creation
     * 
     * 
     * @return JSON
     */
    public static function create($conn)
    {
        if ($conn)
        {

            try
            {

                self::$SQL = "";
                self::$stmt = $conn->prepare(self::$SQL);
                self::$stmt->bindValue(1, self::$email, PDO::PARAM_STR);
                self::$stmt->execute();



            }catch (Exception $ex)
            {
                throw new Exception('[Exception]: ', $ex);
            }

        }
    }

    private static function Generate_Token()
    {
        //Generate a random string.
        $token = openssl_random_pseudo_bytes(16);
        
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);

        return $token;
    }
}

$auth = new Auth();