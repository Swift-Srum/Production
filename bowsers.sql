-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 04:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bowsers`
--

-- --------------------------------------------------------

--
-- Table structure for table `bowsers`
--

CREATE TABLE `bowsers` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `manufacturer_details` text NOT NULL,
  `model` text NOT NULL,
  `serial_number` text NOT NULL,
  `specific_notes` text NOT NULL,
  `capacity_litres` text NOT NULL,
  `length_mm` text NOT NULL,
  `width_mm` text NOT NULL,
  `height_mm` text NOT NULL,
  `weight_empty_kg` text NOT NULL,
  `weight_full_kg` text NOT NULL,
  `supplier_company` text NOT NULL,
  `date_received` text NOT NULL,
  `date_returned` text NOT NULL,
  `eastings` int(11) NOT NULL DEFAULT 0,
  `northings` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bowsers`
--

INSERT INTO `bowsers` (`id`, `ownerId`, `manufacturer_details`, `model`, `serial_number`, `specific_notes`, `capacity_litres`, `length_mm`, `width_mm`, `height_mm`, `weight_empty_kg`, `weight_full_kg`, `supplier_company`, `date_received`, `date_returned`, `eastings`, `northings`, `active`) VALUES
(52, 1, 'Sample details about the item', 'XYZ-123', 'SN456789', 'Handle with care', '100', '50', '30', '40', '10', '110', 'ABC Supplies Ltd.', '2025-02-17', '2025-03-01', 393316, 223029, 1),
(53, 1, 'Sample details about the item', 'XYZ-123', 'SN456789', 'Handle with care', '100', '50', '30', '40', '10', '110', 'ABC Supplies Ltd.', '2025-02-17', '2025-03-01', 0, 0, 1),
(54, 1, '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '2025-02-07', '2025-02-25', 0, 0, 1),
(55, 1, '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '2025-02-07', '2025-02-25', 0, 0, 1),
(56, 1, '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '2025-02-13', '2025-02-28', 0, 0, 1),
(57, 1, '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '2025-02-13', '2025-02-28', 0, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bowsers`
--
ALTER TABLE `bowsers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bowsers`
--
ALTER TABLE `bowsers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
