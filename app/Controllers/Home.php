<?php

namespace App\Controllers;
use App\Models\M_belajar;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use TCPDF;


class Home extends BaseController
{
    public function __construct()
    {
        $this->model = new M_belajar(); // Initialize the model once
         $this->webDetail = $this->model->getWebDetails();
    }

    public function index()
    {
        $id_user = session()->get('id_user');
        $parent['course'] = $this->model->joinw(
            'course',
            'user',
            'course.id_user = user.id_user',
            ['course.id_user' => $id_user] // Filter by user
        );
        $this->model->log_activity($id_user, "User accessed home page.");
        $parent['child']=$this->model->joincount('exam','question','question.id_exam = exam.id_exam','exam.id_exam');
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('index',$parent);
        echo view ('footer');
    }


    public function login()
    {
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('login');
        echo view ('footer');
    }

    public function register()
    {
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('register');
        echo view ('footer');
    }


   public function aksi_login()
{
    $recaptcha_secret = "6LeZQekqAAAAAIk1nT3Xbz4KcKFyZ4Uk51w8m1b4"; // Replace with your actual secret key
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify with Google
    $verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents($verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
    $response_keys = json_decode($response, true);

    if (!$response_keys["success"]) {
        echo "reCAPTCHA verification failed. Please try again.";
        exit();
    }


    //login biasa


    $data = array(
            'username'=> $this->request->getPost('user'),
            'password'=> MD5($this->request->getPost('pass')),
        );
        $cek = $this->model->getWhere('user',$data);   
           
        if ($cek != null) {

    // if ($cek && password_verify($password, $cek->password)) {
    session()->set('id_user', $cek->id_user);
    session()->set('email', $cek->email);
    session()->set('username', $cek->username);
    session()->set('level', $cek->level);
    session()->set('foto', $cek->foto);

        $this->model->log_activity(session()->get('id_user'), "User logged in");
        return redirect()->to('/home/index');
    } else {
        return redirect()->to('/home/login')->with('error', 'Invalid login credentials');
    }
}

public function logout()
{
    $id = session()->get('id_user');
    $this->model->log_activity($id, "User logged out");
    session()->destroy();
    return redirect()->to('/home/login')->with('success', 'You have been logged out.');
}

public function aksi_register()
{

    $username = $this->request->getPost('username');
    $email = $this->request->getPost('email');
    $password = MD5($this->request->getPost('password'));

    // Check if email or username already exists
    $existingUser = $this->model->getWhere('user', ['email' => $email]);
    $existingUsername = $this->model->getWhere('user', ['username' => $username]);

    if ($existingUser) {
        return redirect()->to('/home/register')->with('error', 'Email already registered.');
    }
    if ($existingUsername) {
        return redirect()->to('/home/register')->with('error', 'Username already taken.');
    }

    $data = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'level' => 2 // Default level is 2
    ];

    $this->model->input('user', $data);

     if (session()->get('level') == 1 || session()->get('level') == 49){
        return redirect()->to('/admin/tampiluser');
    } else {
    return redirect()->to('/home/login')->with('success', 'Registration successful! Please login.');
}
}


public function forgorpass()
    {
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('forgorpass');
        echo view ('footer');
    }



public function forgot_password()
{
    $email = $this->request->getPost('email');

    // Check if the email exists in the database
    $user = $this->model->getWhere('user', ['email' => $email]);

    if (!$user || !is_object($user)) {
        return redirect()->to('/home/forgot_password')->with('error', 'No user found with this email.');
    }

    // Set the correct timezone before generating expiry
    date_default_timezone_set('Asia/Jakarta');
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", strtotime("+20 minutes"));

    // Save token to the database
    $this->model->edit('user', [
        'token' => $token_hash,
        'expiry' => $expiry
    ], ['email' => $email]);

    // Reset link
    $resetLink = base_url("/home/reset_password?token=$token");

    // Create email content
    $subject = "Password Reset Request";
    $message = "
    <html>
    <head>
        <title>Password Reset Request</title>
    </head>
    <body>
        <p>Hello,</p>
        <p>You requested to reset your password. Click the link below to proceed:</p>
        <p><a href='$resetLink' style='color: blue;'>Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Thank you.</p>
    </body>
    </html>
    ";

    // Send the email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';   // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ryukusune@gmail.com';  // Your email
        $mail->Password   = 'jlgp wctt gagd vaxg';    // App password (NOT your real email password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587; 

        $mail->setFrom('ryukusune@gmail.com', 'Chibi-Tee Exam Website');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return redirect()->to('/home/login')->with('success', 'A password reset link has been sent to your email.');
    } catch (Exception $e) {
        return redirect()->to('/home/forgot_password')->with('error', "Failed to send email. Error: {$mail->ErrorInfo}");
    }
}

public function reset_password()
{
    $token = $_GET['token'] ?? '';
    $token_hash = hash('sha256', $token); // Hash the token from the URL

    // Ensure correct timezone for token validation
    date_default_timezone_set('Asia/Jakarta');

    // Validate the token
    $reset = $this->model->getWhere('user', ['token' => $token_hash]);

    if (!$reset || !is_object($reset) || strtotime($reset->expiry) < time()) {
        $data['message'] = "Invalid or expired token.";
        return view('error_view', $data); // Render an error view
    }

    // Pass token to the view for the form
    $data['token'] = $token;
    echo view ('header',['webDetail' => $this->webDetail]);
    echo view('reset_password_view', $data); // Render the reset password view
    echo view ('footer');
}

