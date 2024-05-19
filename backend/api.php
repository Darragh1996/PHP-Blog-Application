<?php
require_once './classes/User.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD']; // get the request type
$endpoint = $_SERVER['PATH_INFO']; // get the url
$segments = explode('/', trim($endpoint, '/')); // split the url itno segments on '/'

$path = $segments[0];

switch ($path) {
    case 'users':
        $userObj = new User();
        switch ($method) {
            case 'GET':
                if (count($segments) == 1) {
                } else if (count($segments) == 2) {
                    $userID = $segments[1];
                    geUserByID($userObj, $userID);
                }
                break;
            case 'POST':
                createUser($userObj);
                break;
            default:
                # code...
                break;
        }
        break;
    case 'blogs':
        break;
    default:
        # code...
        break;
}

function geUserByID($user, $userID)
{
    $user->id = $userID;

    $userData = $user->get();

    if ($userData) {
        http_response_code(200);
        echo json_encode($userData); // Return the user data as JSON
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to find user with id = $userID."));
    }
}

function createUser($user)
{
    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->username) &&
        !empty($data->email) &&
        !empty($data->password)

    ) {
        $user->username = $data->username;
        $user->email = $data->email;
        $user->password = $data->password;

        if ($user->add()) {
            http_response_code(201);
            echo json_encode(array("message" => "User was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create user."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
    }
}
