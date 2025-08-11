
<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'semester_id', 'name', 'code', 'description', 'color', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getSubjectsWithTopics()
    {
        return $this->select('subjects.*, semesters.name as semester_name')
                   ->join('semesters', 'semesters.id = subjects.semester_id')
                   ->where('subjects.is_active', 1)
                   ->orderBy('subjects.name')
                   ->findAll();
    }

    public function getSubjectWithHierarchy($subjectId = null)
    {
        $builder = $this->db->table('subjects s')
                           ->select('s.*, sem.name as semester_name')
                           ->join('semesters sem', 'sem.id = s.semester_id')
                           ->where('s.is_active', 1);
        
        if ($subjectId) {
            $builder->where('s.id', $subjectId);
            return $builder->get()->getRowArray();
        }
        
        return $builder->orderBy('s.name')->get()->getResultArray();
    }
}
