@extends('layouts.app')

@section('title', '提出状況確認')

@section('content')

<div class="container mx-auto p-4">
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-6 md:p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">
            {{ $teacherName }} 先生の担当クラス提出状況
        </h1>

        <p class="text-sm text-gray-600 mb-6">
            この画面では、あなたの担当するクラスの生徒が提出した最新の連絡帳の状態を確認できます。
        </p>

        <!-- 提出状況テーブル -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border-collapse">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-semibold rounded-tl-lg">生徒氏名</th>
                        <th class="py-3 px-4 text-left text-sm font-semibold">最新提出日</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">状態</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold rounded-tr-lg">アクション</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @if (empty($students))
                    <tr>
                        <td colspan="4" class="text-center py-8 text-lg text-gray-500">
                            担当クラスに生徒がいません、またはデータ取得に失敗しました。
                        </td>
                    </tr>
                    @else
                    @foreach ($students as $student)
                    @php
                    // 最新エントリの未読状態をチェック
                    $isUnread = $student->latest_entry && $student->latest_entry->is_read == 0;
                    @endphp

                    <tr class="border-t hover:bg-gray-50 {{ $isUnread ? 'bg-yellow-50' : '' }}">
                        <td class="py-4 px-4 font-semibold text-lg">
                            {{ $student->name }}
                            <span class="block text-xs text-gray-500">
                                (ID: {{ $student->id }})
                            </span>
                        </td>

                        <td class="py-4 px-4 whitespace-nowrap">
                            @if ($student->latest_entry)
                            {{ $student->latest_entry->entry_date }}
                            @else
                            <span class="text-gray-400">未提出</span>
                            @endif
                        </td>

                        <td class="py-4 px-4 text-center">
                            @if ($student->latest_entry)
                            @if ($isUnread)
                            <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-medium leading-none text-red-700 bg-red-100 rounded-full">
                                未読
                            </span>
                            @else
                            <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-medium leading-none text-green-700 bg-green-100 rounded-full">
                                既読
                            </span>
                            @endif
                            @else
                            -
                            @endif
                        </td>

                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if ($student->latest_entry && $isUnread)
                            <!-- 未読の場合のみ「既読にする」ボタンを表示 -->
                            <a href="/teachers/read/{{ $student->latest_entry->id }}"
                                class="inline-block bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md text-sm"
                                onclick="return confirm('本当にこの連絡帳を既読にしますか？');">
                                既読にする
                            </a>
                            @elseif ($student->latest_entry)
                            <span class="text-gray-400 text-sm">確認済み</span>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-8 pt-4 border-t text-center">
            <a href="/teachers/dashboard" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                ダッシュボードに戻る
            </a>
        </div>
    </div>
</div>

@endsection