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

// 1. تحديد الدواء المختار مسبقاً إذا تم تمريره عبر الرابط (GET)
$pre_selected_medicine = isset($_GET['medicine_id']) ? intval($_GET['medicine_id']) : 0;

// 2. جلب قائمة الأدوية المتوفرة فقط ليختار منها المستخدم داخل النموذج
$medicines_query = $conn->query("SELECT medicine_id, name, price, quantity FROM medicines WHERE quantity > 0 ORDER BY name ASC");
$medicines_list = $medicines_query->fetchAll(PDO::FETCH_ASSOC);

// 3. معالجة إرسال النموذج (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_id = intval($_POST['medicine_id']);
    $qty_requested = intval($_POST['quantity_issued']);
    $notes = trim($_POST['notes']);
    
    // التحقق من توفر الكمية المطلوبة في المخزن أولاً
    $check_qty = $conn->prepare("SELECT quantity, name FROM medicines WHERE medicine_id = ?");
    $check_qty->execute([$medicine_id]);
    $med_data = $check_qty->fetch(PDO::FETCH_ASSOC);

    if (!$med_data) {
        $error_msg = "الدواء المختار غير موجود بالنظام.";
    } elseif ($qty_requested > $med_data['quantity']) {
        $error_msg = "الكمية المطلوبة (" . $qty_requested . ") أكبر من المتاح حالياً في المخزن (" . $med_data['quantity'] . ").";
    } elseif ($qty_requested < 1) {
        $error_msg = "الرجاء إدخال كمية صحيحة (على الأقل 1).";
    } else {
        // معالجة رفع صورة الوصفة الطبية
        $prescription_filename = null;
        if (isset($_FILES['prescription_img']) && $_FILES['prescription_img']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['prescription_img']['tmp_name'];
            $file_name = $_FILES['prescription_img']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
            
            if (in_array($file_ext, $allowed_extensions)) {
                // توليد اسم فريد للملف لمنع التكرار
                $prescription_filename = 'presc_' . time() . '_' . uniqid() . '.' . $file_ext;
                $upload_path = '../uploads/prescriptions/' . $prescription_filename;
                
                // التأكد من وجود مجلد الرفع
                if (!is_dir('../uploads/prescriptions/')) {
                    mkdir('../uploads/prescriptions/', 0777, true);
                }
                
                move_uploaded_file($file_tmp, $upload_path);
            } else {
                $error_msg = "صيغة ملف الوصفة غير مسموحة. المسموح فقط (JPG, PNG, PDF).";
            }
        }

        // إذا لم يكن هناك أخطاء سابقة، نقوم بإدراج الطلب لقاعدة البيانات
        if (empty($error_msg)) {
            $insert_stmt = $conn->prepare("
                INSERT INTO issued_medicines (user_id, medicine_id, quantity_issued, prescription_img, status, notes) 
                VALUES (:user_id, :medicine_id, :qty, :img, 'قيد المراجعة', :notes)
            ");
            
            $status = $insert_stmt->execute([
                'user_id' => $user_id,
                'medicine_id' => $medicine_id,
                'qty' => $qty_requested,
                'img' => $prescription_filename,
                'notes' => $notes
            ]);

            if ($status) {
                $success_msg = "تم تقديم طلب صرف الدواء بنجاح! طلبك الآن قيد مراجعة الصيدلي المختص.";
                // إعادة تصفير الحقل المختار
                $pre_selected_medicine = 0;
            } else {
                $error_msg = "حدث خطأ غير متوقع أثناء إرسال الطلب. حاول لاحقاً.";
            }
        }
    }
}
?>

<?php include '../include/header.php'; ?>

