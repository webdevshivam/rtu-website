
<?php

namespace App\Controllers;

use App\Models\SubjectModel;
use App\Models\TopicModel;
use App\Models\SubtopicModel;
use App\Models\ContentModel;
use App\Models\LastVisitedModel;
use App\Models\BookmarkModel;

class StudentController extends BaseController
{
    protected $subjectModel;
    protected $topicModel;
    protected $subtopicModel;
    protected $contentModel;
    protected $lastVisitedModel;
    
    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
        $this->topicModel = new TopicModel();
        $this->subtopicModel = new SubtopicModel();
        $this->contentModel = new ContentModel();
        $this->lastVisitedModel = new LastVisitedModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        // For demo purposes, assume user ID 1 is logged in
        $userId = 1;
        
        $data = [
            'title' => 'Student Dashboard - RTU LMS',
            'subjects' => $this->subjectModel->getSubjectsWithTopics(),
            'lastVisited' => $this->lastVisitedModel->getLastVisited($userId),
            'userId' => $userId
        ];

        return view('student/dashboard', $data);
    }

    public function getSubjectHierarchy()
    {
        $subjects = $this->subjectModel->getSubjectWithHierarchy();
        $result = [];
        
        foreach ($subjects as $subject) {
            $topics = $this->topicModel->getTopicsWithSubtopics($subject['id']);
            $subject['topics'] = $topics;
            $result[] = $subject;
        }
        
        return $this->response->setJSON($result);
    }

    public function getSubtopicContent($subtopicId)
    {
        $userId = 1; // For demo purposes
        
        $subtopic = $this->subtopicModel->getSubtopicWithContent($subtopicId);
        
        if (!$subtopic) {
            return $this->response->setJSON(['error' => 'Subtopic not found'], 404);
        }

        // Update last visited
        $this->lastVisitedModel->updateLastVisited($userId, $subtopicId);

        // Get topic and subject info
        $topic = $this->db->table('topics t')
                         ->select('t.*, s.name as subject_name, s.id as subject_id')
                         ->join('subjects s', 's.id = t.subject_id')
                         ->where('t.id', $subtopic['topic_id'])
                         ->get()->getRowArray();

        $subtopic['topic'] = $topic;

        return $this->response->setJSON($subtopic);
    }

    public function incrementView($contentId)
    {
        $this->contentModel->incrementViewCount($contentId);
        return $this->response->setJSON(['success' => true]);
    }

    public function search()
    {
        $query = $this->request->getGet('q');
        $subjectId = $this->request->getGet('subject_id');
        
        if (empty($query)) {
            return $this->response->setJSON([]);
        }

        $results = $this->contentModel->searchContent($query, $subjectId);
        return $this->response->setJSON($results);
    }

    public function downloadFile($contentId)
    {
        $content = $this->contentModel->find($contentId);
        
        if (!$content || !$content['file_url']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        $this->contentModel->incrementDownloadCount($contentId);
        
        $filePath = FCPATH . 'uploads/' . $content['file_url'];
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        return $this->response->download($filePath, null);
    }
}
