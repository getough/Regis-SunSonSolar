<?php

namespace App\Controllers;

use App\Models\UserModel;
use Throwable;

/** Public sign-up. CUSTOMERS ONLY: the role is fixed here, never taken from the form. */
class Register extends BaseController
{
    public function index()
    {
        if (session('user')) {
            return redirect()->to('/dashboard');
        }

        return view('register', ['title' => 'Customer Registration']);
    }

    public function store()
    {
        $rules = UserModel::profileRules() + [
            'password'         => UserModel::passwordRule('Password'),
            'confirm_password' => ['label' => 'Confirm password', 'rules' => 'required|matches[password]'],
            'privacy_consent'  => [
                'label'  => 'Privacy notice',
                'rules'  => 'required|in_list[1]',
                'errors' => [
                    'required' => 'Please accept the privacy notice to register.',
                    'in_list'  => 'Please accept the privacy notice to register.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return $this->backWithErrors($this->validator->getErrors());
        }

        $birthdate = (string) $this->request->getPost('birthdate');
        if (! UserModel::isAdult($birthdate)) {
            return $this->backWithErrors(['birthdate' => 'You must be at least 18 years old to register.']);
        }

        $middle = trim((string) $this->request->getPost('middle_name'));
        $gender = (string) $this->request->getPost('gender');

        try {
            model(UserModel::class)->createAccount([
                'first_name'           => trim((string) $this->request->getPost('first_name')),
                'middle_name'          => $middle === '' ? null : $middle,
                'last_name'            => trim((string) $this->request->getPost('last_name')),
                'birthdate'            => $birthdate,
                'gender'               => $gender === '' ? null : $gender,
                'email'                => trim((string) $this->request->getPost('email')),
                'phone_number'         => (string) $this->request->getPost('phone_number'),
                'address'              => trim((string) $this->request->getPost('address')),
                'username'             => trim((string) $this->request->getPost('username')),
                'password_hash'        => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
                'is_active'            => 1,
                'must_change_password' => 0,
                'privacy_consent_at'   => date('Y-m-d H:i:s'),
            ], 'customer');
        } catch (Throwable $e) {
            log_message('error', 'Customer registration failed: ' . $e->getMessage());

            return $this->backWithErrors(['form' => 'That email address or username may already be registered, or something went wrong. Please try again.']);
        }

        return redirect()->to('/login')->with('success', 'Registration successful! You can now log in.');
    }

    private function backWithErrors(array $errors)
    {
        // Do not leave passwords in the flashed old-input.
        unset($_POST['password'], $_POST['confirm_password']);

        return redirect()->back()->withInput()->with('errors', $errors);
    }
}
