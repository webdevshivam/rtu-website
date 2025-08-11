
<?php

namespace App\Models;

use CodeIgniter\Model;

class BookmarkModel extends Model
{
    protected $table = 'bookmarks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['student_id', 'content_id'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    public function getUserBookmarks($userId)
    {
        return $this->select('bookmarks.*, content.title, content.content_type')
                   ->join('content', 'content.id = bookmarks.content_id')
                   ->where('bookmarks.student_id', $userId)
                   ->orderBy('bookmarks.created_at', 'DESC')
                   ->findAll();
    }

    public function toggleBookmark($userId, $contentId)
    {
        $existing = $this->where('student_id', $userId)
                        ->where('content_id', $contentId)
                        ->first();

        if ($existing) {
            return $this->delete($existing['id']);
        } else {
            return $this->insert([
                'student_id' => $userId,
                'content_id' => $contentId
            ]);
        }
    }
}
