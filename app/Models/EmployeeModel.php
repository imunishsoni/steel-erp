<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emp_code', 'full_name', 'designation', 'department_id', 
        'email', 'phone', 'date_of_joining', 'date_of_leaving', 
        'aadhar_no', 'pan', 'bank_account_no', 'bank_ifsc', 
        'basic_salary', 'address', 'emergency_contact', 'photo', 'is_active'
    ];

    protected $useTimestamps = true;

    public function getWithDepartment()
    {
        return $this->select('employees.*, departments.name as department_name')
                    ->join('departments', 'departments.id = employees.department_id', 'left')
                    ->orderBy('employees.full_name', 'ASC')
                    ->findAll();
    }
}
