@extends('layouts.dashboard')

@section('title', '連絡帳提出')

@section('content')

@php
// 報告対象日を計算するロジック
// 提出日に応じて、記録対象となる平日（月〜金）の日付を確定させる

$today = new \DateTime('today');
$dayOfWeek = (int) $today->format('w'); // 0 (日) - 6 (土)

$reportDate = clone $today; // 今日の日付をコピーし、ここから日数を遡る

// 曜日ごとに遡る日数を決定
if ($dayOfWeek === 1) { // 提出日が月曜日
// 月曜提出は前週の金曜日分（3日遡る: 月→日→土→金）
$reportDate->modify('-3 days');
} elseif ($dayOfWeek === 0) { // 提出日が日曜日
// 日曜提出は金曜日分（2日遡る: 日→土→金）
$reportDate->modify('-2 days');
} else { // 提出日が火・水・木・金・土曜・
// 火〜土曜提出は前日の平日分（1日遡る）
$reportDate->modify('-1 day');
}

// 確定した報告対象日を日本語形式に変換
$reportDateText = $reportDate->format('Y年m月d日');
@endphp

<div class="container mx-auto p-4">
    <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-xl p-6 md:p-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-3 text-center">
            連絡帳提出フォーム
        </h1>

        <div class="bg-indigo-50 p-4 rounded-lg mb-6">
            <p class="text-xl font-bold text-indigo-700 text-center mb-6">
                報告対象日: {{ $reportDateText }} の状況
            </p>
            <p class="text-sm text-indigo-500">
                提出日: {{ date('Y年m月d日') }}
            </p>
        </div>

        {{-- メッセージ表示エリア --}}
        @if (isset($_SESSION['success_message']))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
            {{ $_SESSION['success_message'] }}
            @php unset($_SESSION['success_message']); @endphp
        </div>
        @endif
        @if (isset($_SESSION['error_message']))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
            {{ $_SESSION['error_message'] }}
            @php unset($_SESSION['error_message']); @endphp
        </div>
        @endif

        {{-- 連絡帳提出フォーム --}}
        <form action="{{ route('students.entries.store') }}" method="POST" class="space-y-8">
            <input type="hidden" name="_method" value="POST">
            @csrf

            <!-- 身体的コンディション -->
            <div class="form-group p-4 border border-gray-200 rounded-lg">
                <label for="condition_physical" class="block text-xl font-bold text-gray-700 mb-3">
                    1. 体調
                    <span class="text-sm font-normal text-gray-500 block">（5: 絶好調 〜 1: 体調不良）</span>
                </label>
                <input type="range" id="condition_physical" name="condition_physical" min="1" max="5" value="5" oninput="updateConditionValue('physical', this.value)"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg">
                <div class="flex justify-between text-gray-600 mt-2 text-sm">
                    <span>1 (不良)</span>
                    <span>5 (絶好調)</span>
                </div>
                <div class="mt-3 text-center">
                    現在の評価: <span id="physical_value" class="text-2xl font-extrabold text-blue-600">5</span>
                </div>
            </div>

            <!-- 精神的コンディション -->
            <div class="form-group p-4 border border-gray-200 rounded-lg">
                <label for="condition_mental" class="block text-xl font-bold text-gray-700 mb-3">
                    2. メンタル
                    <span class="text-sm font-normal text-gray-500 block">（5: 非常に良い 〜 1: 落ち込んでいる）</span>
                </label>
                <input type="range" id="condition_mental" name="condition_mental" min="1" max="5" value="5" oninput="updateConditionValue('mental', this.value)"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-lg">
                <div class="flex justify-between text-gray-600 mt-2 text-sm">
                    <span>1 (悪い)</span>
                    <span>5 (非常に良い)</span>
                </div>
                <div class="mt-3 text-center">
                    現在の評価: <span id="mental_value" class="text-2xl font-extrabold text-blue-600">5</span>
                </div>
            </div>

            <!-- 連絡帳内容 (自由記述) -->
            <div class="form-group">
                <label for="content" class="block text-xl font-bold text-gray-700 mb-2">
                    3. 先生への連絡内容・報告事項
                </label>
                <textarea id="content" name="content" rows="6" required
                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800"
                    placeholder="例: 数学の宿題を終えました。明日の部活動は休みます。体調に変化はありません。"></textarea>
                <p class="mt-2 text-sm text-gray-500">
                    学校への連絡や、先生に伝えておきたいことなどを自由に記入してください。
                </p>
            </div>

            <!-- 提出ボタン -->
            <div class="flex justify-center pt-4">
                <button type="submit"
                    class="w-full md:w-1/2 inline-flex justify-center py-3 px-6 border border-transparent shadow-xl text-lg font-medium rounded-xl text-white bg-indigo-700">
                    <i class="fas fa-paper-plane mr-2"></i> 連絡帳を提出する
                </button>
            </div>

        </form>

        <div class="mt-6 text-center">
            <a href="/students/dashboard" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                ダッシュボードに戻る
            </a>
        </div>
    </div>
</div>

<script>
    /**
     * スライダーの値が変更されたときに、隣の表示値を更新する
     * @param {string} type 'physical' or 'mental'
     * @param {string} value スライダーの現在の値
     */
    function updateConditionValue(type, value) {
        document.getElementById(type + '_value').textContent = value;
    }
</script>
@endsection