<?php

require_once 'utils/Response.php';

class CourierContoller {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getCouriers($date, $city) {
        
        // получение дней доставки туда/cюда в регион $city
        $sql = "SELECT delivery_time FROM regions WHERE id = :city";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':city', $city);
            $stmt->execute();
            $resultDaysDelivery = $stmt->fetchAll(PDO::FETCH_ASSOC);    
        } catch (PDOException $e) {
            error_log("Ошибка при выполнении запроса: " . $e->getMessage());
        }

        // дата end delivery
        $countDaysDelivery = $resultDaysDelivery[0]['delivery_time']; 
        $endDelivery = date('Y-m-d',strtotime($date) + (24*3600*$countDaysDelivery));

        // определение свободных курьеров с N по N времени
        $sql = "SELECT c.id, c.full_name
        FROM couriers c
        WHERE c.id NOT IN (
            SELECT t.courier_id
            FROM trips t
            WHERE t.trip_date <= :sdate AND t.trip_date_end >= :endDelivery
            AND t.trip_status IN ('planned', 'in_progress')
        );";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':sdate', $date);
            $stmt->bindValue(':endDelivery', $endDelivery);
            $stmt->execute();
            $resultCur = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        } catch (PDOException $e) {
            error_log("Ошибка при выполнении запроса: " . $e->getMessage());
        }

        if ($resultCur) {
            Response::send(200, ['message' => ['date' => ['S' => $date, 'E' => $endDelivery], 'city' => $city, 'couriers' => $resultCur]]);
        } else {
            Response::send(404, ['message' => 'Курьеры не найдены']);
        }
    }
}