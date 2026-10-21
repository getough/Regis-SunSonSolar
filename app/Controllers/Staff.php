<?php

namespace App\Controllers;

use App\Models\AuditModel;
use App\Models\UserModel;
use Throwable;

/** Employee account management. Routes are protected by the 'auth:admin,hr' filter. */
class Staff extends BaseController
{
    private function me(string $key)
    {
        return (session('user') ?? [])[$key] ?? null;
    }

    /** Roles each actor may create. HR cannot create admins or other HR accounts. */
    private function creatableRoles(): array
    {
        return $this->me('role') === 'admin'
            ? ['admin', 'hr', 'dispatcher', 'technician', 'staff']
            : ['dispatcher', 'technician', 'staff'];
    }

    public function index()
    {
        $isAdmin = $this->me('role') === 'admin';

        return view('staff/index', [
            'title'     => 'User management',
            'cardClass' => 'wide',
            'users'   => model(UserModel::class)->listWithRole($isAdmin),
            'isAdmin' => $isAdmin,
            'selfId'  => (int) $this->me('user_id'),
        ]);
    }

    public function create()
    {
        return view('staff/create', [
            'title'       => 'Create employee account',
            'roles'       => $this->creatableRoles(),
            'departments' => db_connect()->table('departments')->orderBy('department_name')->get()->getResultArray(),
        ]);
    }

    public function store()
    {
        $roles = $this->creatableRoles();
        $rules = UserModel::profileRules() + [
            'role'          => ['label' => 'Role', 'rules' => 'required|in_list[' . implode(',', $roles) . ']'],
            'department_id' => ['label' => 'Department', 'rules' => 'required|is_natural_no_zero|is_not_unique[departments.department_id]'],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $birthdate = (string) $this->request->getPost('birthdate');
        if (! UserModel::isAdult($birthdate)) {
            return redirect()->back()->withInput()->with('errors', ['birthdate' => 'Employees must be at least 18 years old.']);
        }

        $middle   = trim((string) $this->request->getPost('middle_name'));
        $gender   = (string) $this->request->getPost('gender');
        $role     = (string) $this->request->getPost('role');
        $username = trim((string) $this->request->getPost('username'));
        $temp     = temp_password();

        try {
            $newId = model(UserModel::class)->createAccount([
                'first_name'           => trim((string) $this->request->getPost('first_name')),
                'middle_name'          => $middle === '' ? null : $middle,
                'last_name'            => trim((string) $this->request->getPost('last_name')),
                'birthdate'            => $birthdate,
                'gender'               => $gender === '' ? null : $gender,
                'email'                => trim((string) $this->request->getPost('email')),
                'phone_number'         => (string) $this->request->getPost('phone_number'),
                'address'              => trim((string) $this->request->getPost('address')),
                'username'             => $username,
                'password_hash'        => password_hash($temp, PASSWORD_DEFAULT),
                'is_active'            => 1,
                'must_change_password' => 1,
            ], $role, (int) $this->request->getPost('department_id'));
        } catch (Throwable $e) {
            log_message('error', 'Employee creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('errors', ['form' => 'Could not create the account. Please try again.']);
        }

        model(AuditModel::class)->record((int) $this->me('user_id'), 'employee_created', $newId, "role={$role}");

        // Shown once on the next page, never stored.
        return redirect()->to('/staff')->with('new_account', ['username' => $username, 'password' => $temp]);
    }

    public function toggle(int $id)
    {
        $users  = model(UserModel::class);
        $actor  = (int) $this->me('user_id');
        $target = $users->findWithRole($id);

        if (! $target) {
            return redirect()->to('/staff')->with('error', 'User not found.');
        }
        if ($id === $actor) {
            return redirect()->to('/staff')->with('error', 'You cannot deactivate your own account.');
        }
        // HR may only manage the roles they are allowed to create.
        if ($this->me('role') !== 'admin' && ! in_array($target['role_name'], $this->creatableRoles(), true)) {
            return redirect()->to('/staff')->with('error', 'You do not have permission to change that account.');
        }

        $newState = (int) $target['is_active'] === 1 ? 0 : 1;
        $users->update($id, ['is_active' => $newState]);
        model(AuditModel::class)->record($actor, $newState ? 'user_reactivated' : 'user_deactivated', $id);

        return redirect()->to('/staff')->with('success', $newState ? 'Account reactivated.' : 'Account deactivated.');
    }
}
