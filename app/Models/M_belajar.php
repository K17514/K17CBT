<?php

namespace App\Models;
use CodeIgniter\Model;

class M_belajar extends Model
{
	public function tampil($table, $orderBy) {
    return $this->db->table($table)
    				->orderBy($orderBy, 'desc')
                    ->get()
                    ->getResult();
}
public function tampil1($table, $orderBy, $single = false) {
    $query = $this->db->table($table)
                      ->orderBy($orderBy, 'desc')
                      ->get();

    return $single ? $query->getRow() : $query->getResult();
}

	public function join3w($table, $table2, $table3, $on, $on2, $orderBy, $where)
{
    return $this->db->table($table)
                    ->join($table2, $on)
                    ->join($table3, $on2)
                    ->where($where) // Apply filtering first
                    ->orderBy($orderBy, 'desc') // Then apply sorting
                    ->get()
                    ->getResult();
}

public function joinhistory($where){
	return $this->db->table('exam_detail')
            		->select('exam_detail.created_at, exam.nama_exam, exam_detail.exam_score, exam_detail.id_user') // Selecting explicitly
            		->join('exam', 'exam_detail.id_exam = exam.id_exam')
            		->join('user', 'exam_detail.id_user = user.id_user')
            		->where($where)
            		->orderBy('exam_detail.created_at', 'desc') // Order by date played
            		->get()
            		->getResult();
}


	public function join3($table, $table2, $table3, $on,$on2, $orderBy){
		return $this->db->table($table)
						->orderby($orderBy, 'desc')
						->join($table2,$on)
						->join($table3,$on2)
						->get()
						->getResult();
	}

	public function join($table, $table2, $on, $orderBy){
		return $this->db->table($table)
						->orderby($orderBy, 'desc')
						->join($table2,$on)
						->orderBy($orderBy, 'desc')
						->get()
						->getResult();
	}

	public function joinwcount($table, $table2, $on,$w, $orderBy){
    return $this->db->table($table)
                    ->select("$table.*, COUNT($table2.id_question) as total_questions") // Count questions
                    ->join($table2, $on)
                    ->where($w)
                    ->get()
                    ->getRow();
}

public function joinwcount2($table, $table2, $table3, $on, $on2, $w, $orderBy){
        return $this->db->table($table) // Start from 'exam'
                    ->select("$table.*, $table2.nama_course, COUNT($table3.id_question) as total_questions") 
                    ->join($table2, $on) // Join 'course' first
                    ->join($table3, $on2, 'left') // LEFT JOIN 'question' to include exams without questions
                    ->where($w)
                    ->groupBy("$table.id_exam") // Group by 'exam' ID
                    ->orderBy($orderBy, 'desc')
                    ->get()
                    ->getResult();

}


	public function yourexam($where){
	return	$this->db->table('exam')
            		 ->select('exam.id_exam, exam.nama_exam, exam.deskripsi')
            		 ->join('user', 'exam.created_by = user.id_user')
            		 ->where('exam.created_by', $where)
            		 ->orderBy('exam.id_exam', 'DESC') // Sort by latest exam
            		 ->get()
            		 ->getResult();
	}
public function joincount($table, $table2, $on, $orderBy){
    return $this->db->table($table)
                    ->select("$table.*, COUNT($table2.id_question) as total_questions") // Count questions
                    ->join($table2, $on)
                    ->groupBy("$table.id_exam") // Group by exam ID
                    ->orderBy($orderBy, 'desc')
                    ->get()
                    ->getResult();
}

	public function filter($table, $table2, $on, $filter,$filter2,$awal,$akhir,$orderBy){
		return $this->db->table($table)
						->join($table2,$on)
						->where($filter, $awal)
						->where($filter2,$akhir)
						->orderby($orderBy, 'desc')
						->get()
						->getResult();
	}
    public function simplefilter($table, $table2, $on, $filters = [], $orderBy = null) {
        return $this->db->table($table)
        ->join($table2, $on);
        if (!empty($filters)) {
            $query->where($filters);
        }
        if (!empty($orderBy)) {
            $query->orderBy($orderBy, 'desc');
        }

        return $query->get()->getResult();
    }

	public function joinwc($table, $table2, $on, $w,$orderBy)
{
    return $this->db->table($table)
                    ->join($table2, $on)
                    ->where("$table.deleted_at IS NULL") // Ensure deleted courses are excluded
                    ->orderby($orderBy, 'desc')
                    ->get()
                    ->getResult(); // Use getResult() to return multiple rows
}

