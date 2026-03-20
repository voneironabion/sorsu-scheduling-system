@extends('layouts.app')

@section('page-title', 'My Loads')

@section('content')
<div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0"> <i class="fa-solid fa-users-gear me-2"></i>
                    View your assigned courses, teaching units, and overall faculty workload</p>
            </div>
        </div>
        <div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
    <thead class="table-light align-middle">
        <tr>
            <th rowspan="2">Code</th>
            <th rowspan="2">Unit</th>
            <th rowspan="2">Subjects</th>
            <th colspan="2">Hours</th>
            <th rowspan="2">Classes</th>
        </tr>
        <tr>
            <th>Lec</th>
            <th>Lab</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>CS 312</strong></td>
            <td>3</td>
            <td class="text-center">Artificial Intelligence</td>
            <td>3</td>
            <td>0</td>
            <td><strong>BSCS 3-1</strong></td>
        </tr>
        <tr>
            <td><strong>CS 312</strong></td>
            <td>3</td>
            <td class="text-center">Artificial Intelligence</td>
            <td>3</td>
            <td>0</td>
            <td><strong>BSCS 3-2</strong></td>
        </tr>
        <tr>
            <td><strong>CHS 3</strong></td>
            <td>3</td>
            <td class="text-center">Digital Electronics</td>
            <td>2</td>
            <td>3</td>
            <td><strong>BTVTEd CHS 2</strong></td>
        </tr>
        <tr>
            <td><strong>IT 211</strong></td>
            <td>3</td>
            <td class="text-center">Object-Oriented Programming</td>
            <td>2</td>
            <td>3</td>
            <td><strong>BSIT 2-4</strong></td>
        </tr>
    </tbody>
    <tfoot class="table-group-divider fw-bold">
            <tr>
                <td class="text-end text-muted pe-3">Total Units:</td>
                <td>12</td>
                <td></td>
                <td>10</td>
                <td>6</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" class="text-end pe-4">Total Load Hours:</td>
                <td colspan="2" class="text-start ps-4 text-danger fs-5">16</td>
            </tr>
        </tfoot>
</table>
        </div>
    </div>
</div>

    <style>
        .btn-maroon {
            background-color: #660000;
            border-color: #660000;
            color: white;
        }

        .btn-maroon:hover {
            background-color: #550000;
            border-color: #550000;
            color: white;
        }

        .btn-maroon:focus {
            background-color: #550000;
            border-color: #550000;
            box-shadow: 0 0 0 0.2rem rgba(102, 0, 0, 0.25);
            color: white;
        }
    </style>

    <script>
        const searchInput = document.getElementById('search');
        const perPageSelect = document.getElementById('departmentPerPageSelect');
        const tableBody = document.getElementById('departments-table-body');
        const paginationContainer = document.getElementById('pagination-container');
        const spinner = document.getElementById('filter-spinner');

        // Debounce function for search
        function debounce(func, delay) {
            let timeoutId;
            return function(...args) {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // Fetch filtered departments
        function fetchDepartments(resetToFirstPage = false) {
            const search = searchInput.value;
            const perPage = perPageSelect.value;
            const page = resetToFirstPage ? 1 : (new URLSearchParams(window.location.search).get('page') || 1);

            spinner.style.display = 'block';

            fetch(`/admin/departments?search=${encodeURIComponent(search)}&per_page=${perPage}&page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        tableBody.innerHTML = data.html;
                        paginationContainer.innerHTML = data.pagination;
                        window.history.pushState({}, '',
                            `/admin/departments?search=${encodeURIComponent(search)}&per_page=${perPage}&page=${page}`
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load departments');
                })
                .finally(() => {
                    spinner.style.display = 'none';
                    attachTableRowListeners();
                });
        }

        // Search input listener
        searchInput.addEventListener('input', debounce(fetchDepartments, 500));

        // Per page select listener
        perPageSelect.addEventListener('change', fetchDepartments);

        // Clear filter button listener
        document.getElementById('clearFilterBtn').addEventListener('click', function() {
            searchInput.value = '';
            fetchDepartments(true);
        });

        // Attach listeners to table rows
        function attachTableRowListeners() {
            // Edit button listeners
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-department-btn')) {
                    const btn = e.target.closest('.edit-department-btn');
                    const deptId = btn.getAttribute('data-department-id');
                    const deptCode = btn.getAttribute('data-department-code');
                    const deptName = btn.getAttribute('data-department-name');

                    document.getElementById('edit_department_id').value = deptId;
                    document.getElementById('edit_department_code').value = deptCode;
                    document.getElementById('edit_department_name').value = deptName;

                    document.getElementById('editDepartmentForm').classList.remove('was-validated');

                    const editModal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
                    editModal.show();
                }

                // Delete button listeners
                if (e.target.closest('.delete-department-btn')) {
                    const btn = e.target.closest('.delete-department-btn');
                    const deptId = btn.getAttribute('data-department-id');
                    const deptName = btn.getAttribute('data-department-name');

                    document.getElementById('delete_department_id').value = deptId;
                    document.getElementById('delete_department_name').textContent = deptName;

                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteDepartmentModal'));
                    deleteModal.show();
                }
            });
        }

        // Pagination link handling
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('a').getAttribute('href');
                const page = new URLSearchParams(new URL(url, window.location.origin).search).get('page');
                const search = searchInput.value;
                const perPage = perPageSelect.value;

                spinner.style.display = 'block';

                fetch(`/admin/departments?search=${encodeURIComponent(search)}&per_page=${perPage}&page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            tableBody.innerHTML = data.html;
                            paginationContainer.innerHTML = data.pagination;
                            window.history.pushState({}, '',
                                `/admin/departments?search=${encodeURIComponent(search)}&per_page=${perPage}&page=${page}`
                            );
                        }
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        spinner.style.display = 'none';
                        attachTableRowListeners();
                    });
            }
        });

        attachTableRowListeners();
    </script>
</div>

@endsection