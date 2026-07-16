<?php
// هنا ستضع أكواد PHP لمعالجة تعديل صلاحية المستخدم (Role Update) أو حذفه
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <?php include '../include/nav.php'; ?>
    
    <main class="main-content">
        <header>
            <h1>إدارة حسابات الصيدلية وصلاحياتهم</h1>
            <div class="user-info">أدمن الصيدلية ⮟</div>
        </header>

        <div class="table-container">
            <h3 class="table-title">جميع الحسابات المسجلة بالنظام</h3>
            <table>
                <thead>
                    <tr>
                        <th>رقم الحساب</th>
                        <th>اسم المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>الصلاحية الحالية</th>
                        <th>تغيير الصلاحية</th>
                        <th>التحكم بالنظام</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- مثال على مستخدم عادي -->
                    <tr>
                        <td>2</td>
                        <td><strong>Waleed Alsharabi</strong></td>
                        <td>waleed@test.com</td>
                        <td>777777777</td>
                        <td><span class="badge user">مستفيد عادي</span></td>
                        <td>
                            <!-- نموذج صغير وسريع لتحديث الصلاحية مباشرة بالـ PHP -->
                            <form action="manage_users.php" method="POST" style="display:inline-flex; gap:5px;">
                                <input type="hidden" name="user_id" value="2">
                                <select name="new_role" class="select-inline">
                                    <option value="user" selected>مستفيد</option>
                                    <option value="pharmacist">صيدلي مساعد</option>
                                    <option value="admin">مدير</option>
                                </select>
                                <button type="submit" name="update_role" class="btn-update-inline">تحديث</button>
                            </form>
                        </td>
                        <td>
                            <a href="manage_users.php?delete_user=2" class="btn-delete" onclick="return confirm('هل تريد فعلاً حذف هذا المستخدم بشكل نهائي؟')">حذف الحساب 🗑️</a>
                        </td>
                    </tr>
                    
                    <!-- مثال على حساب صيدلي مساعد -->
                    <tr>
                        <td>3</td>
                        <td><strong>Ali Ahmed</strong></td>
                        <td>ali@pharmacy.com</td>
                        <td>771234567</td>
                        <td><span class="badge pharmacist">صيدلي مساعد</span></td>
                        <td>
                            <form action="manage_users.php" method="POST" style="display:inline-flex; gap:5px;">
                                <input type="hidden" name="user_id" value="3">
                                <select name="new_role" class="select-inline">
                                    <option value="user">مستفيد</option>
                                    <option value="pharmacist" selected>صيدلي مساعد</option>
                                    <option value="admin">مدير</option>
                                </select>
                                <button type="submit" name="update_role" class="btn-update-inline">تحديث</button>
                            </form>
                        </td>
                        <td>
                            <a href="manage_users.php?delete_user=3" class="btn-delete" onclick="return confirm('هل تريد فعلاً حذف هذا المستخدم بشكل نهائي؟')">حذف الحساب 🗑️</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../include/footer.php'; ?>