<?php

namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $table = 'content';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'subtopic_id',
        'title',
        'description',
        'content_type',
        'file_url',
        'youtube_url',
        'file_size',
        'duration',
        'thumbnail',
        'download_count',
        'view_count',
        'created_by',
        'is_approved',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getContentBySubtopic($subtopicId)
    {
        return $this->where('subtopic_id', $subtopicId)
            ->where('is_approved', 1)
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function incrementViewCount($contentId)
    {
        return $this->set('view_count', 'view_count + 1', false)
            ->where('id', $contentId)
            ->update();
    }

    public function incrementDownloadCount($contentId)
    {
        return $this->set('download_count', 'download_count + 1', false)
            ->where('id', $contentId)
            ->update();
    }

    public function searchContent($query, $subjectId = null)
    {
        $builder = $this->select('content.*, subtopics.name as subtopic_name, topics.name as topic_name, subjects.name as subject_name')
            ->join('subtopics', 'subtopics.id = content.subtopic_id')
            ->join('topics', 'topics.id = subtopics.topic_id')
            ->join('subjects', 'subjects.id = topics.subject_id')
            ->where('content.is_approved', 1)
            ->where('content.is_active', 1)
            ->groupStart()
            ->like('content.title', $query)
            ->orLike('content.description', $query)
            ->orLike('subtopics.name', $query)
            ->orLike('topics.name', $query)
            ->groupEnd();

        if ($subjectId) {
            $builder->where('subjects.id', $subjectId);
        }

        return $builder->orderBy('content.created_at', 'DESC')->findAll();
    }
}
