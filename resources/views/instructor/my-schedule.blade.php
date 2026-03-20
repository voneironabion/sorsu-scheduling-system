@extends('layouts.app')

@section('page-title', 'My Schedule')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0"> <i class="fa-solid fa-users-gear me-2"></i>
                    Manage your schedule and view class details</p>
            </div>
        </div>

        <!-- Schedule Filter Section -->
        <div class="card shadow-sm mb-3 mb-md-4">
            <div class="card-header bg-maroon text-white py-2 py-md-3">
                <h5 class="mb-0 h6 h5-md">
                    <i class="fa-solid fa-filter me-2"></i>View Class Schedule
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <form id="scheduleFilterForm" novalidate>
                    @csrf
                    <div class="row g-2 g-md-3">
                        <!-- Academic Year -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl">
                            <label for="filterAcademicYear" class="form-label small fw-semibold">
                                Academic Year <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm form-select-md-normal" id="filterAcademicYear"
                                name="academic_year" required>
                                <option value="" selected>Select Year</option>
                                <option value="2024-2025">2024–2025</option>
                                <option value="2025-2026">2025–2026</option>
                                <option value="2026-2027">2026–2027</option>
                            </select>
                            <div class="invalid-feedback small">Please select an academic year.</div>
                        </div>

                        <!-- Semester -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl">
                            <label for="filterSemester" class="form-label small fw-semibold">
                                Semester <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm form-select-md-normal" id="filterSemester"
                                name="semester" required>
                                <option value="" selected>Select Semester</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                            </select>
                            <div class="invalid-feedback small">Please select a semester.</div>
                        </div>

                        <!-- Program -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl">
                            <label for="filterProgram" class="form-label small fw-semibold">
                                Program <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm form-select-md-normal" id="filterProgram"
                                name="program" required>
                                <option value="" selected>Select Program</option>
                                <option value="BSCS">BSCS</option>
                                <option value="BSIT">BSIT</option>
                                <option value="BSIS">BSIS</option>
                                <option value="BTVTED">BTVTED</option>
                            </select>
                            <div class="invalid-feedback small">Please select a program.</div>
                        </div>

                        <!-- Year Level -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl">
                            <label for="filterYearLevel" class="form-label small fw-semibold">
                                Year Level <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm form-select-md-normal" id="filterYearLevel"
                                name="year_level" required>
                                <option value="" selected>Select Year</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                            <div class="invalid-feedback small">Please select a year level.</div>
                        </div>

                        <!-- Block / Section -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl">
                            <label for="filterBlock" class="form-label small fw-semibold">
                                Block / Section <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm form-select-md-normal" id="filterBlock"
                                name="block_section" required>
                                <option value="" selected>Select Block</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <div class="invalid-feedback small">Please select a block.</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-3 mt-md-4">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm btn-md-normal order-2 order-sm-1 w-100 w-sm-auto"
                                    id="resetFiltersBtn">
                                    <i class="fa-solid fa-rotate-left me-2"></i>Reset Filters
                                </button>
                                <button type="submit"
                                    class="btn btn-maroon btn-sm btn-md-normal order-1 order-sm-2 w-100 w-sm-auto"
                                    id="viewScheduleBtn">
                                    <i class="fa-solid fa-eye me-2"></i>View Schedule
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

            <!-- Schedule Display Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-light py-2 py-md-3">
                    <div
                        class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                        <h5 class="mb-0 h6 h5-md">
                            <i class="fa-solid fa-calendar-week me-2 text-maroon"></i>My Class Schedule
                        </h5>
                        <div id="scheduleInfo" class="text-muted small d-none">
                            <span id="displayScheduleDetails"></span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2 p-md-3 p-lg-4">

                    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle mb-0" style="min-width: 800px;">
            <thead class="table-light align-middle">
                <tr>
                    <th style="width: 10%;">Time</th>
                    <th style="width: 15%;">Monday</th>
                    <th style="width: 15%;">Tuesday</th>
                    <th style="width: 15%;">Wednesday</th>
                    <th style="width: 15%;">Thursday</th>
                    <th style="width: 15%;">Friday</th>
                    <th style="width: 15%;">Saturday</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-bold text-muted">7:00-8:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td rowspan="3" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSIT 2-4</div>
                        <div class="text-dark">IT 211</div>
                        <div class="small text-muted">CCB Lab D</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">8:00-9:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSECE 4A</div>
                        <div class="text-dark">Capstone Project 1</div>
                        <div class="small text-muted">Admin Bldg</div>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">9:00-10:00</td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSCS 3-1</div>
                        <div class="text-dark">CS 312</div>
                        <div class="small text-muted">CCB Rm.5</div>
                    </td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSIT 2-4</div>
                        <div class="text-dark">IT 211</div>
                        <div class="small text-muted">CCB Rm. 4</div>
                    </td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSCS 3-1</div>
                        <div class="text-dark">CS 312</div>
                        <div class="small text-muted">CCB Rm.5</div>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">10:00-11:00</td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSCS 3-2</div>
                        <div class="text-dark">CS 312</div>
                        <div class="small text-muted">CCB Rm.5</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">11:00-12:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr class="table-warning">
                    <td class="fw-bold text-muted">12:00-1:00</td>
                    <td colspan="6" class="fw-bold text-center text-muted" style="letter-spacing: 2px;">LUNCH BREAK</td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">1:00-2:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">2:00-3:00</td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BTVTEd CHS 2</div>
                        <div class="text-dark">CHS 3</div>
                        <div class="small text-muted">CCB Rm.6</div>
                    </td>
                    <td></td>
                    <td rowspan="2" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BTVTEd CHS 2</div>
                        <div class="text-dark">CHS 3</div>
                        <div class="small text-muted">CCB Rm.6</div>
                    </td>
                    <td rowspan="3" class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BTVTEd CHS 2</div>
                        <div class="text-dark">CHS 3</div>
                        <div class="small text-muted">CHS Lab.</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">3:00-4:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">4:00-5:00</td>
                    <td></td>
                    <td class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSCS 3-1</div>
                        <div class="text-dark">CS 312</div>
                        <div class="small text-muted">CCB Rm.5</div>
                    </td>
                    <td class="bg-light border-secondary border-1">
                        <div class="fw-bold text-dark">BSECE 4A</div>
                        <div class="text-dark">Capstone Project 1</div>
                        <div class="small text-muted">Online Class</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">5:00-6:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td class="fw-bold text-muted">6:00-7:00</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
                    <!-- Mobile: Card View (Alternative) -->
                    <div id="scheduleMobileView" class="d-none d-md-none mt-3">
                        <div class="schedule-mobile-card text-center">
                            <div class="schedule-mobile-icon">
                                <i class="fa-solid fa-calendar-week"></i>
                            </div>
                            <p class="text-muted mb-1">Schedule preview</p>
                            <p class="small text-muted mb-0">Apply filters to load a mobile-friendly list view.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Frontend Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('scheduleFilterForm');
            const emptyState = document.getElementById('emptyState');
            const scheduleTableView = document.getElementById('scheduleTableView');
            const scheduleMobileView = document.getElementById('scheduleMobileView');
            const scheduleInfo = document.getElementById('scheduleInfo');
            const displayScheduleDetails = document.getElementById('displayScheduleDetails');
            const resetFiltersBtn = document.getElementById('resetFiltersBtn');
            const viewScheduleBtn = document.getElementById('viewScheduleBtn');

            // View Schedule
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate
                if (!filterForm.checkValidity()) {
                    filterForm.classList.add('was-validated');
                    return;
                }

                // Get selected values
                const academicYear = document.getElementById('filterAcademicYear').options[document
                    .getElementById('filterAcademicYear').selectedIndex].text;
                const semester = document.getElementById('filterSemester').options[document.getElementById(
                    'filterSemester').selectedIndex].text;
                const program = document.getElementById('filterProgram').value;
                const yearLevel = document.getElementById('filterYearLevel').options[document
                    .getElementById('filterYearLevel').selectedIndex].text;
                const block = document.getElementById('filterBlock').value;

                // Update schedule info
                displayScheduleDetails.textContent =
                    `${program} ${yearLevel} - ${block} | ${academicYear} (${semester})`;

                // Show schedule, hide empty state
                emptyState.classList.add('d-none');
                scheduleTableView.classList.remove('d-none');
                scheduleMobileView.classList.remove('d-none');
                scheduleInfo.classList.remove('d-none');

                // Scroll to schedule
                scheduleTableView.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            });

            // Reset Filters
            resetFiltersBtn.addEventListener('click', function() {
                filterForm.reset();
                filterForm.classList.remove('was-validated');
                emptyState.classList.remove('d-none');
                scheduleTableView.classList.add('d-none');
                scheduleMobileView.classList.add('d-none');
                scheduleInfo.classList.add('d-none');
            });
        });
    </script>
    </div>
    
@endsection
