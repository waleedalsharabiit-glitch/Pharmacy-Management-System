<?php
// هنا ستضع كود PHP لجلب بيانات الأدمن الحالي وعرضها داخل الحقول
// وأيضاً معالجة تحديث البيانات (UPDATE) عند إرسال النموذج
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content">
        <header>
            <h1>الملف الشخصي للمدير</h1>
            <div class="user-info">أدمن الصيدلية ⮟</div>
        </header>

        <div class="profile-container">
            <!-- كرت عرض الصورة الرمزية السريعة والترحيب -->
            <div class="profile-header-card">
                <div class="profile-avatar-large">👤</div>
                <h2>المدير العام</h2>
                <span class="badge pharmacist">صلاحية أدمن</span>
            </div>

            <!-- نموذج تحديث البيانات -->
            <div class="form-container" style="max-width: 600px; margin-top: 20px;">
                <form action="admin_profile.php" method="POST">
                    <h3 class="form-title">تحديث معلومات الحساب</h3>
                    
                    <div class="form-group">
                        <label>اسم المستخدم</label>
                        <input type="text" name="username" value="admin" required>
                    </div>

                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" value="admin@pharmacy.com" required>
                    </div>

                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone" value="777777777" placeholder="أدخل رقم الهاتف">
                    </div>

                    <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 25px 0;">
                    <h3 class="form-title" style="color: var(--accent);">تغيير كلمة المرور (اختياري)</h3>

                    <div class="form-group">
                        <label>كلمة المرور الحالية</label>
                        <input type="password" name="current_password" placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label>كلمة المرور الجديدة</label>
                        <input type="password" name="new_password" placeholder="أدخل كلمة المرور الجديدة">
                    </div>

                    <button type="submit" name="update_profile" class="btn-submit">حفظ التعديلات الجديدة</button>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include '../include/footer.php'; ?>