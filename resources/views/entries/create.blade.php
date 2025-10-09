<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>連絡帳入力</title>
</head>

<body>
    <h1>連絡帳入力</h1>

    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('entries.store') }}" method="POST">
        @csrf
        <label>生徒ID:</label>
        <input type="number" name="student_id" value="1"><br><br>

        <label>日付:</label>
        <input type="date" name="entry_date" value="{{ $defaultDate }}"><br><br>

        <label>内容:</label><br>
        <textarea name="content" rows="5" cols="40"></textarea><br><br>

        <button type="submit">提出</button>
    </form>
</body>

</html>
