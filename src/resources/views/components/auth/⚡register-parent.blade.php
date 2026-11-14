<?php

use App\Models\ParentProfile;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component
{
    public string $parent_name = '';
    public string $parent_email = '';
    public string $parent_phone = '';
    public string $parent_password = '';
    public string $parent_password_confirmation = '';

    public string $student_name = '';
    public string $student_username = '';
    public string $student_password = '';
    public int $grade = 4;

    public function register()
    {
        $validated = $this->validate([
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'parent_password' => ['required', 'confirmed', Password::min(8)],
            'student_name' => ['required', 'string', 'max:255'],
            'student_username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'alpha_dash',
                'unique:users,username',
            ],
            'student_password' => ['required', Password::min(6)],
            'grade' => ['required', 'integer', 'in:4,5,6'],
        ]);

        $parent = DB::transaction(function () use ($validated) {
            $parentUser = User::create([
                'name' => $validated['parent_name'],
                'email' => $validated['parent_email'],
                'password' => Hash::make($validated['parent_password']),
            ]);

            $parentUser->assignRole('parent');

            $parentProfile = ParentProfile::create([
                'user_id' => $parentUser->id,
                'phone' => $validated['parent_phone'],
            ]);

            $studentUser = User::create([
                'name' => $validated['student_name'],
                'username' => $validated['student_username'],
                'email' => $validated['student_username'] . '@student.mathverse.local',
                'password' => Hash::make($validated['student_password']),
            ]);

            $studentUser->assignRole('student');

            $studentProfile = StudentProfile::create([
                'user_id' => $studentUser->id,
                'full_name' => $validated['student_name'],
                'grade' => $validated['grade'],
            ]);

            $parentProfile->students()->attach($studentProfile->id, [
                'relationship' => 'Orang Tua',
            ]);

            return $parentUser;
        });

        auth()->login($parent);
        request()->session()->regenerate();

        return $this->redirect('/parent/dashboard');
    }
};

?>
