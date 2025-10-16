-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-10-16 04:02:05
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `contactbook_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `kana` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `admins`
--

INSERT INTO `admins` (`id`, `name`, `kana`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, '校内管理者', 'コウナイカンリシャ', 'admin@test.com', '$2y$10$uQW2vppI2PfFtiFkNhRD4Oi.nFIEy52carUDtfKJ7XOpZOXVvpDs2', '2025-10-13 14:24:50', '2025-10-13 14:42:55'),
(2, '校内管理者2', '', 'admin2@test.com', '$2y$10$.1suDK2qziEy.cAHG3MdYur6nj2679OQ.FriY.po8a9d0O//EBx/G', '2025-10-15 15:23:22', '2025-10-15 15:23:22'),
(4, '校内管理者3', '', 'admin3@test.com', '$2y$10$8.RdW1GOaQ9ZzijOmolPF.PA1FmzkQdF.J83nnim0eaHhPBnerkgG', '2025-10-15 15:57:50', '2025-10-15 15:57:50'),
(5, '校内管理者4', '', 'admin4@test.com', '$2y$10$AntBjkQwfM63h5x2rurINet3TF7Xo4zD9h1lTiF5xaYiK5c3fsDVu', '2025-10-16 01:31:56', '2025-10-16 01:31:56'),
(6, '校内管理者5', '', 'admin5@test.com', '$2y$10$XU4OZV1al8B27y2zpNl/Zuks.VFuz53p55EJbEbipuFNtjCllpeJe', '2025-10-16 01:39:03', '2025-10-16 01:39:03'),
(7, '校内管理者6', 'コウナイカンリシャロク', 'admin6@test.com', '$2y$10$KuygzbtmvyrPHxCWm7NZiOt1KgTL8.mAJr755D8o2v6Nvzj/8U.L6', '2025-10-16 01:48:56', '2025-10-16 01:48:56');

-- --------------------------------------------------------

--
-- テーブルの構造 `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `classes`
--

INSERT INTO `classes` (`id`, `name`, `grade`, `created_at`, `updated_at`) VALUES
(1, 'A組', 1, '2025-10-07 04:17:07', '2025-10-07 04:17:07'),
(2, 'B組', 1, '2025-10-07 04:17:07', '2025-10-07 04:17:07'),
(3, 'A組', 2, '2025-10-07 04:17:07', '2025-10-07 04:17:07'),
(4, 'B組', 2, '2025-10-07 04:17:07', '2025-10-07 04:17:07'),
(5, 'A組', 3, '2025-10-07 04:17:07', '2025-10-07 04:17:07'),
(6, 'B組', 3, '2025-10-07 04:17:07', '2025-10-07 04:17:07');

-- --------------------------------------------------------

--
-- テーブルの構造 `entries`
--

