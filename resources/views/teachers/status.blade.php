@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $teacher->class->grade }}年{{ $teacher->class->name }} 担任用：提出状況一覧</h1>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>生徒名</th>
                <th>最新の提出日</th>
                <th>内容</th>
                <th>確認済み</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            @php
            $latestEntry = $student->entries->sortByDesc('entry_date')->first();
            @endphp
            <tr>
                <td>{{ $student->user->name }}</td>
                <td>{{ $latestEntry->entry_date ?? '-' }}</td>
                <td>{{ $latestEntry->content ?? '-' }}</td>
                <td>
                    @if ($latestEntry && $latestEntry->is_read)
                    ✅
                    @else
                    ❌
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
