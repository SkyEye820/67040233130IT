# ฟอร์มลงทะเบียนอบรม (MySQL Version)

## การตั้งค่า

### 1. สร้างฐานข้อมูล
ทำตามขั้นตอนนี้เพื่อสร้างฐานข้อมูล:

#### วิธีที่ 1: ใช้ phpMyAdmin
1. เปิด phpMyAdmin (http://localhost/phpmyadmin)
2. คลิก "New" เพื่อสร้างฐานข้อมูนใหม่
3. ตั้งชื่อฐานข้อมูล: `registration_db`
4. เลือก Collation: `utf8mb4_unicode_ci`
5. คลิก Create
6. เปิดแท็บ "SQL" และ copy ข้อมูลจากไฟล์ `setup_database.sql` ไปวาง
7. คลิก Go

#### วิธีที่ 2: ใช้ Command Line
```bash
cd C:\xampp\mysql\bin
mysql -u root -p < C:\xampp\htdocs\HM03-main\setup_database.sql
```

### 2. ตั้งค่า Database Connection
แก้ไขไฟล์ `db.php` หากต้อง:
- `$servername` - ชื่อเซิร์ฟเวอร์ (ปกติคือ localhost)
- `$username` - ชื่อผู้ใช้ MySQL (ปกติคือ root)
- `$password` - รหัสผ่าน MySQL (ปกติว่าง)
- `$dbname` - ชื่อฐานข้อมูล (ตั้งเป็น registration_db)

### 3. รันแอปพลิเคชัน
1. เปิด http://localhost/HM03-main/index.php
2. กรอกฟอร์มและคลิก "ลงทะเบียน"
3. ข้อมูลจะถูกบันทึกลงในฐานข้อมูล MySQL

## ไฟล์ที่มี

- `index.php` - ไฟล์หลักของฟอร์มลงทะเบียน
- `db.php` - ไฟล์เชื่อมต่อฐานข้อมูล MySQL
- `setup_database.sql` - ไฟล์สำหรับสร้างฐานข้อมูลและตาราง

## การทำงาน

1. ฟอร์มจะบันทึกชื่อ, อีเมล, หัวข้ออบรม, อาหาร, รูปแบบ และราคา
2. ข้อมูลจะถูกบันทึกในตาราง `registrations`
3. หน้าเพจจะแสดงรายชื่อผู้ลงทะเบียนทั้งหมด
4. มีการป้องกัน SQL Injection โดยใช้ Prepared Statement
5. มีการป้องกัน XSS โดยใช้ htmlspecialchars()

## บันทึกข้อมูล

- ค่าลงทะเบียน Onsite: 1,500 บาท
- ค่าลงทะเบียน Online: 800 บาท
- วันเวลาลงทะเบียนจะbันทึกอัตโนมัติ
