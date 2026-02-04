<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\QuizTemplate;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    /**
     * Seed test data for development.
     */
    public function run(): void
    {
        // 1. Create test school
        $school = School::create([
            'name' => 'SDN Test Sekolah',
            'slug' => 'sdn-test',
        ]);

        $this->command->info("✓ Created school: {$school->name}");

        // 2. Create teacher user with NIP login
        $teacher = User::create([
            'name' => 'Guru Test',
            'nip' => '12345',
            'email' => 'guru@test.local',
            'password' => Hash::make('password'),
            'must_change_password' => true,  // Forces password change on first login
            'role' => User::ROLE_TEACHER,
        ]);

        // Manually assign school_id (bypassing fillable protection)
        $teacher->school_id = $school->id;
        $teacher->save();

        $this->command->info("✓ Created teacher: {$teacher->name} (NIP: {$teacher->nip})");

        // 3. Create a sample quiz template
        $quizTemplate = QuizTemplate::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
            'title' => 'Kuis Biodata Siswa',
            'questions' => [
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'text',
                    'label' => 'Nama Lengkap',
                    'required' => true,
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'text',
                    'label' => 'Tempat Lahir',
                    'required' => true,
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'date',
                    'label' => 'Tanggal Lahir',
                    'required' => true,
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'select',
                    'label' => 'Jenis Kelamin',
                    'options' => ['Laki-laki', 'Perempuan'],
                    'required' => true,
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'type' => 'textarea',
                    'label' => 'Alamat Rumah',
                    'required' => false,
                ],
            ],
        ]);

        $this->command->info("✓ Created quiz template: {$quizTemplate->title}");

        // 4. Create classroom with unit_code
        $unitCode = strtoupper(Str::random(6));
        $classroom = Classroom::create([
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
            'name' => 'Kelas 6A',
            'unit_code' => $unitCode,
            'quiz_template_id' => $quizTemplate->id,
        ]);

        $this->command->info("✓ Created classroom: {$classroom->name} (Unit Code: {$unitCode})");

        // Summary
        $this->command->newLine();
        $this->command->info('=== TEST CREDENTIALS ===');
        $this->command->info("NIP:      12345");
        $this->command->info("Password: password");
        $this->command->info("(Must change password on first login)");
        $this->command->newLine();
        $this->command->info("=== PUBLIC INTAKE URL ===");
        $this->command->info("http://localhost/join/{$unitCode}");
    }
}
