<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;
use Log;

class UpdateRoles extends Command
{
    const NAME_PROVIDER = '/Providers/SimpleRoleServiceProvider.php';
    const NAME_STUB = 'stubs/SimpleRoleServiceProvider.stub';
    const ANCHOR_DEFINE = '{{gate_define}}';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:role {--r|reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update new role from role config';

    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $isReset = $this->option('reset');
        $strGateDefine = "";
        $rolesConfig = config('role.roles');

        if ($isReset) {
            if ($this->confirm('Do you confirm to reset roles?')) {
                Schema::disableForeignKeyConstraints();
                Role::query()->truncate();
                DB::table('user_role')->truncate();
                Schema::enableForeignKeyConstraints();

                DB::beginTransaction();
                
                try {
                    $this->updateNewRole($rolesConfig, $strGateDefine);
                    $this->createSimpleRoleProvider($strGateDefine);

                    DB::commit();

                    $this->info('Update roles success.');
                } catch (Exception $e) {
                    DB::rollback();

                    Log::info($e->getMessage());
                    $this->error($e->getMessage());
                    $this->info('Update roles failed.');
                }
            }
        } else {
            DB::beginTransaction();

            try {
                $this->updateRoleNoExist($rolesConfig, $strGateDefine);
                $this->createSimpleRoleProvider($strGateDefine);

                DB::commit();

                $this->info('Update roles success.');
            } catch (Exception $e) {
                DB::rollback();

                Log::info($e->getMessage());
                $this->error($e->getMessage());
                $this->info('Update roles failed.');
            }
        }
    }

    protected function updateNewRole($roles, &$strGateDefine, $parentLevel = null, $parentRoleId = null)
    {
        foreach ($roles as $index => $roleConfig) {
            $level = $parentLevel == null ? $index : $parentLevel . '_' . $index;
            $roleId = $this->insertRole($roleConfig['name'], $level, $parentRoleId);
            $strGateDefine .= $this->addGateDefineString($roleConfig['name']);

            if (isset($roleConfig['child_roles'])) {
                $this->updateNewRole($roleConfig['child_roles'], $strGateDefine, $level, $roleId);
            }
        }
    }

    protected function updateRoleNoExist($roles, &$strGateDefine, $parentLevel = null, $parentRoleId = null)
    {
        foreach ($roles as $index => $roleConfig) {
            if ($role = DB::table('roles')->where('name', $roleConfig['name'])->first()) {
                $strGateDefine .= $this->addGateDefineString($role->name);

                if (isset($roleConfig['child_roles'])) {
                    $this->updateRoleNoExist($roleConfig['child_roles'], $strGateDefine, $role->level, $role->id);
                }
            } else {
                $level = $parentLevel == null ? $index : $parentLevel . '_' . $index;
                $role_id = $this->insertRole($roleConfig['name'], $level, $parentRoleId);
                $strGateDefine .= $this->addGateDefineString($roleConfig['name']);

                if (isset($roleConfig['child_roles'])) {
                    $this->updateRoleNoExist($roleConfig['child_roles'], $strGateDefine, $level, $role_id);
                }
            }
        }
    }

    private function createSimpleRoleProvider($strGateDefine)
    {
        $stub = $this->files->get(resource_path(self::NAME_STUB));
        $stub = str_replace(self::ANCHOR_DEFINE, $strGateDefine, $stub);
        $path = app_path('') . self::NAME_PROVIDER;
        $this->files->put($path, $stub);
    }

    private function addGateDefineString($roleName): string
    {
        return "Gate::define('" . $roleName . "', 'App\Policies\RolePolicy@checkRole');\r\n\t\t";
    }

    private function insertRole($name, $level, $parentId): int
    {
        return DB::table('roles')->insertGetId([
            'name' => $name,
            'level' => $level,
            'parent_id' => $parentId,
        ]);
    }
}
