Bem vindo, {{ $userName }}

O seu plano é o {{$planName }}

@if ($planLimit)
    E você tem direito a cadastrar {{ $planLimit }} usuários.
@else
    E você tem direito a cadastrar usuários ilimitados.
@endif
