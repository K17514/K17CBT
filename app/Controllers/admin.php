<?php

namespace App\Controllers;
use App\Models\M_belajar;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TCPDF;

class Admin extends BaseController
{
    public function __construct()
    {
        $this->model = new M_belajar(); // Initialize the model once
        $this->webDetail = $this->model->getWebDetails();
    }

    public function index()
    {
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('index');
        echo view ('footer');
    }

public function settings()
    {
        if (session()->get('level')==49){
        $id_user = session()->get('id_user');
        $parent['child'] = $this->model->tampil1('web_detail','id_wdetail',true);
        $this->model->log_activity($id_user, "User accessed settings");
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/settings', $parent);
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }
public function update_setting()
{
   if (session()->get('level')==49){

    $webId = 1; // Assuming you have only one row in `web_detail`

    // Fetch existing data
    $currentData = $this->model->getWhereOpt('web_detail', ['id_wdetail' => $webId], true);

    // Get new title input
    $newTitle = $this->request->getPost('fullName');
    if (!$newTitle) {
        return redirect()->back()->with('error', 'Website title is required.');
    }

    // Prepare update data
    $data = ['title' => $newTitle];

    // Handle file upload
    $file = $this->request->getFile('profile_image');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $uploadPath = 'images/';
        $newFileName = 'logo_' . time() . '.' . $file->getExtension();

        if ($file->move($uploadPath, $newFileName)) {
            // Delete old logo if it exists
            $oldFileName = $currentData->logo ?? null;
            if ($oldFileName && file_exists($uploadPath . $oldFileName)) {
                unlink($uploadPath . $oldFileName);
            }
            $data['logo'] = $newFileName;
        } else {
            return redirect()->back()->with('error', 'Failed to upload the logo.');
        }
    }

    // Update the settings
    $this->model->edit('web_detail', $data, ['id_wdetail' => $webId]);

    // Log activity
    $this->model->log_activity(session()->get('id_user'), "Updated website settings");

    return redirect()->to('/admin/settings')->with('successprofil', 'Settings updated successfully.');
    }else{
            return redirect()->to('/home/login');
        }
}


public function tampiluser()
{
    if (session()->get('level') == 1 || session()->get('level') == 49) {
        $where = [
            'deleted_at' => null
        ];
        // Pass 'false' for multiple results, and 'id_user' as the orderBy field
        $parent['child'] = $this->model->getWhereOpt('user', $where, false, 'id_user');
        
        echo view('header',['webDetail' => $this->webDetail]);
        echo view('menu');
        echo view('admin/tampiluser', $parent);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}



    public function hapus_user($id)
{
    if (session()->get('level') == 1 || session()->get('level') == 49) {
        date_default_timezone_set('Asia/Jakarta'); // Set zona waktu
        $now = date('Y-m-d H:i:s'); // Ambil waktu sekarang

        // Soft delete user dengan mengupdate kolom deleted_at
        $this->model->edit('user', ['deleted_at' => $now], ['id_user' => $id]);

        return redirect()->to('admin/tampiluser')->with('success', 'User has been soft deleted.');
    } else {
        return redirect()->to('/home/login');
    }
}

    public function killuser($id)
    {
         if (session()->get('level') == 1 || session()->get('level') == 49){
        $where= array('id_user' =>$id);
        $parent['child']=$this->model->hapus('user',$where);
        return redirect()->to('admin/tampiluser');
        }else{
            return redirect()->to('/home/login');
        }
    }



    public function edit_user($id)
    {
         if (session()->get('level') == 1 || session()->get('level') == 49){
        $where= array('id_user' =>$id);
        $parent['child']=$this->model->getWhere('user',$where);
        echo view('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('admin/euser', $parent);
        echo view('footer');      
        }else{
            return redirect()->to('/home/login');
        }    
    } 

public function simpan_user()
{
    if (session()->get('level') == 1 || session()->get('level') == 49) {
        $where = array('id_user' => $this->request->getPost('id'));
        
        // Fetch current user data (including hashed password)
        $existingUser = $this->model->getWhere('user', $where);

        if (!$existingUser) {
            return redirect()->to('admin/tampiluser')->with('error', 'User not found');
        }

        $data = array(
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'level' => $this->request->getPost('level'),
        );

        // Check if a new password is entered
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $data['password'] = $hashedPassword;
        }

        // Update user data
        $this->model->edit('user', $data, $where);

        return redirect()->to('admin/tampiluser')->with('success', 'User updated successfully');
    } else {  
        return redirect()->to('/home/login');
    }
}


public function laporan_nilai()
{
    $a = $this->request->getPost('tanggal_awal');
    $b = $this->request->getPost('tanggal_akhir');
    $id = $this->request->getPost('id_exam');

    $data = $this->model->filterpesanan(
        'exam', 
        'exam_detail', 
        'user', 
        'exam_detail.id_exam = exam.id_exam', 
        'exam_detail.id_user = user.id_user',
        'exam_detail.date_of_exam', $a, $b, $id
    );

    // Ambil nama ujian berdasarkan id_exam
    $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id], false);
    $nama_exam = $exam ? $exam[0]->nama_exam : 'Tidak Diketahui';

