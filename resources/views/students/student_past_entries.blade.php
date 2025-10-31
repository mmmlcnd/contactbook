@extends('layouts.dashboard')

@section('title', '提出済み連絡帳一覧')

@section('content')
<div class="container mx-auto p-4">
    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-xl p-6 md:p-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 border-b pb-3 text-center">
            連絡帳の提出履歴
        </h1>

        {{-- 月ナビゲーションとタイトル --}}
        <div class="flex items-center justify-between bg-indigo-50 p-4 rounded-lg mb-8 shadow-inner">
            <a href="?month={{ $previousMonth }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150">
                <i class="fas fa-chevron-left"></i> 前月へ
            </a>
            <h2 class="text-xl md:text-2xl font-bold text-indigo-800">
                {{ $displayMonth }} の連絡帳
            </h2>
            <a href="?month={{ $nextMonth }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150 @if($isFutureMonth) opacity-50 cursor-default @endif">
                次月へ <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        {{-- 連絡帳一覧 --}}
        <div class="space-y-6">
            @if (empty($pastEntries))
            {{-- データがない場合の表示 --}}
            <div class="p-8 text-center bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                <p class="text-gray-600 text-lg font-medium">
                    {{ $displayMonth }}の提出履歴はありません。
                </p>
            </div>
            @else
            @foreach ($pastEntries as $pastEntry)
            @php
            $entry = $pastEntry['entry'];
            $readHistory = $pastEntry['read_history'];
            $reportDate = $entry['record_date'];
            @endphp
            {{-- 個別の連絡帳カード --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out p-5">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-3 mb-3">
                    {{-- 日付と曜日 --}}
                    <div class="flex items-center mb-2 md:mb-0">
                        <span class="text-3xl font-extrabold text-gray-800 mr-3">
                            {{ date('d', strtotime($entry->record_date)) }}日
                        </span>
                        <span class="text-base font-semibold text-indigo-600">
                            （{{ $dayOfWeek = ['日', '月', '火', '水', '木', '金', '土'][date('w', strtotime($reportDate))]; }}）
                        </span>
                    </div>

                    {{-- 詳細ボタン --}}
                    <a href="/students/entries/{{ $entry->id }}" class="text-sm font-semibold text-white bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-full shadow-md transition duration-150 ease-in-out">
                        詳細を見る <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                {{-- 体調・メンタル --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex flex-col items-start p-3 bg-red-50 rounded-lg">
                        <span class="text-xs font-medium text-red-500 uppercase tracking-wider">体調</span>
                        <div class="text-2xl font-extrabold text-red-700 mt-1">
                            {{ $entry->condition_physical }} / 5
                        </div>
                    </div>
                    <div class="flex flex-col items-start p-3 bg-blue-50 rounded-lg">
                        <span class="text-xs font-medium text-blue-500 uppercase tracking-wider">メンタル</span>
                        <div class="text-2xl font-extrabold text-blue-700 mt-1">
                            {{ $entry->condition_mental }} / 5
                        </div>
                    </div>
                </div>

                {{-- 自由記述内容の一部表示 --}}
                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">
                        提出内容の抜粋
                    </h4>
                    <p class="text-gray-600 text-base line-clamp-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        {{ mb_substr($entry->content, 0, 100) }}...
                    </p>
                    @if ($entry->teacher_comment)
                    <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                        <span class="text-xs font-bold text-yellow-700 block mb-1"><i class="fas fa-comment-dots mr-1"></i> 先生からのコメント</span>
                        <p class="text-sm text-yellow-800 line-clamp-2">
                            {{ mb_substr($entry->teacher_comment, 0, 50) }}...
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>

        {{-- ページ下部のリンク --}}
        <div class="mt-10 pt-6 border-t border-gray-200 flex justify-center">
            <a href="/students/entries/create" class="inline-flex items-center text-lg font-bold text-indigo-600 hover:text-indigo-800 transition duration-150">
                <i class="fas fa-plus-circle mr-2"></i> 新しく連絡帳を提出する
            </a>
        </div>
    </div>
</div>
@endsection