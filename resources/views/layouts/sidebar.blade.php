<div id="sidebar" class="col-md-2 vh-100 bg-primary d-flex flex-column p-3 sidebar">
    <h4 id="toggleSidebar" class="mb-4 text-white d-flex align-items-center sidebar-title" style="cursor: pointer;">
        <i class="material-icons me-2">menu</i> <span class="sidebar-text">Main Menu</span>
    </h4>
    
    <a href="{{ route('home') }}" class="nav-link mb-3 text-light d-flex align-items-center sidebar-link">
        <i class="material-icons me-2">home</i> <span class="sidebar-text">Home</span>
    </a>
    <a href="{{ route('karyawan.datakar') }}" class="nav-link mb-3 text-light d-flex align-items-center sidebar-link">
        <i class="material-icons me-2">group</i> <span class="sidebar-text">Data Karyawan</span>
    </a>
    <a href="{{ route('tamu.datatamu') }}" class="nav-link mb-3 text-light d-flex align-items-center sidebar-link">
        <i class="material-icons me-2">person_add</i> <span class="sidebar-text">Data Tamu</span>
    </a>
    <a href="{{ route('admin.presensi') }}" class="nav-link mb-3 text-light d-flex align-items-center sidebar-link">
        <i class="material-icons me-2">fingerprint</i> <span class="sidebar-text">Presensi</span>
    </a>
    <a href="{{ route('acara') }}" class="nav-link mb-3 text-light d-flex align-items-center sidebar-link">
        <i class="material-icons me-2">event_available</i> <span class="sidebar-text">Acara</span>
    </a>
</div>

<style>
/* Styling sidebar */
main {
    flex-grow: 1;
    padding-top: 20px;
    margin-left: 225px;
    display: flex;
    justify-content: center;            
    transition: margin-left 0.3s ease;
}

.sidebar {
    position: fixed;
    height: 100vh;
    width: 225px;
    transition: width 0.3s ease;
    overflow: hidden;
}

.sidebar.small {
    width: 70px;
}

.sidebar.small .sidebar-text {
    display: none;
}

.sidebar.small .sidebar-link {
    justify-content: center;
}

.sidebar.small .sidebar-title {
    justify-content: center;
}

/* Menyesuaikan margin main saat sidebar mengecil */
.sidebar.small + main {
    margin-left: 70px;
}

/* Styling links */
.sidebar-link {
    color: #d1c4e9;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.sidebar-link:hover {
    background-color: #0056b3;
    color: #fff;
}

.sidebar-link.active {
    background-color: #00408d;
    color: #fff;
}

.sidebar-link.active i {
    color: #fff;
}

/* Media Query untuk layar kecil */
@media (max-width: 768px) {
    .sidebar {
        width: 70px;
    }
    
    .sidebar .sidebar-text {
        display: none;
    }
    
    .sidebar .sidebar-link {
        justify-content: center;
    }
    
    .sidebar .sidebar-title {
        justify-content: center;
    }
    
    main {
        margin-left: 70px;
    }
    
    /* Ketika sidebar diperbesar pada layar kecil */
    .sidebar.expanded {
        width: 225px;
    }
    
    .sidebar.expanded .sidebar-text {
        display: inline;
    }
    
    .sidebar.expanded .sidebar-link {
        justify-content: flex-start;
    }
    
    .sidebar.expanded .sidebar-title {
        justify-content: flex-start;
    }
    
    .sidebar.expanded + main {
        margin-left: 225px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebar = document.getElementById('toggleSidebar');
    const mainContent = document.querySelector('main');
    
    // Fungsi untuk mengecek ukuran layar dan mengatur sidebar
    function checkScreenSize() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('small');
            sidebar.classList.remove('expanded');
            mainContent.style.marginLeft = '70px';
        } else {
            sidebar.classList.remove('expanded');
            mainContent.style.marginLeft = sidebar.classList.contains('small') ? '70px' : '225px';
        }
    }
    
    // Jalankan pengecekan saat halaman dimuat
    checkScreenSize();
    
    // Jalankan pengecekan saat ukuran window berubah
    window.addEventListener('resize', checkScreenSize);
    
    // Toggle sidebar
    toggleSidebar.addEventListener('click', function () {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('expanded');
            mainContent.style.marginLeft = sidebar.classList.contains('expanded') ? '225px' : '70px';
        } else {
            sidebar.classList.toggle('small');
            mainContent.style.marginLeft = sidebar.classList.contains('small') ? '70px' : '225px';
        }
    });

    // Aktifkan link yang diklik
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', function () {
            document.querySelectorAll('.sidebar-link').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>