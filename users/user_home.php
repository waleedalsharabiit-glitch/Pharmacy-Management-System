<?php
// 1. التحقق من الجلسة وجلب البيانات الأساسية
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once '../config.php'; // اتصال قاعدة البيانات باستخدام PDO ($conn)

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['username'] ?? 'مستفيد'; // اعتمدنا هنا على اسم الحقل في جدولك `username`
$user_address = $_SESSION['address'] ?? 'تعز، اليمن'; // حقل العنوان من جدولك `address`

// =========================================================
// 2. حساب الإحصائيات الفورية متطابقة مع شروط جداولك
// =========================================================

// أولاً: عدد الأدوية التي صرفت للمستفيد (الحالة 'تم الصرف' كما حددتها في جدولك)
$issuedCount = $conn->prepare("SELECT COUNT(*) FROM issued_medicines WHERE user_id = :user_id AND status = 'تم الصرف'");
$issuedCount->execute(['user_id' => $user_id]);
$total_issued_meds = $issuedCount->fetchColumn();

// ثانياً: عدد طلبات التوفير قيد الانتظار للمستفيد (الحالة 'قيد الانتظار' كما حددتها في جدولك)
$pendingCount = $conn->prepare("SELECT COUNT(*) FROM requested_medicines WHERE user_id = :user_id AND status = 'قيد الانتظار'");
$pendingCount->execute(['user_id' => $user_id]);
$total_pending_requests = $pendingCount->fetchColumn();


