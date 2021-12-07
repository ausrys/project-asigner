-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2021 m. Grd 07 d. 10:51
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_asigner`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `project`
--

CREATE TABLE `project` (
  `title` varchar(255) NOT NULL,
  `max_group_num` int(11) NOT NULL,
  `students_per_grp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `project_group`
--

CREATE TABLE `project_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Sukurta duomenų kopija lentelei `students`
--

INSERT INTO `students` (`id`, `full_name`) VALUES
(11, 'Alan Walker'),
(12, 'Harry Potter'),
(10, 'John Smith'),
(15, 'Karil Tron'),
(13, 'Paul Watson'),
(14, 'Robert Junior');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`title`);

--
-- Indexes for table `project_group`
--
ALTER TABLE `project_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_name` (`project_name`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `student_ibfk_1` (`group_id`),
  ADD KEY `project_name` (`project_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `full_name` (`full_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_group`
--
ALTER TABLE `project_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Apribojimai eksportuotom lentelėm
--

--
-- Apribojimai lentelei `project_group`
--
ALTER TABLE `project_group`
  ADD CONSTRAINT `project_group_ibfk_1` FOREIGN KEY (`project_name`) REFERENCES `project` (`title`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Apribojimai lentelei `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `project_group` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_4` FOREIGN KEY (`project_name`) REFERENCES `project` (`title`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