CREATE TABLE `entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `condition_physical` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `condition_mental` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `record_date` date NOT NULL COMMENT '連絡帳の記録日（前日分）',
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `entries`
--

INSERT INTO `entries` (`id`, `student_id`, `condition_physical`, `condition_mental`, `record_date`, `content`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 5, '2025-10-13', '昨日は体調は良好でした。数学の宿題を完璧に終わらせました。明日の登校は通常通りです。', 1, '2025-10-13 14:24:50', '2025-10-13 14:24:50'),
(2, 2, 4, 3, '2025-10-13', '昨日は少し頭痛がありましたが、早めに寝ました。部活は休まず参加しました。', 0, '2025-10-13 14:24:50', '2025-10-13 14:24:50'),
(3, 3, 3, 4, '2025-10-13', '歴史の授業が楽しかった。体調は特に問題なしです。', 0, '2025-10-13 14:24:50', '2025-10-13 14:24:50'),
(4, 1, 5, 5, '2025-10-14', '今日の記録です。体調良好。運動会の練習頑張ります。', 1, '2025-10-15 11:38:28', '2025-10-15 11:38:28'),
(5, 2, 4, 4, '2025-10-14', '英語の単語を覚えるのが大変でした。明日は小テストです。', 0, '2025-10-15 11:38:28', '2025-10-15 11:38:28'),
(6, 3, 5, 4, '2025-10-14', '友達と公園で遊びました。明日の準備は完璧です。', 0, '2025-10-15 11:38:28', '2025-10-15 11:38:28');

-- --------------------------------------------------------

--
-- テーブルの構造 `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(9, '0001_01_01_000000_create_users_table', 1),
(10, '0001_01_01_000001_create_cache_table', 1),
(11, '0001_01_01_000002_create_jobs_table', 1),
(12, '2025_10_06_030519_add_role_to_users_table', 1),
(13, '2025_10_06_035330_create_classes_table', 1),
(14, '2025_10_06_035654_create_students_table', 1),
(15, '2025_10_06_035702_create_entries_table', 1),
(16, '2025_10_06_065326_create_teachers_table', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `read_histories`
--

CREATE TABLE `read_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `entry_id` int(10) UNSIGNED NOT NULL COMMENT '対象の連絡帳エントリID',
  `teacher_id` int(10) UNSIGNED NOT NULL COMMENT '既読処理を行った担任ID',
  `stamp_id` int(10) UNSIGNED NOT NULL,
  `stamped_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '既読処理が行われた日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `read_histories`
--

INSERT INTO `read_histories` (`id`, `entry_id`, `teacher_id`, `stamp_id`, `stamped_at`) VALUES
(1, 1, 1, 1, '2025-10-14 00:00:00'),
(2, 1, 2, 2, '2025-10-14 01:30:00'),
(6, 4, 1, 3, '2025-10-14 23:30:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('rTewFSiXqSDk8FMeixvWwep9SnK0vjKHfy0QO5ob', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicDR2U1BLbGU2QTYwWnR1M2F2MkhjdURkZ1NUNWMwYURvM3BnT2lGYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbnMvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760543870),
('w1iRvvRFVZ9qrJ0UrtAy8LlYWSwGo25HL7kOd2QY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicnhRYUJXUk5vbVZaNDR0QzRUUkVrcFFxS0FXSmtlbEhjTExHWjBFVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbnMvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760579957);

-- --------------------------------------------------------

--
-- テーブルの構造 `stamps`
--

CREATE TABLE `stamps` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'スタンプ名 (例: イイネ, 頑張ったね)',
  `description` varchar(255) DEFAULT NULL COMMENT 'スタンプの説明',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `stamps`
--

INSERT INTO `stamps` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'イイネ', '生徒の記録を評価・承認', '2025-10-13 14:24:50', '2025-10-13 14:24:50'),
(2, '頑張ったね', '学習面を特に評価', '2025-10-13 14:24:50', '2025-10-13 14:24:50'),
(3, 'お大事に', '体調面を気遣う', '2025-10-13 14:24:50', '2025-10-13 14:24:50');

-- --------------------------------------------------------

--
-- テーブルの構造 `students`
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `kana` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `grade` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `permission` enum('write','read') DEFAULT 'write',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `students`
--

INSERT INTO `students` (`id`, `name`, `kana`, `email`, `password`, `grade`, `class`, `permission`, `created_at`, `updated_at`) VALUES
(1, '山田 太郎', 'ヤマダタロウ', 'taro@test.com', '$2y$10$uQW2vppI2PfFtiFkNhRD4Oi.nFIEy52carUDtfKJ7XOpZOXVvpDs2', 1, 'A組', 'write', '2025-10-13 14:24:50', '2025-10-15 08:42:32'),
(2, '伊藤 花子', 'イトウハナコ', 'hanako@test.com', '$2y$10$uQW2vppI2PfFtiFkNhRD4Oi.nFIEy52carUDtfKJ7XOpZOXVvpDs2', 1, 'A組', 'write', '2025-10-13 14:24:50', '2025-10-15 08:42:39'),
(3, '小林 次郎', 'コバヤシジロウ', 'jiro@test.com', '$2y$10$uQW2vppI2PfFtiFkNhRD4Oi.nFIEy52carUDtfKJ7XOpZOXVvpDs2', 2, 'B組', 'write', '2025-10-13 14:24:50', '2025-10-15 08:42:45'),
(4, 'テスト', 'テスト', 'student1@test.com', '$2y$10$iLX6Huz0XhAytM.YiS4dOeGzVSj3SE1s/GIvSY11XV0vy8HAHKg7u', 3, '1', 'write', '2025-10-16 01:50:18', '2025-10-16 01:50:18');

-- --------------------------------------------------------

--
-- テーブルの構造 `teachers`
--

CREATE TABLE `teachers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `kana` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `grade` int(11) NOT NULL,
  `class` varchar(10) NOT NULL,
  `permission` enum('write','read') DEFAULT 'read',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `kana`, `email`, `password`, `grade`, `class`, `permission`, `created_at`, `updated_at`) VALUES
(1, '田中 テスト', 'タナカ テスト', 'tanaka@test.com', '$2y$10$uQW2vppI2PfFtiFkNhRD4Oi.nFIEy52carUDtfKJ7XOpZOXVvpDs2', 1, 'A組', 'read', '2025-10-13 14:24:50', '2025-10-15 11:16:50'),
(2, '佐藤 テスト', 'サトウ テスト', 'sato@test.com', '$2y$10$uQW2vppI2PfFtiFkNhRD4Oi.nFIEy52carUDtfKJ7XOpZOXVvpDs2', 2, 'B組', 'read', '2025-10-13 14:24:50', '2025-10-15 11:16:41'),
(3, 'テスト', 'テスト', 'teacher@test.com', '$2y$10$RhZNFidD5ZayyRCsVxfqFOPvGCjZkgsdur7WL0CAQBpfe6FOGxH3e', 3, '1', 'read', '2025-10-16 01:51:10', '2025-10-16 01:51:10');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- テーブルのインデックス `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- テーブルのインデックス `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- テーブルのインデックス `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student` (`student_id`);

--
-- テーブルのインデックス `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- テーブルのインデックス `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- テーブルのインデックス `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- テーブルのインデックス `read_histories`
--
ALTER TABLE `read_histories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_entry_teacher_stamp` (`entry_id`,`teacher_id`,`stamp_id`),
  ADD KEY `fk_read_history_teacher` (`teacher_id`),
  ADD KEY `fk_read_history_stamp` (`stamp_id`);

--
-- テーブルのインデックス `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- テーブルのインデックス `stamps`
--
ALTER TABLE `stamps`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- テーブルのインデックス `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルの AUTO_INCREMENT `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `entries`
--
ALTER TABLE `entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- テーブルの AUTO_INCREMENT `read_histories`
--
ALTER TABLE `read_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `stamps`
--
ALTER TABLE `stamps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `entries`
--
ALTER TABLE `entries`
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- テーブルの制約 `read_histories`
--
ALTER TABLE `read_histories`
  ADD CONSTRAINT `fk_read_history_entry` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_read_history_stamp` FOREIGN KEY (`stamp_id`) REFERENCES `stamps` (`id`),
  ADD CONSTRAINT `fk_read_history_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
