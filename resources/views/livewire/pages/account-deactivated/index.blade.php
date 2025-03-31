<div class="contents">
    <h1 class="text-3xl font-bold text-white mb-4">Account Deactivated</h1>
    <p class="text-gray-300 text-lg mb-4">
        Congratulations! You’ve successfully registered. To activate your account, please request approval from an
        admin. Once
        approved, logback in to access all features.
    </p>
    <div class="flex justify-center">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Logout
            </button>
        </form>
    </div>
</div>
