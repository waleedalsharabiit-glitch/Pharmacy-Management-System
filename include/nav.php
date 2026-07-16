<?php
// التحقق من بدء الجلسة (Session) لضمان جلب صلاحيات المستخدم
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// جلب اسم الصفحة الحالية لتلوين الزر النشط
$current_page = basename($_SERVER['PHP_SELF']);

// جلب نوع المستخدم من الجلسة (مثلاً: admin أو user)
// سنضع قيمة افتراضية 'user' كأمان إذا لم تكن الجلسة قد بدأت بعد
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; 
?>

<aside class="sidebar-premium">
    <!-- شعار الموقع الفخم -->
    <div class="sidebar-logo">
        <div class="logo-icon">🧪</div>
        <h2>الشفاء الرقمي</h2>
    </div>

    <!-- حاوية روابط القائمة الجانبية -->
    <ul class="sidebar-menu">

        <?php if ($user_role === 'admin'): ?>
            <!-- ==================== قائمة المدير (ADMIN) ==================== -->
            <li class="<?= ($current_page == 'admin_home.php') ? 'active' : '' ?>">
                <a href="admin_home.php">
                    <span class="nav-icon">📊</span>
                    <span class="nav-text">لوحة التحكم العامة</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'manage_users.php') ? 'active' : '' ?>">
                <a href="manage_users.php">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">إدارة كل المستخدمين</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'manage_medicine.php') ? 'active' : '' ?>">
                <a href="manage_medicine.php">
                    <span class="nav-icon">💊</span>
                    <span class="nav-text">إدارة الأدوية والمخزن</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'issued_medicines.php') ? 'active' : '' ?>">
                <a href="issued_medicines.php">
                    <span class="nav-icon">📋</span>
                    <span class="nav-text">سجلات الأدوية المصروفة</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'requested_medicines.php') ? 'active' : '' ?>">
                <a href="requested_medicines.php">
                    <span class="nav-icon">🔔</span>
                    <span class="nav-text">طلبات الأدوية الواردة</span>
                </a>
            </li>

        <?php else: ?>
            <!-- ==================== قائمة المستخدم العادي / المريض (USER) ==================== -->
            <li class="<?= ($current_page == 'user_home.php') ? 'active' : '' ?>">
                <a href="user_home.php">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">الرئيسية</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'view_medicines.php') ? 'active' : '' ?>">
                <a href="view_medicines.php">
                    <span class="nav-icon">💊</span>
                    <span class="nav-text">استعراض الأدوية</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'request_issue.php') ? 'active' : '' ?>">
                <a href="request_issue.php">
                    <span class="nav-icon">📋</span>
                    <span class="nav-text">طلب صرف دواء</span>
                </a>
            </li>
            
            <li class="<?= ($current_page == 'request_a_medicine.php') ? 'active' : '' ?>">
                <a href="request_a_medicine.php">
                    <span class="nav-icon">🧪</span>
                    <span class="nav-text">طلب توفير دواء</span>
                </a>
            </li>

            <li class="<?= ($current_page == 'user_profile.php') ? 'active' : '' ?>">
                <a href="user_profile.php">
                    <span class="nav-icon">👤</span>
                    <span class="nav-text">ملفي الشخصي</span>
                </a>
            </li>
        <?php endif; ?>
        
        <!-- زر تسجيل الخروج المشترك يظهر للجميع في نهاية القائمة دائماً -->
        <li class="logout-item">
            <a href="../pages/logout.php" onclick="return confirm('هل أنت متأكد من رغبتك في تسجيل الخروج؟')">
                <span class="nav-icon">🚪</span>
                <span class="nav-text">تسجيل الخروج الآمن</span>
            </a>
        </li>
    </ul>
</aside>