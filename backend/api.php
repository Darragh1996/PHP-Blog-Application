<?php
// secure and httpOnly flags
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
// ini_set('session.cookie_samesite', 'None');

session_start();
session_regenerate_id(true); // prevent session fixations attacks

require_once './classes/User.php';
require_once './classes/Blog.php';
require_once './classes/Comment.php';

header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


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
        $blogObj = new Blog();
        switch ($method) {
            case 'GET':
                if (count($segments) == 1) {
                    getAllBlogs($blogObj);
                } else if (count($segments) == 2) {
                    $blogID = (int)$segments[1];
                    $blogObj->id = $blogID;
                    getBlogByID($blogObj);
                }
                break;
            case 'POST':
                createBlog($blogObj);
                break;
            case 'PATCH':
                if (count($segments) == 2 && (is_numeric($segments[1]))) {
                    $blogID = $segments[1];
                    $blogObj->id = (int)$blogID;
                    updateBlog($blogObj);
                }
                break;
            case 'DELETE':
                if (count($segments) == 2 && (is_numeric($segments[1]))) {
                    $blogID = $segments[1];
                    $blogObj->id = (int)$blogID;
                    deleteBlog($blogObj);
                }
                break;
            default:
                # code...
                break;
        }
        break;
    case 'comments': // handle comments endpoint
        $commentObj = new Comment();
        switch ($method) {
            case 'GET':
                $commentObj->blog_id = $segments[1];
                getAllCommentsByBlogID($commentObj);
                break;
            case 'POST':
                $commentObj->blog_id = $segments[1];
                createComment($commentObj);
                break;
            case 'DELETE':
                $commentObj->id = $segments[1];
                deleteComment($commentObj);
                break;
            default:
                # code...
                break;
        }
        break;
    default:
        # code...
        break;
}

// handle users

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
            // $cookieParams = session_get_cookie_params();
            // setcookie(
            //     session_name(),
            //     session_id(),
            //     [
            //         'expires' => $cookieParams['lifetime'] ? time() + $cookieParams['lifetime'] : 0,
            //         'path' => $cookieParams['path'],
            //         'domain' => $cookieParams['domain'],
            //         'secure' => $cookieParams['secure'],
            //         'httponly' => $cookieParams['httponly'],
            //         'samesite' => 'None'
            //     ]
            // );
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

// handle blogs

function getAllBlogs($blog)
{
    // echo $_SERVER["HTTP_COOKIE"];
    // echo "\n";
    // echo $_SESSION["user_id"];
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => $_SERVER["HTTP_COOKIE"]));
        return;
    } else {
        $blog->user_id = $_SESSION['user_id'];
    }

    $blogsData = $blog->getAll();

    if ($blogsData) {
        http_response_code(200);
        echo json_encode($blogsData); // Return the blogs data as JSON
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to get blogs."));
    }
}

function getBlogByID($blog)
{
    $blogData = $blog->getByID();

    if ($blogData) {
        http_response_code(200);
        echo json_encode($blogData); // Return the blog data as JSON
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to get blogs."));
    }
}

function createBlog($blog)
{
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => "You must be logged in to create a blog."));
        return;
    } else {
        $blog->user_id = $_SESSION['user_id'];;
    }

    $data = json_decode(file_get_contents("php://input"));

    if (
        // !empty($data->user_id) &&
        !empty($data->title) &&
        !empty($data->text)

    ) {
        // Check if the user_id in the request matches the logged-in user
        // if ($data->user_id != $_SESSION['user_id']) {
        //     http_response_code(403); // Forbidden
        //     echo json_encode(array("message" => "You can only create blogs for your own account."));
        //     return;
        // }

        $blog->title = $data->title;
        $blog->text = $data->text;

        if ($blog->add()) {
            http_response_code(201);
            echo json_encode(array("message" => "Blog was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create blog."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create blog. Data is incomplete."));
    }
}

function updateBlog($blog)
{
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => "You must be logged in to update a blog."));
        return;
    } else {
        $blog->user_id = $_SESSION['user_id'];
        $blog->edit_date = date('Y-m-d H:i:s'); // get the current time for the edit date
    }

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->title) &&
        !empty($data->text)

    ) {
        // Check if the user_id in the request matches the logged-in user
        // if ($data->user_id != $_SESSION['user_id']) {
        //     http_response_code(403); // Forbidden
        //     echo json_encode(array("message" => "You can only update blogs for your own account."));
        //     return;
        // }

        $blog->title = $data->title;
        $blog->text = $data->text;

        if ($blog->update()) {
            http_response_code(201);
            echo json_encode(array("message" => "Blog was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update blog."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to update blog. Data is incomplete."));
    }
}

function deleteBlog($blog)
{
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => "You must be logged in to delete a blog."));
        return;
    } else {
        $blog->user_id = $_SESSION['user_id'];;
    }

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($blog->user_id)) {
        // Check if the user_id in the request matches the logged-in user
        // if ($data->user_id != $_SESSION['user_id']) {
        //     http_response_code(403); // Forbidden
        //     echo json_encode(array("message" => "You can only delete blogs for your own account."));
        //     return;
        // }

        // $blog->user_id = $data->user_id;

        if ($blog->delete()) {
            http_response_code(201);
            echo json_encode(array("message" => "Blog was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete blog."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete blog. Data is incomplete."));
    }
}

// handle comments

function getAllCommentsByBlogID($comment)
{
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => $_SERVER["HTTP_COOKIE"]));
        return;
    } else {
        $comment->user_id = $_SESSION['user_id'];
    }

    $commentsData = $comment->getAllByBlogID();

    if ($commentsData) {
        http_response_code(200);
        echo json_encode($commentsData); // Return the comments data as JSON
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to get comments."));
    }
}

function createComment($comment)
{
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => "You must be logged in to create a comment."));
        return;
    } else {
        $comment->user_id = $_SESSION['user_id'];;
    }

    $data = json_decode(file_get_contents("php://input"));

    if (
        // !empty($data->user_id) &&
        !empty($data->text)

    ) {

        $comment->text = $data->text;

        if ($comment->add()) {
            http_response_code(201);
            echo json_encode(array("message" => "Comment was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create comment."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to create comment. Data is incomplete."));
    }
}

function deleteComment($comment)
{
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(array("message" => "You must be logged in to delete a comment."));
        return;
    } else {
        $comment->user_id = $_SESSION['user_id'];
    }

    if (!empty($comment->user_id)) {

        if ($comment->delete()) {
            http_response_code(201);
            echo json_encode(array("message" => "Comment was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete comment."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete comment. Data is incomplete."));
    }
}
