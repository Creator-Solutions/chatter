<?php

require '../conn.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

class Room
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
    private static string $username;  

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
       

        if (self::$decodedData['Type'] == "Room")
        {
            self::Get_Rooms(self::$dbHelper::$conn);

        } else if (self::$decodedData['Type'] == "Join")
        {
            self::Join_Room(self::$dbHelper::$conn);

        }else if (self::$decodedData['Type'] == "Users")
        {
            self::Get_Users(self::$dbHelper::$conn, self::$decodedData['Room_ID']);
        }      
    }

    public static function Get_Rooms($conn)
    {
        if ($conn)
        {
            try
            {
                self::$SQL = "SELECT * FROM rooms";
                self::$stmt = $conn->query(self::$SQL);                
                                
                while (self::$row = self::$stmt->fetch())
                {                   
                    self::$response[] = self::$row;                 
                }

            }catch (Exceptions $ex)
            {
                self::$Message = "Could Not Fetch Room";
                self::$response[] = array('Message' => self::$Message);
            }        
        }

        echo json_encode(self::$response);
    }

    public static function Join_Room($conn)
    {
        if ($conn)
        {
            self::$room_ID = isset(self::$decodedData) ? self::$decodedData['ID'] : "";
            self::$UUID = isset(self::$decodedData) ? self::$decodedData['UUID'] : "";
            self::$username = isset(self::$decodedData) ? self::$decodedData['Name'] : "";

            try
            {

                if (self::Check_User_Joined($conn, self::$UUID))
                {
                    self::$Message = "Joined";
                    self::$response[] = array('Message' => self::$Message);
                }else 
                {
                    self::$SQL = "INSERT INTO joined_user (Room_ID, UUID, UserName) VALUES (?,?,?)";
                    self::$stmt = $conn->prepare(self::$SQL);
                    self::$stmt->bindValue(1, self::$room_ID, PDO::PARAM_STR);
                    self::$stmt->bindValue(2, self::$UUID, PDO::PARAM_STR);
                    self::$stmt->bindValue(3, self::$username, PDO::PARAM_STR);
                    self::$stmt->execute();
    
                    self::$Message = "Joined";
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

    public static function Check_User_Joined($conn, $uuid)
    {
        if ($conn)
        {
            try
            {
                self::$SQL = "SELECT UUID FROM joined_user WHERE UUID = ?";
                self::$stmt = $conn->prepare(self::$SQL);
                self::$stmt->bindValue(1, self::$UUID, PDO::PARAM_STR);
                self::$stmt->execute();

                self::$row = self::$stmt->fetch(PDO::FETCH_ASSOC);

                if (isset($row['UUID']))
                {
                   return true;
                }
            }catch (Exception $ex)
            {
                return false;
            }
        }
    }

    public static function Get_Users($conn, $id)
    {
        if ($conn)
        {                        
            try 
            {
    
                self::$SQL = "SELECT UserName FROM joined_user WHERE Room_ID=?";
                self::$stmt = $conn->prepare(self::$SQL);
                self::$stmt->bindValue(1, $id, PDO::PARAM_STR);
                self::$stmt->execute();
                
                while (self::$row = self::$stmt->fetch())
                {
                    self::$response[] = self::$row;
                }
                
            
            }catch (Exception $ex)
            {
                self::$Message = 'Could not get users';
                self::$response[] = array('Message' => self::$Message);
            }
        }
       
        echo json_encode(self::$response);
    }
}

$room = new Room();