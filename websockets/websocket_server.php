<?php
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require_once 'vendor/autoload.php';
//require_once "PHP/config.php";

class MyWebSocketServer implements MessageComponentInterface {

    protected $clients;
    protected $subscribers;
    protected $admins;
    protected $pdo;
    protected $connectionMessages;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->subscribers = new \stdClass();
        $this->admins = new \stdClass();
        $this->connectionMessages = [];
        // initiate db connection
        $dsn = 'mysql:host=MacBook-Pro-de-BABATUNDE.local;port=3307;dbname=emergement_csm;charset=utf8mb4';
        $this->pdo = new PDO($dsn, 'mac_user', 'mac_user_pass');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function onOpen(ConnectionInterface $conn) {
        // add the connection to the list of connected clients
        $this->clients->attach($conn);
        $subjectId = '';
        // send the subject IDs to the new client
        foreach ($this->admins as $subjectId => $admins) {
            $conn->send('subject ' . $subjectId);
        }

        // send previous connection messages to the new client
        foreach ($this->connectionMessages as $message) {
            $conn->send($message);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        
        // check if the message is a "connection" message from an admin
        if (is_string($msg) && is_array(json_decode($msg, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                $json = json_decode($msg, true);
                if (isset($json['message'])) {
                    $parts = explode(' ', $json['message']);
                    if (strpos($parts[0], 'connection') === 0) {
                        $subjectId = isset($parts[1]) ? $parts[1] : '';
                        echo "subjectID $subjectId\n";
                        // query db
                        $stmt = $this->pdo->prepare("SELECT su.nom_sujet, s.nom, s.prenom
                                FROM sujet su
                                LEFT JOIN students s ON s.id = :stu_id
                                WHERE su.id = :subject_id");
                        $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);
                        $stmt->bindParam(':stu_id', $json['id'], PDO::PARAM_INT);
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($row) {
                            print_r($row);
                            $hyd = $json['id'];
                            echo "ADMIN-ID  $hyd\n";
                            $subjectName = $row['nom_sujet'];
                            $adminName = $row['nom'] . " " . $row['prenom'];
                            echo "ADMIN-NAME  $adminName\n";
                            echo "admin connected to $subjectName\n";
                            // ws
                            $this->admins->$subjectId[] = $json;
                            $subjectMessage = "subject " . $subjectName;
                            $subjectconnectionMessage = [
                                'pseudo' => $json['pseudo'],
                                'id' => $json['id'],
                                'sId' => $subjectId,
                                'adminName' => $adminName,
                                'message' => $subjectMessage
                            ];

                        // store the connection message
                        $this->connectionMessages[] = json_encode($subjectconnectionMessage);

                        foreach ($this->clients as $client) {
                            $client->send(json_encode($subjectconnectionMessage));
                        }
                    } else {
                        echo "Invalid subject ID or student ID";
                    }
                } elseif (strpos($parts[0], 'subscribe') === 0) {
                    // clientID
                    $userID = isset($parts[1]) ? $parts[1] : '';
                    echo "user " . $json['pseudo'] . " subscribed to " . $json['subName'] . "\n";
                    $this->subscribers->$userID[] = $from;
                    $subjectMessage = "subscribers " . $userID;
                    $subjectSubscriptionMessage = [
                        'pseudo' => $json['pseudo'],
                        // admin Id
                        'id' => $json['id'],
                        'message' => $subjectMessage
                    ];

                    // insert into db
                    $stmt = $this->pdo->prepare('INSERT INTO wlogs (pseudo, student_id, subject_id, subject_name, currentDate, enterTime) VALUES (:pseudo, :student_id, :subject_id, :subject_name, CURRENT_DATE(), CURRENT_TIME())');
                    $stmt->bindParam(':pseudo', $json['pseudo'], PDO::PARAM_STR);
                    $stmt->bindParam(':student_id', $userID, PDO::PARAM_INT);
                    $stmt->bindParam(':subject_id', $json['sId'], PDO::PARAM_INT);
                    $stmt->bindParam(':subject_name', $json['subName'], PDO::PARAM_STR);
                    $stmt->execute();

                    // send to ws
                    foreach ($this->clients as $client) {
                        $client->send(json_encode($subjectSubscriptionMessage));
                    }
                } elseif (strpos($parts[0], 'heartbeat') === 0) {
                    $heartMessage = "heartbeat " . $json['message'];
                    // echo "heartbeat message: $heartMessage\n";

                    $outPut = [
                        'id' => $json['id'],
                        'message' => $heartMessage
                    ];
                    foreach ($this->clients as $client) {
                        $client->send(json_encode($outPut));
                    }
                } elseif (strpos($parts[0], 'profheartbeat') === 0) {
                    $heartMessage = "profheartbeat " . $json['message'];
                    // echo "heartbeatprof message: $heartMessage\n";

                    $outPut = [
                        'id' => $json['id'],
                        'message' => $heartMessage
                    ];

                    foreach ($this->clients as $client) {
                        $client->send(json_encode($outPut));
                    }
                } else {
                    // Broadcast the message to all subscribers
                    foreach ($this->clients as $client) {
                        if (isset($this->subscribers[$client->resourceId])) {
                            $client->send($msg);
                        }
                    }
                }
            }
    } else {
        echo "heartbeat\n";
    }
}

public function onClose(ConnectionInterface $conn) {
    // remove the connection from the list of connected clients and subscribers
    $this->clients->detach($conn);

    foreach ($this->subscribers as $userId => $connections) {
        if (isset($connections[$conn->resourceId])) {
            unset($this->subscribers->$userId[$conn->resourceId]);
            break;
        }
    }
}

public function onError(ConnectionInterface $conn, \Exception $e) {
    // log the error
    echo "Error: {$e->getMessage()}\n";

        $stackTrace = $e->getTraceAsString();
    error_log($errorMessage . $stackTrace);

    // close the connection
    $conn->close();
}

protected function sendSubscriberList($subjectId) {
    if (!isset($this->admins->$subjectId)) {
        return;
    }

    $subscribers = [];
    foreach ($this->subscribers as $userId => $connections) {
        if (isset($connections[$subjectId])) {
            $subscribers[] = $userId;
        }
    }

    foreach ($this->admins->$subjectId as $admin) {
        $admin->send('subscribers ' . implode(',', $subscribers));
    }
}
}

// create a new instance of your WebSocket class
$webSocketServer = new MyWebSocketServer();

// wrap your WebSocket class with the Ratchet WsServer
$wsServer = new WsServer($webSocketServer);

// wrap the WsServer with the Ratchet HttpServer
$httpServer = new HttpServer($wsServer);

// create a new instance of the Ratchet IoServer listening on port 8383
$server = IoServer::factory($httpServer, 8282);

// start the WebSocket server
$server->run();
?>