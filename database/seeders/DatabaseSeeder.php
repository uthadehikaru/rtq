<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'is_admin'=> true,
        ]);

        $course = \App\Models\Course::create([
            'name'=>'Tahsin Balita',
            'fee'=>120000,
        ]);

        $batches = ['Balita Ikhwan','Balita Akhwat'];
        foreach($batches as $batch){
            \App\Models\Batch::create([
                'course_id'=>$course->id,
                'name'=>$batch,
                'description'=>'Jadwal Halaqoh'.$course->name,
            ]);
        }
        
        $course = \App\Models\Course::create([
            'name'=>'Tahsin Anak',
            'fee'=>120000,
        ]);
        $batches = ['Anak 1','Anak 2','Anak 3'];
        foreach($batches as $batch){
            \App\Models\Batch::create([
                'course_id'=>$course->id,
                'name'=>$batch,
                'description'=>'Jadwal Halaqoh'.$course->name,
            ]);
        }
        
        $course = \App\Models\Course::create([
            'name'=>'Tahsin Dewasa',
            'fee'=>120000,
        ]);
        $batches = ['Al-Kahfi','Ali Imron','An-Nashr'];
        foreach($batches as $batch){
            \App\Models\Batch::create([
                'course_id'=>$course->id,
                'name'=>$batch,
                'description'=>'Jadwal Halaqoh'.$course->name,
            ]);
        }
        
        $this->call([
            //CourseSeeder::class,
            MemberSeeder::class,
            PeriodSeeder::class,
            //PaymentSeeder::class,
        ]);
    }
}
