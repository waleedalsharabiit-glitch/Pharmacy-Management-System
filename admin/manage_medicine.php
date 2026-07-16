<?php
// هنا ستكتب لاحقاً كود PHP لمعالجة الإضافة (INSERT) أو الحذف (DELETE) أو التعديل (UPDATE)
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content">
        <header>
            <h1>إدارة المستودع والأدوية</h1>
            <div class="user-info">أدمن الصيدلية ⮟</div>
        </header>

        <!-- قسّمنا الصفحة إلى جزأين باستخدام CSS Flex/Grid لتوزيع مثالي -->
        <div class="admin-grid">
            
            <!-- القسم الأول: نموذج إضافة/تعديل دواء -->
            <div class="form-container">
                <h3 class="form-title">إضافة دواء جديد للمخزن</h3>
                <form action="manage_medicine.php" method="POST">
                    <div class="form-group">
                        <label>الاسم التجاري والعلمي</label>
                        <input type="text" name="name" required placeholder="مثال: Solpadeine Soluble">
                    </div>
                    
                    <div class="form-group">
                        <label>التصنيف الطبي</label>
                        <select name="category" required>
                            <option value="">اختر التصنيف...</option>
                            <option value="Painkiller">مسكنات الآلام</option>
                            <option value="Antibiotic">المضادات الحيوية</option>
                            <option value="Vitamins">الفيتامينات والمكملات</option>
                            <option value="Diabetes">أدوية السكري</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>الكمية المتوفرة</label>
                        <input type="number" name="quantity" required min="0" placeholder="مثال: 50">
                    </div>

                    <div class="form-group">
                        <label>سعر البيع للعبوة ($)</label>
                        <input type="number" step="0.01" name="price" required placeholder="0.00">
                    </div>

                    <button type="submit" name="add_medicine" class="btn-submit">حفظ وإدخال الدواء</button>
                </form>
            </div>

            <!-- القسم الثاني: جدول استعراض الأدوية وإجراءات التحكم -->
            <div class="table-container">
                <h3 class="table-title">المخزون الحالي</h3>
                <table>
                    <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>اسم الدواء</th>
                            <th>التصنيف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- البيانات الافتراضية للتصميم (ستقوم بتكرارها برمجياً بـ foreach لاحقاً) -->
                        <tr>
                            <td>1</td>
                            <td><strong>Panadol Extra</strong></td>
                            <td>Painkiller</td>
                            <td>150</td>
                            <td>$4.50</td>
                            <td><span class="badge pharmacist">متوفر</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_medicine.php?id=1" class="btn-edit">تعديل ✏️</a>
                                    <a href="manage_medicine.php?delete=1" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الدواء؟')">حذف 🗑️</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><strong>Profinal 400mg</strong></td>
                            <td>Painkiller</td>
                            <td>0</td>
                            <td>$3.20</td>
                            <td><span class="badge user">نفذ</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_medicine.php?id=2" class="btn-edit">تعديل ✏️</a>
                                    <a href="manage_medicine.php?delete=2" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الدواء؟')">حذف 🗑️</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</div>

<?php include '../include/footer.php'; ?>