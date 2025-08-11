<?php

namespace App\Models;

use CodeIgniter\Model;

class TopicModel extends Model
{
    protected $table = 'topics';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'subject_id',
        'name',
        'description',
        'order_index',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getTopicsBySubject($subjectId)
    {
        return $this->where('subject_id', $subjectId)
            ->where('is_active', 1)
            ->orderBy('order_index', 'ASC')
            ->findAll();
    }

    public function getTopicsWithSubtopics($subjectId)
    {
        $topics = $this->getTopicsBySubject($subjectId);
        $subtopicModel = new SubtopicModel();

        foreach ($topics as &$topic) {
            $topic['subtopics'] = $subtopicModel->getSubtopicsByTopic($topic['id']);
        }

        return $topics;
    }
}
