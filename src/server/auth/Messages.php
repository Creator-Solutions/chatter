<?php 

require '../conn.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

class Message
{

    private static $dbHelper;
        
    private static $SQL;
    private static $stmt;
    private static $row;

    private static Array $response;
    private static Array $room;

    private static $encodedData;
    private static $decodedData;

    private static string $Message;

    private static string $room_ID;   
    private static string $UUID;
    private static string $timestamp;
    private static string $msg;  

    public function __construct()
    {
        self::$dbHelper = new DatabaseHelper;
        self::$dbHelper::init();

        if (isset(self::$encodedData))
        {
            unset(self::$decodedData);
        }
        
        self::$encodedData = file_get_contents('php://input');
        self::$decodedData = json_decode(self::$encodedData, true);
        
        if (self::$decodedData['Type'] == 'Messages') 
        {
            self::Get_Messages(self::$dbHelper::$conn);

        }else if (self::$decodedData['Type'] == 'Send') 
        {
            self::Send_Message(self::$dbHelper::$conn);
        }
        
    }

    public static function Get_Messages($conn)
    {
        if ($conn)
        {
            self::$room_ID = isset(self::$decodedData) ? self::$decodedData['Room_ID'] : "";

            try
            {

                self::$SQL = "SELECT users.FullName, messages.Timestamp, messages.Message FROM messages INNER JOIN users ON messages.Sender = users.UUID WHERE messages.Sender = users.UUID AND Room_ID=?";
                self::$stmt = $conn->prepare(self::$SQL);
                self::$stmt->bindValue(1, self::$room_ID, PDO::PARAM_STR);
                self::$stmt->execute();
                
                while (self::$row = self::$stmt->fetch())
                {
                    self::$response[] = self::$row;
                }
                

            }catch (Exceptions $ex)
            {
                self::$Message = "Could Not Get Messages";
            }    
        }

        echo json_encode(self::$response);
    }

    public static function Send_Message($conn)
    {
        if ($conn)
        {
            //Timestamp
            self::$room_ID = isset(self::$decodedData) ? self::$decodedData['Room_ID'] : "";
            self::$UUID = isset(self::$decodedData) ? self::$decodedData['UUID'] : "";
            self::$timestamp = isset(self::$decodedData) ? self::$decodedData['Timestamp'] : "";
            self::$msg = isset(self::$decodedData) ? self::$decodedData['Message'] : "";

            try
            {               
                self::$SQL = "INSERT INTO messages (Room_ID, Sender, Timestamp, message) VALUES (?,?,?,?)";
                self::$stmt = $conn->prepare(self::$SQL);
                self::$stmt->bindValue(1, self::$room_ID, PDO::PARAM_STR);
                self::$stmt->bindValue(2, self::$UUID, PDO::PARAM_STR);
                self::$stmt->bindValue(3, self::$timestamp, PDO::PARAM_STR);
                self::$stmt->bindValue(4, self::$msg, PDO::PARAM_STR);
                self::$stmt->execute();

                self::$Message = "Success";
                self::$response[] = array('Message' => self::$Message);
                                                                           
            }catch (Exception $ex)
            {
                self::$Message = $ex;
                self::$response[] = array('Message' => self::$Message);
            }
        }

        echo json_encode(self::$response);
    }
}

$messages = new Message();