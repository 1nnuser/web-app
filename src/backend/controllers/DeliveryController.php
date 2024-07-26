<?php

require_once 'utils/Response.php';

class DeliveryController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function addDelivery($data) {

        $tripStart = $data['tripStart'];
        $tripEnd = $data['tripEnd'];
        $courierId = $data['courier'];
        $regionId = $data['region'];

        $sql = "INSERT INTO trips (courier_id, region_id, trip_date, trip_date_end, trip_status) 
                VALUES (:courier_id, :region_id, :trip_date, :trip_date_end, :trip_status)";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':courier_id', $courierId);
            $stmt->bindValue(':region_id', $regionId);
            $stmt->bindValue(':trip_date', $tripStart);
            $stmt->bindValue(':trip_date_end', $tripEnd);
            $stmt->bindValue(':trip_status', 'planned');
            $stmt->execute();        
        } catch (PDOException $e) {
            error_log("Ошибка при выполнении запроса: " . $e->getMessage());
        }

        Response::send(200, ['message' => 'okay']);
    }

    public function getDelivery($date, $amount, $offset) {

        $sql = "SELECT COUNT(id) AS Total FROM trips;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultCountData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (isset($date)) {
            $sql = "SELECT 
                        c.full_name AS \"fullname\",
                        c.id AS \"courier_id\",
                        t.trip_date AS \"start\",
                        t.trip_date_end AS \"end\",
                        t.trip_status AS \"status\",
                        r.name AS \"region_name\"
                    FROM 
                        couriers c
                    JOIN 
                        trips t ON c.id = t.courier_id
                    JOIN
                        regions r ON t.region_id = r.id
                    WHERE t.trip_date = :date
                    LIMIT :amount OFFSET :offset;";

            try {
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':amount', $amount, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->bindValue(':date', $date);
                $stmt->execute();
                $resultAllNotSort = $stmt->fetchAll(PDO::FETCH_ASSOC);       
            } catch (PDOException $e) {
                error_log("Ошибка при выполнении запроса: " . $e->getMessage());
            }
        }                
       
        if ($resultAllNotSort) {
            Response::send(200, ['message' => ['total' => $resultCountData, 'deliveries' => $resultAllNotSort]]);
        } 
        else {
            Response::send(404, ['message' => 'Доставки не найдены']);
        }
    
    }

}