    public function joinw($table, $table2, $on, $w){
        return $this->db->table($table)
                        ->join($table2,$on)
                        ->where($w)
                        ->get()
                        ->getRow();
    }
	public function joinwall($table, $table2, $on, $w, $orderBy){
		return $this->db->table($table)
						->join($table2,$on)
						->where($w)
						->orderBy($orderBy, 'desc')
						->get()
						->getResult();
	}
	public function hapus($table, $where){
		return $this->db->table($table)
						->delete($where);
	}
	public function getWhere($table, $where){
		return $this->db->table($table)
						->getWhere($where)
						->getRow();
	}
   public function getDelete($table, $where)
{
    return $this->db->table($table)
                    ->where($where, null, false) // Cegah escaper untuk SQL raw
                    ->get()
                    ->getResultArray(); // Ambil semua data sebagai array
}


	public function getWhereOpt($table, $where, $single = false, $orderBy = null) {
		$builder = $this->db->table($table)->where($where);
		if ($orderBy) {
			$builder->orderBy($orderBy);
		}
		$query = $builder->get();
		return $single ? $query->getRow() : $query->getResult();
	}


	public function edit($table, $data, $where){
		return $this->db->table($table)
						->update($data, $where);
	}
	public function input($table, $data){
		return $this->db->table($table)
						->insert($data);
	}

   public function inputBatch($table, $data)
{
    $builder = $this->db->table($table);
    return $builder->insertBatch($data);
}


    
public function get_correct_answers($id_exam) {
        return $this->db->select('id_question, right_option')
                        ->from('question')
                        ->where('id_exam', $id_exam)
                        ->get()
                        ->result();
    }

    public function save_exam_result($data) {
        return $this->db->insert('exam_detail', $data);
    }

    public function get_user_result($id_exam, $id_user) {
    	return $this->db->table('exam_detail')
    					->orderBy('date_of_exam', 'desc')
    					->limit(1) // Get the latest attempt
    					->getWhere([
    						'id_exam' => $id_exam,
    						'id_user' => $id_user])
    					->getRow();
    }

    public function get_leaderboard($id_exam) {
    return $this->db->table('exam_detail')
        ->select('user.username, user.foto, exam_detail.exam_score, exam_detail.created_at')
        ->join('user', 'user.id_user = exam_detail.id_user')
        ->where('exam_detail.id_exam', $id_exam) // Filter by exam ID
        ->orderBy('exam_detail.exam_score', 'DESC', 'exam_detail.created_at', 'DESC')
        ->limit(10) // Top 10
        ->get()
        ->getResult();
}