public function update_password()
{
    $token = $_GET['token'] ?? '';
    $token_hash = hash('sha256', $token);
    // $password = 1111;
    $password = $this->request->getPost('pass');
    $confirmPassword = $this->request->getPost('confirm_password');

    if ($password !== $confirmPassword) {
        $data['message'] = "Passwords do not match.";
        $data['type'] = "error";
        return view('status_view', $data);
    }

    date_default_timezone_set('Asia/Jakarta');

    $reset = $this->model->getWhere('user', ['token' => $token_hash]);

    if (!$reset || !is_object($reset) || strtotime($reset->expiry) < time()) {
        $data['message'] = "Invalid or expired token.";
        $data['type'] = "error";
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('status_view', $data);
        echo view ('footer');
    }

    $this->model->edit('user', ['password' => null], ['email' => $reset->email]);

    // $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $hashedPassword = md5($password);

    $data = [
        'token' => null,
        'expiry' => null,
        'password' => $hashedPassword,
    ];

    $this->model->edit('user', $data,  ['email' => $reset->email]);

    $data['message'] = "Your password has been updated successfully.";
    $data['type'] = "success";
    echo view ('header',['webDetail' => $this->webDetail]);
    echo view('status_view', $data);
    echo view ('footer');
}












public function listing($id)
{
    if (session()->get('level') > 0) {
        session()->set('id_course', $id);
        
        $where = [
            'exam.id_course' => $id,
            'exam.deleted_at' => null // Hanya ambil ujian yang belum dihapus
        ];

        $parent['user'] = $this->model->joinw(
            'user', 
            'course', 
            'course.id_user = user.id_user', 
            ['course.id_course' => $id]
        );

        $parent['course'] = $this->model->getWhere('course', ['id_course' => $id]);

        $parent['child'] = $this->model->joinwcount2(
            'exam', 
            'course', 
            'question', 
            'course.id_course = exam.id_course',  
            'exam.id_exam = question.id_exam', 
            $where, 
            'exam.id_exam'
        );

        $this->model->log_activity(session()->get('id_user'), "User Accessed Listing Page");
        echo view('header',['webDetail' => $this->webDetail]);
        echo view('listing-page', $parent);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}
public function detail($id)
{
    if (session()->get('level') > 0) {
        $id_user = session()->get('id_user'); // Get logged-in user ID
        $where = array('exam.id_exam' => $id);
        
        // Fetch exam details and leaderboard
        $parent['child'] = $this->model->joinwcount('exam', 'question', 'question.id_exam = exam.id_exam', $where, 'exam.id_exam');
        $parent['leaderboard'] = $this->model->get_leaderboard($id);
        
        // Fetch only attempts from the logged-in user

        $parent['attempts'] =  $attempts = $this->model->getUserAttemptsByExam($id_user, $id);

        // Ensure $parent['child'] is not empty before logging
        $exam_name = $parent['child']->nama_exam;

        // Log activity
        $this->model->log_activity($id_user, "User accessed $exam_name");

        // Load views
        echo view('header', ['webDetail' => $this->webDetail]);
        echo view('menu');
        echo view('detail-page', $parent);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}



    
    public function about()
    {
        if (session()->get('level')>0){
        $this->model->log_activity($id_user, "User opened about page");
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('about');
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }

public function profile()
    {
        if (session()->get('level')>0){
        $this->model->log_activity($id_user, "User accessed profile");
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('profile');
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }


    public function create()
{
    if (session()->get('level') > 0) {
        $id_user = session()->get('id_user');
        $this->model->log_activity($id_user, "User's creating exam");
        echo view('create_exam',['webDetail' => $this->webDetail]);
    } else {
        return redirect()->to('/home/login');
    }
}

public function answer($id_exam)
{
    date_default_timezone_set('Asia/Jakarta');

    if (session()->get('level') > 0) {
        $id_user = session()->get('id_user');

        // Check for unfinished attempt
        $exam_detail = $this->model->getWhereOpt('exam_detail', [
            'id_exam' => $id_exam,
            'id_user' => $id_user,
            'date_of_submit' => null // Not submitted yet
        ], true);

        if (!$exam_detail) { 
            // No unfinished attempt, check if allowed to start a new one
            $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id_exam], true);
            $examAttempts = $this->model->getWhereOpt('exam_detail', [
                'id_exam' => $id_exam,
                'id_user' => $id_user
            ], false);
            $attemptsCount = count($examAttempts);

            if (!is_null($exam->allowed_attempt) && $attemptsCount >= $exam->allowed_attempt) {
                return redirect()->to('home/detail/' . $id_exam)->with('error', 'You have exceeded the allowed attempts.');
            }

            // Create a new attempt
            $newAttempt = [
                'id_user' => $id_user,
                'id_exam' => $id_exam,
                'date_of_exam' => date("Y-m-d H:i:s")
            ];
            $this->model->input('exam_detail', $newAttempt);

            // Fetch the latest attempt (newly created)
            $exam_detail = $this->model->getWhereOpt('exam_detail', [
                'id_exam' => $id_exam,
                'id_user' => $id_user
            ], true);
        }

        // Fetch exam details
        $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id_exam], true);

        // Fetch questions and options
         $questions = $this->model->getWhereOpt('question', ['id_exam' => $id_exam], false);

        // Jika q_shuffle = 1, acak pertanyaan
        if (!empty($exam->q_shuffle) && $exam->q_shuffle == 1) {
            shuffle($questions);
        }
        
         // Fetch options untuk setiap pertanyaan
        foreach ($questions as $q) {
            $q->options = $this->model->getWhereOpt('option', ['id_question' => $q->id_question], false);

            // Jika o_shuffle = 1, acak opsi jawaban
            if (!empty($exam->o_shuffle) && $exam->o_shuffle == 1) {
                shuffle($q->options);
            }
        }

        $this->model->log_activity($id_user, "User answers {$exam->nama_exam}");

        return view('answer', [
            'exam' => $exam,
            'questions' => $questions,
            'exam_detail' => $exam_detail,
            'webDetail' => $this->webDetail
        ]);
    } else {
        return redirect()->to('/home/login');
    }
}


   public function submit()
{
    date_default_timezone_set('Asia/Jakarta');
    $id_user = session()->get('id_user');
    $id_exam = $this->request->getPost('id_exam');

    // Fetch exam details
    $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id_exam], true);
    $questions = $this->model->getWhereOpt('question', ['id_exam' => $id_exam], false);
    $submittedAnswers = $this->request->getPost('answers');

    // Get the latest attempt
    $examAttempts = $this->model->getWhereOpt('exam_detail', [
        'id_exam' => $id_exam,
        'id_user' => $id_user
    ], false);
    $lastAttempt = end($examAttempts);
    
    if (!$lastAttempt) {
        return redirect()->to('home/detail/' . $id_exam)->with('error', 'No valid attempt found.');
    }

    $id_attempt = $lastAttempt->id_detail;

    // Initialize counters
    $correctAnswers = 0;
    $totalQuestions = count($questions);
    $essayExists = false;
    $answersData = [];

    foreach ($questions as $q) {
        $chosen_option = $submittedAnswers[$q->id_question] ?? null;

        if (is_null($q->right_option)) {
            // Essay Question: Mark as "pending review"
            $is_correct = null; 
            $essayExists = true;
        } else {
            // Multiple Choice: Auto-grade
            $is_correct = ($chosen_option == $q->right_option) ? 1 : 0;
            if ($is_correct) {
                $correctAnswers++;
            }
        }

        $answersData[] = [
            'id_exam' => $id_exam,
            'id_user' => $id_user,
            'id_detail' => $id_attempt,
            'id_question' => $q->id_question,
            'chosen_option' => $chosen_option,
            'is_correct' => $is_correct
        ];
    }

    if (!empty($answersData)) {
        $this->model->inputBatch('exam_attempts', $answersData);
    }

    // Calculate initial score (only for multiple-choice)
    $mcqCount = $totalQuestions - ($essayExists ? 1 : 0); 
   if ($essayExists) {
    $score = null; // Ensure exam_score remains NULL
    $examResult = 'pending';
} else {
    $score = ($mcqCount > 0) ? round(($correctAnswers / $mcqCount) * 100, 2) : 0;
    $examResult = ($score >= $exam->min_score) ? 'lulus' : 'gagal';
}


    // Calculate time taken
    $timeTakenSeconds = strtotime(date("Y-m-d H:i:s")) - strtotime($lastAttempt->date_of_exam);
    $timeTakenFormatted = gmdate("H:i:s", $timeTakenSeconds);

    // Update exam details
    $updateData = [
        'exam_score' => $score,
        'exam_result' => $examResult,
        'time_taken' => $timeTakenFormatted,
        'date_of_submit' => date("Y-m-d H:i:s")
    ];
    $this->model->edit('exam_detail', $updateData, ['id_detail' => $id_attempt]);

    // Log activity
    $exam_name = $exam->nama_exam;
    $this->model->log_activity($id_user, "User finished $exam_name");

    return redirect()->to('home/result/' . $id_exam)->with('success', 'Exam submitted successfully.');
}





