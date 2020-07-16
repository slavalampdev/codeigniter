<?php

namespace App\Controllers;

use Config\Services;
use Config\Validation;
use App\Models\User;

class UserController extends BaseController
{

    public function index()
    {
        return view('welcome_message');
    }

    public function login()
    {
        // Redirect if already logged in
        if (!empty($_SESSION['user_id'])) {
            return redirect()->to('/');
        }
        // Working with form data if POST request
        if ($this->request->getPost()) {
            $data = $this->request->getPost();
            $validation = Services::validation();
            $validation->setRules(
                [
                    'password' => 'required|min_length[6]',
                    'email'    => 'required',
                ]
            );
            // Checking reCAPTCHA
            if (empty($data['g-recaptcha-response'])) {
                $validation->setError('g-recaptcha-response', 'Please activate recaptcha');
                return $this->renderPage('login', $validation->listErrors());
            }
            // Data validation
            if ($validation->withRequest($this->request)->run()) {
                unset($data['g-recaptcha-response']);
                $result = User::checkUser($data);
                // Logging in if user with such data exists
                if ($result) {
                    $_SESSION['user_id'] = $result;
                    return redirect()->to('/');
                } else {
                    $validation->setError('email', 'Invalid parameters');
                    return $this->renderPage('login', $validation->listErrors());
                }
            } else {
                // Data validation fails, return with validation errors
                return $this->renderPage('login', $validation->listErrors());
            }
        } else {
            // Render page if GET request
            return $this->renderPage('login');
        }
    }

    public function registration()
    {
        // Redirect if already logged in
        if (!empty($_SESSION['user_id'])) {
            return redirect()->to('/');
        }
        // Working with form data if POST request
        if ($this->request->getPost()) {
            $data = $this->request->getPost();
            $validation = Services::validation();
            $validation->setRules(
                [
                    'name'     => 'required',
                    'password' => 'required|min_length[6]',
                    'email'    => 'required|is_unique[users.email,id,{id}]',
                ]
            );
            // Checking reCAPTCHA
            if (empty($data['g-recaptcha-response'])) {
                $validation->setError('g-recaptcha-response', 'Please activate recaptcha');
                return $this->renderPage('registration', $validation->listErrors());
            }
            // Validating data
            if ($validation->withRequest($this->request)->run()) {
                // Checking email with debounce.io
                $curl = curl_init();

                curl_setopt_array(
                    $curl,
                    array(
                        CURLOPT_URL => "https://api.debounce.io/v1/?api=" . getenv(
                                'DEBOUNCER_KEY'
                            ) . "&email=" . $data['email'],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                    )
                );

                $response = json_decode(curl_exec($curl));
                $err = curl_error($curl);

                curl_close($curl);
                // If request fails return error
                if ($err) {
                    $validation->setError('email', 'Checking email error');
                    return $this->renderPage('registration', $validation->listErrors());
                } elseif ($response->debounce->code != '5') {
                    // If email is valid and acceptable
                    $validation->setError('email', 'Your email is invalid or not acceptable');
                    return $this->renderPage('registration', $validation->listErrors());
                }

                unset($data['g-recaptcha-response']);
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                $result = User::registerUser($data);
                if ($result) {
                    // If successful inserting, logging in
                    $_SESSION['user_id'] = $result;
                    return redirect()->to('/');
                } else {
                    // If inserting in database fails, return error
                    $validation->setError('email', 'Database insert error');
                    return $this->renderPage('registration', $validation->listErrors());
                }
            } else {
                // Render page with validation errors
                return $this->renderPage('registration', $validation->listErrors());
            }
        } else {
            // Render page if GET request
            return $this->renderPage('registration');
        }
    }

    public function logout()
    {
        // Deleting user's data from session
        session_unset();
        return redirect()->to('/registration');
    }
    //--------------------------------------------------------------------

}