	public function filterpesanan($table1, $table2, $table3, $join1, $join2, $where, $a, $b, $examId)
{
    return $this->db->table($table1)
        ->select('*')
        ->join($table2, $join1)
        ->join($table3, $join2)
        ->where($where . ' >=', $a)
        ->where($where . ' <=', $b)
        ->where('exam.id_exam', $examId) 
        ->get()
        ->getResult();
}

public function filter_attempts($table1, $table2, $table3, $table4, $table5, $join1, $join2, $join3, $join4, $where, $a, $b, $examId)
{
    return $this->db->table($table1)
        ->select('user.username, user.email, exam.nama_exam, question.id_question, exam_attempts.is_correct, exam_detail.date_of_exam, exam_detail.date_of_submit, exam_detail.time_taken, exam_detail.exam_score, exam_detail.id_detail')
        ->join($table2, $join1) // Join exam_detail
        ->join($table3, $join2) // Join user
        ->join($table4, $join3) // Join question
        ->join($table5, $join4) // Join exam
        ->where($where . ' >=', $a)
        ->where($where . ' <=', $b)
        ->where('exam_detail.id_exam', $examId)
        ->get()
        ->getResult();
}



public function getAttemptCount($id_user, $id_exam)
{
    $query = $this->db->query("
        SELECT COUNT(*) AS total_attempts 
        FROM exam_detail 
        WHERE id_user = ? AND id_exam = ?", [$id_user, $id_exam]);

    return $query->getRow()->total_attempts ?? 0;
}


    public function log_activity($id, $activity) 
    {
    	date_default_timezone_set('Asia/Jakarta');
        $data = [
            'id_user' => $id,
            'what_happens' => $activity,
            'ip_address' => file_get_contents("https://api.ipify.org"), // Get public IP, // Use the function
            'happens_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->table('log_activity')
						->insert($data);
    }
    
    public function getLastInsertedId($table)
{
    return $this->db->insertID(); // If your database connection supports this
}
public function getAttemptsTable($id_exam)
{
    // Fetch attempt details
    $query = $this->db->table('exam_detail')
        ->select('user.username, user.email, exam_detail.date_of_exam, exam_detail.date_of_submit, exam_detail.time_taken, exam_detail.exam_score, exam_detail.id_detail')
        ->join('user', 'user.id_user = exam_detail.id_user')
        ->where('exam_detail.id_exam', $id_exam)
        ->where('exam_detail.deleted_at IS NULL') // Tambahkan filter soft delete
        ->orderBy('exam_detail.date_of_exam', 'DESC')
        ->get();

    $attempts = $query->getResult();

    // Fetch all questions for this exam
    $questions = $this->getWhereOpt('question', ['id_exam' => $id_exam], false);
    $total_questions = count($questions); // Get total questions count

    // Prevent division by zero
    $mark_per_question = $total_questions > 0 ? 100 / $total_questions : 0;

    // Create an array to store all attempt data
    $attemptData = [];

    // Attach marks for each attempt
    foreach ($attempts as $attempt) {
        // Store the base attempt data
        $tempAttempt = (array) $attempt; 

        foreach ($questions as $q) {
            // Fetch user's answer correctness
            $answer = $this->db->table('exam_attempts')
                ->select('is_correct')
                ->where([
                    'id_detail' => $attempt->id_detail,
                    'id_question' => $q->id_question
                ])
                ->get()
                ->getRow();

            // Assign calculated mark if correct, otherwise '0'
            $tempAttempt["q_{$q->id_question}"] = (!empty($answer) && $answer->is_correct == 1) ? $mark_per_question : 0;
        }

        // Store modified attempt data
        $attemptData[] = (object) $tempAttempt;
    }

    return $attemptData;
}



public function getUsernameById($id) {
    $result = $this->db->table('user')->select('username')->where('id_user', $id)->get()->getRow();
    return $result ? $result->username : 'Unknown User';  // If result exists, return username, otherwise 'Unknown User'
}


 public function getWebDetails()
    {
        return $this->db->table('web_detail')
                        ->get()
                        ->getRowArray(); // Fetch website details
    }

public function getEssayQuestions($id_exam)
{
    return $this->db->table('question')
        ->where('id_exam', $id_exam)
        ->where('right_option', null) // Hanya soal essay
        ->get()->getResult();
}

public function getAttemptAnswers($id_exam)
{
    return $this->db->table('exam_attempts')
        ->select('exam_attempts.id_detail, exam_attempts.id_question, exam_attempts.is_correct')
        ->join('exam_detail', 'exam_attempts.id_detail = exam_detail.id_detail')
        ->where('exam_detail.id_exam', $id_exam)
        ->get()->getResult();
}

public function countWhere($table, $where)
{
    return $this->db->table($table)->where($where)->countAllResults();
}

public function getUserAttemptsByExam($id_user, $id_exam)
{
    return $this->db->table('exam_detail ed')
        ->select('ed.id_detail, ed.date_of_exam, ed.date_of_submit, ed.time_taken, ed.exam_result, ed.exam_score, e.nama_exam')
        ->join('exam e', 'ed.id_exam = e.id_exam')
        ->where('ed.id_user', $id_user)
        ->where('ed.id_exam', $id_exam) // Tambahkan filter exam
        ->orderBy('ed.date_of_exam', 'DESC')
        ->get()->getResult();
}


public function getUserAttemptDetails($id_detail, $id_exam)
{
    $questions = $this->db->table('exam_attempts')
        ->select('question.id_question, question.question, question.right_option, exam_attempts.chosen_option, exam_attempts.is_correct, correct_option.description AS correct_answer')
        ->join('question', 'question.id_question = exam_attempts.id_question')
        ->join('option AS correct_option', 'correct_option.id_question = question.id_question AND correct_option.option = question.right_option', 'left')
        ->where('exam_attempts.id_detail', $id_detail)
        ->where('question.id_exam', $id_exam)
        ->get()
        ->getResult();

    // Fetch all options separately
    foreach ($questions as $q) {
        $q->options = $this->db->table('option')
            ->select('option, description')
            ->where('id_question', $q->id_question)
            ->get()
            ->getResult();
    }

    return $questions;
}



}

	
