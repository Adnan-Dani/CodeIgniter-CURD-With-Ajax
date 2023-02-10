<?php

namespace App\Controllers;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, token, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST");

use App\Models\Users_Model;
use CodeIgniter\Controller;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{
    public function __construct($config = 'rest')
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, GET, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
    }
    public function Index()
    {
        print_r(23);
    }
    public function getUsers()
    {
        $user_model = new Users_Model();
        return $this->response->setStatusCode(200)->setJSON($user_model->findAll());
    }

    function registerUser()
    {
        $user = new Users_Model();
        $name = $this->request->getVar('userName');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user_info  = $user->where('email', $email)->first();
        if ($user_info) {
            return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => "Enter Unique email address."]);
        }
        $data = [
            'name' => $name,
            'email'  => $email,
            'password'  => $this->hash_password($password),
        ];
        $user->insert($data);
        return $this->response->setStatusCode(200)->setJSON(['status' => true, 'message' => "Successfully Registered."]);
    }
    function authUser()
    {
        $user = new Users_Model();
        $email = $this->request->getVar('userName');
        $pass = $this->request->getVar('password');

        $user_info  = $user->where('email', $email)->first();
        if ($user_info) {
            $verify = password_verify($pass, $user_info['password']);
            if ($verify) {
                $key = getenv('JWT_SECRET');
                $iat = time(); // current timestamp value
                $exp = $iat + 3600;

                $payload = array(
                    "iss" => "adnan",
                    "aud" => "user",
                    "sub" => "login",
                    "iat" => $iat, //Time the JWT issued at
                    "exp" => $exp, // Expiration time of token
                    "email" => $user_info['email'],
                );

                $token = JWT::encode($payload, $key, 'HS256');

                return $this->response->setStatusCode(200)->setJSON(['status' => true, 'message' => 'Successfully login', 'token' => $token]);
            } else {
                return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => 'Incorrrect email and password']);
            }
        } else {
            return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => 'Incorrrect email and password']);
        }
    }
    function deleteUser()
    { 
            $user = new Users_Model();
            $id = $this->request->getVar('id');
            if ($id == null || $id == 'undefined') {
                return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => 'User not found.']);
  
            }
            $user_info = $user->where('user_id', $id)->delete();
            if ($user_info) {
                return $this->response->setStatusCode(200)->setJSON(['status' => true, 'message' => 'Successfully Deleted.']);
            } else {
                return $this->response->setStatusCode(403)->setJSON(['status' => false, 'message' => 'User not found.']);
            } 
    }
    function updateUser()
    {
        $user = new Users_Model();
        $id = $this->request->getVar('id');
        $name = $this->request->getVar('userName');
        $email = $this->request->getVar('email');
        $data = [
            'user' => $id,
            'name' => $name,
            'email' => $email

        ];
        return $this->response->setStatusCode(200)->setJSON($data);


        $user->set('name', $name);
        $user->set('email', $email);
        $user->where('user_id', $id);
        $res = $user->update();
        if ($res) {
            return $this->response->setStatusCode(200)->setJSON(['status' => true, 'message' => 'Successfully Updated']);
        } else {
            return $this->response->setStatusCode(200)->setJSON(['status' => false, 'message' => 'Failed to Updated']);
        }
    }

    private function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
