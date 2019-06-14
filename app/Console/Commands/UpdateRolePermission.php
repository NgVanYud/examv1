<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRolePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update roles list and permissions list.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role_model = config('permission.models.role');
        $perm_model = config('permission.models.permission');
        $role_has_per = config('permission.table_names.role_has_permissions');
        $all_roles = config('access.roles_list');
        $role_created = 0;
        $perm_created = 0;

        /**
         * Reset permissions, roles tables
         */
        disableForeignKey();
        $perm_model::truncate();
        $role_model::truncate();
        DB::table($role_has_per)->truncate();
        enableForeignKey();

        foreach($all_roles as $role => $name){
            if(!$role_model::whereName($name)->exists()){
                $new = new $role_model;
                $new->unguard();
                $guard_name = $name == config('access.roles_list.student') ? 'student' : 'manager';
                $new->fill(['name' => $name, 'guard_name' => $guard_name]);
                if($new->save()) $role_created++;
            }
        }

        $all_permissions = config('access.permissions_list');
        foreach($all_permissions as $permission => $role){
            if(!$perm_model::whereName($permission)->exists()){
                $new = new $perm_model();
                $new->unguard();
                $guard_name = $role == 'student' ? 'student' : 'manager';
                $new->fill(['name' => mb_convert_case(str_replace('_', ' ', $permission), MB_CASE_TITLE, "UTF-8"), 'guard_name' => $guard_name]);
                if($new->save()) $perm_created++;
                $role_name = config('access.roles_list.' . $role, '');
                /** @var Role $role */
                $of_role = $role_model::whereName($role_name)->first();
                if($role){
                    $of_role->givePermissionTo($new);
                }
            }
        }

        echo "Created " . $role_created . " role(s) and " . $perm_created . ' permission(s).' . "\n";
    }
}
