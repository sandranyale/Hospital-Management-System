-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 06:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myhmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintb`
--

CREATE TABLE `admintb` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admintb`
--

INSERT INTO `admintb` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `appointmenttb`
--

CREATE TABLE `appointmenttb` (
  `pid` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `doctor` varchar(30) NOT NULL,
  `docFees` int(5) NOT NULL,
  `appdate` date NOT NULL,
  `apptime` time NOT NULL,
  `userStatus` int(5) NOT NULL,
  `doctorStatus` int(5) NOT NULL DEFAULT 0,
  `payment_status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointmenttb`
--

INSERT INTO `appointmenttb` (`pid`, `ID`, `fname`, `lname`, `gender`, `email`, `contact`, `doctor`, `docFees`, `appdate`, `apptime`, `userStatus`, `doctorStatus`, `payment_status`) VALUES
(12, 15, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Kieran Farook', 0, '2026-04-17', '14:00:00', 1, 1, 'Paid'),
(12, 16, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Steven Wambugu', 0, '2026-04-24', '10:00:00', 1, 1, 'Pending'),
(13, 17, 'Reginald', 'Muathe', 'Male', 'reginald@gmail.com', '0723456782', 'Emmanuel Mumbo', 0, '2026-04-28', '10:00:00', 1, 1, 'Pending'),
(13, 18, 'Reginald', 'Muathe', 'Male', 'reginald@gmail.com', '0723456782', 'Keane Rotich', 0, '2026-04-30', '14:00:00', 1, 1, 'Pending'),
(15, 19, 'Tim', 'Mutethia', 'Male', 'tim@gmail.com', '0789643672', 'Kieran Farook', 0, '2026-04-28', '14:00:00', 1, 1, 'Pending'),
(15, 20, 'Tim', 'Mutethia', 'Male', 'tim@gmail.com', '0789643672', 'Fahima Ramadhan', 0, '2026-04-30', '10:00:00', 1, 1, 'Pending'),
(16, 21, 'Beverly', 'Muthoni', 'Female', 'beverly@gmail.com', '0786543772', 'Steven Wambugu', 0, '2026-04-23', '16:00:00', 1, 1, 'Pending'),
(16, 22, 'Beverly', 'Muthoni', 'Female', 'beverly@gmail.com', '0786543772', 'Angel Kiarie', 0, '2026-05-05', '12:00:00', 1, 1, 'Pending'),
(17, 23, 'Jacob', 'Mae', 'Male', 'jacob@gmail.com', '0792453782', 'Kelvin Kiprono', 0, '2026-06-05', '14:00:00', 1, 1, 'Pending'),
(17, 24, 'Jacob', 'Mae', 'Male', 'jacob@gmail.com', '0792453782', 'Steven Wambugu', 0, '2026-04-29', '10:00:00', 1, 1, 'Pending'),
(18, 25, 'Ashley', 'Maraga', 'Female', 'ashley@gmail.com', '0756345627', 'Vicky Kaloki', 0, '2026-06-17', '08:00:00', 1, 1, 'Paid'),
(18, 26, 'Ashley', 'Maraga', 'Female', 'ashley@gmail.com', '0756345627', 'Esha Kahindi', 0, '2026-04-30', '14:00:00', 1, 1, 'Pending'),
(27, 27, 'Esther', 'Wanjohi', 'Female', 'esther@gmail.com', '0783547898', 'Angel Kiarie', 0, '2026-05-07', '08:00:00', 1, 1, 'Paid'),
(12, 28, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Emmanuel Mumbo', 0, '2026-04-22', '12:00:00', 0, 1, 'Pending'),
(16, 29, 'Beverly', 'Muthoni', 'Female', 'beverly@gmail.com', '0786543772', 'Angel Kiarie', 2500, '2026-05-08', '14:00:00', 1, 1, 'Paid'),
(12, 30, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Fiona Watiri', 1000, '2026-04-30', '14:00:00', 1, 0, 'Pending'),
(12, 31, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Emmanuel Mumbo', 1000, '2026-04-24', '12:00:00', 1, 1, 'Pending'),
(12, 32, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Fiona Watiri', 1000, '2026-04-28', '14:00:00', 1, 1, 'Pending'),
(32, 33, 'Mambo ', 'Nyale', 'Male', 'nyale@afyaone.com', '0720392074', 'Kieran Farook', 1000, '2026-05-06', '08:00:00', 1, 1, 'Paid'),
(12, 34, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Steven Wambugu', 2000, '2026-05-06', '08:00:00', 1, 1, 'Paid'),
(15, 35, 'Tim', 'Mutethia', 'Male', 'tim@gmail.com', '0789643672', 'Emmanuel Mumbo', 1000, '2026-05-07', '08:00:00', 1, 1, 'Paid'),
(34, 36, 'churchill', 'kaingu', 'Male', 'kainguchurchill@gmail.com', '0794721854', 'Emmanuel Mumbo', 1000, '2026-06-01', '12:00:00', 1, 1, 'Pending'),
(12, 37, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', 'Keane Rotich', 4500, '2026-06-17', '14:00:00', 1, 1, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(30) NOT NULL,
  `email` text NOT NULL,
  `contact` varchar(10) NOT NULL,
  `message` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`name`, `email`, `contact`, `message`) VALUES
('Anu', 'anu@gmail.com', '7896677554', 'Hey Admin'),
(' Viki', 'viki@gmail.com', '9899778865', 'Good Job, Pal'),
('Ananya', 'ananya@gmail.com', '9997888879', 'How can I reach you?'),
('Aakash', 'aakash@gmail.com', '8788979967', 'Love your site'),
('Mani', 'mani@gmail.com', '8977768978', 'Want some coffee?'),
('Karthick', 'karthi@gmail.com', '9898989898', 'Good service'),
('Abbis', 'abbis@gmail.com', '8979776868', 'Love your service'),
('Asiq', 'asiq@gmail.com', '9087897564', 'Love your service. Thank you!'),
('Jane', 'jane@gmail.com', '7869869757', 'I love your service!'),
('Emily Nyale', 'emily@gmail.com', '0795221184', 'I forgot my password');

-- --------------------------------------------------------

--
-- Table structure for table `doctb`
--

CREATE TABLE `doctb` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `spec` varchar(50) NOT NULL,
  `docFees` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctb`
--

INSERT INTO `doctb` (`username`, `password`, `email`, `spec`, `docFees`) VALUES
('Kieran Farook', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kieran@gmail.com', 'General', 1000),
('Vicky Kaloki', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vicky@gmail.com', 'Cardiologist', 2500),
('Esha Kahindi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'esha@gmail.com', 'Pediatrician', 1500),
('Emmanuel Mumbo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'emmanuel@gmail.com', 'General', 1000),
('Angel Kiarie', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'angel@gmail.com', 'Cardiologist', 2500),
('Keane Rotich', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'keane@gmail.com', 'Neurologist', 4500),
('Steven Wambugu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'steve@gmail.com', 'Pediatrician', 2000),
('Fiona Watiri', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fiona@gmail.com', 'General', 1000),
('Ian Koech', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ian@gmail.com', 'Cardiologist', 2500),
('Fahima Ramadhan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fahima@gmail.com', 'Neurologist', 4500),
('Kelvin Kiprono', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kelvin@gmail.com', 'Pediatrician', 1500),
('Kelvin Kiprono', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kelvin@gmail.com', 'Pediatrician', 1500),
('Sarah Ndegwa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sarah@afyaone.com', 'Psychiatrist', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `patreg`
--

CREATE TABLE `patreg` (
  `pid` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cpassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `patreg`
--

INSERT INTO `patreg` (`pid`, `fname`, `lname`, `gender`, `email`, `contact`, `password`, `cpassword`) VALUES
(12, 'Sandra ', 'Mbuche', 'Female', 'sandra@gmail.com', '0712345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(13, 'Reginald', 'Muathe', 'Male', 'reginald@gmail.com', '0723456782', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(14, 'Stacy', 'Gacheri', 'Female', 'stacy@gmail.com', '0786542356', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(15, 'Tim', 'Mutethia', 'Male', 'tim@gmail.com', '0789643672', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(16, 'Beverly', 'Muthoni', 'Female', 'beverly@gmail.com', '0786543772', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(17, 'Jacob', 'Mae', 'Male', 'jacob@gmail.com', '0792453782', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(18, 'Ashley', 'Maraga', 'Female', 'ashley@gmail.com', '0756345627', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(19, 'Jesse', 'Clay', 'Male', 'jesse@gmail.com', '0764326653', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(20, 'Angela', 'Hawi', 'Female', 'angela@gmail.com', '0756276389', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(21, 'Bryan', 'Gwendo', 'Male', 'bryan@gmail.com', '0789543675', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(22, 'Destiny', 'Mukiri', 'Female', 'destiny@gmail.com', '0786462456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(23, 'Stacy', 'Stevens', 'Male', 'stacy@gmail.com', '0754325675', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(24, 'Ken ', 'Mutuma', 'Male', 'ken@gmail.com', '0783562456', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(25, 'Ishmael', 'Isaac', 'Male', 'ishmael@gmail.com', '0786346789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(26, 'Emily', 'Nyale', 'Female', 'emily@gmail.com', '0786739028', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(27, 'Esther', 'Wanjohi', 'Female', 'esther@gmail.com', '0783547898', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(32, 'Mambo ', 'Nyale', 'Male', 'nyale@afyaone.com', '0720392074', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(33, 'Eloise', 'Bridgerton', 'Female', 'eloise@gmail.com', '0765432189', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(34, 'churchill', 'kaingu', 'Male', 'kainguchurchill@gmail.com', '0794721854', '$2y$10$dc02gYGBRltzdtI3fo4NS.WHcO9e19PAat2N505bqYNdDZdf2iL76', '$2y$10$dc02gYGBRltzdtI3fo4NS.WHcO9e19PAat2N505bqYNdDZdf2iL76');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacytb`
--

CREATE TABLE `pharmacytb` (
  `med_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(30) NOT NULL DEFAULT 'per tablet'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pharmacytb`
--

INSERT INTO `pharmacytb` (`med_id`, `name`, `category`, `price`, `unit`) VALUES
(1, 'Paracetamol 500mg', 'Analgesic', 15.00, 'per tablet'),
(2, 'Ibuprofen 400mg', 'Analgesic', 20.00, 'per tablet'),
(3, 'Amoxicillin 500mg', 'Antibiotic', 35.00, 'per capsule'),
(4, 'Azithromycin 250mg', 'Antibiotic', 55.00, 'per tablet'),
(5, 'Metronidazole 400mg', 'Antibiotic', 18.00, 'per tablet'),
(6, 'Ciprofloxacin 500mg', 'Antibiotic', 40.00, 'per tablet'),
(7, 'Omeprazole 20mg', 'Antacid', 25.00, 'per capsule'),
(8, 'Antacid Suspension', 'Antacid', 180.00, 'per bottle'),
(9, 'ORS Sachet', 'Rehydration', 30.00, 'per sachet'),
(10, 'Chloroquine 250mg', 'Antimalarial', 22.00, 'per tablet'),
(11, 'Artemether 80mg', 'Antimalarial', 85.00, 'per tablet'),
(12, 'Albendazole 400mg', 'Antiparasitic', 45.00, 'per tablet'),
(13, 'Cetirizine 10mg', 'Antihistamine', 20.00, 'per tablet'),
(14, 'Loratadine 10mg', 'Antihistamine', 18.00, 'per tablet'),
(15, 'Prednisolone 5mg', 'Steroid', 12.00, 'per tablet'),
(16, 'Dexamethasone 4mg', 'Steroid', 30.00, 'per tablet'),
(17, 'Atorvastatin 20mg', 'Cardiovascular', 55.00, 'per tablet'),
(18, 'Amlodipine 5mg', 'Cardiovascular', 35.00, 'per tablet'),
(19, 'Metformin 500mg', 'Antidiabetic', 20.00, 'per tablet'),
(20, 'Glibenclamide 5mg', 'Antidiabetic', 18.00, 'per tablet'),
(21, 'Vitamin C 500mg', 'Supplement', 10.00, 'per tablet'),
(22, 'Multivitamin', 'Supplement', 25.00, 'per tablet'),
(23, 'Iron + Folic Acid', 'Supplement', 15.00, 'per tablet'),
(24, 'Diclofenac 50mg', 'Analgesic', 22.00, 'per tablet'),
(25, 'Tramadol 50mg', 'Analgesic', 40.00, 'per tablet'),
(26, 'Salbutamol Inhaler', 'Respiratory', 450.00, 'per inhaler'),
(27, 'Budesonide Inhaler', 'Respiratory', 850.00, 'per inhaler'),
(28, 'Cough Syrup 100ml', 'Respiratory', 220.00, 'per bottle'),
(29, 'Eye Drops (Gentamicin)', 'Ophthalmic', 180.00, 'per bottle'),
(30, 'Betamethasone Cream', 'Dermatology', 150.00, 'per tube');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptionmeds`
--

CREATE TABLE `prescriptionmeds` (
  `id` int(11) NOT NULL,
  `pres_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `med_id` int(11) NOT NULL,
  `med_name` varchar(100) NOT NULL,
  `quantity` int(5) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `prescriptionmeds`
--

INSERT INTO `prescriptionmeds` (`id`, `pres_id`, `pid`, `med_id`, `med_name`, `quantity`, `unit_price`, `total`) VALUES
(1, 17, 13, 1, 'Paracetamol 500mg', 1, 15.00, 15.00),
(2, 17, 13, 8, 'Antacid Suspension', 1, 180.00, 180.00),
(3, 17, 13, 25, 'Tramadol 50mg', 1, 40.00, 40.00),
(4, 15, 12, 1, 'Paracetamol 500mg', 1, 15.00, 15.00),
(5, 15, 12, 2, 'Ibuprofen 400mg', 1, 20.00, 20.00),
(6, 15, 12, 25, 'Tramadol 50mg', 1, 40.00, 40.00),
(7, 35, 15, 3, 'Amoxicillin 500mg', 1, 35.00, 35.00),
(8, 35, 15, 4, 'Azithromycin 250mg', 1, 55.00, 55.00),
(9, 35, 15, 7, 'Omeprazole 20mg', 1, 25.00, 25.00),
(10, 37, 12, 2, 'Ibuprofen 400mg', 1, 20.00, 20.00),
(11, 37, 12, 24, 'Diclofenac 50mg', 1, 22.00, 22.00);

-- --------------------------------------------------------

--
-- Table structure for table `prestb`
--

CREATE TABLE `prestb` (
  `doctor` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `appdate` date NOT NULL,
  `apptime` time NOT NULL,
  `disease` varchar(250) NOT NULL,
  `allergy` varchar(250) NOT NULL,
  `prescription` varchar(1000) NOT NULL,
  `medicine_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `prestb`
--

INSERT INTO `prestb` (`doctor`, `pid`, `ID`, `fname`, `lname`, `appdate`, `apptime`, `disease`, `allergy`, `prescription`, `medicine_total`) VALUES
('Dinesh', 4, 11, 'Kishan', 'Lal', '2020-03-27', '15:00:00', 'Cough', 'Nothing', 'Just take a teaspoon of Benadryl every night', 0.00),
('Ganesh', 2, 8, 'Alia', 'Bhatt', '2020-03-21', '10:00:00', 'Severe Fever', 'Nothing', 'Take bed rest', 0.00),
('Kumar', 9, 12, 'William', 'Blake', '2020-03-26', '12:00:00', 'Sever fever', 'nothing', 'Paracetamol -> 1 every morning and night', 0.00),
('Tiwary', 9, 13, 'William', 'Blake', '2020-03-26', '14:00:00', 'Cough', 'Skin dryness', 'Intake fruits with more water content', 0.00),
('Kieran Farook', 12, 15, 'Sandra ', 'Mbuche', '2026-04-17', '14:00:00', 'Common Cold', 'Peanuts & Dairy products', 'Agumentin\r\nCertrizine\r\nParacetamol 1X3', 75.00),
('Vicky Kaloki', 18, 25, 'Ashley', 'Maraga', '2026-06-17', '08:00:00', 'High Blood Pressure', 'Lactose intolerant', 'Bp omron Machine\r\nAdvantec 1x1', 0.00),
('Angel Kiarie', 27, 27, 'Esther', 'Wanjohi', '2026-05-07', '08:00:00', 'Diabetes type 2', 'None', 'Insulin-every morning\r\nTriviamet 1X3\r\n', 0.00),
('Kieran Farook', 12, 15, 'Sandra ', 'Mbuche', '2026-04-17', '14:00:00', 'Diabetes', 'none', 'Insulin ', 75.00),
('Angel Kiarie', 16, 29, 'Beverly', 'Muthoni', '2026-05-08', '14:00:00', 'Common Cold', 'Peanuts, Dairy Products', 'Flugone tablets 1 X 3\r\nCetrizine 1 X 1', 0.00),
('Emmanuel Mumbo', 12, 28, 'Sandra ', 'Mbuche', '2026-04-22', '12:00:00', 'Malaria', 'Dairy products', 'Elemontus 1 X 3', 0.00),
('Fiona Watiri', 12, 30, 'Sandra ', 'Mbuche', '2026-04-30', '14:00:00', 'Diabetes', 'Dairy products', 'Triviamet tablets', 0.00),
('Kieran Farook', 32, 33, 'Mambo ', 'Nyale', '2026-05-06', '08:00:00', 'Malaria', 'None', 'Elemontus 1 X 3', 0.00),
('Steven Wambugu', 12, 34, 'Sandra ', 'Mbuche', '2026-05-06', '08:00:00', 'Malaria', 'Dairy Products', 'Paracetamol 500mg — 1 tablet every 8 hours for 5 days\r\n\r\nBed rest for 3-5 days. Plenty of fluids.', 0.00),
('Emmanuel Mumbo', 15, 35, 'Tim', 'Mutethia', '2026-05-07', '08:00:00', 'Typhoid Fever', 'Penicillin', 'Amoxicillin 500mg — 1 capsule every 8 hours for 7 days\r\nORS — Take after every loose stool\r\nBed rest for 3-5 days. Plenty of fluids.', 115.00),
('Emmanuel Mumbo', 13, 17, 'Reginald', 'Muathe', '2026-04-28', '10:00:00', 'Hypertension', 'None known', 'Avoid spicy foods.\r\nBed rest for 3-5 days.', 235.00),
('Kieran Farook', 12, 15, 'Sandra ', 'Mbuche', '2026-04-17', '14:00:00', 'Type 2 Diabetes', 'Penicillin', 'Drink plenty of fluids.', 75.00),
('Emmanuel Mumbo', 15, 35, 'Tim', 'Mutethia', '2026-05-07', '08:00:00', 'Hypertension', 'None known', 'Avoid spicy foods.', 115.00),
('Keane Rotich', 12, 37, 'Sandra ', 'Mbuche', '2026-06-17', '14:00:00', 'Typhoid Fever', 'NSAIDs', 'Return for review in 1 week.', 42.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `patreg`
--
ALTER TABLE `patreg`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `pharmacytb`
--
ALTER TABLE `pharmacytb`
  ADD PRIMARY KEY (`med_id`);

--
-- Indexes for table `prescriptionmeds`
--
ALTER TABLE `prescriptionmeds`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `patreg`
--
ALTER TABLE `patreg`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `pharmacytb`
--
ALTER TABLE `pharmacytb`
  MODIFY `med_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `prescriptionmeds`
--
ALTER TABLE `prescriptionmeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