    $pdf = new TCPDF();
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Laporan Nilai');
    $pdf->SetSubject('Laporan PDF');
    $pdf->SetKeywords('TCPDF, PDF, laporan, nilai');

    // Tambahkan logo
    $pdf->Image(FCPATH . 'public/images/chibitee-logo.png', 10, 10, 50, 50);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 8);

    // Kirim $a, $b, dan $nama_exam ke view
    $html = view('admin/laporan_nilai', [
        'data' => $data,
        'nama_exam' => $nama_exam,
        'tanggal_awal' => $a,
        'tanggal_akhir' => $b
    ]);

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('laporan_nilai_' . date('YmdHis') . '.pdf', 'D'); // 'D' for download
}


public function deleted_data()
{
    if (session()->get('level') == 1 || session()->get('level') == 49) {
        $deletedData = [
            'question' => $this->model->getDelete('question', "deleted_at IS NOT NULL"),
            'option' => $this->model->getDelete('option', "deleted_at IS NOT NULL"),
            'exam' => $this->model->getDelete('exam', "deleted_at IS NOT NULL"),
            'course' => $this->model->getDelete('course', "deleted_at IS NOT NULL"),
            'exam_detail' => $this->model->getDelete('exam_detail', "deleted_at IS NOT NULL"),
            'user' => $this->model->getDelete('user', "deleted_at IS NOT NULL"),
        ];

        return view('header',['webDetail' => $this->webDetail])
         . view('menu')
            . view('admin/deleted_data', ['deletedData' => $deletedData])
            . view('footer');
    }
    return redirect()->to('home/login');
}

public function restore()
{
    if (session()->get('level') == 49) {
        $table = $this->request->getPost('table');
        $id = $this->request->getPost('id');

        $primaryKeys = [
            'question' => 'id_question',
            'option' => 'id_option',
            'exam' => 'id_exam',
            'course' => 'id_course',
            'exam_detail' => 'id_detail',
            'user' => 'id_user',
        ];
        if (!empty($table) && !empty($id) && isset($primaryKeys[$table])) {
            $primaryKey = $primaryKeys[$table]; 

            $this->model->edit($table, ['deleted_at' => NULL], [$primaryKey => $id]);

            $id_user = session()->get('id_user');
            $this->model->log_activity($id_user, "You have restored an/a $table");

            return redirect()->to('admin/deleted_data')->with('success', 'Data restored successfully.');
        }
    }
    return redirect()->to('home/login');
}

public function restore_all()
{
    if (session()->get('level') == 49) {
        $table = $this->request->getPost('table');

        if (!empty($table)) {
            $this->model->edit($table, ['deleted_at' => NULL], ['deleted_at IS NOT NULL' => NULL]);


            $id_user = session()->get('id_user');
            $this->model->log_activity($id_user, "You have restored all $table");
            return redirect()->to('admin/deleted_data')->with('success', 'All data restored successfully.');
        }
    }
    return redirect()->to('home/login');
}



