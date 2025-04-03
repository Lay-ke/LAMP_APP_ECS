<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css') center/cover fixed;
            min-height: 100vh;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .page-container {
            background: linear-gradient(135deg, #6B46C1 0%, #2C5282 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .card-header {
            background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 1rem;
        }

        .table-header {
            background: #F3F4F6;
            position: sticky;
            top: 0;
        }

        .hover-effect {
            transition: all 0.3s ease;
        }

        .hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <h1 class="text-4xl font-bold text-white mb-8 text-center shadow-text">Data Management System</h1>
            
            <?php if (isset($_GET['success'])): ?>
            <div class="glass-effect bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 hover-effect" role="alert">
                <span class="block sm:inline">Operation completed successfully!</span>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
            <div class="glass-effect bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hover-effect" role="alert">
                <span class="block sm:inline">An error occurred. Please try again.</span>
            </div>
            <?php endif; ?>
            
            <!-- Input Form -->
            <div class="glass-effect rounded-lg mb-8 hover-effect">
                <div class="card-header">
                    <h2 class="text-xl font-semibold">Add New Record</h2>
                </div>
                <div class="p-6">
                    <form action="process.php" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                Title
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="title" 
                                   type="text" 
                                   name="title" 
                                   required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                Description
                            </label>
                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required></textarea>
                        </div>
                        <div>
                            <button class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transform transition hover:-translate-y-1" 
                                    type="submit">
                                Save Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="glass-effect rounded-lg mb-8 hover-effect">
                <div class="card-header">
                    <h2 class="text-xl font-semibold">Filter Records</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="searchInput">
                                Search
                            </label>
                            <input type="text" 
                                   id="searchInput" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Search in title or description...">
                        </div>
                        <div class="w-48">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="dateFilter">
                                Date Filter
                            </label>
                            <select id="dateFilter" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Records Display -->
            <div class="glass-effect rounded-lg hover-effect">
                <div class="card-header">
                    <h2 class="text-xl font-semibold">Existing Records</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto" id="recordsTable">
                            <thead>
                                <tr class="table-header">
                                    <th class="px-4 py-2 text-left cursor-pointer select-none" data-sort="title">
                                        Title 
                                        <span class="sort-icon ml-1">↕</span>
                                    </th>
                                    <th class="px-4 py-2 text-left cursor-pointer select-none" data-sort="description">
                                        Description
                                        <span class="sort-icon ml-1">↕</span>
                                    </th>
                                    <th class="px-4 py-2 text-left cursor-pointer select-none" data-sort="date">
                                        Date Created
                                        <span class="sort-icon ml-1">↕</span>
                                    </th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $records = getRecords($pdo);
                                foreach($records as $record): ?>
                                <tr class="border-b hover:bg-gray-50" data-record>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($record['title']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($record['description']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($record['date_created']); ?></td>
                                    <td class="px-4 py-2">
                                        <a href="edit.php?id=<?php echo $record['id']; ?>" 
                                           class="bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white font-bold py-1 px-3 rounded mr-2 inline-block transition transform hover:-translate-y-1">
                                            Edit
                                        </a>
                                        <a href="#" 
                                           onclick="deleteRecord(<?php echo $record['id']; ?>)"
                                           class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-bold py-1 px-3 rounded inline-block transition transform hover:-translate-y-1">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('recordsTable');
        const searchInput = document.getElementById('searchInput');
        const dateFilter = document.getElementById('dateFilter');
        let sortOrders = {};

        // Sorting functionality
        table.querySelectorAll('th[data-sort]').forEach(th => {
            th.addEventListener('click', () => {
                const column = th.dataset.sort;
                sortOrders[column] = sortOrders[column] === 'asc' ? 'desc' : 'asc';
                
                // Update sort icons
                table.querySelectorAll('.sort-icon').forEach(icon => icon.textContent = '↕');
                th.querySelector('.sort-icon').textContent = sortOrders[column] === 'asc' ? '↑' : '↓';

                sortTable(column, sortOrders[column]);
            });
        });

        // Search and filter functionality
        searchInput.addEventListener('input', filterRecords);
        dateFilter.addEventListener('change', filterRecords);

        function sortTable(column, order) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                let valueA = a.children[getColumnIndex(column)].textContent.trim();
                let valueB = b.children[getColumnIndex(column)].textContent.trim();
                
                if (column === 'date') {
                    valueA = new Date(valueA);
                    valueB = new Date(valueB);
                }
                
                if (valueA < valueB) return order === 'asc' ? -1 : 1;
                if (valueA > valueB) return order === 'asc' ? 1 : -1;
                return 0;
            });

            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }

        function getColumnIndex(column) {
            switch(column) {
                case 'title': return 0;
                case 'description': return 1;
                case 'date': return 2;
                default: return 0;
            }
        }

        function filterRecords() {
            const searchTerm = searchInput.value.toLowerCase();
            const dateValue = dateFilter.value;
            const rows = table.querySelectorAll('tr[data-record]');

            rows.forEach(row => {
                const title = row.children[0].textContent.toLowerCase();
                const description = row.children[1].textContent.toLowerCase();
                const date = new Date(row.children[2].textContent);
                
                let matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                let matchesDate = true;

                // Date filtering
                if (dateValue !== 'all') {
                    const today = new Date();
                    const diffTime = Math.abs(today - date);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    switch(dateValue) {
                        case 'today':
                            matchesDate = diffDays <= 1;
                            break;
                        case 'week':
                            matchesDate = diffDays <= 7;
                            break;
                        case 'month':
                            matchesDate = diffDays <= 30;
                            break;
                    }
                }

                row.style.display = matchesSearch && matchesDate ? '' : 'none';
            });
        }
    });

    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            window.location.href = 'delete.php?id=' + id;
        }
    }
    </script>
</body>
</html>