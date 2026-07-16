<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit();
}

require_once '../config.php';

// 1. جلب جميع الفئات الفريدة المتوفرة لتصفية البحث ديناميكياً
$categories_query = $conn->query("SELECT DISTINCT category FROM medicines ORDER BY category ASC");
$categories = $categories_query->fetchAll(PDO::FETCH_COLUMN);

// 2. معالجة البحث والتصفية (تلقائي وآمن ضد الـ SQL Injection)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$selected_category = isset($_GET['category']) ? trim($_GET['category']) : '';

$sql = "SELECT * FROM medicines WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND name LIKE :search";
    $params['search'] = '%' . $search . '%';
}

if (!empty($selected_category)) {
    $sql .= " AND category = :category";
    $params['category'] = $selected_category;
}

$sql .= " ORDER BY quantity DESC"; // إظهار المتوفر أولاً
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once '../include/header.php'; ?>

<div class="dashboard-wrapper">
    <!-- استدعاء القائمة الجانبية الذكية المشتركة -->
    <?php include_once '../include/nav.php'; ?>
    
    <main class="main-content-premium">
        <!-- هيدر الصفحة الفخم -->
        <header class="dashboard-header">
            <div class="header-title">
                <h1>مستودع الأدوية الذكي 💊</h1>
                <p>ابحث عن علاجك، تصفح الأسعار والكميات المتاحة في المخزن الفوري للصيدلية.</p>
            </div>
            <link rel="stylesheet" href="../assets/css/style.css">
        </header>

        <!-- شريط البحث الفلترة المتطور زجاجي المظهر -->
        <section class="search-filter-panel">
            <form method="GET" action="" class="filter-form">
                <div class="search-input-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" placeholder="ابحث باسم الدواء..." value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="filter-select-wrap">
                    <select name="category">
                        <option value="">كل التصنيفات الطبية</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $selected_category === $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-premium-search">تصفية البحث</button>
                <?php if (!empty($search) || !empty($selected_category)): ?>
                    <a href="view_medicines.php" class="btn-clear-filters">إعادة ضبط</a>
                <?php endif; ?>
            </form>
        </section>

        <!-- شبكة كروت الأدوية (Grid Layout) -->
        <section class="medicines-grid">
            <?php if (!empty($medicines)): ?>
                <?php foreach ($medicines as $med): ?>
                    <?php 
                        $is_available = $med['quantity'] > 0;
                        $card_border_class = $is_available ? 'border-cyan' : 'border-red';
                        $status_badge_class = $is_available ? 'success' : 'danger';
                        $status_text = $is_available ? 'متوفر بالمخزن' : 'نفذت الكمية';
                    ?>
                    <div class="medicine-card <?= $card_border_class ?>">
                        <div class="card-image-wrap">
                            <!-- صورة الدواء أو صورة افتراضية فخمة -->
                            <img src="../assets/images/<?= htmlspecialchars($med['image']) ?>" alt="<?= htmlspecialchars($med['name']) ?>" onerror="this.src='../assets/images/default_medicine.png'">
                            <span class="badge-status-modern <?= $status_badge_class ?> card-status">
                                <?= $status_text ?>
                            </span>
                        </div>

                        <div class="card-body">
                            <span class="medicine-cat-tag"><?= htmlspecialchars($med['category']) ?></span>
                            <h3 class="medicine-name-title"><?= htmlspecialchars($med['name']) ?></h3>
                            
                            <div class="medicine-meta-info">
                                <div class="meta-item">
                                    <span class="meta-label">السعر:</span>
                                    <span class="meta-value price-text">$<?= number_format($med['price'], 2) ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">المخزون المتاح:</span>
                                    <span class="meta-value"><?= $med['quantity'] ?> وحدة</span>
                                </div>
                                <?php if (!empty($med['expiry_date'])): ?>
                                    <div class="meta-item">
                                        <span class="meta-label">انتهاء الصلاحية:</span>
                                        <span class="meta-value expiry-text"><?= $med['expiry_date'] ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-actions">
                                <?php if ($is_available): ?>
                                    <a href="request_issue.php?medicine_id=<?= $med['medicine_id'] ?>" class="btn-card-action request">
                                        طلب صرف فوري 📥
                                    </a>
                                <?php else: ?>
                                    <a href="request_a_medicine.php?medicine_name=<?= urlencode($med['name']) ?>" class="btn-card-action suggest">
                                        طلب توفير الدواء 🔍
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results-card">
                    <span class="no-results-icon">🕵️‍♂️</span>
                    <h3>لم نجد أي نتيجة تطابق بحثك!</h3>
                    <p>ربما هذا الدواء غير مسجل أو غير متاح حالياً. هل ترغب في تقدير طلب لتوفيره خصيصاً لك؟</p>
                    <a href="request_a_medicine.php" class="btn-premium-search" style="display: inline-block; margin-top: 15px;">تقديم طلب توفير دواء</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<?php include '../include/footer.php'; ?>