<?php

namespace Database\Seeders;

use App\Models\QuizTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class WelcomeQuizSeeder extends Seeder
{
    /**
     * Seed the Welcome Quiz for teacher with NIP: 12345.
     */
    public function run(): void
    {
        // Find the teacher with NIP 12345
        $teacher = User::where('nip', '12345')->first();

        if (!$teacher) {
            $this->command->warn('Teacher with NIP 12345 not found. Skipping Welcome Quiz seeder.');
            return;
        }

        // Check if quiz already exists
        $existingQuiz = QuizTemplate::where('teacher_id', $teacher->id)
            ->where('title', 'Angket Kebutuhan Peserta Didik (Kelas 10)')
            ->first();

        if ($existingQuiz) {
            $this->command->info('Welcome Quiz already exists for this teacher. Skipping.');
            return;
        }

        // Create the Welcome Quiz with 50 Yes/No questions
        $questions = $this->getQuestions();

        QuizTemplate::create([
            'teacher_id' => $teacher->id,
            'title' => 'Angket Kebutuhan Peserta Didik (Kelas 10)',
            'questions' => $questions,
        ]);

        $this->command->info('Welcome Quiz created successfully for teacher NIP: 12345');
        $this->command->info('Total questions: ' . count($questions));
    }

    /**
     * Get the 50 questions for the student needs assessment.
     */
    private function getQuestions(): array
    {
        $statements = [
            'Saya merasa belum disiplin dalam beribadah pada Tuhan YME',
            'Saya kadang-kadang berperilaku dan bertutur kata tidak jujur',
            'Saya kadang-kadang masih suka menyontek pada waktu tes',
            'Saya merasa belum bisa mengendalikan emosi dengan baik',
            'Saya belum paham tentang sikap dan perilaku asertif',
            'Saya belum tahu cara mengenal dan memahami diri sendiri',
            'Saya belum memahami potensi diri',
            'Saya belum tahu perubahan dan permasalahan yang terjadi pada masa remaja',
            'Saya belum mengenal tentang macam-macam kepribadian',
            'Saya kurang memiliki rasa percaya diri',
            'Saya kadang kurang menjaga kesehatan diri',
            'Saya belum tahu ciri-ciri/sifat/prilaku pribadi yang berkarakter',
            'Saya merasa kurang memiliki tanggung jawab pada diri sendiri',
            'Saya kesulitan mengatur waktu belajar dan bermain',
            'Kondisi orang tua saya sedang tidak harmonis',
            'Saya merasa tidak betah tinggal di rumah sendiri',
            'Saya mempunyai masalah dengan anggota keluarga di rumah',
            'Saya belum bisa menjadi pribadi yang mandiri',
            'Saya sedang memiliki konflik pribadi',
            'Saya belum memahami tentang norma/cara membangun berkeluarga',
            'Saya belum banyak mengenal lingkungan sekolah baru',
            'Saya belum memahami tentang kenakalan remaja',
            'Saya masih sedikit mengetahui tentang dampak atau bahaya rokok',
            'Saya belum banyak mengetahui tentang perilaku sosial yang bertanggung jawab',
            'Saya belum tahu tentang bullying dan cara mensikapinya',
            'Saya sukar bergaul dengan teman-teman di sekolah',
            'Sering saya dianggap tidak sopan pada orang lain',
            'Saya kurang memahami dampak dari media sosial',
            'Saya jarang bermain/berteman di lingkungan tempat saya tinggal',
            'Saya belum banyak teman atau sahabat',
            'Saya kurang suka berkomunikasi dengan teman lawan jenis',
            'Saya belum tahu cara belajar yang baik dan benar di SMK/MAK',
            'Saya belum tahu cara meraih prestasi di sekolah',
            'Saya belum paham tentang gaya belajar dan strategi yang sesuai dengannya',
            'Orang tua saya tidak peduli dengan kegiatan belajar saya',
            'Saya masih sering menunda-nunda tugas sekolah/pekerjaan rumah (PR)',
            'Saya merasa kesulitan dalam memahami pelajaran tertentu',
            'Saya belum tahu cara memanfaatkan sumber belajar',
            'Saya belajarnya jika akan ada tes atau ujian saja',
            'Saya belum tahu tentang struktur kurikulum yang ada di sekolah',
            'Saya merasa malas belajar dan kalau belajar sering ngantuk',
            'Saya belum terbiasa belajar bersama atau belajar kelompok',
            'Saya belum paham cara memilih lembaga bimbingan belajar yang baik',
            'Saya belum dapat memanfaatkan teknologi informasi untuk belajar',
            'Saya belum tahu cara memperoleh bantuan pendidikan (beasiswa)',
            'Saya terpaksa harus bekerja untuk mencukupi kebutuhan hidup',
            'Saya merasa bingung memilih kegiatan ekstrakurikuler di sekolah',
            'Saya merasa belum mantap pada pilihan peminatan yang diambil',
            'Saya merasa belum paham hubungan antara hobi, bakat, minat, kemampuan dan karir',
            'Saya belum memiliki perencanaan karir masa depan',
        ];

        $questions = [];

        foreach ($statements as $index => $statement) {
            $questions[] = [
                'id' => (string) ($index + 1),
                'type' => 'boolean',
                'label' => ($index + 1) . '. ' . $statement,
                'required' => true,
            ];
        }

        return $questions;
    }
}
