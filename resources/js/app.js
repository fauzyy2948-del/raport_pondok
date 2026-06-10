// Bootstrap JS not used
// ===== SIDEBAR TOGGLE =====
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarToggle = document.getElementById('sidebarToggle');
const mainContent = document.querySelector('.main-content');
const topbar = document.querySelector('.topbar');

function toggleSidebar() {
    if (window.innerWidth <= 992) {
        sidebar?.classList.toggle('show');
        sidebarOverlay?.classList.toggle('show');
        document.body.style.overflow = sidebar?.classList.contains('show') ? 'hidden' : '';
    } else {
        sidebar?.classList.toggle('sidebar-collapsed');
        const collapsed = sidebar?.classList.contains('sidebar-collapsed');
        if (mainContent) mainContent.style.marginLeft = collapsed ? '0' : 'var(--sidebar-width)';
        if (topbar) topbar.style.left = collapsed ? '0' : 'var(--sidebar-width)';
    }
}

sidebarToggle?.addEventListener('click', toggleSidebar);
sidebarOverlay?.addEventListener('click', () => {
    sidebar?.classList.remove('show');
    sidebarOverlay?.classList.remove('show');
    document.body.style.overflow = '';
});

window.addEventListener('resize', () => {
    if (window.innerWidth > 992) {
        sidebar?.classList.remove('show');
        sidebarOverlay?.classList.remove('show');
        document.body.style.overflow = '';
    }
});

// ===== DARK MODE =====
const darkToggle = document.getElementById('darkModeToggle');
const root = document.documentElement;

const savedTheme = localStorage.getItem('theme') || 'light';
if (savedTheme === 'dark') {
    root.setAttribute('data-theme', 'dark');
    if (darkToggle) darkToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
}

darkToggle?.addEventListener('click', () => {
    const isDark = root.getAttribute('data-theme') === 'dark';
    root.setAttribute('data-theme', isDark ? 'light' : 'dark');
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    darkToggle.innerHTML = isDark ? '<i class="bi bi-moon-fill"></i>' : '<i class="bi bi-sun-fill"></i>';
});

// ===== SCROLL TO TOP =====
const scrollTopBtn = document.querySelector('.scroll-top');
window.addEventListener('scroll', () => {
    scrollTopBtn?.classList.toggle('show', window.scrollY > 300);
});
scrollTopBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// ===== SWEETALERT CONFIRM DELETE =====
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form') || document.getElementById(this.dataset.form);
            const message = this.dataset.confirm || 'Apakah Anda yakin ingin menghapus data ini?';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1B6B3A',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    borderRadius: '12px',
                }).then((result) => {
                    if (result.isConfirmed) form?.submit();
                });
            } else {
                if (confirm(message)) form?.submit();
            }
        });
    });

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-hide').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);

    // DataTable init
    if (typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 15,
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
        });
    }

    // Tooltip init
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // Active nav link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.startsWith(href) && href !== '/') {
            link.classList.add('active');
        }
    });
});

// ===== NILAI AUTO CALCULATE =====
function hitungNilaiAkhir(row) {
    const nh = parseFloat(row.querySelector('[name$="[nilai_harian]"]')?.value) || 0;
    const nt = parseFloat(row.querySelector('[name$="[nilai_tugas]"]')?.value) || 0;
    const nuts = parseFloat(row.querySelector('[name$="[nilai_uts]"]')?.value) || 0;
    const nuas = parseFloat(row.querySelector('[name$="[nilai_uas]"]')?.value) || 0;
    const nhf = parseFloat(row.querySelector('[name$="[nilai_hafalan]"]')?.value) || 0;
    const nad = parseFloat(row.querySelector('[name$="[nilai_adab]"]')?.value) || 0;

    const akhir = (nh * 0.20 + nt * 0.10 + nuts * 0.25 + nuas * 0.30 + nhf * 0.10 + nad * 0.05).toFixed(1);
    const predikatEl = row.querySelector('.nilai-akhir-display');
    if (predikatEl) {
        predikatEl.textContent = akhir;
        predikatEl.className = 'nilai-akhir-display grade-' + getGrade(akhir);
    }
}

function getGrade(nilai) {
    if (nilai >= 90) return 'A';
    if (nilai >= 80) return 'B';
    if (nilai >= 70) return 'C';
    return 'D';
}

document.querySelectorAll('.nilai-input').forEach(input => {
    input.addEventListener('input', function () {
        hitungNilaiAkhir(this.closest('tr'));
    });
});

window.hitungNilaiAkhir = hitungNilaiAkhir;
