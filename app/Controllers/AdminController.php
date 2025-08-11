<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SubjectModel;
use App\Models\ContentModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $subjectModel;
    protected $contentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->subjectModel = new SubjectModel();
        $this->contentModel = new ContentModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        // Check if user is admin (simplified for demo)
        $data = [
            'title' => 'Admin Dashboard - RTU LMS',
            'totalStudents' => $this->userModel->where('role', 'student')->countAllResults(),
            'totalSubjects' => $this->subjectModel->countAllResults(),
            'totalContent' => $this->contentModel->countAllResults(),
            'pendingApprovals' => $this->contentModel->where('is_approved', 0)->countAllResults()
        ];

        return view('admin/dashboard', $data);
    }

    public function login()
    {
        return view('admin/login');
    }

    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash']) && $user['role'] === 'admin') {
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_role' => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/admin/login')->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}
