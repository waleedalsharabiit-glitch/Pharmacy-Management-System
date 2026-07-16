<?php
// هنا ستضع كود PHP الخاص بك لاحقاً لجلب الإحصائيات من قاعدة البيانات واستدعاء config.php
// مثال: $total_medicines = ...

include_once '..\config.php';


$total_medicines=$conn->query("SELECT COUNT(*) FROM medicines")->fetchColumn();
$total_users=$conn->query("SELECT COUNT(*)FROM users")->fetchColumn();
$total_issued=$conn->query("SELECT COUNT(*)FROM issued_medicines")->fetchColumn();








?>

<?php include_once '../include/header.php'; ?>

<div class="dashboard-wrapper">
    <!-- استدعاء القائمة الجانبية المشتركة -->
    <?php include_once '../include/nav.php'; ?>
    
    <main class="main-content-premium">
        <!-- الهيدر العلوي المطور -->
        <header class="dashboard-header">
            <div class="header-title">
                <h1>لوحة تحكم الصيدلية الذكية 🧪</h1>
                <p>متابعة فورية للمخزون، عمليات الصرف، وإحصائيات النظام العامة.</p>
            </div>
            <div class="user-profile-badge">
                <span class="user-avatar">👤</span>
                <span class="user-name">أدمن الصيدلية</span>
            </div>
        </header>
        
        <!-- شبكة الكروت الإحصائية المطورة ذات المؤشرات الملونة -->
        <section class="premium-stats-grid">
            <div class="stat-card border-cyan">
                <div class="stat-icon-wrap bg-cyan-dim">💊</div>
                <div class="stat-info">
                    <h3><?=$total_medicines?></h3>
                    <span>إجمالي الأدوية بالمخزن</span>
                </div>
            </div>
            
            <div class="stat-card border-green">
                <div class="stat-icon-wrap bg-green-dim">📊</div>
                <div class="stat-info">
                    <h3><?=$total_issued?></h3> 
                    <span>الأدوية المصروفة</span>
                </div>
            </div>
            
            <div class="stat-card border-red">
                <div class="stat-icon-wrap bg-red-dim">⚠️</div>
                <div class="stat-info">
                    <h3>5</h3> <!-- برمجياً: <?=$out_of_stock?> -->
                    <span>أدوية نفذت كميتها</span>
                </div>
            </div>
            
            <div class="stat-card border-purple">
                <div class="stat-icon-wrap bg-purple-dim">👥</div>
                <div class="stat-info">
                    <h3><?=$total_users?></h3> <!-- برمجياً:  -->
                    <span>المستخدمين والزبائن</span>
                </div>
            </div>
        </section>

        <!-- قسم الوصول السريع المميز -->
        <div class="section-divider">
            <h2>أقسام الإدارة السريعة</h2>
            <div class="line"></div>
        </div>

        <section class="shortcuts-grid">
            <a href="manage_medicine.php" class="shortcut-card glow-teal">
                <div class="shortcut-icon">🧪</div>
                <div class="shortcut-desc">
                    <h4>إضافة وإدارة الأدوية</h4>
                    <p>إدارة مخزون الصيدلية والأسعار وتحديث الكميات.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>

            <a href="manage_users.php" class="shortcut-card glow-blue">
                <div class="shortcut-icon">👥</div>
                <div class="shortcut-desc">
                    <h4>إدارة الحسابات والصيادلة</h4>
                    <p>التحكم بصلاحيات المستخدمين وترقيتهم بالنظام.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>

            <a href="issued_medicines.php" class="shortcut-card glow-amber">
                <div class="shortcut-icon">📋</div>
                <div class="shortcut-desc">
                    <h4>سجل صرف الأدوية</h4>
                    <p>متابعة عمليات الصرف والوصفات الطبية للمرضى.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>

            <a href="requested_medicines.php" class="shortcut-card glow-rose">
                <div class="shortcut-icon">🔔</div>
                <div class="shortcut-desc">
                    <h4>طلبات الأدوية الواردة</h4>
                    <p>مراجعة طلبات توفير الأدوية غير المتوفرة حالياً.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>
        </section>
    </main>
</div>

<?php include_once '../include/footer.php'; ?>
