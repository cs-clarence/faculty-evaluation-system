<?php
namespace App\Features\ChatBot;

use App\Features\ChatBot\Contracts\ChatCompletionDriver;
use App\Features\ChatBot\Contracts\ToolCallingDriver;
use App\Features\ChatBot\Drivers\GroqDriver;
use App\Features\ChatBot\Services\ChatCompleter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ChatBotServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $config = Config::get('chatbot.completion_drivers.groq');
        $groq   = new GroqDriver($config);

        $this->app->singleton(
            ChatCompletionDriver::class, fn() => $groq
        );

        $this->app->singleton(
            ToolCallingDriver::class, fn() => $groq
        );

        $this->app->singleton(
            ChatCompleter::class,
            ChatCompleter::class,
        );
    }

    public function boot()
    {
        //
    }
}
