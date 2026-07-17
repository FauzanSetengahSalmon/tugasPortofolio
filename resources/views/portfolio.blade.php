<!DOCTYPE html>
<html lang="id" x-data="{
        editMode: localStorage.getItem('editMode') === 'true',
        editingExp: null, editingEdu: null, editingProj: null, editingSkill: null,
        showAddExp: false, showAddEdu: false, showAddProj: false, showAddSkill: false, editingProfile: false
    }"
    x-init="$watch('editMode', value => localStorage.setItem('editMode', value))"
    class="scroll-behavior-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $profile->name ?? 'Personal Portfolio' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        success: '#16a34a',
                        warning: '#ca8a04',
                        danger: '#dc2626',
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased font-sans">

    <!-- Tombol Toggle Edit Mode -->
    <div class="fixed bottom-6 right-6 z-50">
        <button
            @click="editMode = !editMode; window.location.href = editMode ? '{{ route('home') }}?edit=1' : '{{ route('home') }}'"
            class="group flex items-center gap-3 px-6 py-3.5 rounded-full shadow-2xl font-semibold text-base transition-all duration-300 hover:shadow-primary/30"
            :class="editMode ? 'bg-danger text-white hover:bg-red-700' : 'bg-primary text-white hover:bg-blue-700'">

            <template x-if="!editMode">
                <span class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Mode Edit
                </span>
            </template>
            <template x-if="editMode">
                <span class="flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Mode Lihat
                </span>
            </template>
        </button>
    </div>

    <!-- Alert / Flash Messages -->
    <div class="max-w-5xl mx-auto px-4 pt-6" x-cloak>
        @if (session('success'))
        <div class="flex items-center gap-3 bg-green-50 text-success px-5 py-4 rounded-xl border border-green-200 shadow-sm" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        @endif
        @if ($errors->any())
        <div class="bg-red-50 text-danger px-5 py-4 rounded-xl border border-red-200 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="font-bold">Terjadi Kesalahan</h4>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-9">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="max-w-5xl mx-auto px-4 py-12 space-y-16">

        <!-- PROFILE SECTION -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                @if ($profile)
                <template x-if="!editingProfile || !editMode">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                        <img src="{{ $profile->avatar ? asset('storage/'.$profile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($profile->name).'&background=3b82f6&color=fff&size=128' }}"
                            class="w-32 h-32 md:w-36 md:h-36 rounded-full object-cover border-4 border-white shadow-xl ring-1 ring-gray-200">

                        <div class="flex-1 text-center md:text-left space-y-3">
                            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                                <h1 class="text-4xl font-extrabold tracking-tight text-gray-950">{{ $profile->name }}</h1>
                                <button x-show="editMode" @click="editingProfile = true" x-cloak
                                    class="text-xs px-3 py-1 bg-warning/10 text-warning rounded-full font-medium hover:bg-warning/20 transition">Edit Profil</button>
                            </div>
                            <p class="text-xl text-primary font-semibold tracking-tight">{{ $profile->headline }}</p>
                            <p class="text-base text-gray-600 max-w-2xl leading-relaxed">{{ $profile->bio }}</p>

                            <div class="pt-4 flex flex-wrap justify-center md:justify-start gap-3 text-sm">
                                @if ($profile->email)
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 border border-gray-200">
                                    ✉️ {{ $profile->email }}
                                </span>
                                @endif
                                @if ($profile->linkedin)
                                <a href="{{ $profile->linkedin }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                                    </svg>
                                    LinkedIn
                                </a>
                                @endif
                                @if ($profile->github)
                                <a href="{{ $profile->github }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-900 text-white border border-gray-900 hover:bg-gray-800 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                    </svg>
                                    GitHub
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="editingProfile && editMode" x-cloak>
                    <form action="{{ route('profile.update', $profile) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf @method('PUT')
                        <h3 class="text-xl font-bold border-b pb-3">Edit Data Diri</h3>
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $profile->name }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Headline</label>
                                <input type="text" name="headline" value="{{ $profile->headline }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="mis: Senior Web Developer">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Bio Singkat</label>
                                <textarea name="bio" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">{{ $profile->bio }}</textarea>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Email</label>
                                <input type="email" name="email" value="{{ $profile->email }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">URL LinkedIn</label>
                                <input type="text" name="linkedin" value="{{ $profile->linkedin }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="https://linkedin.com/in/...">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">URL GitHub</label>
                                <input type="text" name="github" value="{{ $profile->github }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="https://github.com/...">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Foto Profil (Avatar)</label>
                                <input type="file" name="avatar" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                        <div class="flex gap-3 pt-4 border-t">
                            <button type="submit" class="px-5 py-2.5 bg-primary text-white hover:bg-blue-700 shadow-sm rounded-lg font-medium text-sm transition-all active:scale-95 inline-flex items-center gap-2">Simpan Perubahan</button>
                            <button type="button" @click="editingProfile = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-sm transition-all active:scale-95 inline-flex items-center gap-2">Batal</button>
                        </div>
                    </form>
                </template>
                @else
                <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-500">Belum ada data profil. Silakan isi data lewat database atau seeder terlebih dahulu.</p>
                </div>
                @endif
            </div>
        </section>

        <!-- EXPERIENCES SECTION -->
        <section class="space-y-6">
            <div class="flex justify-between items-center gap-4 border-b pb-4">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-950">Pengalaman Kerja</h2>
                <button x-show="editMode" @click="showAddExp = !showAddExp" x-cloak
                    class="px-4 py-2 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-xs transition-all active:scale-95 inline-flex items-center gap-2">
                    <span x-show="!showAddExp">+ Tambah</span>
                    <span x-show="showAddExp">× Tutup</span>
                </button>
            </div>

            <div x-show="editMode && showAddExp" x-cloak x-transition class="bg-white rounded-2xl shadow-sm border border-green-200 bg-green-50/50 p-6 md:p-8 mb-6">
                <form action="{{ route('experiences.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <h4 class="font-bold text-green-900">Tambah Pengalaman Baru</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" name="position" placeholder="Jabatan (mis: UI Designer)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" required>
                        <input type="text" name="institution" placeholder="Nama Perusahaan / Institusi" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                        <input type="text" name="period" placeholder="Periode (mis: Jan 2022 - Sekarang)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                        <textarea name="description" placeholder="Deskripsi pekerjaan (opsional)" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2"></textarea>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-sm transition-all active:scale-95 inline-flex items-center gap-2">Simpan Pengalaman</button>
                </form>
            </div>

            <div class="space-y-6">
                @forelse ($experiences as $exp)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:border-gray-200 transition relative group" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex gap-4">
                                <div class="mt-1 flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-xl">🏢</div>
                                <div class="space-y-1">
                                    <h3 class="text-xl font-bold text-gray-950">{{ $exp->position }}</h3>
                                    <p class="text-primary font-medium">{{ $exp->institution }}</p>
                                    <p class="text-sm text-gray-500 font-mono bg-gray-100 inline-block px-2 py-0.5 rounded">{{ $exp->period }}</p>
                                    @if($exp->description)
                                    <p class="mt-3 text-gray-600 leading-relaxed text-sm">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div x-show="editMode" x-cloak class="flex gap-2 flex-shrink-0 opacity-0 group-hover:opacity-100 transition">
                                <button @click="editing = true" class="px-3 py-1.5 bg-warning text-white hover:bg-yellow-700 shadow-sm rounded-md font-medium text-xs transition-all active:scale-95 inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('experiences.destroy', $exp) }}" method="POST" onsubmit="return confirm('Hapus pengalaman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-danger text-white hover:bg-red-700 shadow-sm rounded-md font-medium text-xs transition-all active:scale-95 inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>

                    <template x-if="editing && editMode" x-cloak>
                        <form action="{{ route('experiences.update', $exp) }}" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <h4 class="font-bold border-b pb-2">Edit Pengalaman</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <input type="text" name="position" value="{{ $exp->position }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" required>
                                <input type="text" name="institution" value="{{ $exp->institution }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                                <input type="text" name="period" value="{{ $exp->period }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                                <textarea name="description" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" rows="3">{{ $exp->description }}</textarea>
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="px-4 py-2 bg-primary text-white hover:bg-blue-700 rounded-lg font-medium text-xs transition">Simpan</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-xs transition">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-center py-8 text-gray-400 bg-white rounded-xl border border-gray-100 shadow-sm">Belum ada data pengalaman kerja.</p>
                @endforelse
            </div>
        </section>

        <!-- EDUCATIONS SECTION -->
        <section class="space-y-6">
            <div class="flex justify-between items-center gap-4 border-b pb-4">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-950">Pendidikan</h2>
                <button x-show="editMode" @click="showAddEdu = !showAddEdu" x-cloak
                    class="px-4 py-2 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-xs transition-all active:scale-95 inline-flex items-center gap-2">
                    <span x-show="!showAddEdu">+ Tambah</span>
                    <span x-show="showAddEdu">× Tutup</span>
                </button>
            </div>

            <div x-show="editMode && showAddEdu" x-cloak x-transition class="bg-white rounded-2xl shadow-sm border border-green-200 bg-green-50/50 p-6 md:p-8 mb-6">
                <form action="{{ route('educations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <h4 class="font-bold text-green-900">Tambah Pendidikan Baru</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" name="institution" placeholder="Nama Sekolah / Universitas" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" required>
                        <input type="text" name="degree" placeholder="Jenjang & Jurusan (mis: S1 Teknik Informatika)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                        <input type="text" name="period" placeholder="Periode (mis: 2018 - 2022)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                        <textarea name="description" placeholder="Deskripsi tambahan (opsional)" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2"></textarea>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-sm transition-all active:scale-95 inline-flex items-center gap-2">Simpan Pendidikan</button>
                </form>
            </div>

            <div class="space-y-6">
                @forelse ($educations as $edu)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 hover:border-gray-200 transition relative group" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex gap-4">
                                <div class="mt-1 flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-xl">🎓</div>
                                <div class="space-y-1">
                                    <h3 class="text-xl font-bold text-gray-950">{{ $edu->institution }}</h3>
                                    <p class="text-primary font-medium">{{ $edu->degree }}</p>
                                    <p class="text-sm text-gray-500 font-mono bg-gray-100 inline-block px-2 py-0.5 rounded">{{ $edu->period }}</p>
                                    @if($edu->description)
                                    <p class="mt-2 text-gray-600 leading-relaxed text-sm">{{ $edu->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div x-show="editMode" x-cloak class="flex gap-2 flex-shrink-0 opacity-0 group-hover:opacity-100 transition">
                                <button @click="editing = true" class="p-1.5 bg-warning text-white hover:bg-yellow-700 shadow-sm rounded-md font-medium text-xs transition">✏️</button>
                                <form action="{{ route('educations.destroy', $edu) }}" method="POST" onsubmit="return confirm('Hapus data pendidikan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-danger text-white hover:bg-red-700 shadow-sm rounded-md font-medium text-xs transition">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </template>

                    <template x-if="editing && editMode" x-cloak>
                        <form action="{{ route('educations.update', $edu) }}" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <h4 class="font-bold border-b pb-2">Edit Pendidikan</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <input type="text" name="institution" value="{{ $edu->institution }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" required>
                                <input type="text" name="degree" value="{{ $edu->degree }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                                <input type="text" name="period" value="{{ $edu->period }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                                <textarea name="description" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" rows="2">{{ $edu->description }}</textarea>
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="px-4 py-2 bg-primary text-white hover:bg-blue-700 rounded-lg font-medium text-xs transition">Simpan</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-xs transition">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-center py-8 text-gray-400 bg-white rounded-xl border border-gray-100 shadow-sm">Belum ada data pendidikan.</p>
                @endforelse
            </div>
        </section>

        <!-- PROJECTS SECTION -->
        <section class="space-y-6">
            <div class="flex justify-between items-center gap-4 border-b pb-4">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-950">Proyek Unggulan</h2>
                <button x-show="editMode" @click="showAddProj = !showAddProj" x-cloak
                    class="px-4 py-2 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-xs transition-all active:scale-95 inline-flex items-center gap-2">
                    <span x-show="!showAddProj">+ Tambah</span>
                    <span x-show="showAddProj">× Tutup</span>
                </button>
            </div>

            <div x-show="editMode && showAddProj" x-cloak x-transition class="bg-white rounded-2xl shadow-sm border border-green-200 bg-green-50/50 p-6 md:p-8 mb-6">
                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <h4 class="font-bold text-green-900">Tambah Proyek Baru</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Nama Proyek" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2" required>
                        <textarea name="description" placeholder="Deskripsi proyek" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition md:col-span-2"></textarea>
                        <input type="url" name="link" placeholder="Link Demo/Repo (https://...)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                        <input type="text" name="tech_stack" placeholder="Tech stack (pisahkan dengan koma, mis: Laravel, Vue)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-gray-700 mb-1 block">Thumbnail Proyek</label>
                            <input type="file" name="thumbnail" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-sm transition-all active:scale-95 inline-flex items-center gap-2">Simpan Proyek</button>
                </form>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @forelse ($projects as $proj)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 relative group flex flex-col p-0 overflow-hidden" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex flex-col h-full">
                            <div class="w-full h-48 bg-gray-100 border-b overflow-hidden relative group-hover:subtle-zoom">
                                @if ($proj->thumbnail)
                                <img src="{{ asset('storage/'.$proj->thumbnail) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs mt-1">No Thumbnail</span>
                                </div>
                                @endif

                                <div x-show="editMode" x-cloak class="absolute top-2 right-2 flex gap-1.5 opacity-0 group-hover:opacity-100 transition">
                                    <button @click="editing = true" class="bg-warning text-white p-1.5 rounded shadow hover:bg-yellow-700 transition">✏️</button>
                                    <form action="{{ route('projects.destroy', $proj) }}" method="POST" onsubmit="return confirm('Hapus proyek ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-danger text-white p-1.5 rounded shadow hover:bg-red-700 transition">🗑️</button>
                                    </form>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-gray-950 mb-2 group-hover:text-primary transition-colors">{{ $proj->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed flex-1">{{ Str::limit($proj->description, 120) }}</p>

                                @if ($proj->link)
                                <a href="{{ $proj->link }}" target="_blank" class="text-primary text-sm font-medium inline-flex items-center gap-1.5 mb-4 hover:text-blue-700">
                                    Kunjungi Proyek
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                                @endif

                                <div class="flex flex-wrap gap-2 pt-4 border-t mt-auto">
                                    @foreach ($proj->techStackArray() as $tech)
                                    <span class="text-xs font-medium bg-blue-50 text-primary px-2.5 py-1 rounded-full border border-blue-100">{{ $tech }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="editing && editMode" x-cloak>
                        <form action="{{ route('projects.update', $proj) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-6">
                            @csrf @method('PUT')
                            <h4 class="font-bold border-b pb-2">Edit Proyek</h4>
                            <input type="text" name="name" value="{{ $proj->name }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                            <textarea name="description" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" rows="4">{{ $proj->description }}</textarea>
                            <input type="url" name="link" value="{{ $proj->link }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="Link">
                            <input type="text" name="tech_stack" value="{{ $proj->tech_stack }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="Tech Stack">
                            <div>
                                <label class="text-sm font-semibold block mb-1">Ganti Thumbnail</label>
                                <input type="file" name="thumbnail" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="px-4 py-2 bg-primary text-white hover:bg-blue-700 rounded-lg font-medium text-xs transition">Simpan</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg font-medium text-xs transition">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
                @empty
                <div class="md:col-span-2 text-center py-10 text-gray-400 bg-white rounded-xl border border-gray-100 shadow-sm">Belum ada data proyek.</div>
                @endforelse
            </div>
        </section>

        <!-- SKILLS SECTION -->
        <section class="space-y-6">
            <div class="flex justify-between items-center gap-4 border-b pb-4">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-950">Keahlian & Teknologi</h2>
                <button x-show="editMode" @click="showAddSkill = !showAddSkill" x-cloak
                    class="px-4 py-2 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-xs transition-all active:scale-95 inline-flex items-center gap-2">
                    <span x-show="!showAddSkill">+ Tambah</span>
                    <span x-show="showAddSkill">× Tutup</span>
                </button>
            </div>

            <div x-show="editMode && showAddSkill" x-cloak x-transition class="bg-white rounded-2xl shadow-sm border border-green-200 bg-green-50/50 p-5 mb-6">
                <form action="{{ route('skills.store') }}" method="POST" class="flex flex-col md:flex-row gap-3">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Skill (mis: PHP)" class="flex-1 w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" required>
                    <input type="text" name="level" placeholder="Level/Keterangan (opsional, mis: Mahir)" class="flex-1 w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    <button type="submit" class="px-5 py-2.5 bg-success text-white hover:bg-green-700 shadow-sm rounded-lg font-medium text-sm transition-all active:scale-95 inline-flex items-center gap-2 flex-shrink-0">Simpan</button>
                </form>
            </div>

            <div class="flex flex-wrap gap-4">
                @forelse ($skills as $skill)
                <div class="bg-white px-5 py-3 rounded-full shadow-sm border border-gray-100 flex items-center gap-3 transition hover:border-blue-100 hover:shadow-md hover:-translate-y-0.5 group" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-800">{{ $skill->name }}</span>
                            @if ($skill->level)
                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full border">{{ $skill->level }}</span>
                            @endif

                            <template x-if="editMode" x-cloak>
                                <span class="flex gap-1 ml-3 opacity-0 group-hover:opacity-100 transition">
                                    <button @click="editing = true" class="text-warning hover:text-yellow-700 text-xs">✏️</button>
                                    <form action="{{ route('skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Hapus skill ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-danger hover:text-red-700 text-xs">×</button>
                                    </form>
                                </span>
                            </template>
                        </div>
                    </template>

                    <template x-if="editing && editMode" x-cloak>
                        <form action="{{ route('skills.update', $skill) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $skill->name }}" class="border rounded-full px-3 py-1 text-xs w-24 focus:ring-1 focus:ring-blue-300" required>
                            <input type="text" name="level" value="{{ $skill->level }}" class="border rounded-full px-3 py-1 text-xs w-24 focus:ring-1 focus:ring-blue-300" placeholder="Level">
                            <button type="submit" class="text-success text-xs font-bold">✓</button>
                            <button type="button" @click="editing = false" class="text-gray-400 text-xs">×</button>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-center py-5 text-gray-400 w-full bg-white rounded-xl border border-gray-100">Belum ada data keahlian.</p>
                @endforelse
            </div>
        </section>

    </div>

    <footer class="border-t mt-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 py-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ $profile->name ?? 'Portfolio' }}. All rights reserved.
        </div>
    </footer>
</body>

</html>