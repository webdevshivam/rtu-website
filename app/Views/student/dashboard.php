
<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: false, sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden" x-data="studentDashboard()">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-30 w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
             :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">RTU LMS</h1>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Search Box -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <input type="text" 
                           x-model="searchQuery"
                           @input="debounceSearch"
                           placeholder="Search topics, notes..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <!-- Search Results -->
                <div x-show="searchResults.length > 0" 
                     x-transition
                     class="absolute left-4 right-4 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50">
                    <template x-for="result in searchResults" :key="result.id">
                        <div @click="loadSearchResult(result)" 
                             class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0">
                            <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="result.title"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="`${result.subject_name} > ${result.topic_name} > ${result.subtopic_name}`"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto p-4">
                <nav class="space-y-2">
                    <template x-for="subject in subjects" :key="subject.id">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <!-- Subject Header -->
                            <button @click="toggleSubject(subject.id)"
                                    class="w-full px-4 py-3 text-left bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                                    :style="`border-left: 4px solid ${subject.color || '#3B82F6'}`">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white" x-text="subject.name"></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400" x-text="subject.semester_name"></div>
                                    </div>
                                    <i class="fas fa-chevron-down transform transition-transform duration-200"
                                       :class="{ 'rotate-180': expandedSubjects.includes(subject.id) }"></i>
                                </div>
                            </button>

                            <!-- Topics -->
                            <div x-show="expandedSubjects.includes(subject.id)" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="bg-white dark:bg-gray-800">
                                <template x-for="topic in subject.topics || []" :key="topic.id">
                                    <div>
                                        <!-- Topic Header -->
                                        <button @click="toggleTopic(topic.id)"
                                                class="w-full px-6 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-100 dark:border-gray-600 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="topic.name"></span>
                                                <i class="fas fa-chevron-down transform transition-transform duration-200 text-xs"
                                                   :class="{ 'rotate-180': expandedTopics.includes(topic.id) }"></i>
                                            </div>
                                        </button>

                                        <!-- Subtopics -->
                                        <div x-show="expandedTopics.includes(topic.id)" 
                                             x-transition
                                             class="bg-gray-25 dark:bg-gray-750">
                                            <template x-for="subtopic in topic.subtopics || []" :key="subtopic.id">
                                                <button @click="loadSubtopic(subtopic.id)"
                                                        class="w-full px-8 py-2 text-left text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-primary-600 dark:hover:text-primary-400 transition-colors border-t border-gray-50 dark:border-gray-600"
                                                        :class="{ 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 border-l-4 border-l-primary-500': activeSubtopicId === subtopic.id }">
                                                    <i class="fas fa-play-circle mr-2 text-xs"></i>
                                                    <span x-text="subtopic.name"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </nav>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <button @click="darkMode = !darkMode" 
                        class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="currentTitle"></h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600 dark:text-gray-400">Welcome, Student</span>
                        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Welcome Screen -->
                <div x-show="!currentContent" class="text-center py-12">
                    <i class="fas fa-book-reader text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Welcome to RTU LMS</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Select a topic from the sidebar to start learning</p>
                    
                    <?php if ($lastVisited): ?>
                    <button @click="loadSubtopic(<?= $lastVisited['subtopic_id'] ?>)" 
                            class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-play mr-2"></i>
                        Continue: <?= esc($lastVisited['subtopic_name']) ?>
                    </button>
                    <?php endif; ?>
                </div>

                <!-- Content Display -->
                <div x-show="currentContent" x-transition>
                    <!-- Breadcrumb -->
                    <nav class="mb-6" x-show="currentContent">
                        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <span x-text="currentContent?.topic?.subject_name"></span>
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span x-text="currentContent?.topic?.name"></span>
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span class="text-gray-900 dark:text-white" x-text="currentContent?.name"></span>
                        </div>
                    </nav>

                    <!-- Video Player -->
                    <div x-show="currentContent?.content?.[0]?.content_type === 'video'" class="mb-6">
                        <div class="aspect-video bg-black rounded-lg overflow-hidden">
                            <template x-for="content in currentContent?.content || []">
                                <div x-show="content.content_type === 'video'">
                                    <!-- YouTube Video -->
                                    <iframe x-show="content.youtube_url"
                                            :src="getYouTubeEmbedUrl(content.youtube_url)"
                                            class="w-full h-full"
                                            frameborder="0"
                                            allowfullscreen>
                                    </iframe>
                                    
                                    <!-- Local Video -->
                                    <video x-show="!content.youtube_url && content.file_url"
                                           :src="`/uploads/${content.file_url}`"
                                           class="w-full h-full"
                                           controls>
                                    </video>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Content Title and Description -->
                    <div class="mb-6" x-show="currentContent">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3" x-text="currentContent?.name"></h1>
                        <p class="text-gray-600 dark:text-gray-400" x-text="currentContent?.description"></p>
                    </div>

                    <!-- Resources Section -->
                    <div x-show="currentContent?.content?.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Notes and Files -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-file-alt mr-2"></i>
                                Notes & Resources
                            </h3>
                            
                            <div class="space-y-3">
                                <template x-for="content in currentContent?.content || []" :key="content.id">
                                    <div x-show="['pdf', 'note'].includes(content.content_type)" 
                                         class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-file-pdf text-red-500" x-show="content.content_type === 'pdf'"></i>
                                            <i class="fas fa-sticky-note text-yellow-500" x-show="content.content_type === 'note'"></i>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white" x-text="content.title"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="formatFileSize(content.file_size)"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-400" x-text="`${content.download_count || 0} downloads`"></span>
                                            <a :href="`/student/download/${content.id}`" 
                                               class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                <i class="fas fa-download mr-1"></i>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Quick Info
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-400">Subject:</span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="currentContent?.topic?.subject_name"></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-400">Topic:</span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="currentContent?.topic?.name"></span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 dark:text-gray-400">Resources:</span>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="`${currentContent?.content?.length || 0} items`"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function studentDashboard() {
            return {
                subjects: [],
                expandedSubjects: [],
                expandedTopics: [],
                currentContent: null,
                currentTitle: 'Student Dashboard',
                activeSubtopicId: null,
                searchQuery: '',
                searchResults: [],
                searchTimeout: null,

                async init() {
                    await this.loadSubjects();
                    
                    // Auto-expand last visited if available
                    <?php if ($lastVisited): ?>
                    const lastVisitedSubjectId = <?= $lastVisited['subject_id'] ?>;
                    this.expandedSubjects.push(lastVisitedSubjectId);
                    
                    // Find and expand the topic containing the last visited subtopic
                    setTimeout(() => {
                        const subject = this.subjects.find(s => s.id === lastVisitedSubjectId);
                        if (subject && subject.topics) {
                            const topic = subject.topics.find(t => 
                                t.subtopics && t.subtopics.some(st => st.id === <?= $lastVisited['subtopic_id'] ?>)
                            );
                            if (topic) {
                                this.expandedTopics.push(topic.id);
                            }
                        }
                    }, 100);
                    <?php endif; ?>
                },

                async loadSubjects() {
                    try {
                        const response = await fetch('/student/hierarchy');
                        this.subjects = await response.json();
                    } catch (error) {
                        console.error('Error loading subjects:', error);
                    }
                },

                toggleSubject(subjectId) {
                    const index = this.expandedSubjects.indexOf(subjectId);
                    if (index > -1) {
                        this.expandedSubjects.splice(index, 1);
                        // Also close all topics in this subject
                        const subject = this.subjects.find(s => s.id === subjectId);
                        if (subject && subject.topics) {
                            subject.topics.forEach(topic => {
                                const topicIndex = this.expandedTopics.indexOf(topic.id);
                                if (topicIndex > -1) {
                                    this.expandedTopics.splice(topicIndex, 1);
                                }
                            });
                        }
                    } else {
                        this.expandedSubjects.push(subjectId);
                        this.loadTopicsForSubject(subjectId);
                    }
                },

                toggleTopic(topicId) {
                    const index = this.expandedTopics.indexOf(topicId);
                    if (index > -1) {
                        this.expandedTopics.splice(index, 1);
                    } else {
                        this.expandedTopics.push(topicId);
                    }
                },

                async loadTopicsForSubject(subjectId) {
                    const subject = this.subjects.find(s => s.id === subjectId);
                    if (subject && !subject.topics) {
                        try {
                            const response = await fetch(`/student/hierarchy`);
                            const allSubjects = await response.json();
                            const fullSubject = allSubjects.find(s => s.id === subjectId);
                            if (fullSubject) {
                                subject.topics = fullSubject.topics;
                            }
                        } catch (error) {
                            console.error('Error loading topics:', error);
                        }
                    }
                },

                async loadSubtopic(subtopicId) {
                    this.activeSubtopicId = subtopicId;
                    try {
                        const response = await fetch(`/student/subtopic/${subtopicId}`);
                        this.currentContent = await response.json();
                        this.currentTitle = this.currentContent.name;
                        
                        // Increment view count for video content
                        if (this.currentContent.content) {
                            this.currentContent.content.forEach(content => {
                                if (content.content_type === 'video') {
                                    fetch(`/student/content/${content.id}/view`, { method: 'POST' });
                                }
                            });
                        }
                        
                        // Close mobile sidebar
                        this.sidebarOpen = false;
                    } catch (error) {
                        console.error('Error loading subtopic:', error);
                    }
                },

                debounceSearch() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.performSearch();
                    }, 300);
                },

                async performSearch() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/student/search?q=${encodeURIComponent(this.searchQuery)}`);
                        this.searchResults = await response.json();
                    } catch (error) {
                        console.error('Search error:', error);
                    }
                },

                loadSearchResult(result) {
                    this.searchResults = [];
                    this.searchQuery = '';
                    this.loadSubtopic(result.subtopic_id);
                },

                getYouTubeEmbedUrl(url) {
                    if (!url) return '';
                    const videoId = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/);
                    return videoId ? `https://www.youtube.com/embed/${videoId[1]}` : url;
                },

                formatFileSize(bytes) {
                    if (!bytes) return '';
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(1024));
                    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
                }
            }
        }
    </script>
</body>
</html>
