<?php

    $test_db = new PDO('sqlite:/home/remi/ProjetPlanificateurEvenements/models/database.sqlite');
    $test_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$queryText = "SELECT E.numEvent id, datetime(S.date_sond || ' ' || S.heureD || ':00') 'start_date', datetime(S.date_sond || ' ' || S.heureF || ':00') end_date, E.titre 'text' 
                    FROM  (Evenements E JOIN Sondages S ON E.numSond=S.numSond) 
                    JOIN Participants P ON E.numEvent=P.numEvent 
                    WHERE numUser=?";
                    
        $queryParams = [];
        array_push($queryParams, '1');
        // handle dynamic loading
        if (isset($requestParams["from"]) && isset($requestParams["to"])) {
            $queryText .= " WHERE `end_date`>=? AND `start_date` < ?;";
            array_push($queryParams, $requestParams["from"], $requestParams["to"]);
        }
        $statement = $test_db->prepare($queryText);
        $statement->execute($queryParams);
        $events = $statement->fetchAll();
        
                foreach ($events as $event) {
                    $data[] = array(
                'id'   => $event["id"],
                'start_date'   => $event["start_date"],
                'end_date'   => $event["end_date"],
                'text'   => $event["text"]
                );
                }


var_dump($data);

// function read($test_db, $requestParams)
// {
//     $queryParams = [];
//     $queryText = "SELECT * FROM `events`";
//     $query = $test_db->prepare($queryText);
//     $query->execute($queryParams);
//     $events = $query->fetchAll();
//     return $events;
// }


// switch ($_SERVER["REQUEST_METHOD"]) {
//     case "GET":
//         $result = read($test_db, $_GET);
//         break;
//     case "POST":
//         // we'll implement this later
//     break;
//     default:
//         throw new Exception("Unexpected Method");
//     break;
// }
// header("Content-Type: application/json");
// echo json_encode($result);
