
<?php

namespace App\Models;

use CodeIgniter\Model;

class LastVisitedModel extends Model
{
    protected $table = 'last_visited';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'subtopic_id'];

    protected $useTimestamps = false;

    public function updateLastVisited($userId, $subtopicId)
    {
        $existing = $this->where('user_id', $userId)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'subtopic_id' => $subtopicId,
                'visited_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->insert([
                'user_id' => $userId,
                'subtopic_id' => $subtopicId,
                'visited_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function getLastVisited($userId)
    {
        return $this->select('last_visited.*, subtopics.name as subtopic_name, topics.name as topic_name, subjects.name as subject_name, subjects.id as subject_id')
                   ->join('subtopics', 'subtopics.id = last_visited.subtopic_id')
                   ->join('topics', 'topics.id = subtopics.topic_id')
                   ->join('subjects', 'subjects.id = topics.subject_id')
                   ->where('last_visited.user_id', $userId)
                   ->first();
    }
}
