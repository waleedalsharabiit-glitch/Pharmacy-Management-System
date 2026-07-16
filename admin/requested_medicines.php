<?php
// هنا ستضع كود PHP لعرض طلبات الأدوية من جدول (requested_medicines)
// ومعالجة طلبات الموافقة (Approved) أو الرفض (Rejected)
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content">
        <header>
            <h1>طلبات الأدوية الواردة من المستخدمين</h1>
            <div class="user-info">أدمن الصيدلية ⮟</div>
        </header>

        <div class="table-container">
            <h3 class="table-title">طلبات توفير الأدوية قيد المراجعة</h3>
            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم صاحب الطلب</th>
                        <th>اسم الدواء المطلوب</th>
                        <th>التصنيف المقترح</th>
                        <th>تاريخ تقديم الطلب</th>
                        <th>الحالة الحالية</th>
                        <th>اتخاذ إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- مثال لطلب قيد الانتظار -->
                    <tr>
                        <td>#R-501</td>
                        <td><strong>Waleed Alsharabi</strong></td>
                        <td>Insulin Lantus SoloStar</td>
                        <td>Diabetes (السكري)</td>
                        <td>2026-07-16</td>
                        <td><span class="badge-status pending">قيد الانتظار ⏳</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="requested_medicines.php?action=approve&id=501" class="btn-update-inline" style="text-align: center; text-decoration: none;">موافقة وتوفير ✔</a>
                                <a href="requested_medicines.php?action=reject&id=501" class="btn-delete" style="text-align: center;" onclick="return confirm('هل تريد رفض هذا الطلب؟')">رفض ✖</a>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- مثال لطلب تمت الموافقة عليه وتوفيره -->
                    <tr>
                        <td>#R-499</td>
                        <td><strong>خالد العبسي</strong></td>
                        <td>Profinal 400mg</td>
                        <td>Painkiller</td>
                        <td>2026-07-10</td>
                        <td><span class="badge-status approved">تم توفيره وصرفه 🎉</span></td>
                        <td>
                            <span style="font-size: 13px; color: var(--success); font-weight: bold;">مكتمل</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../include/footer.php'; ?>