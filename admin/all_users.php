<?php
// هنا ستضع كود PHP لمعالجة طلبات الـ AJAX كما شرحنا سابقاً
// إذا كان الطلب يحمل متغير ajax=1، قم بجلب البيانات وطباعة الجدول ثم عمل exit.
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content">
        <header>
            <h1>قائمة المستخدمين والبحث الفوري</h1>
        </header>

        <!-- صندوق مدخلات البحث الفوري -->
        <div class="search-box">
            <input type="text" id="userSearch" placeholder="ابحث باسم المستخدم أو البريد الإلكتروني...">
        </div>

        <!-- الحاوية الديناميكية التي سيتم تعبئتها بجدول المستخدمين عبر كود الـ JavaScript الخاص بك -->
        <div class="table-container" id="userTable">
            <!-- الجدول الافتراضي المبدئي (سيقوم كود الـ JS والـ PHP الخاص بك بتحديثه بالكامل) -->
            <table>
                <thead>
                    <tr>
                        <th>رقم المستخدم</th>
                        <th>اسم المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>العنوان</th>
                        <th>تاريخ التسجيل</th>
                        <th>الصلاحية</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- هذه البيانات ثابتة للتصميم فقط، ستستبدلها بحلقة تكرار PHP حية جالبة للبيانات -->
                    <tr>
                        <td>1</td>
                        <td>waleed</td>
                        <td>waleed@test.com</td>
                        <td>777777777</td>
                        <td>تعز</td>
                        <td>2026-04-20</td>
                        <td><span class="badge user">مستفيد</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>mohammed</td>
                        <td>mohammed@test.com</td>
                        <td>771111111</td>
                        <td>صنعاء</td>
                        <td>2026-05-15</td>
                        <td><span class="badge pharmacist">صيدلي مساعد</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- هنا يمكنك كتابة كود الـ JavaScript (AJAX) لربط حقل الإدخال #userSearch مع الجدول #userTable -->
<script>
    // اكتب كود الـ AJAX الخاص بك هنا ليتنصت على حدث الإدخال ويرسل الطلبات الخلفية
</script>

<?php include '../include/footer.php'; ?>