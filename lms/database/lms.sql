-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 03:00 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_books`
--

CREATE TABLE `add_books` (
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `book_keyword_id` int(11) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `date_received` date NOT NULL,
  `availability` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(100) NOT NULL,
  `cupboard_id` int(11) NOT NULL,
  `shelve_name` varchar(100) NOT NULL,
  `shelve_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `add_books`
--

INSERT INTO `add_books` (`bid`, `accession_number`, `title`, `book_keyword`, `book_keyword_id`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `date_received`, `availability`, `description`, `price`, `cupboard_name`, `cupboard_id`, `shelve_name`, `shelve_id`) VALUES
(1, 1, 'The Pragmatic Programmer', 'Java', 3, 'Andrew Hunt, David Thomas', 'book_images/The Pragmatic Programmer.jpg', 5, '1999', 'Addison-Wesley', '2147483647', '2025-02-12', 1, 'A practical guide to software development best practices.', 750, 'Cupboard 1', 2, 'Shelve 1', 1),
(2, 2, 'Clean Code', 'Java', 2, 'Robert C. Martin', 'book_images/Clean Code.jpg', 3, '2008', 'Prentice Hall', '7890', '2025-02-12', 1, 'Guides developers in writing readable and maintainable code.', 750, 'Cupboard 1', 1, 'Shelve 1', 1),
(4, 3, 'Introduction to the Theory of Computation', 'Python', 2, 'Michael Sipser', 'book_images/Introduction to the Theory of Computation.jpg', 10, '2006', 'Cengage Learning', '978', '2025-02-17', 7, 'Covers formal languages, automata, and computational complexity.', 850, 'Cupboard 2', 2, 'Shelve 2', 2),
(5, 4, 'Database System Concepts', 'Python', 2, 'Abraham Silberschatz, Henry Korth', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', '2025-02-18', 14, 'A fundamental book for database management systems.', 950, 'Cupboard 2', 2, 'Shelve 1', 1),
(14, 5, 'Artificial Intelligence: A Modern Approach', 'Java', 1, 'Stuart Russell, Peter Norvig', 'book_images/Artificial Intelligence A Modern Approach.jpg', 10, '2020', 'Pearson', '9789332543515', '2025-03-10', 7, 'Comprehensive AI concepts, covering theory and applications.', 1200, 'Cupboard 1', 1, 'Shelve 2', 2);

-- --------------------------------------------------------

--
-- Table structure for table `admin_acc_create`
--

CREATE TABLE `admin_acc_create` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` varchar(250) NOT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_acc_create`
--

INSERT INTO `admin_acc_create` (`admin_id`, `name`, `pfno`, `email`, `password`) VALUES
(1, 'prakash', '123', 'prakash@gmail.com', 'prakash');

-- --------------------------------------------------------

--
-- Table structure for table `book_keywords`
--

CREATE TABLE `book_keywords` (
  `book_keyword_id` int(11) NOT NULL,
  `book_keyword` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_keywords`
--

INSERT INTO `book_keywords` (`book_keyword_id`, `book_keyword`) VALUES
(1, 'Java'),
(2, 'Python'),
(3, 'PHP'),
(4, 'HTML');

-- --------------------------------------------------------

--
-- Table structure for table `cupboards`
--

CREATE TABLE `cupboards` (
  `cupboard_id` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cupboards`
--

INSERT INTO `cupboards` (`cupboard_id`, `cupboard_name`) VALUES
(1, 'Cupboard 1'),
(2, 'Cupboard 2'),
(4, 'Cupboard 3');

-- --------------------------------------------------------

--
-- Table structure for table `int_student_renewal_books`
--

CREATE TABLE `int_student_renewal_books` (
  `in_renewal_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(250) NOT NULL,
  `email` varchar(300) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(300) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `exp_return_date` date NOT NULL,
  `renew_date` date NOT NULL,
  `new_return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `int_temp_borrow_book_request`
--

CREATE TABLE `int_temp_borrow_book_request` (
  `tid` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(250) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `int_temp_borrow_book_request`
--

INSERT INTO `int_temp_borrow_book_request` (`tid`, `stu_id`, `username`, `rollno`, `email`, `selectyourdegree`, `selectyourcourse`, `currectstudyingyear`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`) VALUES
(36, 2, 'PRAKASH S', '23PCS113', 'gaccprakashlab@gmail.com', 'M.Sc.', 'Computer Science', '2', 14, 5, 'Artificial Intelligence: A Modern Approach', 'Java', 'Stuart Russell, Peter Norvig', 'book_images/Artificial Intelligence A Modern Approach.jpg', 10, '2020', 'Pearson', '9789332543515', 7, 1200, 'Cupboard 1', 'Shelve 2');

-- --------------------------------------------------------

--
-- Table structure for table `int_temp_return_book_request`
--

CREATE TABLE `int_temp_return_book_request` (
  `ini_temp_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(250) NOT NULL,
  `email` varchar(500) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(300) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `issued_books`
--

CREATE TABLE `issued_books` (
  `issu_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(150) NOT NULL,
  `email` varchar(250) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(300) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `issued_books`
--

INSERT INTO `issued_books` (`issu_id`, `stu_id`, `username`, `rollno`, `email`, `selectyourdegree`, `selectyourcourse`, `currectstudyingyear`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`, `issued_date`, `return_date`) VALUES
(2, 3, 'vijay', '23PCS114', 'vijay@gmail.com', 'M.Sc.', 'Computer Science', '2', 2, 2, 'Clean Code', 'Java', 'Robert C. Martin', 'book_images/Clean Code.jpg', 3, '2008', 'Prentice Hall', '7890', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-20', '2025-04-04'),
(3, 3, 'vijay', '23PCS114', 'vijay@gmail.com', 'M.Sc.', 'Computer Science', '2', 5, 4, 'Database System Concepts', 'Python', 'Abraham Silberschatz, Henry Korth', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', 11, 950, 'Cupboard 2', 'Shelve 1', '2025-03-20', '2025-04-04'),
(4, 4, 'Ramakrishnan B', '23PCS115', 'rama@gmail.com', 'M.Sc.', 'Computer Science', '2', 2, 2, 'Clean Code', 'Java', 'Robert C. Martin', 'book_images/Clean Code.jpg', 3, '2008', 'Prentice Hall', '7890', 1, 750, 'Cupboard 1', 'Shelve 1', '2025-03-20', '2025-04-04'),
(5, 4, 'Ramakrishnan B', '23PCS115', 'rama@gmail.com', 'M.Sc.', 'Computer Science', '2', 1, 1, 'The Pragmatic Programmer', 'Java', 'Andrew Hunt, David Thomas', 'book_images/The Pragmatic Programmer.jpg', 5, '1999', 'Addison-Wesley', '2147483647', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-20', '2025-04-04');

-- --------------------------------------------------------

--
-- Table structure for table `issued_books_staffs`
--

CREATE TABLE `issued_books_staffs` (
  `staff_b_issu_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` int(11) NOT NULL,
  `email` varchar(500) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(300) NOT NULL,
  `book_image` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issue_date` date NOT NULL,
  `return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `issued_books_staffs`
--

INSERT INTO `issued_books_staffs` (`staff_b_issu_id`, `staff_id`, `name`, `pfno`, `email`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`, `issue_date`, `return_date`) VALUES
(1, 2, 'PRAKASH S', 123, 'gaccprakashlab@gmail.com', 5, 4, 'Database System Concepts', 'Python', 'Abraham Silberschatz, Henry Korth, S. Sudarshan', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', 12, 950, 'Cupboard 2', 'Shelve 1', '2025-03-15', '2025-09-11'),
(2, 2, 'PRAKASH S', 123, 'gaccprakashlab@gmail.com', 2, 2, 'Clean Code', 'Java', 'Robert C. Martin', 'book_images/Clean Code.jpg', 3, '2008', 'Prentice Hall', '7890', 1, 750, 'Cupboard 1', 'Shelve 1', '2025-03-29', '2025-06-27');

-- --------------------------------------------------------

--
-- Table structure for table `lib_acc_create`
--

CREATE TABLE `lib_acc_create` (
  `lib_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `pfno` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lib_acc_create`
--

INSERT INTO `lib_acc_create` (`lib_id`, `name`, `pfno`, `email`, `password`) VALUES
(1, 'PRAKASH S', '123', 'prakash@gmail.com', '$2y$10$cuPnxHG0uwxAfVNZHvdHMuGyZUYusmTF5HiyIHWvU602RLhngvkfC');

-- --------------------------------------------------------

--
-- Table structure for table `returned_books`
--

CREATE TABLE `returned_books` (
  `return_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(250) NOT NULL,
  `email` varchar(300) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(300) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `returned_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `returned_books`
--

INSERT INTO `returned_books` (`return_id`, `stu_id`, `username`, `rollno`, `email`, `selectyourdegree`, `selectyourcourse`, `currectstudyingyear`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`, `issued_date`, `returned_date`) VALUES
(2, 4, 'Ramakrishnan B', '23PCS115', 'rama@gmail.com', 'M.Sc.', 'Computer Science', '2', 2, 2, 'Clean Code', 'Java', 'Robert C. Martin', 'book_images/Clean Code.jpg', 3, '2008', 'Prentice Hall', '7890', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-20', '2025-03-23'),
(3, 3, 'vijay', '23PCS114', 'vijay@gmail.com', 'M.Sc.', 'Computer Science', '2', 5, 4, 'Database System Concepts', 'Python', 'Abraham Silberschatz, Henry Korth', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', 12, 950, 'Cupboard 2', 'Shelve 1', '2025-03-20', '2025-03-23'),
(4, 2, 'PRAKASH S', '23PCS113', 'gaccprakashlab@gmail.com', 'M.Sc.', 'Computer Science', '2', 1, 1, 'The Pragmatic Programmer', 'Java', 'Andrew Hunt, David Thomas', 'book_images/The Pragmatic Programmer.jpg', 5, '1999', 'Addison-Wesley', '2147483647', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-24', '2025-03-24'),
(5, 2, 'PRAKASH S', '23PCS113', 'gaccprakashlab@gmail.com', 'M.Sc.', 'Computer Science', '2', 1, 1, 'The Pragmatic Programmer', 'Java', 'Andrew Hunt, David Thomas', 'book_images/The Pragmatic Programmer.jpg', 5, '1999', 'Addison-Wesley', '2147483647', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-24', '2025-03-24'),
(6, 2, 'PRAKASH S', '23PCS113', 'gaccprakashlab@gmail.com', 'M.Sc.', 'Computer Science', '2', 1, 1, 'The Pragmatic Programmer', 'Java', 'Andrew Hunt, David Thomas', 'book_images/The Pragmatic Programmer.jpg', 5, '1999', 'Addison-Wesley', '2147483647', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-24', '2025-03-24');

-- --------------------------------------------------------

--
-- Table structure for table `returned_books_staffs`
--

CREATE TABLE `returned_books_staffs` (
  `return_id_staff` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(300) NOT NULL,
  `book_image` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `returned_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `returned_books_staffs`
--

INSERT INTO `returned_books_staffs` (`return_id_staff`, `staff_id`, `name`, `pfno`, `email`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`, `issued_date`, `returned_date`) VALUES
(2, 2, 'PRAKASH S', 123, 'gaccprakashlab@gmail.com', 5, 4, 'Database System Concepts', 'Python', 'Abraham Silberschatz, Henry Korth', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', 14, 950, 'Cupboard 2', 'Shelve 1', '2025-03-15', '2025-03-26');

-- --------------------------------------------------------

--
-- Table structure for table `selected_departments`
--

CREATE TABLE `selected_departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(250) NOT NULL,
  `selected_at` varchar(500) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `selected_departments`
--

INSERT INTO `selected_departments` (`id`, `department_name`, `selected_at`) VALUES
(1, 'Tamil', '2025-02-01 06:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `shelves`
--

CREATE TABLE `shelves` (
  `shelve_id` int(11) NOT NULL,
  `shelve_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shelves`
--

INSERT INTO `shelves` (`shelve_id`, `shelve_name`) VALUES
(1, 'Shelve 1'),
(2, 'Shelve 2'),
(5, 'Shelve 3');

-- --------------------------------------------------------

--
-- Table structure for table `staff_acc_create`
--

CREATE TABLE `staff_acc_create` (
  `staff_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `pfno` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff_acc_create`
--

INSERT INTO `staff_acc_create` (`staff_id`, `name`, `pfno`, `email`, `password`) VALUES
(2, 'PRAKASH S', '123', 'gaccprakashlab@gmail.com', '$2y$10$OySUxSjDHKtj8DFiy5Y8.eKg1nruJ9uvcYWFiUSO1F08z.00R/icm'),
(3, 'RAMA B', '456', 'rama@gmail.com', '$2y$10$FuKgKCiSYrHKTrqSqRi/6uafUy/inqSzRf2wKNj.sCWLczOmElS4S');

-- --------------------------------------------------------

--
-- Table structure for table `staff_renewed_books`
--

CREATE TABLE `staff_renewed_books` (
  `renewal_staff_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` varchar(250) NOT NULL,
  `email` varchar(300) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(300) NOT NULL,
  `book_image` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `exp_return_date` date NOT NULL,
  `renewed_date` date NOT NULL,
  `new_return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff_renewed_books`
--

INSERT INTO `staff_renewed_books` (`renewal_staff_id`, `staff_id`, `name`, `pfno`, `email`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`, `issued_date`, `exp_return_date`, `renewed_date`, `new_return_date`) VALUES
(1, 2, 'PRAKASH S', '123', 'gaccprakashlab@gmail.com', 5, 4, 'Database System Concepts', 'Python', 'Abraham Silberschatz, Henry Korth, S. Sudarshan', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', 12, 950, 'Cupboard 2', 'Shelve 1', '2025-03-15', '2025-06-13', '2025-03-15', '2025-09-11');

-- --------------------------------------------------------

--
-- Table structure for table `student_renewed_books`
--

CREATE TABLE `student_renewed_books` (
  `renewal_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(250) NOT NULL,
  `email` varchar(300) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(300) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(300) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `exp_return_date` date NOT NULL,
  `renewed_date` date NOT NULL,
  `new_return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `st_acc_create`
--

CREATE TABLE `st_acc_create` (
  `stu_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `selectyourdegree` varchar(10) NOT NULL,
  `selectyourcourse` varchar(100) NOT NULL,
  `rollno` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `number` varchar(10) NOT NULL,
  `address` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `batch_starting_year` varchar(500) NOT NULL,
  `batch_ending_year` varchar(500) NOT NULL,
  `currectstudyingyear` varchar(500) NOT NULL,
  `college_join_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `st_acc_create`
--

INSERT INTO `st_acc_create` (`stu_id`, `username`, `selectyourdegree`, `selectyourcourse`, `rollno`, `email`, `number`, `address`, `password`, `batch_starting_year`, `batch_ending_year`, `currectstudyingyear`, `college_join_date`) VALUES
(2, 'PRAKASH S', 'M.Sc.', 'Computer Science', '23PCS113', 'gaccprakashlab@gmail.com', '6380443779', '154/1, Neyveli, Cuddalore District.', '$2y$10$TyrZritGpsRK0K/lRzekxeWy1eFDlrXqf0veztcJbD1c0UK0bfJMW', '2023', '2025', '2', '2023-09-13'),
(3, 'vijay', 'M.Sc.', 'Computer Science', '23PCS114', 'vijay@gmail.com', '1232112213', 'NEYVELI TOWNSHIP', '$2y$10$J8gNXxhx3bt4dq/uWd8Cl.3JMKHLhs3HjUkUuXlE6Nzp.U8jnqGbW', '2023', '2025', '2', '2023-08-25'),
(4, 'Ramakrishnan B', 'M.Sc.', 'Computer Science', '23PCS115', 'rama@gmail.com', '0012345678', 'SUPER BAZAR', '$2y$10$WJxDjbTAgsbPEqij5Gz62uxlzZXIb0pg5RPdXxy2Y7FFyBlawYkAG', '2023', '2025', '2', '2023-08-23');

-- --------------------------------------------------------

--
-- Table structure for table `temp_borrow_book_request`
--

CREATE TABLE `temp_borrow_book_request` (
  `tid` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `rollno` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `selectyourdegree` varchar(250) NOT NULL,
  `selectyourcourse` varchar(250) NOT NULL,
  `currectstudyingyear` varchar(250) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(250) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issue_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `temp_borrow_book_request`
--

INSERT INTO `temp_borrow_book_request` (`tid`, `stu_id`, `username`, `rollno`, `email`, `selectyourdegree`, `selectyourcourse`, `currectstudyingyear`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`, `issue_date`) VALUES
(30, 2, 'PRAKASH S', '23PCS113', 'gaccprakashlab@gmail.com', 'M.Sc.', 'Computer Science', '2', 1, 1, 'The Pragmatic Programmer', 'Java', 'Andrew Hunt, David Thomas', 'book_images/The Pragmatic Programmer.jpg', 5, '1999', 'Addison-Wesley', '2147483647', 2, 750, 'Cupboard 1', 'Shelve 1', '2025-03-30');

-- --------------------------------------------------------

--
-- Table structure for table `temp_staffs_borrow_book_request`
--

CREATE TABLE `temp_staffs_borrow_book_request` (
  `tid` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` int(11) NOT NULL,
  `email` varchar(500) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(300) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `temp_staffs_borrow_book_request`
--

INSERT INTO `temp_staffs_borrow_book_request` (`tid`, `staff_id`, `name`, `pfno`, `email`, `bid`, `accession_number`, `title`, `book_keyword`, `author`, `book_image`, `copies`, `publication`, `publisher`, `isbn`, `availability`, `price`, `cupboard_name`, `shelve_name`) VALUES
(14, 2, 'PRAKASH S', 123, 'gaccprakashlab@gmail.com', 5, 4, 'Database System Concepts', 'Python', 'Abraham Silberschatz, Henry Korth', 'book_images/Database System Concepts.jpg', 15, '2019', 'McGraw Hill', '978-9353164811', 13, 950, 'Cupboard 2', 'Shelve 1');

-- --------------------------------------------------------

--
-- Table structure for table `temp_staffs_renew_book_request`
--

CREATE TABLE `temp_staffs_renew_book_request` (
  `temp_renew_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` varchar(250) NOT NULL,
  `email` varchar(300) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `book_image` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL,
  `issued_date` date NOT NULL,
  `due_date` date NOT NULL,
  `new_return_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `temp_staffs_return_book_request`
--

CREATE TABLE `temp_staffs_return_book_request` (
  `trid_staff` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `pfno` int(11) NOT NULL,
  `email` varchar(500) NOT NULL,
  `bid` int(11) NOT NULL,
  `accession_number` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `book_keyword` varchar(250) NOT NULL,
  `author` varchar(300) NOT NULL,
  `book_image` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL,
  `publication` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `isbn` varchar(250) NOT NULL,
  `availability` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `cupboard_name` varchar(250) NOT NULL,
  `shelve_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_books`
--
ALTER TABLE `add_books`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `admin_acc_create`
--
ALTER TABLE `admin_acc_create`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `book_keywords`
--
ALTER TABLE `book_keywords`
  ADD PRIMARY KEY (`book_keyword_id`);

--
-- Indexes for table `cupboards`
--
ALTER TABLE `cupboards`
  ADD PRIMARY KEY (`cupboard_id`);

--
-- Indexes for table `int_student_renewal_books`
--
ALTER TABLE `int_student_renewal_books`
  ADD PRIMARY KEY (`in_renewal_id`);

--
-- Indexes for table `int_temp_borrow_book_request`
--
ALTER TABLE `int_temp_borrow_book_request`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `int_temp_return_book_request`
--
ALTER TABLE `int_temp_return_book_request`
  ADD PRIMARY KEY (`ini_temp_id`);

--
-- Indexes for table `issued_books`
--
ALTER TABLE `issued_books`
  ADD PRIMARY KEY (`issu_id`);

--
-- Indexes for table `issued_books_staffs`
--
ALTER TABLE `issued_books_staffs`
  ADD PRIMARY KEY (`staff_b_issu_id`);

--
-- Indexes for table `lib_acc_create`
--
ALTER TABLE `lib_acc_create`
  ADD PRIMARY KEY (`lib_id`);

--
-- Indexes for table `returned_books`
--
ALTER TABLE `returned_books`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `returned_books_staffs`
--
ALTER TABLE `returned_books_staffs`
  ADD PRIMARY KEY (`return_id_staff`);

--
-- Indexes for table `selected_departments`
--
ALTER TABLE `selected_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shelves`
--
ALTER TABLE `shelves`
  ADD PRIMARY KEY (`shelve_id`);

--
-- Indexes for table `staff_acc_create`
--
ALTER TABLE `staff_acc_create`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `staff_renewed_books`
--
ALTER TABLE `staff_renewed_books`
  ADD PRIMARY KEY (`renewal_staff_id`);

--
-- Indexes for table `student_renewed_books`
--
ALTER TABLE `student_renewed_books`
  ADD PRIMARY KEY (`renewal_id`);

--
-- Indexes for table `st_acc_create`
--
ALTER TABLE `st_acc_create`
  ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `temp_borrow_book_request`
--
ALTER TABLE `temp_borrow_book_request`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `temp_staffs_borrow_book_request`
--
ALTER TABLE `temp_staffs_borrow_book_request`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `temp_staffs_renew_book_request`
--
ALTER TABLE `temp_staffs_renew_book_request`
  ADD PRIMARY KEY (`temp_renew_id`);

--
-- Indexes for table `temp_staffs_return_book_request`
--
ALTER TABLE `temp_staffs_return_book_request`
  ADD PRIMARY KEY (`trid_staff`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_books`
--
ALTER TABLE `add_books`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin_acc_create`
--
ALTER TABLE `admin_acc_create`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book_keywords`
--
ALTER TABLE `book_keywords`
  MODIFY `book_keyword_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cupboards`
--
ALTER TABLE `cupboards`
  MODIFY `cupboard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `int_student_renewal_books`
--
ALTER TABLE `int_student_renewal_books`
  MODIFY `in_renewal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `int_temp_borrow_book_request`
--
ALTER TABLE `int_temp_borrow_book_request`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `int_temp_return_book_request`
--
ALTER TABLE `int_temp_return_book_request`
  MODIFY `ini_temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `issued_books`
--
ALTER TABLE `issued_books`
  MODIFY `issu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `issued_books_staffs`
--
ALTER TABLE `issued_books_staffs`
  MODIFY `staff_b_issu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lib_acc_create`
--
ALTER TABLE `lib_acc_create`
  MODIFY `lib_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `returned_books`
--
ALTER TABLE `returned_books`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `returned_books_staffs`
--
ALTER TABLE `returned_books_staffs`
  MODIFY `return_id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `selected_departments`
--
ALTER TABLE `selected_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shelves`
--
ALTER TABLE `shelves`
  MODIFY `shelve_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_acc_create`
--
ALTER TABLE `staff_acc_create`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_renewed_books`
--
ALTER TABLE `staff_renewed_books`
  MODIFY `renewal_staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_renewed_books`
--
ALTER TABLE `student_renewed_books`
  MODIFY `renewal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `st_acc_create`
--
ALTER TABLE `st_acc_create`
  MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `temp_borrow_book_request`
--
ALTER TABLE `temp_borrow_book_request`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `temp_staffs_borrow_book_request`
--
ALTER TABLE `temp_staffs_borrow_book_request`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `temp_staffs_renew_book_request`
--
ALTER TABLE `temp_staffs_renew_book_request`
  MODIFY `temp_renew_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `temp_staffs_return_book_request`
--
ALTER TABLE `temp_staffs_return_book_request`
  MODIFY `trid_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
