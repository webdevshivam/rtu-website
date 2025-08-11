
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, Admin</span>
                    <a href="/admin/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Students</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $totalStudents ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Subjects</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $totalSubjects ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-file-video text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Content</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $totalContent ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                        <p class="text-2xl font-semibold text-gray-900"><?= $pendingApprovals ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="#" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg border border-blue-200 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-plus-circle text-blue-600 text-xl"></i>
                            <span class="ml-3 text-blue-800 font-medium">Add New Subject</span>
                        </div>
                    </a>

                    <a href="#" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg border border-green-200 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-user-plus text-green-600 text-xl"></i>
                            <span class="ml-3 text-green-800 font-medium">Manage Users</span>
                        </div>
                    </a>

                    <a href="#" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg border border-purple-200 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            <span class="ml-3 text-purple-800 font-medium">Review Content</span>
                        </div>
                    </a>

                    <a href="#" class="bg-orange-50 hover:bg-orange-100 p-4 rounded-lg border border-orange-200 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar text-orange-600 text-xl"></i>
                            <span class="ml-3 text-orange-800 font-medium">View Analytics</span>
                        </div>
                    </a>

                    <a href="#" class="bg-red-50 hover:bg-red-100 p-4 rounded-lg border border-red-200 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-cog text-red-600 text-xl"></i>
                            <span class="ml-3 text-red-800 font-medium">System Settings</span>
                        </div>
                    </a>

                    <a href="/" class="bg-gray-50 hover:bg-gray-100 p-4 rounded-lg border border-gray-200 transition-colors">
                        <div class="flex items-center">
                            <i class="fas fa-external-link-alt text-gray-600 text-xl"></i>
                            <span class="ml-3 text-gray-800 font-medium">View Student Portal</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
