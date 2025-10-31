<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Entry;
use App\Models\ReadHistory;
use App\Models\Stamp;
use Carbon\Carbon;

class TestDatabaseSeeder extends Seeder
{
    public function run()
    {
        // 日本語ロケールを設定したFakerを使用
        $faker = \Faker\Factory::create('ja_JP');

        // --- 1. スタンプデータの作成（SQLデータの再現＋追加） ---
        $stampNames = [
            1 => 'イイネ',
            2 => '頑張ったね',
            3 => 'お大事に',
        ];

        $stamps = collect($stampNames)->map(function ($name, $id) {
            return Stamp::updateOrCreate(['id' => $id], ['name' => $name]);
        });
        $stampIds = $stamps->pluck('id')->toArray();

        // --- 2. 教師アカウントの作成 (2名: 1年A組担任, 1年B組担任) ---
        $teachersData = [
            'A' => Teacher::factory()->create([
                'name' => 'クラスA担任',
                'email' => 'teacher_a@test.com',
                'grade' => 1,
                'class_name' => 'A組',
                'kana' => $faker->kanaName,
            ]),
            'B' => Teacher::factory()->create([
                'name' => 'クラスB担任',
                'email' => 'teacher_b@test.com',
                'grade' => 1,
                'class_name' => 'B組',
                'kana' => $faker->kanaName,
            ]),
        ];

        // --- 3. 生徒アカウントの作成 (2クラス × 10名 = 20名) ---
        $classes = [
            ['grade' => 1, 'class_name' => 'A組', 'teacher' => $teachersData['A']],
            ['grade' => 1, 'class_name' => 'B組', 'teacher' => $teachersData['B']],
        ];

        $entriesToStamp = []; // 後でスタンプ処理を行うエントリーを格納

        foreach ($classes as $classInfo) {
            $classTeacher = $classInfo['teacher'];
            $grade = $classInfo['grade'];
            $className = $classInfo['class_name'];

            // 10名の生徒を生成
            for ($i = 0; $i < 10; $i++) {
                // Factoryが生成した基本データのうち、locale依存やuniqueな値を $faker で上書き
                $student = Student::factory()->create([
                    'grade' => $grade,
                    'class_name' => $className,

                    // Factoryの値を日本語Fakerで上書きする
                    'name' => $faker->name('male' | 'female'),
                    'kana' => $faker->kanaName,
                    'email' => $faker->unique()->safeEmail(),
                ]);

                // 過去3日分のエントリーを作成
                for ($j = 1; $j <= 3; $j++) {
                    $date = Carbon::today()->subDays($j);

                    // エントリーの作成
                    $entry = Entry::factory()->create([
                        'student_id' => $student->id,
                        'record_date' => $date->format('Y-m-d'),
                        'is_read' => false, // 初期状態は未読とする
                    ]);

                    // 既読/スタンプ処理を後で行うため、エントリーを一時的に保存 (例: 70%の確率でスタンプ対象)
                    if (rand(1, 100) <= 70) {
                        // 後の処理で $entriesToStamp を使用するために、エントリーIDと担任IDを保持
                        $entriesToStamp[] = [
                            'entry_id' => $entry->id,
                            'teacher_id' => $classTeacher->id,
                        ];
                    }
                }
            }
        }

        // --- 5. 既読履歴/スタンプ処理の一括実行 ---
        foreach ($entriesToStamp as $data) {
            $stampId = $stampIds[array_rand($stampIds)]; // ランダムなスタンプIDを選択

            // read_histories に記録を挿入
            ReadHistory::create([
                'entry_id' => $data['entry_id'],
                'teacher_id' => $data['teacher_id'],
                'stamp_id' => $stampId,
                'stamped_at' => Carbon::now()->subMinutes(rand(1, 1440)), // 過去24時間以内のランダムな時間
            ]);

            // entries テーブルの is_read フラグを 1 に更新
            Entry::where('id', $data['entry_id'])->update(['is_read' => 1]);
        }
    }
}