public function result($id_exam)
{
    $id_user = session()->get('id_user');

    // Get exam details
    $examDetail = $this->model->getWhereOpt('exam_detail', [
        'id_exam' => $id_exam,
        'id_user' => $id_user
    ], true, 'date_of_submit DESC');

    if (!$examDetail) {
        return redirect()->to('home')->with('error', 'Exam result not found.');
    }

//     // Check if the exam contains essay questions
//    $hasEssay = !empty($this->model->getWhere('question', [
//     'id_exam' => $id_exam,
//     'right_option' => null
// ]));

// // Pastikan pending hanya berlaku jika memang ada essay
// $isPending = ($hasEssay && $examDetail->exam_result === 'pending');

//     $data['hasEssay'] = !empty($hasEssay);

//     // Get leaderboarSd (only if not pending)
//     $leaderboard = !$isPending ? $this->model->get_leaderboard($id_exam) : [];
    $leaderboard = $this->model->get_leaderboard($id_exam);

    return view('exam_result', [
        'result' => $examDetail,
        'leaderboard' => $leaderboard,
        // 'isPending' => $isPending
    ]);
}



    public function store() {

    $examData = [
        'nama_exam' => $this->request->getPost('nama_exam'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'exam_open' => date("Y-m-d H:i:s", strtotime($this->request->getPost('exam_open'))),
        'exam_closed' => date("Y-m-d H:i:s", strtotime($this->request->getPost('exam_closed'))),
        'min_score' => $this->request->getPost('min_score'),
        'allowed_attempt' => $this->request->getPost('allowed_attempt'),
        'time_limit' => $this->request->getPost('time_limit'),
        'times_play' => 0,
        'q_shuffle' => $this->request->getPost('q_shuffle'),
        'o_shuffle' => $this->request->getPost('o_shuffle'),
        'id_course' => session()->get('id_course'),
        'created_by' => session()->get('id_user'),
    ];

    $this->model->input('exam', $examData);

    // Fetch the last inserted exam ID
    $exam = $this->model->getWhereOpt('exam', $examData, false, 'id_exam DESC', 1);
    $id_exam = $exam ? $exam[0]->id_exam : null;

    $questions = $this->request->getPost('questions');
    
    foreach ($questions as $q) {
        $questionData = [
            'question' => $q['question'],
            'id_exam' => $id_exam
        ];

        // Only add right_option if it exists in input
        if (!empty($q['right_option'])) {
            $questionData['right_option'] = $q['right_option'];
        }

        $this->model->input('question', $questionData);

        // Fetch the last inserted question ID
        $question = $this->model->getWhereOpt('question', $questionData, false, 'id_question DESC', 1);
        $id_question = $question ? $question[0]->id_question : null;

        // Ensure options exist before inserting
        if (!empty($q['options']) && is_array($q['options'])) {
            foreach ($q['options'] as $optionKey => $optionValue) {
                $optionData = [
                    'id_question' => $id_question,
                    'option' => $optionKey,
                    'description' => $optionValue
                ];
                
                $this->model->input('option', $optionData);
            }
        }
    }

    $id_user = session()->get('id_user');
    $exam_name = $this->request->getPost('nama_exam');
    $this->model->log_activity($id_user, "User created $exam_name exam.");

    return redirect()->to(base_url('home/listing/' . session()->get('id_course')));
}




// fitur profile


public function update_profile()
{
    $userId = session()->get('id_user');

    if ($userId !== null) { 
        $where = ['id_user' => $userId];
        $nameColumn = 'username';
        $table = 'user';
    } else {
        return redirect()->to('/error')->with('error', 'Invalid user level.');
    }

    $newName = $this->request->getPost('fullName');
    if (!$newName) {
        return redirect()->back()->with('error', 'Full Name is required.');
    }
    $data = [$nameColumn => $newName];
    $this->model->edit($table, $data, $where);
    session()->set('username', $newName);

    $file = $this->request->getFile('profile_image');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $uploadPath = 'images/';
        $newFileName = $userId . '_' . $file->getRandomName();
        if ($file->move($uploadPath, $newFileName)) {
            $currentData = $this->model->getWhere('user', ['id_user' => $userId]);
             $oldFileName = $currentData->foto ?? null;
            $this->model->edit('user', ['foto' => $newFileName], ['id_user' => $userId]);
            session()->set('foto', $newFileName);
            if ($oldFileName && file_exists($uploadPath . $oldFileName)) {
                unlink($uploadPath . $oldFileName);
            }
        } else {
            return redirect()->back()->with('error', 'Failed to upload the profile image.');
        }
    }

     $this->model->log_activity($userId, "User updated profile");
    return redirect()->to('/home/profile')->with('successprofil', 'Profile updated successfully.');
}
public function delete_profile_picture()
{
    $userId = session()->get('id_user');

    $currentData = $this->model->getWhere('user', ['id_user' => $userId]);
    $oldFileName = $currentData->foto ?? null;

    if ($oldFileName) {
        $filePath = 'images/' . $oldFileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $this->model->edit('user', ['foto' => null], ['id_user' => $userId]);
        session()->set('foto', null);
    }
     $this->model->log_activity($userId, "User deleted profile picture");
    return redirect()->to('/home/profile')->with('successprofil', 'Profile picture removed successfully.');
}

