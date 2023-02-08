<?php

namespace App\Controllers;

use App\Models\Users_Model;

class Home extends BaseController
{
    public function index()
    {
        $data['title'] = 'Login Account';
        return view('index.php', $data);
    }
    function registerView()
    {
        $data['title'] = 'Register Account';
        return view('register.php', $data);
    }
    function usersView()
    {
        $data['title'] = 'Users';
        $users = new Users_Model();
        $data['users'] = $users->findAll();
        return view('users.php', $data);
    }
    function registerUser()
    {
        if ($this->request->isAJAX()) {

            $validation = [
                'name' => [
                    'label'  => 'Name',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'Enter a valid {field}.'
                    ]
                ],
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'trim|required|valid_email|is_unique[users.email, email]',
                    'errors' => [
                        'required' => 'Enter a valid {field} address.'
                    ]
                ],
                'password' => [
                    'label'  => 'Password',
                    'rules'  => 'trim|required|min_Length[8]|max_length[255]',
                    'errors' => [
                        'required' => '{field} is required.',
                        'max_length' => '{field} must have atleast 255 characters in length.'
                    ]
                ],
                'cpassword' => [
                    'label'  => 'Confirm Password',
                    'rules'  => 'trim|required|min_Length[8]|max_length[255]|matches[password]',
                    'errors' => [
                        'required' => '{field} is required.',
                        'max_length' => '{field} must have atleast 255 characters in length.'
                    ]
                ]
            ];
            if ($this->validate($validation) == FALSE) {
                if ($this->validator->hasError('name')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('name'),
                    ];
                    echo json_encode($data);
                }
                if ($this->validator->hasError('email')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('email'),
                    ];
                    echo json_encode($data);
                }
                if ($this->validator->hasError('password')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('password'),
                    ];
                    echo json_encode($data);
                }
                if ($this->validator->hasError('cpassword')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('cpassword'),
                    ];
                    echo json_encode($data);
                }
            } else {

                $user = new Users_Model();
                $name = $this->request->getPost('name');
                $email = $this->request->getPost('email');
                $pass = $this->request->getPost('password');

                $user_info  = $user->where('email', $email)->first();
                if (!$user_info) {

                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $this->hash_password($pass)
                    ];
                    $res = $user->insert($data);
                    if ($res) {
                        $data = [
                            'status' => true,
                            'message' => 'Successfully Register.'
                        ];
                        echo json_encode($data);
                    } else {
                        $data = [
                            'status' => false,
                            'message' => 'Failed to Register.'
                        ];
                        echo json_encode($data);
                    }
                } else {
                    $data = [
                        'status' => false,
                        'message' => 'Enter unique email address.'
                    ];
                    echo json_encode($data);
                }
            }
        } else {
            return false;
        }
    }
    function getUser()
    {
        $user = new Users_Model();
        $id = $this->request->getPost('id');
        if ($id == null || $id == 'undefined') {
            $data = [
                'status' => false,
                'message' => 'User not found'
            ];
            echo json_encode($data);
            return;
        }
        $user_info = $user->find($id);
        echo json_encode($user_info);
    }
    function updateUser()
    {
        if ($this->request->isAJAX()) {
            $validation = [
                'name' => [
                    'label'  => 'Name',
                    'rules'  => 'trim|required',
                    'errors' => [
                        'required' => 'Enter a valid {field}.'
                    ]
                ],
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'trim|required|valid_email|is_unique[users.email, email]',
                    'errors' => [
                        'required' => 'Enter a valid {field} address.'
                    ]
                ],
            ];
            if ($this->validate($validation) == FALSE) {
                if ($this->validator->hasError('name')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('name'),
                    ];
                    echo json_encode($data);
                }
                if ($this->validator->hasError('email')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('email'),
                    ];
                    echo json_encode($data);
                }
            } else {

                $user = new Users_Model();
                $id = $this->request->getPost('id');
                $name = $this->request->getPost('name');
                $email = $this->request->getPost('email');

                $user->set('name', $name);
                $user->set('email', $email);
                $user->where('user_id', $id);
                $res = $user->update();
                if ($res) {
                    $data = [
                        'status' => true,
                        'message' => 'Successfully Updated.'
                    ];
                    echo json_encode($data);
                } else {
                    $data = [
                        'status' => false,
                        'message' => 'Failed to Register.'
                    ];
                    echo json_encode($data);
                }
            }
        } else {
            return false;
        }
    }
    function deleteUser()
    {
        if ($this->request->isAJAX()) {
            $user = new Users_Model();
            $id = $this->request->getPost('id');
            if ($id == null || $id == 'undefined') {
                $data = [
                    'status' => false,
                    'message' => 'User not found'
                ];
                echo json_encode($data);
                return;
            }
            $user_info = $user->where('user_id', $id)->delete();
            if ($user_info) {
                $data = [
                    'status' => true,
                    'message' => 'Successfully Deleted.'
                ];
                echo json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'User not found.'
                ];
                echo json_encode($data);
            }
        } else {
            return false;
        }
    }
    function authUser()
    {
        if ($this->request->isAJAX()) {
            $validation = [
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'required|valid_email',
                    'errors' => [
                        'required' => 'Enter a valid {field} address.'
                    ]
                ],
                'password' => [
                    'label'  => 'Password',
                    'rules'  => 'required|max_length[255]',
                    'errors' => [
                        'required' => '{field} is required.',
                        'max_length' => '{field} must have atleast 255 characters in length.'
                    ]
                ]
            ];

            if ($this->validate($validation) == FALSE) {
                if ($this->validator->hasError('email')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('email'),
                    ];
                    echo json_encode($data);
                }

                if ($this->validator->hasError('password')) {
                    $data = [
                        'status' => false,
                        'message' => $this->validator->getError('password'),
                    ];
                    echo json_encode($data);
                }
            } else {

                $user = new Users_Model();
                $email = $this->request->getPost('email');
                $pass = $this->request->getPost('password');

                $user_info  = $user->where('email', $email)->first();
                if ($user_info) {
                    $verify = password_verify($pass, $user_info['password']);

                    if ($verify) {

                        $data = [
                            'status' => true,
                            'message' => 'Successfully login'
                        ];
                        echo json_encode($data);
                    } else {

                        $data = [
                            'status' => false,
                            'message' => 'Incorrrect email and password'
                        ];
                        echo json_encode($data);
                    }
                } else {
                    $data = [
                        'status' => false,
                        'message' => 'Incorrect email and password'
                    ];
                    echo json_encode($data);
                }
                exit();
            }
            exit();
        }
    }
    protected function setUserSession($user)
    {
        $data = [
            'id' => $user['user_id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'isLoggedIn' => true,

        ];
        session()->set($data);
        return true;
    }
    private function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
