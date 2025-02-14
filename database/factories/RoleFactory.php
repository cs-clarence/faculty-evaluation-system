<?php
namespace Database\Factories;

use App\Models\RoleCode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * @var Collection
     */
    private static $roleCodeCases;

    public static function __constructStatic()
    {
        self::$roleCodeCases = collect(RoleCode::cases());
    }

    private static function getId(RoleCode $roleCode)
    {
        return self::$roleCodeCases->search($roleCode) + 1;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function hidden(bool $hidden = true)
    {
        return $this->state(function (array $attributes) use ($hidden) {
            return [
                'hidden' => $hidden,
            ];
        });
    }

    public function admin()
    {
        return $this->roleCode(RoleCode::Admin);
    }

    public function student()
    {
        return $this->roleCode(RoleCode::Student);
    }

    public function teacher()
    {
        return $this->roleCode(RoleCode::Teacher);
    }

    public function evaluator()
    {
        return $this->roleCode(RoleCode::Evaluator);
    }
    public function humanResourcesStaff()
    {
        return $this->roleCode(RoleCode::HumanResourcesStaff);
    }
    public function dean()
    {
        return $this->roleCode(RoleCode::Dean);
    }

    public function roleCode(RoleCode $roleCode)
    {
        return $this->state(function (array $attributes) use ($roleCode) {
            return [
                 ...$attributes,
                'id'           => self::getId($roleCode),
                'display_name' => Str::of(implode(' ', preg_split('/(?=[A-Z])/', $roleCode->name)))->title()->toString(),
                'code'         => $roleCode->value,
                'hidden'       => $roleCode === RoleCode::Admin
                || $roleCode === RoleCode::HumanResourcesStaff
                || $roleCode === RoleCode::Evaluator,
            ];
        });
    }

}

RoleFactory::__constructStatic();
