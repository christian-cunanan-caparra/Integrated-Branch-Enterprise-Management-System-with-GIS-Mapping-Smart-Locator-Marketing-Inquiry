<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Inquiry Form' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --primary: #0f3b5e;
            --primary-light: #1a5276;
            --primary-dark: #0a2647;
            --secondary: #e8b931;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light-bg: #f0f4f8;
            --card-shadow: 0 8px 32px rgba(15, 59, 94, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: var(--light-bg); 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1a202c;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--primary));
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header .logo-icon {
            font-size: 3rem;
            color: var(--primary);
            background: rgba(15, 59, 94, 0.08);
            padding: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 12px;
        }
        .form-header h2 {
            font-weight: 800;
            color: var(--primary-dark);
            font-size: 1.8rem;
        }
        .form-header p {
            color: #718096;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 0.9rem;
        }
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 59, 94, 0.08);
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--danger);
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            width: 100%;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(15, 59, 94, 0.25);
        }
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Suggested Branch - Hidden until after submit */
        .suggested-branch-card {
            background: linear-gradient(135deg, #f0f9ff, #e8f4fd);
            border: 2px solid var(--primary);
            border-radius: 14px;
            padding: 20px;
            margin-top: 20px;
            display: none;
            animation: slideDown 0.5s ease;
        }
        .suggested-branch-card.show {
            display: block;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .suggested-branch-card .branch-name {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }
        .suggested-branch-card .branch-detail {
            color: #4a5568;
            font-size: 0.9rem;
            margin-top: 4px;
        }
        .suggested-branch-card .branch-detail i {
            color: var(--primary);
            width: 20px;
        }
        .suggested-branch-card .nearby-badge {
            background: var(--secondary);
            color: var(--primary-dark);
            padding: 3px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        #map {
            height: 280px;
            border-radius: 12px;
            margin-top: 15px;
            display: none;
        }
        #map.show {
            display: block;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px 20px;
            border-radius: 12px;
            margin-top: 20px;
            display: none;
            animation: slideDown 0.5s ease;
        }
        .success-message.show {
            display: block;
        }

        .required-star {
            color: var(--danger);
            margin-left: 2px;
        }

        .invalid-feedback {
            display: none;
            font-size: 0.8rem;
            color: var(--danger);
            margin-top: 4px;
        }
        .form-control.is-invalid ~ .invalid-feedback,
        .form-select.is-invalid ~ .invalid-feedback {
            display: block;
        }

        .branch-details-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            border: 1px solid #e2e8f0;
        }
        .branch-details-box .detail-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 0;
            font-size: 0.9rem;
        }
        .branch-details-box .detail-row i {
            color: var(--primary);
            width: 20px;
        }

        .result-section {
            display: none;
        }
        .result-section.show {
            display: block;
        }

        @media (max-width: 768px) {
            .form-container { padding: 25px; }
            .form-header h2 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Header -->
        <div class="form-header">
            <span class="logo-icon"><i class="bi bi-chat-dots"></i></span>
            <h2>Inquiry Form</h2>
            <p>Fill in your details and we'll find the nearest branch for you</p>
        </div>

        <!-- Form -->
        <form id="inquiryForm" novalidate>
            <!-- CSRF Protection -->
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="full_name" class="form-label">
                        Full Name <span class="required-star">*</span>
                    </label>
                    <input type="text" class="form-control" id="full_name" name="full_name" 
                           placeholder="Enter your full name" required>
                    <div class="invalid-feedback">Please enter your full name</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_number" class="form-label">
                        Contact Number <span class="required-star">*</span>
                    </label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                           placeholder="Enter your contact number" required>
                    <div class="invalid-feedback">Please enter a valid contact number</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="province" class="form-label">
                        Province <span class="required-star">*</span>
                    </label>
                    <select class="form-select" id="province" name="province" required>
                        <option value="">Select Province</option>
                    </select>
                    <div class="invalid-feedback">Please select a province</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">
                        City / Municipality <span class="required-star">*</span>
                    </label>
                    <select class="form-select" id="city" name="city" required>
                        <option value="">Select Province first</option>
                    </select>
                    <input type="hidden" id="city_id" name="city_id">
                    <div class="invalid-feedback">Please select a city</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="Enter your email address (optional)">
                <div class="invalid-feedback">Please enter a valid email address</div>
            </div>

            <!-- Hidden fields for location -->
            <input type="hidden" id="latitude" name="latitude" value="">
            <input type="hidden" id="longitude" name="longitude" value="">
            <input type="hidden" id="suggested_branch_id" name="suggested_branch_id" value="">
            <input type="hidden" id="suggested_branch_name" name="suggested_branch_name" value="">

            <!-- Submit Button -->
            <button type="submit" class="btn-submit mt-3" id="submitBtn">
                <i class="bi bi-send"></i> Submit Inquiry
            </button>
        </form>

        <!-- Result Section - Shows after submission -->
        <div class="result-section" id="resultSection">
            <!-- Success Message with Branch Details -->
            <div class="success-message" id="successMessage">
                <i class="bi bi-check-circle-fill"></i>
                <strong>Thank you!</strong> Your inquiry has been submitted successfully.
                <br><small>We will contact you shortly.</small>
            </div>

            <!-- Suggested Branch (Shows after submit) -->
            <div class="suggested-branch-card" id="suggestedBranch">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="branch-name">
                            <i class="bi bi-geo-alt-fill" style="color: var(--primary);"></i>
                            Suggested Branch: <span id="suggestedBranchName">-</span>
                        </div>
                        <div class="branch-detail" id="suggestedBranchDetails"></div>
                    </div>
                    <span class="nearby-badge"><i class="bi bi-compass"></i> Nearby</span>
                </div>
            </div>

            <!-- Map -->
            <div id="map"></div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('inquiryForm');
            const submitBtn = document.getElementById('submitBtn');
            const suggestedBranch = document.getElementById('suggestedBranch');
            const suggestedBranchName = document.getElementById('suggestedBranchName');
            const suggestedBranchDetails = document.getElementById('suggestedBranchDetails');
            const mapDiv = document.getElementById('map');
            const successMessage = document.getElementById('successMessage');
            const resultSection = document.getElementById('resultSection');
            
            // Dropdown elements
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');
            const cityIdInput = document.getElementById('city_id');
            
            // Hidden fields for branch
            const suggestedBranchIdInput = document.getElementById('suggested_branch_id');
            const suggestedBranchNameInput = document.getElementById('suggested_branch_name');
            
            let map = null;
            let marker = null;
            let allCities = [];
            let selectedBranch = null;

            // ========== LOAD PROVINCES ==========
            function loadProvinces() {
                fetch('<?= base_url('inquiry/getProvinces') ?>')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.provinces && data.provinces.length > 0) {
                            provinceSelect.innerHTML = '<option value="">Select Province</option>';
                            data.provinces.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.province_name;
                                option.textContent = province.province_name;
                                provinceSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => console.error('Error loading provinces:', error));
            }

            // ========== LOAD CITIES ==========
            function loadCities(province) {
                citySelect.innerHTML = '<option value="">Loading...</option>';
                citySelect.disabled = true;
                cityIdInput.value = '';

                if (!province) {
                    citySelect.innerHTML = '<option value="">Select Province first</option>';
                    citySelect.disabled = true;
                    return;
                }

                fetch(`<?= base_url('inquiry/getCitiesByProvince') ?>?province=${encodeURIComponent(province)}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.disabled = false;
                        
                        if (data.success && data.cities && data.cities.length > 0) {
                            citySelect.innerHTML = '<option value="">Select City / Municipality</option>';
                            allCities = data.cities;
                            data.cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.city_name;
                                option.dataset.id = city.id;
                                option.dataset.lat = city.latitude || '';
                                option.dataset.lng = city.longitude || '';
                                option.textContent = city.city_name;
                                citySelect.appendChild(option);
                            });
                        } else {
                            citySelect.innerHTML = '<option value="">No cities found in this province</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                        citySelect.disabled = false;
                        citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    });
            }

            // ========== PROVINCE CHANGE ==========
            provinceSelect.addEventListener('change', function() {
                const province = this.value;
                
                citySelect.innerHTML = '<option value="">Select City / Municipality</option>';
                citySelect.disabled = true;
                cityIdInput.value = '';
                
                if (province) {
                    loadCities(province);
                } else {
                    citySelect.disabled = false;
                    citySelect.innerHTML = '<option value="">Select Province first</option>';
                }
            });

            // ========== CITY CHANGE - Store branch data silently ==========
            citySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const cityName = this.value;
                const cityId = selectedOption ? selectedOption.dataset.id : '';
                const lat = selectedOption ? selectedOption.dataset.lat : '';
                const lng = selectedOption ? selectedOption.dataset.lng : '';
                
                cityIdInput.value = cityId;
                
                if (lat && lng) {
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                }
                
                if (cityName) {
                    // Store branch data silently (without showing)
                    autoSuggestBranchSilent(cityName, provinceSelect.value);
                }
            });

            // ========== AUTO SUGGEST BRANCH (SILENT - no display) ==========
            function autoSuggestBranchSilent(city, province) {
                if (!city && !province) {
                    selectedBranch = null;
                    suggestedBranchIdInput.value = '';
                    suggestedBranchNameInput.value = '';
                    return;
                }

                const query = new URLSearchParams();
                if (city) query.append('city', city);
                if (province) query.append('province', province);

                fetch(`<?= base_url('inquiry/suggestBranch') ?>?${query.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.branches && data.branches.length > 0) {
                            const branch = data.branches[0];
                            selectedBranch = branch;
                            
                            // Store branch data in hidden inputs (for submission)
                            suggestedBranchIdInput.value = branch.id || '';
                            suggestedBranchNameInput.value = branch.branch_name || '';
                        } else {
                            selectedBranch = null;
                            suggestedBranchIdInput.value = '';
                            suggestedBranchNameInput.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            // ========== SHOW MAP & BRANCH AFTER SUBMISSION ==========
            function showResult(branch) {
                // Show result section
                resultSection.classList.add('show');
                
                // Show success message
                successMessage.classList.add('show');
                
                // Show suggested branch with details
                if (branch) {
                    suggestedBranchName.textContent = branch.branch_name || 'N/A';
                    
                    let details = '';
                    if (branch.address) {
                        details += `<div><i class="bi bi-geo-alt"></i> ${escapeHtml(branch.address)}</div>`;
                    }
                    if (branch.contact_number) {
                        details += `<div><i class="bi bi-phone"></i> ${escapeHtml(branch.contact_number)}</div>`;
                    }
                    if (branch.email) {
                        details += `<div><i class="bi bi-envelope"></i> ${escapeHtml(branch.email)}</div>`;
                    }
                    suggestedBranchDetails.innerHTML = details;
                    suggestedBranch.classList.add('show');
                    
                    // Show map with branch location
                    if (branch.latitude && branch.longitude) {
                        showMapAfterSubmit(branch.latitude, branch.longitude, branch.branch_name);
                    }
                }
                
                // Scroll to result section
                resultSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // ========== SHOW MAP AFTER SUBMISSION ==========
            function showMapAfterSubmit(lat, lng, branchName) {
                mapDiv.classList.add('show');
                
                setTimeout(function() {
                    if (!map) {
                        map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);
                    } else {
                        map.setView([parseFloat(lat), parseFloat(lng)], 13);
                    }

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    const branchIcon = L.divIcon({
                        html: '<div style="background: #0f3b5e; color: white; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 3px solid #e8b931; box-shadow: 0 2px 10px rgba(0,0,0,0.2);"><i class="bi bi-geo-alt-fill"></i></div>',
                        className: 'custom-marker',
                        iconSize: [32, 32]
                    });

                    marker = L.marker([parseFloat(lat), parseFloat(lng)], { icon: branchIcon })
                        .addTo(map)
                        .bindPopup(`<strong>${branchName}</strong><br>Recommended branch for you`)
                        .openPopup();

                    map.setView([parseFloat(lat), parseFloat(lng)], 14);
                    
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 300);
                }, 200);
            }

            // ========== FORM SUBMISSION ==========
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Check if form is valid
                let isValid = true;
                form.querySelectorAll('.form-control[required], .form-select[required]').forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Submitting...';

                const formData = new FormData(form);
                
                fetch('<?= base_url('inquiry/submit') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> Submit Inquiry';

                    if (data.success) {
                        // Show the result section with branch details and map
                        if (data.suggested_branch) {
                            showResult(data.suggested_branch);
                        } else {
                            // If no branch, just show success
                            resultSection.classList.add('show');
                            successMessage.classList.add('show');
                        }
                        
                        // Clear form
                        form.reset();
                        cityIdInput.value = '';
                        document.getElementById('latitude').value = '';
                        document.getElementById('longitude').value = '';
                        suggestedBranchIdInput.value = '';
                        suggestedBranchNameInput.value = '';
                        selectedBranch = null;
                        
                        // Reset dropdowns
                        provinceSelect.value = '';
                        citySelect.innerHTML = '<option value="">Select Province first</option>';
                        citySelect.disabled = true;
                    } else {
                        if (data.errors) {
                            const errors = data.errors;
                            for (const [field, message] of Object.entries(errors)) {
                                const input = document.getElementById(field);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    let feedback = input.nextElementSibling;
                                    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                                        feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback';
                                        input.parentNode.insertBefore(feedback, input.nextSibling);
                                    }
                                    feedback.textContent = message;
                                }
                            }
                            alert('Please fix the errors in the form.');
                        } else {
                            alert(data.message || 'An error occurred. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> Submit Inquiry';
                    alert('Network error: ' + error.message + '. Please try again.');
                });
            });

            // Real-time validation
            form.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
                input.addEventListener('blur', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Escape HTML helper
            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // ========== INITIALIZE ==========
            // Load provinces on page load
            loadProvinces();
            
            // Set initial city state
            citySelect.disabled = true;
            citySelect.innerHTML = '<option value="">Select Province first</option>';
        });
    </script>
</body>
</html>