// =========================================================
// 3. استعلام الدمج الفاخر لآخر 5 نشاطات (تم إصلاح كافة المسميات)
// =========================================================
$activityQuery = $conn->prepare("
    (SELECT
        'طلب صرف' AS request_type,
        m.name AS med_name,
        i.issue_date AS req_date,
        i.quantity_issued AS qty,
        i.status AS req_status
     FROM issued_medicines i
     INNER JOIN medicines m ON i.medicine_id = m.medicine_id
     WHERE i.user_id = :user_id)
     
    UNION ALL
    
    (SELECT
        'طلب توفير' AS request_type,
        r.medicine_name AS med_name,
        r.request_date AS req_date,
        r.quantity_requested AS qty,
        r.status AS req_status
     FROM requested_medicines r
     WHERE r.user_id = :user_id)
     
    ORDER BY req_date DESC
    LIMIT 5
");

$activityQuery->execute(['user_id' => $user_id]);
$activities = $activityQuery->fetchAll(PDO::FETCH_ASSOC);
?>



<?php include_once '../include/header.php';?>

<div class="dashboard-wrapper">
    <!-- استدعاء القائمة الجانبية المشتركة للمستخدم -->
    <?php include_once '../include/nav.php'; ?>
    
    <main class="main-content-premium">
        <!-- الهيدر الترحيبي المطور للمريض/المستفيد -->
        <header class="dashboard-header">
            <div class="header-title">
                <h1>Hello <?=$user_name?>👋</h1> 
                <p>مرحباً بك في بوابتك الطبية الذكية. يمكنك متابعة أدويتك وطلب وصفاتك بكل سهولة.</p>
            </div>
            <div class="user-profile-badge">
                <span class="user-avatar">🩺</span>
                <span class="user-name">ملف المستفيد</span>
            </div>
            <link rel="stylesheet" href="../assets/css/style.css">
        </header>
        
        <!-- شبكة الكروت الإحصائية الشخصية للمستخدم -->
        <section class="premium-stats-grid">
            <div class="stat-card border-green">
                <div class="stat-icon-wrap bg-green-dim font-bold">✓</div>
                <div class="stat-info">
                    <h3><?= $total_issued_meds?></h3> <!-- برمجياً: ستجلب عدد الأدوية المصروفة لهذا المستخدم فقط -->
                    <span>أدوية تم صرفها لك</span>
                </div>
            </div>
            
            <div class="stat-card border-cyan">
                <div class="stat-icon-wrap bg-cyan-dim font-bold">⏳</div>
                <div class="stat-info">
                    <h3><?= $total_pending_requests?></h3> <!-- برمجياً: عدد طلبات توفير الأدوية قيد الانتظار للمستخدم -->
                    <span>طلبات توفير قيد الانتظار</span>
                </div>
            </div>

            <div class="stat-card border-purple">
                <div class="stat-icon-wrap bg-purple-dim font-bold">📍</div>
                <div class="stat-info">
                    <h3>تعز، اليمن</h3> <!-- برمجياً: عنوان المريض المسجل لتوصيل الأدوية -->
                    <span>عنوان التوصيل المسجل</span>
                </div>
            </div>
        </section>

        <!-- قسم الإجراءات والوصول السريع للمستخدم -->
        <div class="section-divider">
            <h2>الخدمات السريعة للمستفيد</h2>
            <div class="line"></div>
        </div>

        <section class="shortcuts-grid">
            <a href="view_medicines.php" class="shortcut-card glow-teal">
                <div class="shortcut-icon">💊</div>
                <div class="shortcut-desc">
                    <h4>استعراض الأدوية المتوفرة</h4>
                    <p>ابحث في مخزن الصيدلية وتعرف على الأسعار والكميات المتاحة حالياً.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>

            <a href="request_issue.php" class="shortcut-card glow-blue">
                <div class="shortcut-icon">📋</div>
                <div class="shortcut-desc">
                    <h4>طلب صرف دواء بوصفة</h4>
                    <p>أرسل طلب صرف دواء متوفر مع إرفاق صورة من الوصفة الطبية المعتمدة.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>

            <a href="request_a_medicine.php" class="shortcut-card glow-amber">
                <div class="shortcut-icon">🧪</div>
                <div class="shortcut-desc">
                    <h4>طلب توفير دواء غير متوفر</h4>
                    <p>هل تبحث عن دواء مقطوع أو غير متاح؟ قدم طلباً لنقوم بتوفيره لك فوراً.</p>
                </div>
                <div class="arrow-indicator">⮞</div>
            </a>
        </section>

        <!-- جدول الحركات الأخيرة لتتبع حالة الطلبات فوراً دون الانتقال لصفحات أخرى -->
        <div class="section-divider" style="margin-top: 40px;">
            <h2>آخر النشاطات وتحديثات طلباتك</h2>
            <div class="line"></div>
        </div>

        <div class="table-container-premium">
            <table>
                <thead>
                    <tr>
                        <th>نوع الطلب</th>
                        <th>اسم الدواء</th>
                        <th>التاريخ</th>
                        <th>الكمية</th>
                        <th>حالة الطلب</th>
                    </tr>
                </thead>
            <tbody>
    <?php if (!empty($activities)): ?>
        <?php foreach ($activities as $row): ?>
            <tr>
                <!-- تصنيف نوع الحركة -->
                <td>
                    <?php if ($row['request_type'] === 'طلب صرف'): ?>
                        <span class="activity-type issue">طلب صرف 📥</span>
                    <?php else: ?>
                        <span class="activity-type request">طلب توفير 🔍</span>
                    <?php endif; ?>
                </td>
                
                <!-- اسم الدواء والبيانات الأخرى -->
                <td><strong><?= htmlspecialchars($row['med_name']) ?></strong></td>
                <td><?= date('Y-m-d H:i', strtotime($row['req_date'])) ?></td>
                <td><?= $row['qty'] ?></td>
                
                <!-- إضاءة حالة الطلب بناءً على القيم المدخلة في جدولك -->
                <td>
                    <?php 
                    $status = $row['req_status'];
                    if ($status === 'تم الصرف' || $status === 'تمت الموافقة وتوفيره'): ?>
                        <span class="badge-status-modern success">مكتمل ✔</span>
                    
                    <?php elseif ($status === 'قيد المراجعة' || $status === 'قيد الانتظار'): ?>
                        <span class="badge-status-modern pending">قيد الانتظار ⏳</span>
                    
                    <?php else: ?>
                        <span class="badge-status-modern danger">مرفوض ✖</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-slate);">
                ✨ لا توجد لديك طلبات سابقة في حسابك حتى الآن.
            </td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </main>
</div>

<?php include_once '../include/footer.php'; ?>
