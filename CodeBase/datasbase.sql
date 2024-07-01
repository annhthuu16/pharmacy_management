-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 07, 2024 lúc 09:46 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `appotheke`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer`
--

CREATE TABLE `customers` (
  `Id` int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Name` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE suppliers (
    Id int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name varchar(255) NOT NULL,
    phone_number varchar(15) NOT NULL,
    Email varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `medicine`
--
CREATE TABLE medicines (
    Id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    medicine_name varchar(500) NOT NULL,
    Price int(255) NOT NULL,
    Type varchar(255) NOT NULL,
    Id_supplier int(11) NOT NULL,
    quantity int(255) NOT NULL,
    expiry_date date NOT NULL,
    FOREIGN KEY (Id_supplier) REFERENCES suppliers(Id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pharmacist`
--

CREATE TABLE `pharmacists` (
  `Id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Username` varchar(255) NOT NULL,
  `Pwd` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `last_seen` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sales`
--

CREATE TABLE `sales` (
  `Id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Pharmacist_Id` INT(11) NOT NULL,
  `Customer_Id` INT(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `Total` int(255) NOT NULL,
  `Date` date NOT NULL,
  FOREIGN KEY (`Pharmacist_Id`) REFERENCES `pharmacists`(`Id`),
  FOREIGN KEY (`Customer_Id`) REFERENCES `customers`(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `invoice_medicines` (
  `Id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Invoice_Id` INT(11) NOT NULL,
  `Medicine_Id` INT(11) NOT NULL,
  `Price` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  FOREIGN KEY (`Invoice_Id`) REFERENCES `sales`(`Id`),
  FOREIGN KEY (`Medicine_Id`) REFERENCES `medicines`(`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
