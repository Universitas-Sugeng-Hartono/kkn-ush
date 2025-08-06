<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            'view locations',
            'create locations',
            'edit locations',
            'delete locations',
            
            'view groups',
            'create groups',
            'edit groups',
            'delete groups',
            
            'view logbooks',
            'create logbooks',
            'edit logbooks',
            'delete logbooks',
            
            'view berita',
            'create berita',
            'edit berita',
            'delete berita',
            
            'view dokumen',
            'create dokumen',
            'edit dokumen',
            'delete dokumen',
            
            'view galeri',
            'create galeri',
            'edit galeri',
            'delete galeri',
            
            'view pengaduan',
            'create pengaduan',
            'edit pengaduan',
            'delete pengaduan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo($permissions);

        $role = Role::create(['name' => 'dpl']);
        $role->givePermissionTo([
            'view logbooks',
            'view berita',
            'view dokumen',
            'view galeri',
            'view pengaduan',
            'edit pengaduan',
        ]);

        $role = Role::create(['name' => 'mahasiswa']);
        $role->givePermissionTo([
            'view logbooks',
            'create logbooks',
            'edit logbooks',
            'view berita',
            'view dokumen',
            'view galeri',
        ]);
    }
} 