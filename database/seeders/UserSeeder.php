<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nim' => '22310001',
                'email' => 'admin@mail.com',
                'password' => Hash::make('22310001'),
                'text_password' => '22310001',
                'role' => 'Admin',
                'active_member' => true,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '22310002',
                'email' => 'bph1@mail.com',
                'password' => Hash::make('22310002'),
                'text_password' => '22310002',
                'role' => 'BPH UKM',
                'active_member' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '22310003',
                'email' => 'mhs1@mail.com',
                'password' => Hash::make('22310003'),
                'text_password' => '22310003',
                'role' => 'Mahasiswa',
                'active_member' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}