public function reset_pass ($id)
    {
        $where= array('id_user' => session()->get('id_user'));
        $data = array(
            
            "password"=> '$2y$10$06lHaz6.m7r5x3drFKem8e3EbEOUlDX2CqW7TjrRY8.w0.s0EVq4K',   
        );
        $this->model->edit('user',$data,$where);
         $id_user = session()->get('id_user');
         $this->model->log_activity($id_user, "User resetted password");
        return redirect()->to('home/profile');
    }
    public function change_pass()
    {
            $where= array('id_user' => session()->get('id_user'));
        $data = array(
            'password'=> MD5($this->request->getPost('newpassword')),
        );
        $this->model->edit('user',$data,$where);

       
        return redirect()->to('/home/profile')->with('success','Password berhasil diganti');
    }






    // end fitur profile





//start fitur history exam

    public function history($id)
    {
        if (session()->get('level')>0){
        $id_user = session()->get('id_user');
        $where= array('exam_detail.id_user' =>$id);
        $parent['child']=$this->model->joinhistory($where);

         $this->model->log_activity($id_user, "User visitted exam history");
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('play-history', $parent);
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }


    public function yourexam($id)
    {
        if (session()->get('level')>0){
        $where= array('user.id_user' =>$id);
         $parent['child']=$this->model->yourexam($where);
         $id_user = session()->get('id_user');
         $this->model->log_activity($id_user, "User visitted made courses");
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view ('menu');
        echo view('your-exam', $parent);
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }





    //end history exam

public function edit($id)
{
    // Fetch exam details
    $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id], true);

    if (!$exam) {
        return redirect()->to('home/index')->with('error', 'Exam not found.');
    }

    // Fetch questions related to the exam
    $questions = $this->model->getWhereOpt('question', ['id_exam' => $id]);

    if ($questions) {
        foreach ($questions as &$q) { 
            $q->options = $this->model->getWhereOpt('option', ['id_question' => $q->id_question]);
        }
    } else {
        $questions = [];
    }

    if ($this->request->getMethod() === 'post') {
        $data = [
            'allowed_attempt' => $this->request->getPost('allowed_attempt') ?? null,
            'min_score' => $this->request->getPost('min_score') ?? null,
            'time_limit' => $this->request->getPost('time_limit') ?? null,
            'q_shuffle' => $this->request->getPost('q_shuffle') ?? 0,
            'o_shuffle' => $this->request->getPost('o_shuffle') ?? 0,
        ];

        // **Convert empty string to NULL**
        $exam_open = trim($this->request->getPost('exam_open'));
        $data['exam_open'] = !empty($exam_open) ? $exam_open : null;

        $exam_closed = trim($this->request->getPost('exam_closed'));
        $data['exam_closed'] = !empty($exam_closed) ? $exam_closed : null;

        // Debugging: Log the values before updating
        log_message('debug', 'Exam Update Data: ' . print_r($data, true));

        // **Update the database with NULL values if empty**
        $this->model->edit('exam', $data, ['id_exam' => $id]);

        return redirect()->to("exam/edit/$id")->with('success', 'Exam updated successfully.');
    }

    return view('edit', [
        'exam' => $exam,
        'questions' => $questions
    ]);
}




// public function update()
// {

//     $id = $this->request->getPost('id_exam');
//     $nama_exam = $this->request->getPost('nama_exam');
//     $deskripsi = $this->request->getPost('deskripsi');

//     $this->model->edit('exam', ['nama_exam' => $nama_exam, 'deskripsi' => $deskripsi], ['id_exam' => $id]);

//     return redirect()->to('home/yourexam/'. session()->get('id_user'))->with('success', 'Exam updated successfully.');
// }

