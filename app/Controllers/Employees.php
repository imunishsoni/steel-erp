<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use App\Models\DepartmentModel;

class Employees extends BaseController
{
    protected $employeeModel;
    protected $deptModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
        $this->deptModel = new DepartmentModel();
    }

    public function index()
    {
        $employees = $this->employeeModel->getWithDepartment();
        $departments = $this->deptModel->findAll();

        return $this->render('employees/index', [
            'pageTitle'   => 'Plant Workforce & Employee Register',
            'employees'   => $employees,
            'departments' => $departments,
        ]);
    }

    public function create()
    {
        $departments = $this->deptModel->where('is_active', 1)->findAll();

        // Auto Employee Code: EMP-001
        $latest = $this->employeeModel->orderBy('id', 'DESC')->first();
        $nextSeq = 1;
        if ($latest && preg_match('/(\d+)$/', $latest['emp_code'], $matches)) {
            $nextSeq = intval($matches[1]) + 1;
        }
        $autoCode = 'EMP-' . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return $this->render('employees/form', [
            'pageTitle'   => 'Add New Plant Employee / Engineer',
            'employee'    => null,
            'departments' => $departments,
            'autoCode'    => $autoCode,
        ]);
    }

    public function new()
    {
        return $this->create();
    }

    public function store()
    {
        $rules = [
            'emp_code'        => 'required|is_unique[employees.emp_code]',
            'full_name'       => 'required|min_length[3]',
            'designation'     => 'required',
            'department_id'   => 'required|is_not_unique[departments.id]',
            'date_of_joining' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'emp_code'          => $this->request->getPost('emp_code'),
            'full_name'         => $this->request->getPost('full_name'),
            'designation'       => $this->request->getPost('designation'),
            'department_id'     => $this->request->getPost('department_id'),
            'email'             => $this->request->getPost('email'),
            'phone'             => $this->request->getPost('phone'),
            'date_of_joining'   => $this->request->getPost('date_of_joining'),
            'aadhar_no'         => $this->request->getPost('aadhar_no'),
            'pan'               => strtoupper($this->request->getPost('pan') ?? ''),
            'bank_account_no'   => $this->request->getPost('bank_account_no'),
            'bank_ifsc'         => strtoupper($this->request->getPost('bank_ifsc') ?? ''),
            'basic_salary'      => floatval($this->request->getPost('basic_salary') ?? 0),
            'address'           => $this->request->getPost('address'),
            'emergency_contact' => substr($this->request->getPost('emergency_contact') ?? '', 0, 15),
            'is_active'         => 1,
        ];

        $this->employeeModel->insert($data);
        log_activity('HR', 'Added new employee ' . $data['full_name'] . ' (' . $data['emp_code'] . ')');

        return redirect()->to('/employees')->with('success', 'Employee ' . $data['full_name'] . ' added successfully.');
    }

    public function edit($id)
    {
        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            return redirect()->to('/employees')->with('error', 'Employee record not found.');
        }

        $departments = $this->deptModel->where('is_active', 1)->findAll();

        return $this->render('employees/form', [
            'pageTitle'   => 'Edit Employee - ' . $employee['full_name'],
            'employee'    => $employee,
            'departments' => $departments,
            'autoCode'    => $employee['emp_code'],
        ]);
    }

    public function update($id)
    {
        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            return redirect()->to('/employees')->with('error', 'Employee not found.');
        }

        $rules = [
            'full_name'     => 'required|min_length[3]',
            'designation'   => 'required',
            'department_id' => 'required|is_not_unique[departments.id]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name'         => $this->request->getPost('full_name'),
            'designation'       => $this->request->getPost('designation'),
            'department_id'     => $this->request->getPost('department_id'),
            'email'             => $this->request->getPost('email'),
            'phone'             => $this->request->getPost('phone'),
            'aadhar_no'         => $this->request->getPost('aadhar_no'),
            'pan'               => strtoupper($this->request->getPost('pan') ?? ''),
            'bank_account_no'   => $this->request->getPost('bank_account_no'),
            'bank_ifsc'         => strtoupper($this->request->getPost('bank_ifsc') ?? ''),
            'basic_salary'      => floatval($this->request->getPost('basic_salary') ?? 0),
            'address'           => $this->request->getPost('address'),
            'emergency_contact' => substr($this->request->getPost('emergency_contact') ?? '', 0, 15),
            'is_active'         => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $this->employeeModel->update($id, $data);
        log_activity('HR', 'Updated employee details ' . $data['full_name']);

        return redirect()->to('/employees')->with('success', 'Employee updated successfully.');
    }
}
