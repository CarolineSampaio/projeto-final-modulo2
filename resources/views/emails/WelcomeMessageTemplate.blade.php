<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao Go!Fit System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .bgcolor {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            height: 100%;
            padding: 30px 0px;
            border-radius: 8px 8px 0px 0px;
            background-color: #424242;
        }

        .logo {
            max-width: 220px;
            margin-bottom: 30px;
        }

        .logoSecundario {
            max-width: 140px;
            margin-bottom: 10px;
        }

        .welcome-text {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin: 0px;
        }

        .info {
            width: 75%;
            margin: 0 auto;
            padding: 30px 0px 0px;
        }

        .welcomeMessage {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 30px;
        }

        .plan-info {
            font-size: 1rem;
            color: #555;
            line-height: 1.5rem;
        }

        .bold {
            font-weight: bold;
        }

        .buttonAcess {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffc107;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .cta-button:hover {
            background-color: #e0a800;
        }

        .footer {

            margin-top: 30px;
            text-align: left;
            font-size: 0.8rem;
            color: #777;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .footer a {
            color: #777;
            text-decoration: none;
            margin-right: 15px;
        }

        .footer a:hover {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class ="bgcolor">
            <img class="logo" src="{{ $message->embed(public_path('gofit_logo_white.svg')) }}"
                alt="Logo Go!Fit System branco">
            <p class="welcome-text">Bem-vindo, {{ $userName }}!</p>
        </div>

        <div class="info">
            <p class="welcomeMessage">Nós, do time GO!FIT System estamos felizes em ter você conosco.</p>
            <p class="plan-info">O seu plano é o <b>{{ $planName }}.</b></p>
            @if ($planLimit)
                <p class="plan-info">Você poderá cadastrar até <b>{{ $planLimit }}</b> alunos. Além
                    disso, terá acesso a todos os outros benefícios, como exercícios e treinos ilimitados.</p>
            @else
                <p class="plan-info">Você poderá cadastrar alunos <b>ilimitados</b>. Além
                    disso, terá acesso a todos os outros benefícios, como exercícios e treinos, também ilimitados.</p>
            @endif
            <div class="buttonAcess">
                <p class="plan-info bold">Comece a usar todos seus benefícios agora mesmo!</p>
                <a class="cta-button" href="{{ url('https://gofitsystem.vercel.app/login') }}">Faça seu login</a>
            </div>

        </div>
    </div>
    <div class="footer">
        <img class="logoSecundario" src="{{ $message->embed(public_path('gofit_logo.svg')) }}" alt="Logo Go!Fit System">
        <div>
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
            <a href="#">Instagram</a>

        </div>
        <br>
        <p>&copy; {{ date('Y') }} Go!Fit System. Todos os direitos reservados.</p>
        <div>
            <a href="#">Unsubscribe</a>
            <a href="#">Preferences</a>
            <a href="#">View in browser</a>
        </div>
</body>

</html>
