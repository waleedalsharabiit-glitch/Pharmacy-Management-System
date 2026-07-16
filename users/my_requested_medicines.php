<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once '../config.php';

$user_id = $_SESSION['user_id'];

// جلب كل طلبات التوفير الخاصة بهذا المستخدم مرتبة من الأحدث إلى الأقدم
$requests_query = $conn->prepare("
    SELECT request_id, medicine_name, category, quantity_requested, request_date, status 
    FROM requested_medicines
    WHERE user_id = :user_id
    ORDER BY request_date DESC
");
$requests_query->execute(['user_id' => $user_id]);
$my_requests = $requests_query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once '../include/header.php'; ?>

<div class="dashboard-wrapper">
    <?php include_once '../include/nav.php'; ?>
    
    <main class="main-content-premium">
        <header class="dashboard-header">
            <div class="header-title">
                <h1>سجل طلبات توفير الأدوية الخاص بك 📋</h1>
                <p>تتبع المسار الزمني وحالة توفر أدويتك التي قمت بطلب توفيرها مسبقاً من الصيدلية.</p>
            </div>
            <div class="header-actions">
                <a href="request_a_medicine.php" class="btn-premium-search" style="text-decoration: none;">+ طلب توفير دواء جديد</a>
            </div>
            <link rel="stylesheet" href="../assets/css/style.css">
        </header>

        <!-- شبكة متابعة الطلبات الإبداعية (Grid & Visual Cards) -->
        <section class="requests-tracking-grid">
            <?php if (!empty($my_requests)): ?>
                <?php foreach ($my_requests as $req): ?>
                    <?php
                        $status = $req['status'];
                        $card_border = 'border-purple';
                        $status_icon = '⏳';
                        $status_label_class = 'pending';
                        
                        if ($status === 'تمت الموافقة وتوفيره') {
                            $card_border = 'border-green';
                            $status_icon = '✓';
                            $status_label_class = 'success';
                        } elseif ($status === 'مرفوض') {
                            $card_border = 'border-red';
                            $status_icon = '✖';
                            $status_label_class = 'danger';
                        }
                    ?>
                    
                    <div class="request-track-card <?= $card_border ?>">
                        <div class="track-card-header">
                            <span class="request-badge">رقم الطلب #<?= $req['request_id'] ?></span>
                            <span class="request-time-stamp">📅 <?= date('Y-m-d', strtotime($req['request_date'])) ?></span>
                        </div>

                        <div class="track-card-body">
                            <h3 class="med-request-title"><?= htmlspecialchars($req['medicine_name']) ?></h3>
                            <span class="med-cat-tag-badge"><?= htmlspecialchars($req['category'] ?: 'تصنيف غير محدد') ?></span>
                            
                            <!-- تفاصيل المسار -->
                            <div class="track-details">
                                <div class="detail-row">
                                    <span>الكمية المطلوبة:</span>
                                    <strong><?= $req['quantity_requested'] ?> علبة</strong>
                                </div>
                            </div>

                            <!-- خط الحالة البصري التفاعلي (Progress Line) -->
                            <div class="progress-track-wrapper">
                                <div class="progress-bar-line">
                                    <div class="progress-fill <?= $status_label_class ?>"></div>
                                </div>
                                <div class="progress-points">
                                    <span class="point active">طلب تقديم</span>
                                    <span class="point <?= $status !== 'قيد الانتظار' ? 'active' : '' ?>">المراجعة</span>
                                    <span class="point <?= $status === 'تمت الموافقة وتوفيره' ? 'active-success' : ($status === 'مرفوض' ? 'active-danger' : '') ?>">
                                        <?= $status ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- ذيل الكرت الذي يتفاعل بناءً على الحالة المتوفرة -->
                        <div class="track-card-footer">
                            <div class="status-indicator-badge <?= $status_label_class ?>">
                                <span class="indicator-icon"><?= $status_icon ?></span>
                                <span class="indicator-text"><?= $status ?></span>
                            </div>
                            
                            <?php if ($status === 'تمت الموافقة وتوفيره'): ?>
                                <a href="request_issue.php" class="btn-action-go-issue">طلب صرفه الآن 📥</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results-card full-width-card">
                    <span class="no-results-icon">📭</span>
                    <h3>لا توجد أي طلبات توفير مسجلة حتى الآن!</h3>
                    <p>إذا كنت تبحث عن دواء ولم تجده في مستودعنا، يمكنك تقدير أول طلب لك لتأمين وتوفير هذا العلاج وسنقوم بالعمل الفوري عليه.</p>
                    <a href="request_a_medicine.php" class="btn-premium-search" style="display: inline-block; margin-top: 15px; text-decoration: none;">ابدأ طلب توفير دواء الآن</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<?php include_once '../include/footer.php'; ?>
