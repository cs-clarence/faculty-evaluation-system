<?php
namespace App\Providers;

use App\Facades\Services\FormSubmissionEvaluateeService;
use App\Facades\Services\PendingEvaluationsService;
use App\Helpers\SectionHelper;
use App\Services\DeanService;
use App\Services\FileNameService;
use App\Services\FormSubmissionExportService;
use App\Services\HumanResourcesStaffService;
use App\Services\RegistrarService;
use App\Services\SchoolYearService;
use App\Services\StudentService;
use App\Services\TeacherService;
use App\Services\UserService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        SectionHelper::class                  => SectionHelper::class,
        UserService::class                    => UserService::class,
        StudentService::class                 => StudentService::class,
        TeacherService::class                 => TeacherService::class,
        DeanService::class                    => DeanService::class,
        HumanResourcesStaffService::class     => HumanResourcesStaffService::class,
        SchoolYearService::class              => SchoolYearService::class,
        FileNameService::class                => FileNameService::class,
        FormSubmissionEvaluateeService::class => FormSubmissionEvaluateeService::class,
        FormSubmissionExportService::class    => FormSubmissionExportService::class,
        PendingEvaluationsService::class      => PendingEvaluationsService::class,
        RegistrarService::class               => RegistrarService::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $forceRootUrl = Config::get('app.force_root_url');
        if (isset($forceRootUrl) && $forceRootUrl === true || $forceRootUrl === 'true') {
            $appUrl = Config::get('app.url');
            if (isset($appUrl)) {
                URL::forceRootUrl($appUrl);
            }
        }
    }
}
