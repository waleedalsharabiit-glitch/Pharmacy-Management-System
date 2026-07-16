
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
$success_msg = '';
$error_msg = '';

// جلب اسم الدواء تلقائياً إذا جاء من صفحة المستودع كـ GET
$pre_filled_name = isset($_GET['medicine_name']) ? trim($_GET['medicine_name']) : '';

// معالجة إرسال طلب التوفير
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_name = trim($_POST['medicine_name']);
    $category = trim($_POST['category']);
    $quantity_requested = intval($_POST['quantity_requested']);

    if (empty($medicine_name)) {
        $error_msg = "الرجاء كتابة اسم الدواء المطلوب.";
    } elseif ($quantity_requested < 1) {
        $error_msg = "الرجاء تحديد كمية صحيحة (على الأقل 1).";
    } else {
        // إدخال الطلب إلى جدول requested_medicines
        $insert_stmt = $conn->prepare("
            INSERT INTO requested_medicines (user_id, medicine_name, category, quantity_requested, status) 
            VALUES (:user_id, :med_name, :category, :qty, 'قيد الانتظار')
        ");
        
        $status = $insert_stmt->execute([
            'user_id' => $user_id,
            'med_name' => $medicine_name,
            'category' => $category,
            'qty' => $quantity_requested
        ]);

        if ($status) {
            $success_msg = "تم تسجيل طلب توفير الدواء بنجاح! سنعمل على توفيره وإشعارك فور وصوله.";
            $pre_filled_name = ''; // تصفير الحقل بعد النجاح
        } else {
            $error_msg = "حدث خطأ غير متوقع أثناء إرسال طلبك، يرجى المحاولة لاحقاً.";
        }
    }
}
?>

<?php include_once '../include/header.php'; ?>

<div class="dashboard-wrapper">
    <?php include_once '../include/nav.php'; ?>
    
    <main class="main-content-premium">
        <header class="dashboard-header">
            <div class="header-title">
                <h1>طلب توفير دواء غير متوفر 🔍</h1>
                <p>علاجك غير متاح حالياً؟ لا تقلق، أخبرنا باسمه وتصنيفه الطبي وسوف نسعى جاهدين لتأمينه لك في أقرب وقت.</p>
            </div>
            <link rel="stylesheet" href="../assets/css/style.css">
        </header>

        <!-- التنبيهات الملونة للنجاح والفشل -->
        <?php if (!empty($success_msg)): ?>
            <div class="alert-box success-alert">
                <span class="alert-icon">✓</span>
                <div class="alert-text"><?= $success_msg ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="alert-box danger-alert">
                <span class="alert-icon">⚠️</span>
                <div class="alert-text"><?= $error_msg ?></div>
            </div>
        <?php endif; ?>

        <!-- حاوية الفورم الزجاجية الفخمة -->
        <section class="premium-form-container">
            <div class="form-header-badge">
                <span class="badge-icon">📦</span>
                <h3>تفاصيل طلب التوفير الجديد</h3>
            </div>
            
            <form action="" method="POST" class="modern-form">
                <!-- 1. اسم الدواء المطلوب -->
                <div class="form-group">
                    <label for="medicine_name">اسم الدواء المطلوب بالتفصيل *</label>
                    <input type="text" name="medicine_name" id="medicine_name" 
                           placeholder="مثال: Insulin Lantus أو Panadol Extra..." 
                           value="<?= htmlspecialchars($pre_filled_name) ?>" required>
                </div>

                <div class="form-row-double">
                    <!-- 2. الفئة/التصنيف الطبي -->
                    <div class="form-group">
                        <label for="category">التصنيف الطبي (اختياري)</label>
                        <select name="category" id="category">
                            <option value="">-- حدد نوع العلاج --</option>
                            <option value="Painkiller">مسكنات الآلام (Painkiller)</option>
                            <option value="Antibiotics">مضادات حيوية (Antibiotics)</option>
                            <option value="Diabetes">أدوية السكري (Diabetes)</option>
                            <option value="Blood Pressure">ضغط الدم (Blood Pressure)</option>
                            <option value="Vitamins">فيتامينات ومكملات (Vitamins)</option>
                            <option value="Other">أخرى / غير محدد</option>
                        </select>
                    </div>

                    <!-- 3. الكمية المطلوبة -->
                    <div class="form-group">
                        <label for="quantity_requested">الكمية المطلوبة (وحدة/علبة) *</label>
                        <input type="number" name="quantity_requested" id="quantity_requested" value="1" min="1" required>
                    </div>
                </div>

                <!-- نصائح وتوجيهات تفاعلية للمستخدم -->
                <div class="info-tip-box">
                    <span class="tip-icon">💡</span>
                    <p class="tip-text">يرجى التأكد من كتابة الاسم العلمي أو التجاري للدواء بشكل صحيح لمساعدتنا في توفيره بدقة متناهية.</p>
                </div>

                <!-- أزرار التحكم الفخمة -->
                <div class="form-button-group">
                    <button type="submit" class="btn-form-submit">تقديم طلب التوفير الآن 🚀</button>
                    <a href="my_requested_medicines.php" class="btn-form-cancel">استعراض طلباتي السابقة</a>
                </div>
            </form>
        </section>
    </main>
</div>

<?php include '../include/footer.php'; ?>