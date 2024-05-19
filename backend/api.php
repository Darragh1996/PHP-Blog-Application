<?php
// secure and httpOnly flags
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

session_start();
session_regenerate_id(true); // prevent session fixations attacks

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
    case 'users': // handle users endpoints
        $userObj = new User();
        switch ($method) {
            case 'GET':
                if (count($segments) == 1) {
                    geAllUsers($userObj);
                } else if (count($segments) == 2) {
                    $userID = $segments[1];
                    geUserByID($userObj, $userID);
                }
                break;
            case 'POST':
                if (count($segments) == 1) {
                    createUser($userObj);
                } else if (count($segments) == 2 && $segments[1] == 'login') {
                    loginUser($userObj);
                } else if (count($segments) == 2 && $segments[1] == 'logout') {
                    logoutUser();
                }
                break;
            default:
                # code...
                break;
        }
        break;
    case 'blogs': // handle blogs endpoints
        break;
    default:
        # code...
        break;
}

function geAllUsers($user)
{
    $userData = $user->getAll();

    if ($userData) {
        http_response_code(200);
        echo json_encode($userData); // Return the user data as JSON
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to get users."));
    }
}

function geUserByID($user, $userID)
{
    $user->id = $userID;

    $userData = $user->getByID();

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

function loginUser($user)
{
    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->username) &&
        !empty($data->password)
    ) {
        $username = $data->username;
        $password = $data->password;

        $loginResult = $user->login($username, $password);

        if ($loginResult['status'] === 'success') {
            $_SESSION['user_id'] = $loginResult['user']['id'];
            http_response_code(200); // OK
            echo json_encode($loginResult['user']); // Return user data
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(array("message" => $loginResult['message']));
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Unable to login. Data is incomplete."));
    }
}

function logoutUser()
{
    // remove session data
    $_SESSION = array();

    // Destroy the session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 50000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    http_response_code(200);
    echo json_encode(array("message" => "Successfully logged out."));
}
