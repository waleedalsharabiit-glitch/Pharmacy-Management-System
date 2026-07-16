<?php
// هنا ستضمن ملف الاتصال وتتحقق من الجلسة وإرسال الفورم برمجياً:
require_once '../config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
$email=trim($_POST['email']);
$password=$_POST['password'];
if(empty($email)||empty($password)){
$error="please fill in all filed";
}else{
    $stmt=$conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindParam(':email',$email);
    $stmt->execute();
$user=$stmt->fetch(PDO::FETCH_ASSOC);
if($user && password_verify($password,$user['password'])){
$_SESSION['user_id']=$user['id'];
$_SESSION['user_name']=$user['username'];
$_SESSION['user_email']=$user['email'];
$_SESSION['user_password']=$user['password'];
$_SESSION['user_address']=$user['address'];
if($user['role']=="admin"){
$_SESSION['role']="admin";
    header("Location:../admin/admin_home.php");
    exit;
    }elseif($user['role']=="user"){
        $_SESSION['role']="user";
        header("Location:../users/user_home.php");
        exit;
}else{
    $error="there is an error";
}


}
}



}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام الصيدلية الذكي</title>
    <!-- خطوط جوجل للحصول على مظهر احترافي ومريح للعين -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* تعريف متغيرات الألوان والتأثيرات لتسهيل التعديل لاحقاً */
        :root {
            --primary-gradient: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            --accent-glow: 0 0 20px rgba(20, 184, 166, 0.4);
            --bg-dark: #0f172a;
            --text-light: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* الحاوية الرئيسية بتقسيم 50/50 */
        .login-wrapper {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            width: 100%;
            height: 100vh;
            background: #090d16;
        }

        /* --- النصف الأول: الجانب الفني والجمالي --- */
        .branding-side {
            background: var(--primary-gradient);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            overflow: hidden;
        }

        /* دوائر زخرفية مضيئة في الخلفية */
        .branding-side::before, .branding-side::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(20, 184, 166, 0.2);
            filter: blur(80px);
        }
        .branding-side::before { width: 300px; height: 300px; top: -50px; right: -50px; }
        .branding-side::after { width: 400px; height: 400px; bottom: -100px; left: -100px; }

        .branding-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 500px;
        }

        .branding-icon {
            font-size: 75px;
            margin-bottom: 25px;
            animation: pulse 3s infinite ease-in-out;
            display: inline-block;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));
        }

        .branding-content h1 {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.4;
            margin-bottom: 15px;
            text-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .branding-content p {
            font-size: 16px;
            color: #ccfbf1;
            line-height: 1.8;
            font-weight: 300;
        }

        /* --- النصف الثاني: نموذج الدخول الاحترافي --- */
        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            background: radial-gradient(circle at center, #111827 0%, #030712 100%);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 45px;
            border-radius: 24px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.8s var(--transition-smooth);
        }

        .login-card h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .login-card .subtitle {
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 35px;
        }

        /* تنسيق الحقول بشكل تفاعلي حديث */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 8px;
            font-weight: 600;
            transition: var(--transition-smooth);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            padding: 15px 18px;
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: #ffffff;
            font-size: 15px;
            outline: none;
            transition: var(--transition-smooth);
        }

        /* تأثير التوهج والإضاءة عند الكتابة */
        .input-wrapper input:focus {
            border-color: #14b8a6;
            box-shadow: var(--accent-glow);
            background: rgba(15, 23, 42, 0.9);
        }

        /* تحريك العنوان لأعلى عند التركيز على الحقل */
        .form-group:focus-within label {
            color: #14b8a6;
        }

        /* زر الدخول الفاخر */
        .btn-submit-premium {
            width: 100%;
            padding: 16px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
            margin-top: 10px;
        }

        .btn-submit-premium:hover {
            transform: translateY(-2px);
            box-shadow: var(--accent-glow), 0 10px 20px rgba(20, 184, 166, 0.4);
            letter-spacing: 0.5px;
        }

        .btn-submit-premium:active {
            transform: translateY(1px);
        }

        /* المذيل والخيارات الإضافية */
        .login-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13.5px;
            color: #94a3b8;
        }

        .login-footer a {
            color: #14b8a6;
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition-smooth);
        }

        .login-footer a:hover {
            color: #2dd4bf;
            text-decoration: underline;
        }

        /* --- الحركات والأنيميشن التفاعلية --- */
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.95; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* التجاوب مع شاشات الهواتف والأجهزة اللوحية */
        @media (max-width: 992px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            .branding-side {
                display: none; /* إخفاء نصف التصميم في الموبايل لتركيز الاهتمام على الفورم */
            }
            .form-side {
                padding: 20px;
            }
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- 1. الجانب الزخرفي والبصري الفاخر -->
    <div class="branding-side">
        <div class="branding-content">
            <span class="branding-icon">🧪</span>
            <h1>نظام إدارة الصيدلية الرقمي المتكامل</h1>
            <p>حلول تقنية ذكية تمنحك تحكماً شاملاً وسريعاً في المخزون، حركة المبيعات، وصرف الأدوية للمرضى بدقة وأمان متناهي.</p>
        </div>
    </div>

    <!-- 2. الجانب التفاعلي لإدخال البيانات -->
    <div class="form-side">
        <div class="login-card">
            <h2>تسجيل الدخول 🔐</h2>
            <p class="subtitle">يرجى إدخال بيانات حسابك المعتمدة لبدء العمل.</p>
            
            <form action="login.php" method="POST">
                <!-- حقل البريد الإلكتروني -->
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" required placeholder="name@pharmacy.com">
                    </div>
                </div>
                
                <!-- حقل كلمة المرور -->
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" required placeholder="••••••••">
                    </div>
                </div>
                
                <!-- زر الدخول المتوهج -->
                <button type="submit" name="login" class="btn-submit-premium">دخول آمن للنظام</button>
            </form>
            
            <div class="login-footer">
                ليس لديك حساب نظام بعد؟ <a href="register.php">أنشئ حسابك الآن</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>