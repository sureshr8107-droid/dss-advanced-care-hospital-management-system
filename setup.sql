-- DSS Advanced Care Hospital - Database Setup
-- Run this in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS dss_hospital;
USE dss_hospital;

-- Users table (patients)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Doctors table
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    disease_tags VARCHAR(255) NOT NULL,
    experience INT DEFAULT 5,
    rating DECIMAL(2,1) DEFAULT 4.5,
    bio TEXT,
    available_days VARCHAR(100) DEFAULT 'Mon,Tue,Wed,Thu,Fri',
    fee INT DEFAULT 500,
    image VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255)
);

-- Time slots table
CREATE TABLE IF NOT EXISTS slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    slot_date DATE NOT NULL,
    slot_time VARCHAR(20) NOT NULL,
    is_booked TINYINT(1) DEFAULT 0,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    doctor_id INT NOT NULL,
    slot_id INT NOT NULL,
    patient_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    height DECIMAL(5,2),
    weight DECIMAL(5,2),
    food_pref VARCHAR(20),
    illness_description TEXT,
    disease VARCHAR(100),
    payment_method VARCHAR(30) DEFAULT 'pay_at_hospital',
    payment_status VARCHAR(20) DEFAULT 'pending',
    status VARCHAR(20) DEFAULT 'confirmed',
    booking_ref VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (slot_id) REFERENCES slots(id)
);

-- Insert Doctors
INSERT INTO doctors (name, specialization, disease_tags, experience, rating, bio, fee, image, email, password) VALUES
('Mathivanan M', 'General Physician', 'Fever,General Checkup,Respiratory', 12, 4.8, 'Dr. Mathivanan is a seasoned General Physician with over 12 years of experience in treating common illnesses, fevers, and conducting routine health checkups.', 300, 'doc1.jpg', 'mathivanan@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Suresh R', 'Cardiologist', 'Heart Issues', 15, 4.9, 'Dr. Suresh R is a leading Cardiologist specializing in heart disease management, interventional cardiology, and cardiac rehabilitation.', 800, 'doc2.jpg', 'suresh@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Zainudeen', 'Dermatologist', 'Skin Problems', 10, 4.7, 'Dr. Zainudeen is an expert Dermatologist with a decade of experience treating skin disorders, cosmetic concerns, and hair/nail conditions.', 600, 'doc3.jpg', 'zainudeen@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Maaran V', 'Dentist', 'Dental', 8, 4.6, 'Dr. Maaran V is a skilled Dentist offering comprehensive dental care including preventive dentistry, cosmetic procedures, and oral surgery.', 400, 'doc4.jpg', 'maaran@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Rashika Shree', 'Neurologist', 'Neurology', 13, 4.9, 'Dr. Rashika Shree is a distinguished Neurologist specializing in brain and nervous system disorders, epilepsy, and stroke management.', 900, 'doc5.jpg', 'rashika@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Karthick M', 'Orthopedic Specialist', 'Orthopedic', 11, 4.7, 'Dr. Karthick M is an Orthopedic Specialist with expertise in joint replacement, sports injuries, spine disorders, and trauma care.', 700, 'doc6.jpg', 'karthick@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Arjun', 'Eye Specialist', 'Eye Problems', 9, 4.8, 'Dr. Arjun is an Ophthalmologist specializing in eye disorders, cataract surgery, LASIK procedures, and pediatric eye care.', 550, 'doc7.jpg', 'arjun@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Trisha V', 'Gynecologist', 'General Checkup', 14, 4.9, 'Dr. Trisha V is a highly regarded Gynecologist specializing in womens health, obstetrics, fertility treatments, and minimally invasive gynecologic surgery.', 750, 'doc8.jpg', 'trisha@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Stalin K', 'Diabetologist', 'Diabetes', 10, 4.6, 'Dr. Stalin K is a specialist in Diabetes management, endocrine disorders, obesity, and metabolic syndrome with a patient-first approach.', 600, 'doc9.jpg', 'stalin@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Thulasi D', 'ENT Specialist', 'ENT', 7, 4.5, 'Dr. Thulasi D specializes in Ear, Nose, and Throat disorders including sinusitis, hearing loss, tonsillitis, and head-neck surgeries.', 500, 'doc10.jpg', 'thulasi@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Ganesh G', 'General Surgeon', 'General Checkup,Fever', 16, 4.8, 'Dr. Ganesh G is a seasoned General Surgeon with expertise in laparoscopic surgery, trauma surgery, and complex abdominal procedures.', 850, 'doc11.jpg', 'ganesh@dsscare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Generate slots for next 7 days for each doctor
DELIMITER $$
CREATE PROCEDURE GenerateSlots()
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE j INT DEFAULT 1;
    DECLARE slot_d DATE;
    DECLARE times VARCHAR(500);
    DECLARE t VARCHAR(20);
    
    WHILE i < 7 DO
        SET slot_d = DATE_ADD(CURDATE(), INTERVAL i DAY);
        SET j = 1;
        WHILE j <= 11 DO
            SET t = ELT(j, '09:00 AM','09:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','02:00 PM','02:30 PM','03:00 PM','03:30 PM','04:00 PM');
            INSERT IGNORE INTO slots (doctor_id, slot_date, slot_time, is_booked)
            SELECT id, slot_d, t, 0 FROM doctors;
            SET j = j + 1;
        END WHILE;
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER ;

CALL GenerateSlots();
DROP PROCEDURE IF EXISTS GenerateSlots;
