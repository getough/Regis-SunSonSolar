<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use RuntimeException;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'user_id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'role_id', 'first_name', 'middle_name', 'last_name', 'birthdate', 'gender',
        'email', 'phone_number', 'address', 'username', 'password_hash',
        'is_active', 'must_change_password', 'failed_login_attempts',
        'locked_until', 'last_login', 'privacy_consent_at',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Validation rules shared by customer sign-up and HR/Admin account creation. */
    public static function profileRules(): array
    {
        return [
            'first_name'   => ['label' => 'First name', 'rules' => 'required|min_length[2]|max_length[50]'],
            'middle_name'  => ['label' => 'Middle name', 'rules' => 'permit_empty|max_length[50]'],
            'last_name'    => ['label' => 'Last name', 'rules' => 'required|min_length[2]|max_length[50]'],
            'gender'       => ['label' => 'Gender', 'rules' => 'permit_empty|in_list[Male,Female,Other,Prefer not to say]'],
            'birthdate'    => ['label' => 'Birthdate', 'rules' => 'required|valid_date[Y-m-d]'],
            'email'        => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|max_length[100]|is_unique[users.email]',
                'errors' => ['is_unique' => 'That email address is already registered.'],
            ],
            'phone_number' => [
                'label'  => 'Phone number',
                'rules'  => ['required', 'regex_match[/^09[0-9]{9}$/]'],
                'errors' => ['regex_match' => 'Please enter a valid 11-digit phone number starting with 09.'],
            ],
            'address'      => ['label' => 'Address', 'rules' => 'required|min_length[5]|max_length[500]'],
            'username'     => [
                'label'  => 'Username',
                'rules'  => ['required', 'min_length[3]', 'max_length[50]', 'regex_match[/^[A-Za-z0-9._-]+$/]', 'is_unique[users.username]'],
                'errors' => [
                    'regex_match' => 'Username may only contain letters, numbers, dot, underscore and hyphen.',
                    'is_unique'   => 'That username is already taken.',
                ],
            ],
        ];
    }

    /** Password rule used when a person chooses their own password. */
    public static function passwordRule(string $label = 'Password'): array
    {
        return [
            'label'  => $label,
            'rules'  => ['required', 'min_length[8]', 'max_length[72]', 'regex_match[/^(?=.*[A-Za-z])(?=.*[0-9]).+$/]'],
            'errors' => ['regex_match' => $label . ' must include at least one letter and one number.'],
        ];
    }

    /** Real date, not in the future, age 18 to 120. */
    public static function isAdult(string $birthdate): bool
    {
        $date = DateTime::createFromFormat('!Y-m-d', $birthdate);
        if (! $date) {
            return false;
        }
        $today = new DateTime('today');
        if ($date > $today) {
            return false;
        }
        $age = $date->diff($today)->y;

        return $age >= 18 && $age <= 120;
    }

    public function roleId(string $roleName): int
    {
        $row = $this->db->table('roles')->where('role_name', $roleName)->get()->getRowArray();
        if (! $row) {
            throw new RuntimeException("Role '{$roleName}' is missing. Run: php spark migrate");
        }

        return (int) $row['role_id'];
    }

    /**
     * Creates a user (and employee profile when a department is given) in one transaction.
     * $data must already contain password_hash; role_id is set here from $roleName.
     */
    public function createAccount(array $data, string $roleName, ?int $departmentId = null): int
    {
        $data['role_id'] = $this->roleId($roleName);

        $this->db->transStart();
        if ($this->insert($data) === false) {
            $this->db->transRollback();
            throw new RuntimeException('Could not create the account.');
        }
        $userId = (int) $this->getInsertID();
        if ($departmentId !== null) {
            $this->db->table('employee_profiles')->insert([
                'user_id'       => $userId,
                'department_id' => $departmentId,
            ]);
        }
        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            throw new RuntimeException('Could not create the account.');
        }

        return $userId;
    }

    /** User + role + department, looked up by username (for login). */
    public function findForLogin(string $username): ?array
    {
        return $this->baseWithRole()->where('users.username', $username)->first();
    }

    public function findWithRole(int $userId): ?array
    {
        return $this->baseWithRole()->where('users.user_id', $userId)->first();
    }

    /** All users with role and department, for the staff management list. */
    public function listWithRole(bool $includeCustomers): array
    {
        $builder = $this->baseWithRole();
        if (! $includeCustomers) {
            $builder->where('roles.role_name !=', 'customer');
        }

        return $builder->orderBy('roles.role_name')->orderBy('users.last_name')->findAll();
    }

    private function baseWithRole(): static
    {
        return $this->select('users.*, roles.role_name, departments.department_name')
            ->join('roles', 'roles.role_id = users.role_id')
            ->join('employee_profiles', 'employee_profiles.user_id = users.user_id', 'left')
            ->join('departments', 'departments.department_id = employee_profiles.department_id', 'left');
    }
}