<div class="dashboard-wrapper">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content-premium">
        <header class="dashboard-header">
            <div class="header-title">
                <h1>طلب صرف دواء بوصفة 📥</h1>
                <p>قم بتعبئة طلب الصرف وقم بإرفاق الوصفة الطبية لتدقيقها وإعداد علاجك بأسرع وقت.</p>
            </div>
            <link rel="stylesheet" href="../assets/css/style.css">
        </header>

        <!-- قسم التنبيهات الملونة للنجاح أو الفشل -->
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

        <!-- حاوية الفورم الزجاجية الكبيرة الفخمة -->
        <section class="premium-form-container">
            <form action="" method="POST" enctype="multipart/form-data" class="modern-form">
                
                <!-- 1. اختيار الدواء المطلوبة صرفه -->
                <div class="form-group">
                    <label for="medicine_id">اختر الدواء المطلوب صرفه *</label>
                    <select name="medicine_id" id="medicine_id" required>
                        <option value="">-- اضغط للاختيار من القائمة المتاحة بالمخزن --</option>
                        <?php foreach ($medicines_list as $med): ?>
                            <option value="<?= $med['medicine_id'] ?>" 
                                <?= $pre_selected_medicine === intval($med['medicine_id']) ? 'selected' : '' ?>
                                data-price="<?= $med['price'] ?>"
                                data-max="<?= $med['quantity'] ?>">
                                <?= htmlspecialchars($med['name']) ?> (المتاح: <?= $med['quantity'] ?> وحدة | السعر: $<?= $med['price'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- صف يحتوي على الكمية والسعر التقديري الجانبي لتجربة مستخدم مذهلة -->
                <div class="form-row-double">
                    <!-- 2. الكمية المطلوبة -->
                    <div class="form-group">
                        <label for="quantity_issued">الكمية المطلوبة *</label>
                        <input type="number" name="quantity_issued" id="quantity_issued" value="1" min="1" required>
                    </div>

                    <!-- 3. مؤشر السعر الكلي الحسابي (تلقائي) -->
                    <div class="form-group price-preview-container">
                        <label>السعر الإجمالي التقريبي</label>
                        <div class="price-badge-display">$<span id="calc-price">0.00</span></div>
                    </div>
                </div>

                <!-- 4. رفع الوصفة الطبية (مطور بصرياً ومميز) -->
                <div class="form-group">
                    <label>الوصفة الطبية المعتمدة (اختياري/موصى به) </label>
                    <div class="custom-file-upload">
                        <input type="file" name="prescription_img" id="prescription_img" accept=".jpg,.jpeg,.png,.pdf">
                        <label for="prescription_img" class="upload-area-label">
                            <span class="upload-icon">📂</span>
                            <span class="upload-text">اسحب الصورة هنا أو اضغط للتصفح من جهازك</span>
                            <span class="upload-limits">الصيغ المسموح بها: JPG, PNG, PDF</span>
                        </label>
                    </div>
                </div>

                <!-- 5. ملاحظات إضافية للمستفيد -->
                <div class="form-group">
                    <label for="notes">ملاحظات أو توصيات خاصة للطلب</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="أضف أي تفاصيل تود إخبار الصيدلي بها (مثل الجرعة المفضلة، تفضيلات التوصيل...إلخ)"></textarea>
                </div>

                <!-- أزرار التحكم الفاخرة -->
                <div class="form-button-group">
                    <button type="submit" class="btn-form-submit">إرسال طلب الصرف الفوري</button>
                    <a href="view_medicines.php" class="btn-form-cancel">إلغاء والعودة للمستودع</a>
                </div>
            </form>
        </section>
    </main>
</div>

<!-- كود جافاسكريبت فائق الذكاء لحساب السعر الإجمالي بشكل حي ومباشر عند تغيير الكمية أو الدواء -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const medSelect = document.getElementById('medicine_id');
    const qtyInput = document.getElementById('quantity_issued');
    const priceDisplay = document.getElementById('calc-price');

    function updatePrice() {
        const selectedOption = medSelect.options[medSelect.selectedIndex];
        if (selectedOption && selectedOption.value !== "") {
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const qty = parseInt(qtyInput.value) || 0;
            const maxQty = parseInt(selectedOption.getAttribute('data-max')) || 0;

            // حماية المستخدم من تجاوز المخزون المتاح
            if (qty > maxQty) {
                alert(`⚠️ عذراً! الكمية المطلوبة تتجاوز المتاح حالياً بالمستودع (${maxQty})`);
                qtyInput.value = maxQty;
            }

            priceDisplay.textContent = (price * qtyInput.value).toFixed(2);
        } else {
            priceDisplay.textContent = "0.00";
        }
    }

    medSelect.addEventListener('change', updatePrice);
    qtyInput.addEventListener('input', updatePrice);
    updatePrice(); // تشغيل أولي لحساب السعر الافتراضي
});
</script>

<?php include '../include/footer.php'; ?>

