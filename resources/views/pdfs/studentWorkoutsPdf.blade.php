<html>

<head>

</head>

<body>

    <h1>Student Workouts</h1>
    <p>Student Name: {{ $student_name }}</p>
    @foreach ($workouts as $day => $dayWorkouts)
        <p>Day: {{ $day }}</p>

        @if (count($dayWorkouts) > 0)
            @foreach ($dayWorkouts as $workout)
                <p>Exercise: {{ $workout->exercises->description }}</p>
                <p>Repetitions: {{ $workout->repetitions }}</p>
                <p>Weight: {{ $workout->weight }}</p>
                <p>Break Time: {{ $workout->break_time }}</p>
                <p>Observations: {{ $workout->observations }}</p>
                <p>Time: {{ $workout->time }}</p>
                <hr>
            @endforeach
        @else
            <p>Não há treinos cadastrados para esse dia.</p>
        @endif
    @endforeach
</body>

</html>