public function update()
{
    $id_exam = $this->request->getPost('id_exam');

    // 1. Update Exam Details
    $examData = [
        'nama_exam' => $this->request->getPost('nama_exam'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'allowed_attempt' => $this->request->getPost('allowed_attempt'),
        'min_score' => $this->request->getPost('min_score'),
        'exam_open' => date("Y-m-d H:i:s", strtotime($this->request->getPost('exam_open'))),
        'exam_closed' => date("Y-m-d H:i:s", strtotime($this->request->getPost('exam_closed'))),
        'q_shuffle' => $this->request->getPost('q_shuffle'),
        'o_shuffle' => $this->request->getPost('o_shuffle'),
        'time_limit' => $this->request->getPost('time_limit')
    ];
    $this->model->edit('exam', $examData, ['id_exam' => $id_exam]);

    // 2. Get Existing Questions for This Exam
    $existingQuestions = $this->model->getWhereOpt('question', ['id_exam' => $id_exam]);

    // Convert existing questions into an associative array for lookup
    $existingQuestionMap = [];
    foreach ($existingQuestions as $q) {
        $existingQuestionMap[$q->id_question] = $q;
    }

    $submittedQuestions = $this->request->getPost('questions');
    $questionIdsToKeep = [];

    foreach ($submittedQuestions as $q) {
    $id_question = isset($q['id_question']) ? $q['id_question'] : null;

    // Cek apakah soal memiliki opsi atau tidak
    $isEssay = empty($q['options']); // Jika kosong, berarti soal essay

    if ($id_question && isset($existingQuestionMap[$id_question])) {
        // Update existing question
        $this->model->edit('question', [
            'question' => $q['question'],
            'right_option' => $isEssay ? null : $q['right_option'] // Set NULL jika soal essay
        ], ['id_question' => $id_question]);
    } else {
        // Insert new question
        $this->model->input('question', [
            'question' => $q['question'],
            'right_option' => $isEssay ? null : $q['right_option'],
            'id_exam' => $id_exam
        ]);

        // Ambil ID soal yang baru dimasukkan
        $id_question = $this->model->getLastInsertedId('question');
    }

    $questionIdsToKeep[] = $id_question;

    // Jika soal bukan essay, proses opsi
    if (!$isEssay) {
        $existingOptions = $this->model->getWhereOpt('option', ['id_question' => $id_question]);
        $existingOptionMap = [];
        foreach ($existingOptions as $opt) {
            $existingOptionMap[$opt->option] = $opt;
        }

        $submittedOptions = $q['options'];
        $optionKeysToKeep = [];

        foreach ($submittedOptions as $optionKey => $optionValue) {
            if (isset($existingOptionMap[$optionKey])) {
                // Update existing option
                $this->model->edit('option', [
                    'description' => $optionValue
                ], ['id_question' => $id_question, 'option' => $optionKey]);
            } else {
                // Insert new option
                $this->model->input('option', [
                    'id_question' => $id_question,
                    'option' => $optionKey,
                    'description' => $optionValue
                ]);
            }
            $optionKeysToKeep[] = $optionKey;
        }

        // Hapus opsi yang tidak lagi digunakan
        foreach ($existingOptions as $existingOpt) {
            if (!in_array($existingOpt->option, $optionKeysToKeep)) {
                $this->model->hapus('option', ['id_question' => $id_question, 'option' => $existingOpt->option]);
            }
        }
    } else {
        // Jika soal essay, pastikan semua opsi yang tersisa dihapus
        $this->model->hapus('option', ['id_question' => $id_question]);
    }
}


    // 4. Delete removed questions
    foreach ($existingQuestions as $existingQ) {
        if (!in_array($existingQ->id_question, $questionIdsToKeep)) {
            $this->model->hapus('question', ['id_question' => $existingQ->id_question]);
            $this->model->hapus('option', ['id_question' => $existingQ->id_question]);
        }
    }

    $id_user = session()->get('id_user');
    $nama_exam = $this->request->getPost('nama_exam');

    $this->model->log_activity($id_user, "User updated $nama_exam");

    return redirect()->to('home/listing/' . session()->get('id_course'))->with('success', 'Exam updated successfully.');
}


public function deleteexam($id_exam)
{
    date_default_timezone_set('Asia/Jakarta'); // Set zona waktu
    $deletedAt = date('Y-m-d H:i:s'); // Waktu sekarang

    // Update deleted_at untuk opsi berdasarkan id_question yang terkait dengan id_exam
    $this->model->edit('option', ['deleted_at' => $deletedAt], [
        'id_question IN (SELECT id_question FROM question WHERE id_exam = ?)' => $id_exam
    ]);

    // Update deleted_at untuk semua pertanyaan berdasarkan id_exam
    $this->model->edit('question', ['deleted_at' => $deletedAt], ['id_exam' => $id_exam]);

    // Update deleted_at untuk ujian berdasarkan id_exam
    $this->model->edit('exam', ['deleted_at' => $deletedAt], ['id_exam' => $id_exam]);


    $id_user = session()->get('id_user');
    $this->model->log_activity($id_user, "User deleted an exam");

    return redirect()->to(base_url('home/listing/' . session()->get('id_course')))
        ->with('success', 'Exam has been marked as deleted.');
}

public function killexam($id_exam)
{
    $db = \Config\Database::connect(); // Get database connection

    // Disable foreign key checks
    $db->query("SET FOREIGN_KEY_CHECKS=0");

    // Hapus semua opsi berdasarkan id_question yang terkait dengan id_exam
    $db->query("
        DELETE FROM option
        WHERE id_question IN (
            SELECT id_question FROM question WHERE id_exam = ?
        )
    ", [$id_exam]);

    // Hapus semua pertanyaan berdasarkan id_exam
    $db->query("
        DELETE FROM question WHERE id_exam = ?
    ", [$id_exam]);

    // Hapus ujian berdasarkan id_exam
    $this->model->hapus('exam', ['id_exam' => $id_exam]);

    // Re-enable foreign key checks
    $db->query("SET FOREIGN_KEY_CHECKS=1");

    return redirect()->to(base_url('home/listing/' . session()->get('id_course')))
        ->with('success', 'Exam and all related data deleted successfully.');
}


//update delete end




public function courseview()
{
    if (session()->get('level') > 0) {
        $id_user = session()->get('id_user');
        
        // Ambil hanya course yang belum dihapus (deleted_at IS NULL)
        $parent['child'] = $this->model->joinwall(
            'course',
            'user',
            'course.id_user = user.id_user',
            ['course.id_user' => $id_user, 'course.deleted_at' => null], // Tambahkan kondisi
            'id_course'
        );
         // Debugging: Check what data is being retrieved
         // Stop execution to inspect the output
        $this->model->log_activity($id_user, "User visitted course list");
        echo view('header',['webDetail' => $this->webDetail]);
        echo view('courseview', $parent);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}

public function allcourseview()
{
    if (session()->get('level') > 0) {
        $id_user = session()->get('id_user');

        // Fetch only courses where deleted_at IS NULL
        $parent['child'] = $this->model->joinwc(
            'course',
            'user',
            'course.id_user = user.id_user',
            [],
            'course.id_course'
        );

        // Filter by search query if provided
        if (!empty($search)) {
            $parent['child'] = array_filter($parent['child'], function ($course) use ($search) {
                return stripos($course->nama_course, $search) !== false;
            });
        }

        $this->model->log_activity($id_user, "User visitted course list");
        echo view('header',['webDetail' => $this->webDetail]);
        echo view('allcourseview', $parent);
        echo view('footer');
    } else {
        return redirect()->to('/home/login');
    }
}


public function addcourse()
    {
        if (session()->get('level')>0){
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('addcourse');
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }

public function inputcourse()
    {
        if (session()->get('level')>0){

        $id_user = session()->get('id_user');
        $nama_course = $this->request->getPost('nama_course');

        $this->model->input('course', [
            'id_user' => $id_user,
            'nama_course' => $this->request->getPost('nama_course'),
            'created_by' => $id_user
        ]);

         $course = $this->model->getWhere('course', [
            'id_user' => $id_user,
            'nama_course' => $nama_course
        ]);
         $this->model->log_activity($id_user, "User added $nama_course course");
        return redirect()->to('/home/courseview');
        }else{
            return redirect()->to('/home/login');
        }
    }

    public function editcourse($id)
    {
        if (session()->get('level')>0){
        $where= array('id_course' =>$id);
        $parent['child']=$this->model->getWhere('course',$where);
        echo view ('header',['webDetail' => $this->webDetail]);
        echo view('editcourse',$parent);
        echo view ('footer');
        }else{
            return redirect()->to('/home/login');
        }
    }

public function simpancourse()
{
    if (session()->get('level') > 0) {
        $id_course = $this->request->getPost('id_course'); // Get the course ID
        $nama_course = $this->request->getPost('nama_course'); // Get the edited course name
        $id_user = session()->get('id_user'); // Get the current user ID

        // Set timezone to Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        $current_time = date('Y-m-d H:i:s'); // Get the current timestamp in Jakarta timezone

        // Update the course name, updated_by, and updated_at where id_course matches
        $this->model->edit('course', [
            'nama_course' => $nama_course,
            'updated_by' => $id_user,
            'updated_at' => $current_time
        ], ['id_course' => $id_course]);

        // Log the activity
        $this->model->log_activity($id_user, "User edited course: $nama_course");

        return redirect()->to('/home/courseview');
    } else {
        return redirect()->to('/home/login');
    }
}


public function deleteCourse($id_course)
{
    date_default_timezone_set('Asia/Jakarta'); // Set zona waktu
    $now = date('Y-m-d H:i:s'); // Ambil waktu sekarang
    $id_user = session()->get('id_user');

    // Ambil ID ujian (id_exam) yang paling lama dari course ini
    $oldest_exam = $this->model->getWhereOpt('exam', ['id_course' => $id_course], true, 'id_exam ASC');

    if (!empty($oldest_exam)) {  // Cek apakah data tidak null
        $id_exam = $oldest_exam->id_exam;

        // Ambil ID pertanyaan (id_question) yang paling lama dari ujian ini
        $oldest_question = $this->model->getWhereOpt('question', ['id_exam' => $id_exam], true, 'id_question ASC');

        if (!empty($oldest_question)) { // Cek apakah data tidak null
            $id_question = $oldest_question->id_question;

            // Soft delete opsi berdasarkan id_question yang paling lama
            $this->model->edit('option', ['deleted_at' => $now, 'deleted_by' => $id_user], ['id_question' => $id_question]);
        }

        // Soft delete pertanyaan yang paling lama
        $this->model->edit('question', ['deleted_at' => $now, 'deleted_by' => $id_user], ['id_exam' => $id_exam]);

        // Soft delete ujian yang paling lama
        $this->model->edit('exam', ['deleted_at' => $now, 'deleted_by' => $id_user], ['id_course' => $id_course]);
    }

    // Soft delete course
    $this->model->edit('course', ['deleted_at' => $now, 'deleted_by' => $id_user], ['id_course' => $id_course]);

    
    $nama_course = $oldest_exam->nama_exam;
    $this->model->log_activity($id_user, "User deleted $nama_course course");
   if (session()->get('level') == 1 || session()->get('level') == 49) { 

    return redirect()->to(base_url('home/allcourseview/'));
   } else {
    return redirect()->to(base_url('home/courseview/' . session()->get('id_user')))
        ->with('success', 'Course and all related data deleted successfully.');
}
}


public function killCourse($id_course)
{
    $db = \Config\Database::connect(); // Get database connection

    // Disable foreign key checks
    $db->query("SET FOREIGN_KEY_CHECKS=0");

    // Hapus semua opsi berdasarkan id_question yang terkait dengan id_exam dari course ini
    $db->query("
        DELETE FROM option
        WHERE id_question IN (
            SELECT id_question FROM question 
            WHERE id_exam IN (
                SELECT id_exam FROM exam WHERE id_course = ?
            )
        )
    ", [$id_course]);

    // Hapus semua pertanyaan berdasarkan id_exam
    $db->query("
        DELETE FROM question
        WHERE id_exam IN (
            SELECT id_exam FROM exam WHERE id_course = ?
        )
    ", [$id_course]);

    // Hapus semua ujian yang terkait dengan course
    $this->model->hapus('exam', ['id_course' => $id_course]);

    // Hapus course
    $this->model->hapus('course', ['id_course' => $id_course]);

    // Re-enable foreign key checks
    $db->query("SET FOREIGN_KEY_CHECKS=1");

    return redirect()->to(base_url('home/courseview/' . session()->get('id_user')))
        ->with('success', 'Course and all related data deleted successfully.');
}



public function attempts_list($id_exam)
{
    // Simpan id_exam di session agar tetap tersedia
    session()->set('id_exam', $id_exam);

    // Ambil data ujian
    $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id_exam], true);
    $questions = $this->model->getWhereOpt('question', ['id_exam' => $id_exam], false);

    // Ambil data attempts
    $attempts = $this->model->getAttemptsTable($id_exam);

    // Ambil data jawaban dari tabel exam_attempts
    $attempt_answers = $this->model->getAttemptAnswers($id_exam);

    // Format hasil attempt agar sesuai dengan tampilan tabel
    $formatted_attempts = [];
    foreach ($attempts as $attempt) {
        $key = $attempt->id_detail;
        if (!isset($formatted_attempts[$key])) {
            $formatted_attempts[$key] = [
                'id_detail' => $attempt->id_detail,
                'username' => $attempt->username,
                'email' => $attempt->email,
                'date_of_exam' => $attempt->date_of_exam,
                'date_of_submit' => $attempt->date_of_submit,
                'time_taken' => $attempt->time_taken,
                'exam_score' => $attempt->exam_score,
                'questions' => [],
            ];
        }
    }

    // Tambahkan status jawaban ke dalam data attempt
    foreach ($attempt_answers as $answer) {
        $formatted_attempts[$answer->id_detail]['questions'][$answer->id_question] = $answer->is_correct;
    }

    // Simpan aktivitas log
    $id_user = session()->get('id_user');
    $nama_exam = $exam->nama_exam;
    $this->model->log_activity($id_user, "User seen $nama_exam attempt list");

    // Load tampilan
    echo view('header',['webDetail' => $this->webDetail]);
    echo view('menu');
    echo view('exam_attempts', [
        'exam' => $exam,
        'questions' => $questions,
        'formatted_attempts' => $formatted_attempts
    ]);
    echo view('footer');
}

public function attemptDetails($id_detail)
{
    if (session()->get('id_user') <= 0) {
        return redirect()->to('/home/login');
    }

    $id_user = session()->get('id_user');

    // Fetch exam details
    $attempt = $this->model->getWhere('exam_detail', ['id_detail' => $id_detail, 'id_user' => $id_user]);

    if (!$attempt) {
        return redirect()->to('/home/yourAttempts')->with('error', 'Attempt not found');
    }

    $exam_id = $attempt->id_exam;

    // Fetch exam name
    $exam = $this->model->getWhere('exam', ['id_exam' => $exam_id]);
    if (!$exam) {
        return redirect()->to('/home/yourAttempts')->with('error', 'Exam not found');
    }

    // Fetch questions and user's answers
    $questions = $this->model->getUserAttemptDetails($id_detail, $exam_id);
    if (!$questions) {
        return redirect()->to('/home/detail/' . $exam_id)->with('error', 'No questions found for this attempt');
    }

    $data = [
        'exam_name' => $exam->nama_exam,
        'questions' => $questions
    ];

    // Load views
    echo view('header', ['webDetail' => $this->webDetail]);
    echo view('menu');
    echo view('attempt_details', $data);
    echo view('footer');
}



public function review_essay($id_detail)
{
    // Ambil data attempt berdasarkan id_detail
    $attempt = $this->model->getWhereOpt('exam_attempts', ['id_detail' => $id_detail], true);
    
    if (!$attempt) {
        return redirect()->to(base_url('home/attempts_list/' . session()->get('id_exam')))
                         ->with('error', 'Attempt tidak ditemukan.');
    }

    // Ambil daftar pertanyaan essay yang belum dikoreksi
    $questions = $this->model->getEssayQuestions($attempt->id_exam);

    // Ambil semua jawaban dari tabel `exam_attempts` berdasarkan `id_detail`
    $answers = $this->model->getWhereOpt('exam_attempts', ['id_detail' => $id_detail], false);
    
    // Ubah menjadi array dengan `id_question` sebagai key
    $answerMap = [];
    foreach ($answers as $a) {
        $answerMap[$a->id_question] = $a->chosen_option; // Gantilah jika jawaban tersimpan di field lain
    }

    return view('review_essay', [
        'webDetail' => $this->webDetail, // Untuk data global
        'attempt' => $attempt,
        'questions' => $questions,
        'answerMap' => $answerMap
    ]);
}

public function submit_essay_correction()
{
    $id_detail = $this->request->getPost('id_detail');
    $scores = $this->request->getPost('score');

    if (!$id_detail || !$scores) {
        return redirect()->to(base_url('home/review_essay/' . $id_detail))
                         ->with('error', 'Data tidak valid.');
    }

    $totalScore = 0;

    foreach ($scores as $id_question => $score) {
        // Simpan skor untuk masing-masing essay
        $this->model->edit('exam_attempts', ['is_correct' => $score > 0 ? 1 : 0], [
            'id_detail' => $id_detail,
            'id_question' => $id_question
        ]);

        $totalScore += $score;
    }

    // **Ambil jumlah total soal (pilihan ganda + essay) dalam ujian ini**
    $totalQuestions = $this->model->countWhere('exam_attempts', ['id_detail' => $id_detail]);

    // **Ambil jumlah jawaban benar dari pilihan ganda yang sudah ada**
    $existingCorrect = $this->model->countWhere('exam_attempts', [
        'id_detail' => $id_detail,
        'is_correct' => 1
    ]);

    // Hitung nilai akhir
 $totalPossibleScore = 100; // Nilai maksimum
$finalScore = $existingCorrect * ($totalPossibleScore / $totalQuestions);


    // Ambil `min_score` dari tabel `exam`
    $exam = $this->model->getWhere('exam', ['id_exam' => session()->get('id_exam')]);
    $min_score = $exam ? $exam->min_score : 70; // Default 70 jika tidak ada min_score

    // Tentukan hasil berdasarkan `min_score`
    $exam_result = $finalScore >= $min_score ? 'lulus' : 'gagal';

    // Update total skor di `exam_detail`
    $this->model->edit('exam_detail', [
        'exam_score' => $finalScore,
        'exam_result' => $exam_result
    ], [
        'id_detail' => $id_detail
    ]);

    return redirect()->to(base_url('home/attempts_list/' . session()->get('id_exam')))
                     ->with('success', 'Essay successfully reviewed!');
}





public function delete_attempt($id_attempt)
{
    date_default_timezone_set('Asia/Jakarta'); // Set zona waktu
    $now = date('Y-m-d H:i:s'); // Ambil waktu sekarang

    // Get the exam ID before updating
    $attempt = $this->model->getWhereOpt('exam_detail', ['id_detail' => $id_attempt], true);

    if (!$attempt) {
        return redirect()->back()->with('error', 'Attempt not found.');
    }

    $id_exam = $attempt->id_exam; // Extract exam ID

    // Soft delete related answers from exam_attempts
    $this->model->edit('exam_attempts', ['deleted_at' => $now], ['id_detail' => $id_attempt]);

    // Soft delete the attempt record from exam_detail
    $this->model->edit('exam_detail', ['deleted_at' => $now], ['id_detail' => $id_attempt]);

    // Redirect to attempt list with id_exam
    return redirect()->to('home/attempts_list/' . $id_exam)->with('success', 'Attempt deleted successfully.');
}


public function killattempts()
{
    $request = service('request');
    $json = $request->getJSON();

    if (!isset($json->ids) || empty($json->ids)) {
        return $this->response->setJSON(['success' => false, 'message' => 'No attempts selected']);
    }

    // Loop untuk menghapus semua attempt yang dipilih
    foreach ($json->ids as $id_attempt) {
        // Ambil data attempt sebelum dihapus
        $attempt = $this->model->getWhereOpt('exam_detail', ['id_detail' => $id_attempt], true);

        if (!$attempt) {
            continue; // Skip jika tidak ditemukan
        }

        $id_exam = $attempt->id_exam; // Simpan ID exam sebelum delete

        // Hapus jawaban dari `exam_attempts`
        $this->model->hapus('exam_attempts', ['id_detail' => $id_attempt]);

        // Hapus attempt dari `exam_detail`
        $this->model->hapus('exam_detail', ['id_detail' => $id_attempt]);
    }

    // Log aktivitas
    $id_user = session()->get('id_user');
    $this->model->log_activity($id_user, "User deleted multiple attempts");

    return $this->response->setJSON(['success' => true, 'message' => 'Attempts deleted successfully']);
}


  public function laporan_attempt()
{
    $a = $this->request->getPost('tanggal_awal');
    $b = $this->request->getPost('tanggal_akhir');
    $id = session()->get('id_exam');

    // Fetch exam details
    $exam = $this->model->getWhereOpt('exam', ['id_exam' => $id], false);
     $nama_exam = $exam ? $exam[0]->nama_exam : 'Tidak Diketahui';

    // Fetch all questions for this exam (only need question IDs)
    $questions = $this->model->getWhereOpt('question', ['id_exam' => $id], false);

    // Fetch filtered attempt data
    $attempts = $this->model->filter_attempts(
        'exam_attempts',
        'exam_detail',
        'user',
        'question',
        'exam',
        'exam_attempts.id_detail = exam_detail.id_detail',
        'exam_attempts.id_user = user.id_user',
        'exam_attempts.id_question = question.id_question',
        'exam_detail.id_exam = exam.id_exam',
        'exam_detail.date_of_exam', $a, $b, $id
    );

    $id_user = session()->get('id_user');
     $this->model->log_activity($id_user, "User printed $nama_exam's report");

    // Organize attempt data into a single row per user
    $formatted_attempts = [];
    foreach ($attempts as $attempt) {
        $key = $attempt->id_detail;
        if (!isset($formatted_attempts[$key])) {
            $formatted_attempts[$key] = [
                'username' => $attempt->username,
                'email' => $attempt->email,
                'date_of_exam' => $attempt->date_of_exam,
                'date_of_submit' => $attempt->date_of_submit,
                'time_taken' => $attempt->time_taken,
                'exam_score' => $attempt->exam_score,
                'questions' => [],
            ];
        }
        // Store question results (1 for correct, 0 for incorrect)
        $formatted_attempts[$key]['questions'][$attempt->id_question] = $attempt->is_correct;
    }

    // Load view and generate HTML for PDF
    $html = view('laporan_attempt', [
         'nama_exam' => $nama_exam,
        'questions' => $questions,
        'formatted_attempts' => $formatted_attempts,
        'tanggal_awal' => $a,
        'tanggal_akhir' => $b
    ]);

    // Generate PDF
    $pdf = new TCPDF();
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Your System');
    $pdf->SetTitle('Laporan Attempt');
    $pdf->SetSubject('Laporan PDF');
    $pdf->SetKeywords('TCPDF, PDF, laporan, attempt');

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 6);
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('laporan_attempt_' . date('YmdHis') . '.pdf', 'D'); // 'D' for download
}



public function user_log_activity()
{
    // Check if the user is logged in
    if (session()->has('id_user')) {
        $id_user = session()->get('id_user');

        // Get log activity for the logged-in user
        $where = ['log_activity.id_user' => $id_user];
        $data['child'] = $this->model->joinwall('log_activity', 'user', 'log_activity.id_user = user.id_user', ['log_activity.id_user' => $id_user], 'id_log');


        // Load views
         $this->model->log_activity($id_user, "User accessed log activity");
        echo view('header',['webDetail' => $this->webDetail]);
        echo view('menu');
        echo view('user_log_activity', $data); // Updated to a 'user' folder
    } else {
        return redirect()->to('/home/login');
    }
}





}








