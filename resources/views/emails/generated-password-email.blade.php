<x-layouts.email title="Generated Password">
    @push('styles')
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                background: #ffffff;
                margin: 20px auto;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .button {
                display: inline-block;
                background: #007bff;
                color: #ffffff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
        </style>
    @endpush

    <div class="container">
        <h2>New Account For {{ $email }}</h2>
        <p>Your new password has been generated:</p>
        <p><strong>{{ $password }}</strong></p>
        <p>For security reasons, please log in and change your password as soon as possible.</p>
        <a href="{{ $loginUrl }}" class="button">Login</a>
    </div>
</x-layouts.email>
