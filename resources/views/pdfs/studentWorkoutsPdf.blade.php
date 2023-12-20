<html>

<head>
    <title>Student Workouts</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #424242;
        }

        .container {
            page-break-inside: avoid;
            max-width: 800px;
            margin: 20px 0px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            border-radius: 8px 8px 0 0;
            text-align: center;
            margin-bottom: 50px;
        }

        .logo {
            max-width: 220px;
            margin-bottom: 20px;
        }

        h3 {
            padding: 0px 20px;
        }

        .day {
            background-color: #FFCA28;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 10px;

        }

        .noWorkouts {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            padding: 0px 10px;
        }

        th,
        td {
            border: none;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }

        td {
            padding: 30px 0px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img class="logo" src="{{ public_path('gofit_logo.svg') }}" alt="Logo Go!Fit System">
        <h1>Treinos da Semana</h1>
    </div>

    <h3>Aluno: {{ $student_name }}</h3>

    @foreach ($workouts as $day => $dayWorkouts)
        <div class="container">
            <div class="day">
                <p>{{ $day }}</p>
            </div>

            @if (count($dayWorkouts) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Exercício</th>
                            <th>Repetições</th>
                            <th>Peso</th>
                            <th>Pausa</th>
                            <th>Observações</th>
                            <th>Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dayWorkouts as $workout)
                            <tr>
                                <td>{{ $workout['exercises']['description'] }}</td>
                                <td>{{ $workout['repetitions'] }}</td>
                                <td>{{ $workout['weight'] }} kg</td>
                                <td>{{ $workout['break_time'] }} s</td>
                                <td>{{ $workout['observations'] }}</td>
                                <td>{{ $workout['time'] }} min</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="noWorkouts">Não há treinos cadastrados para esse dia.</p>
            @endif
        </div>
    @endforeach
</body>

</html>