public function log_activity()
    {
        if (session()->get('level') == 1 || session()->get('level') == 49){
        $where = session()->get('id_user');
        $parent['child']=$this->model->join('log_activity','user','log_activity.id_user=user.id_user','id_log');
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view ('admin/log_activity',$parent);
        }else{
            return redirect()->to('/home/login');
        }
    }


    public function kill_all()
    {
        if (session()->get('level') != 49) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $id_user = session()->get('id_user');

        $db = \Config\Database::connect();
        $table = $this->request->getPost('table');

        if (!in_array($table, ['exam', 'course', 'user'])) {
            return redirect()->to(base_url('admin/deleted_data'))
            ->with('error', 'Invalid table selected.');
        }

    // Disable foreign key checks before deletion
        $db->query("SET FOREIGN_KEY_CHECKS=0");

        if ($table === 'exam') {
        // Delete related options for deleted exams
            $db->query("
                DELETE FROM `option` 
                WHERE id_question IN (
                    SELECT id_question FROM question WHERE id_exam IN (
                        SELECT id_exam FROM exam WHERE deleted_at IS NOT NULL
                        )
                    )
                ");

        // Delete related questions for deleted exams
            $db->query("
                DELETE FROM question 
                WHERE id_exam IN (
                    SELECT id_exam FROM exam WHERE deleted_at IS NOT NULL
                    )
                ");

        // Delete exams where deleted_at is not null
            $db->query("DELETE FROM exam WHERE deleted_at IS NOT NULL");
            $this->model->log_activity($id_user, "You have massacred the exams");
        } elseif ($table === 'course') {
        // Delete options for exams in deleted courses
            $db->query("
                DELETE FROM `option` 
                WHERE id_question IN (
                    SELECT id_question FROM question WHERE id_exam IN (
                        SELECT id_exam FROM exam WHERE id_course IN (
                            SELECT id_course FROM course WHERE deleted_at IS NOT NULL
                            )
                        )
                    )
                ");

        // Delete questions for exams in deleted courses
            $db->query("
                DELETE FROM question 
                WHERE id_exam IN (
                    SELECT id_exam FROM exam WHERE id_course IN (
                        SELECT id_course FROM course WHERE deleted_at IS NOT NULL
                        )
                    )
                ");

        // Delete exams under deleted courses
            $db->query("
                DELETE FROM exam 
                WHERE id_course IN (
                    SELECT id_course FROM course WHERE deleted_at IS NOT NULL
                    )
                ");

        // Delete courses where deleted_at is not null
            $db->query("DELETE FROM course WHERE deleted_at IS NOT NULL");
            $this->model->log_activity($id_user, "You have massacred the courses");
        } elseif ($table === 'user') {
    // Delete users where deleted_at is not null
            $db->query("DELETE FROM user WHERE deleted_at IS NOT NULL");

            $this->model->log_activity($id_user, "You have massacred the users");
        }

    // Re-enable foreign key checks after deletion
        $db->query("SET FOREIGN_KEY_CHECKS=1");

        return redirect()->to(base_url('admin/deleted_data'))
        ->with('success', ucfirst($table) . ' records permanently deleted.');
    }




    public function killexam($id_exam)
    {
        if (session()->get('level') != 49) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }
        $db = \Config\Database::connect();

        // Disable foreign key checks
        $db->query("SET FOREIGN_KEY_CHECKS=0");

        // Delete options related to the exam
        $db->query("
            DELETE FROM `option`
            WHERE id_question IN (
                SELECT id_question FROM question WHERE id_exam = ?
            )
        ", [$id_exam]);

        // Delete questions related to the exam
        $db->query("DELETE FROM question WHERE id_exam = ?", [$id_exam]);

        // Delete the exam itself
        $this->model->hapus('exam', ['id_exam' => $id_exam]);

        // Re-enable foreign key checks
        $db->query("SET FOREIGN_KEY_CHECKS=1");

        $id_user = session()->get('id_user');
        $this->model->log_activity($id_user, "You have killed an exam");
        return redirect()->to(base_url('admin/deleted_data'))
            ->with('success', 'Exam and all related data deleted successfully.');
    }

    // Delete Course and all related data
    public function killCourse($id_course)
    {
        if (session()->get('level') != 49) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }
        $db = \Config\Database::connect();

        // Disable foreign key checks
        $db->query("SET FOREIGN_KEY_CHECKS=0");

        // Delete options related to the course's exams
        $db->query("
            DELETE FROM `option`
            WHERE id_question IN (
                SELECT id_question FROM question 
                WHERE id_exam IN (
                    SELECT id_exam FROM exam WHERE id_course = ?
                )
            )
        ", [$id_course]);

        // Delete questions related to the course's exams
        $db->query("
            DELETE FROM question
            WHERE id_exam IN (
                SELECT id_exam FROM exam WHERE id_course = ?
            )
        ", [$id_course]);

        // Delete all exams related to the course
        $this->model->hapus('exam', ['id_course' => $id_course]);

        // Delete the course itself
        $this->model->hapus('course', ['id_course' => $id_course]);

        // Re-enable foreign key checks
        $db->query("SET FOREIGN_KEY_CHECKS=1");


        $id_user = session()->get('id_user');
        $this->model->log_activity($id_user, "You have killed a course");
        return redirect()->to(base_url('admin/deleted_data'))
        ->with('success', 'Course and all related data deleted successfully.');
    }

    public function detailcourse($id)
{
    if (session()->get('level') == 49) {
        $where = array('id_course' => $id);
        $course = $this->model->getWhere('course', $where);

        if ($course) {
            // Load user names for created_by, updated_by, deleted_by
            $course->created_by_name = $this->model->getUsernameById($course->created_by);
            $course->updated_by_name = $this->model->getUsernameById($course->updated_by);
            $course->deleted_by_name = $this->model->getUsernameById($course->deleted_by);
        }

        $data['child'] = $course;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view('admin/course_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}

 public function detailexam($id)
{
    if (session()->get('level') == 49) {
        $where = array('id_exam' => $id);
        $exam = $this->model->getWhere('exam', $where);

        if ($exam) {
            // Load user names for created_by, updated_by, deleted_by
            $exam->created_by_name = $this->model->getUsernameById($exam->created_by);
            $exam->updated_by_name = $this->model->getUsernameById($exam->updated_by);
            $exam->deleted_by_name = $this->model->getUsernameById($exam->deleted_by);
        }

        $data['child'] = $exam;
        
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view('admin/exam_info', $data);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}

}


