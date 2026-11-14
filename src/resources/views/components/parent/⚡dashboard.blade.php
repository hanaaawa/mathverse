<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::check() && Auth::user()->hasRole('parent'), 403);
    }

    public function getParentProperty()
    {
        return Auth::user()->load('parentProfile.students.user');
    }
};
?>

<div>
    <h1>Dashboard Orang Tua</h1>

    <p>
        Selamat datang, {{ $this->parent->name }}!
    </p>

    <h2>Daftar Anak</h2>

    @forelse ($this->parent->parentProfile->students as $student)
        <div>
            <h3>{{ $student->full_name }}</h3>
            <p>Kelas {{ $student->grade }} SD</p>
            <p>Username: {{ $student->user->username }}</p>
        </div>
    @empty
        <p>Belum ada akun siswa yang terhubung.</p>
    @endforelse
</div>
