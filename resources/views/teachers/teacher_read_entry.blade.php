@extends('layouts.app')

@section('title', '連絡帳確認 - ' . $entry->student_name)

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-8 bg-white shadow-lg rounded-xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
        <a href="/teachers/status" class="text-blue-500 hover:text-blue-700 transition duration-150 ease-in-out">
            &larr; 提出一覧に戻る
        </a>
    </h1>

    <div class="bg-indigo-50 p-6 rounded-lg mb-6">
        <h2 class="text-xl font-semibold text-indigo-700 mb-2">
            {{ $entry->student_name }} ({{ $entry->student_class }}) の連絡帳
        </h2>
        <p class="text-sm text-indigo-500">記録日: {{ $entry->record_date }}</p>
    </div>

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

    <div class="space-y-6">
        <!-- 体調・精神状態 -->
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-sm font-medium text-gray-500">体調 (5段階)</p>
                <p class="text-2xl font-extrabold text-blue-600">{{ $entry->condition_physical }}</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-sm font-medium text-gray-500">精神状態 (5段階)</p>
                <p class="text-2xl font-extrabold text-purple-600">{{ $entry->condition_mental }}</p>
            </div>
        </div>

        <!-- 連絡帳の内容 -->
        <div class="p-5 bg-gray-50 border border-gray-200 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">連絡内容</h3>
            <p class="whitespace-pre-wrap text-gray-800">{{ $entry->content }}</p>
        </div>

        <!-- スタンプ・既読履歴 -->
        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">先生からの確認・スタンプ履歴</h3>

            @if ($readHistory)
            @foreach ($readHistory as $history)
            <div class="flex items-center space-x-3 mb-2 p-3 bg-white rounded-lg border border-gray-100 shadow-sm">
                <span class="text-2xl" role="img" aria-label="Stamp">
                    @if ($history->stamp_name == 'イイネ') 👍 @elseif ($history->stamp_name == '頑張ったね') ✨ @elseif ($history->stamp_name == 'お大事に') 🍀 @else 🏷️ @endif
                </span>
                <div class="flex-1">
                    <span class="font-bold text-sm text-gray-800">{{ $history->stamp_name }}</span>
                    <span class="text-xs text-gray-500 ml-2">({{ $history->teacher_name }})</span>
                </div>
                <span class="text-xs text-gray-400">
                    {{ date('Y-m-d H:i', strtotime($history->stamped_at)) }}
                </span>
            </div>
            @endforeach
            @else
            <p class="text-gray-500 italic">まだどの先生からも確認されていません。</p>
            @endif
        </div>

        <!-- スタンプ押印エリア -->
        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">スタンプを押して確認済みにする</h3>

            @if ($currentTeacherStamped)
            <p class="text-green-600 font-medium p-3 bg-green-50 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i> {{ $teacher->name }}先生は既に確認済みのスタンプを押しています。
            </p>
            @else
            <form method="POST" action="/teachers/stamp/{{ $entry->id }}" class="flex flex-wrap gap-4">
                <input type="hidden" name="_method" value="POST">

                @foreach ($stamps as $stamp)
                <button type="submit" name="stamp_id" value="{{ $stamp->id }}"
                    class="flex items-center justify-center p-3 border-2 border-transparent rounded-lg text-lg font-semibold transition duration-150 ease-in-out shadow-md hover:shadow-lg
                                       @if ($stamp->name == 'イイネ') bg-yellow-400 text-gray-800 hover:bg-yellow-500 @endif
                                       @if ($stamp->name == '頑張ったね') bg-blue-500 text-white hover:bg-blue-600 @endif
                                       @if ($stamp->name == 'お大事に') bg-red-400 text-gray-800 hover:bg-red-500 @endif
                                       ">
                    @if ($stamp->name == 'イイネ') 👍 イイネ @endif
                    @if ($stamp->name == '頑張ったね') ✨ 頑張ったね @endif
                    @if ($stamp->name == 'お大事に') 🍀 お大事に @endif
                </button>
                @endforeach

                <p class="text-sm text-gray-500 mt-2 w-full">※ スタンプを押すと、この連絡帳は既読としてマークされます。</p>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection