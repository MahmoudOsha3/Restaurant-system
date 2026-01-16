<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name"=> $this->faker->unique()->jobTitle
        ];
    }

    public function withPermissions(array $allowedPermissions = [])
    {
        return $this->afterCreating(function (Role $role) use ($allowedPermissions){
            $permissions = array_keys(config('permission')) ;

            foreach ($permissions as $permission)
            {
                RolePermission::create([
                    'role_id'   => $role->id,
                    'permission'=> $permission,
                    'authorize' => in_array($permission , $allowedPermissions) ? 'allow' : 'deny' ,
                ]) ;
            }
        });
    }
}
