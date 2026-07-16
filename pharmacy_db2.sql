
CREATE DATABASE IF NOT EXISTS `pharmacy_db2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pharmacy_db2`;

-- 1. جدول المستخدمين (المدراء، الصيادلة، المرضى)
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `gender` VARCHAR(10) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'user', -- (admin, pharmacist, user)
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. جدول الأدوية (المخزن الرئيسي)
CREATE TABLE IF NOT EXISTS `medicines` (
  `medicine_id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `price` DECIMAL(10, 2) NOT NULL,
  `expiry_date` DATE DEFAULT NULL, -- تاريخ انتهاء الصلاحية (إضافة احترافية)
  `status` VARCHAR(50) GENERATED ALWAYS AS (CASE WHEN `quantity` > 0 THEN 'متوفر' ELSE 'غير متوفر' END) STORED, -- حالة تلقائية بناءً على الكمية
  `image` VARCHAR(255) DEFAULT 'default_medicine.png' -- صورة الدواء اختيارية
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. جدول طلبات صرف الأدوية المتوفرة (الطلب والحجز)
CREATE TABLE IF NOT EXISTS `issued_medicines` (
  `issue_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `medicine_id` INT NOT NULL,
  `quantity_issued` INT NOT NULL DEFAULT 1,
  `prescription_img` VARCHAR(255) DEFAULT NULL, -- صورة الوصفة الطبية المرفوعة من العميل
  `issue_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) NOT NULL DEFAULT 'قيد المراجعة', -- (قيد المراجعة, تم الصرف, مرفوض)
  `notes` TEXT DEFAULT NULL, -- ملاحظات الصيدلي (مثلاً سبب الرفض)
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`medicine_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. جدول طلبات توفير الأدوية غير المتوفرة بالمخزن
CREATE TABLE IF NOT EXISTS `requested_medicines` (
  `request_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `medicine_name` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `quantity_requested` INT NOT NULL DEFAULT 1,
  `request_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) NOT NULL DEFAULT 'قيد الانتظار', -- (قيد الانتظار, تمت الموافقة وتوفيره, مرفوض)
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إدخال بيانات تجريبية ممتازة للاختبار الفوري
INSERT INTO `users` (`username`, `password`, `email`, `role`, `address`, `phone`) VALUES
('أدمن الصيدلية', '123', 'admin@pharmacy.com', 'admin', 'تعز', '777777777'),
('وليد الشرعبي', '123', 'waleed@test.com', 'user', 'تعز', '771111111');

INSERT INTO `medicines` (`name`, `category`, `quantity`, `price`, `expiry_date`) VALUES
('Panadol Extra', 'Painkiller', 120, 3.50, '2028-12-31'),
('Amoxicillin 500mg', 'Antibiotics', 50, 8.00, '2027-06-30'),
('Insulin Lantus', 'Diabetes', 0, 25.00, '2026-09-15');