<?php
// هنا ستضع كود PHP لاستعلام SELECT يجمع بيانات من جدولي (users) و (medicines) وجدول (issued_medicines)
// وعمل كود الحذف أو الإرجاع
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content">
        <header>
            <h1>سجل الأدوية المصروفة للزبائن</h1>
            <div class="user-info">أدمن الصيدلية ⮟</div>
        </header>

        <!-- فلتر وبحث سريع للأدوية المصروفة -->
        <div class="search-box">
            <input type="text" id="issuedSearch" placeholder="ابحث باسم المريض أو الدواء المصروف...">
        </div>

        <div class="table-container">
            <h3 class="table-title">قائمة سجلات الصرف الحالية</h3>
            <table>
                <thead>
                    <tr>
                        <th>رقم السجل</th>
                        <th>اسم المستلم (المريض)</th>
                        <th>اسم الدواء المصروف</th>
                        <th>الكمية المصروفة</th>
                        <th>تاريخ ووقت الصرف</th>
                        <th>الحالة</th>
                        <th>إجراءات السجل</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- مثال لبيانات صرف حية -->
                    <tr>
                        <td>#1002</td>
                        <td><strong>Waleed Alsharabi</strong></td>
                        <td>Amoxicillin 500mg</td>
                        <td>3 علب</td>
                        <td>2026-07-16 10:30 AM</td>
                        <td><span class="badge pharmacist">تم الصرف بنجاح</span></td>
                        <td>
                            <!-- زر لإرجاع الدواء وإلغاء الصرف لإعادة الكمية للمستودع -->
                            <a href="issued_medicines.php?return=1002" class="btn-edit" style="background-color: var(--accent);" onclick="return confirm('هل ترغب في إلغاء عملية الصرف وإرجاع الكمية للمخزن؟')">إرجاع للمخزن 🔄</a>
                        </td>
                    </tr>
                    <tr>
                        <td>#1001</td>
                        <td><strong>أحمد محمد علي</strong></td>
                        <td>Panadol Extra</td>
                        <td>1 علبة</td>
                        <td>2026-07-15 09:15 PM</td>
                        <td><span class="badge user" style="background-color: #fee2e2; color: #991b1b;">تم الإلغاء / الإرجاع</span></td>
                        <td>
                            <span style="font-size: 13px; color: var(--text-muted);">لا توجد إجراءات</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../include/footer.php'; ?>