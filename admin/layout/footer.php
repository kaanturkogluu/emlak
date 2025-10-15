        </div>
    </div>
    
    <script>
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            toggleMobileSidebar();
        });
        
        // Auto-hide sidebar on mobile when clicking menu item
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMobileSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('mobile-open');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });
        
        // İlçe yükleme fonksiyonu
        function loadDistricts(cityId) {
            const districtSelect = document.getElementById('district_id');
            
            if (!districtSelect) {
                return;
            }
            
            if (cityId) {
                const url = `../api/districts.php?city_id=${cityId}`;
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">Seçiniz</option>';
                        data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading districts:', error);
                    });
            } else {
                districtSelect.innerHTML = '<option value="">Önce şehir seçiniz</option>';
            }
        }
        
        // Belde yükleme fonksiyonu
        function loadNeighborhoods(districtId) {
            const neighborhoodSelect = document.getElementById('neighborhood_id');
            
            if (districtId) {
                fetch(`../api/neighborhoods.php?district_id=${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        neighborhoodSelect.innerHTML = '<option value="">Seçiniz (İsteğe bağlı)</option>';
                        data.forEach(neighborhood => {
                            const option = document.createElement('option');
                            option.value = neighborhood.id;
                            option.textContent = neighborhood.name;
                            neighborhoodSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                neighborhoodSelect.innerHTML = '<option value="">Önce ilçe seçiniz</option>';
            }
        }
        
        // Resim önizleme fonksiyonu
        function previewImages(input) {
            const container = document.getElementById('imagePreviewContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'image-preview';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-btn" onclick="removeImage(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        container.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
        
        // Resim kaldırma fonksiyonu
        function removeImage(index) {
            const input = document.getElementById('images');
            if (!input) return;
            
            const dt = new DataTransfer();
            
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            previewImages(input);
        }
        
        // Drag and drop fonksiyonları
        document.addEventListener('DOMContentLoaded', function() {
            const fileUploadContainers = document.querySelectorAll('.file-upload-container');
            
            fileUploadContainers.forEach(container => {
                container.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });
                
                container.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });
                
                container.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                    
                    const files = e.dataTransfer.files;
                    const input = this.querySelector('input[type="file"]');
                    if (input) {
                        input.files = files;
                        if (typeof previewImages === 'function') {
                            previewImages(input);
                        }
                    }
                });
            });
        });
        
        // Form validasyonu
        function validateForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return false;
            
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    field.style.borderColor = '#e9ecef';
                }
            });
            
            return isValid;
        }
        
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
            
        });
    </script>
</body>
</html>
