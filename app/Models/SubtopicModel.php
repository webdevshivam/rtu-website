<?php

namespace App\Models;

use CodeIgniter\Model;

class SubtopicModel extends Model
{
    protected $table = 'subtopics';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'topic_id',
        'name',
        'description',
        'order_index',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getSubtopicsByTopic($topicId)
    {
        return $this->where('topic_id', $topicId)
            ->where('is_active', 1)
            ->orderBy('order_index', 'ASC')
            ->findAll();
    }

    public function getSubtopicWithContent($subtopicId)
    {
        $subtopic = $this->find($subtopicId);
        if ($subtopic) {
            $contentModel = new ContentModel();
            $subtopic['content'] = $contentModel->getContentBySubtopic($subtopicId);
        }
        return $subtopic;
    }
}
