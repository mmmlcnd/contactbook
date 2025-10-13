-- 全ユーザーのパスワードは、文字列 'password' を bcrypt でハッシュ化した値です。
-- ハッシュ値: $2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.

INSERT INTO `admins` (`id`, `name`, `kana`, `email`, `password`) VALUES
(1, '校内管理者', 'コウナイカンリシャ', 'admin@test.com', '$2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.');

INSERT INTO `teachers` (`id`, `name`, `kana`, `email`, `password`, `grade`, `class`) VALUES
(1, '田中 先生', 'タナカセンセイ', 'tanaka@test.com', '$2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.', 1, 'A組'),
(2, '佐藤 先生', 'サトウセンセイ', 'sato@test.com', '$2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.', 2, 'B組');

INSERT INTO `students` (`id`, `name`, `kana`, `email`, `password`, `grade`, `class`, `permission`) VALUES
(1, '山田 太郎', 'ヤマダタロウ', 'taro@test.com', '$2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.', 1, 'A組', 'write'),
(2, '伊藤 花子', 'イトウハナコ', 'hanako@test.com', '$2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.', 1, 'A組', 'write'),
(3, '小林 次郎', 'コバヤシジロウ', 'jiro@test.com', '$2y$10$wE9K2Y7nQ3F5pGz1eI8tqO.d9hZ2vU4c5P6X7R8s0T1u2V3w4Y5z.', 2, 'B組', 'write');

INSERT INTO `stamps` (`id`, `name`, `description`) VALUES
(1, 'イイネ', '生徒の記録を評価・承認'),
(2, '頑張ったね', '学習面を特に評価'),
(3, 'お大事に', '体調面を気遣う');

INSERT INTO `entries` (`id`, `student_id`, `record_date`, `condition_physical`, `condition_mental`, `content`, `is_read`) VALUES
(1, 1, '2025-10-13', 5, 5, '昨日は体調は良好でした。数学の宿題を完璧に終わらせました。明日の登校は通常通りです。', 1),
(2, 2, '2025-10-13', 4, 3, '昨日は少し頭痛がありましたが、早めに寝ました。部活は休まず参加しました。', 0),
(3, 3, '2025-10-13', 3, 4, '歴史の授業が楽しかった。体調は特に問題なしです。', 0);

INSERT INTO `read_histories` (`entry_id`, `teacher_id`, `stamp_id`, `stamped_at`) VALUES
(1, 1, 1, '2025-10-14 09:00:00'),
(1, 2, 2, '2025-10-14 10:30:00');
