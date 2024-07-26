<?php

require_once 'config.php';
require_once 'utils/Response.php';
require_once 'controllers/DeliveryController.php';
require_once 'controllers/CourierContoller.php';


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $controller = new DeliveryController();
        $data = json_decode(file_get_contents('php://input'), true); // получение json

        if ($_SERVER['REQUEST_URI'] == '/deliveries') { 
            $controller->addDelivery($data); 
        }  
        break;

    case 'GET':
        $urlParts = parse_url($_SERVER['REQUEST_URI']);
        $path = $urlParts['path'];

        if ($path === '/deliveries') {

            $controller = new DeliveryController();

            $date = isset($_GET['date']) ? $_GET['date'] : null;
            $amount = isset($_GET['amount']) ? $_GET['amount'] : null;
            $offset = isset($_GET['offset']) ? $_GET['offset'] : null;

            $controller->getDelivery($date, $amount, $offset);
        } 

        else if ($path === '/couriers') {

            $controller = new CourierContoller();

            $date = isset($_GET['date']) ? $_GET['date'] : null;
            $city = isset($_GET['city']) ? $_GET['city'] : null;

            $controller->getCouriers($date, $city);
        }
        break;
    default:
        Response::send(405, ['message' => 'Такого метода нет']);
        break;
}