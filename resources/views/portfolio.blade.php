<!DOCTYPE html>
<html lang="id" x-data="{
        editMode: localStorage.getItem('editMode') === 'true',
        editingExp: null, editingEdu: null, editingProj: null, editingSkill: null,
        showAddExp: false, showAddEdu: false, showAddProj: false, showAddSkill: false, editingProfile: false
    }"
    x-init="$watch('editMode', value => localStorage.setItem('editMode', value))">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $profile->name ?? 'Personal Portfolio' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="fixed top-4 right-4 z-50">
        <button
            @click="editMode = !editMode; window.location.href = editMode ? '{{ route('home') }}?edit=1' : '{{ route('home') }}'"
            class="px-4 py-2 rounded-full shadow-lg font-medium text-sm"
            :class="editMode ? 'bg-red-600 text-white' : 'bg-blue-600 text-white'">
            <span x-show="!editMode">✏️ Edit Mode</span>
            <span x-show="editMode">👁️ View Mode</span>
        </button>
    </div>

    @if (session('success'))
    <div class="max-w-4xl mx-auto mt-6 bg-green-100 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="max-w-4xl mx-auto mt-6 bg-red-100 text-red-800 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 py-10 space-y-12">

        <section class="bg-white rounded-2xl shadow p-8">
            @if ($profile)
            <template x-if="!editingProfile || !editMode">
                <div class="flex items-center gap-6">
                    <img src="{{ $profile->avatar ? asset('storage/'.$profile->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($profile->name) }}"
                        class="w-28 h-28 rounded-full object-cover border">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold">{{ $profile->name }}</h1>
                        <p class="text-blue-600 font-medium">{{ $profile->headline }}</p>
                        <p class="mt-2 text-gray-600">{{ $profile->bio }}</p>
                        <div class="mt-3 flex gap-4 text-sm text-gray-500">
                            @if ($profile->email)<span>✉️ {{ $profile->email }}</span>@endif
                            @if ($profile->linkedin)<a href="{{ $profile->linkedin }}" target="_blank" class="text-blue-600">LinkedIn</a>@endif
                            @if ($profile->github)<a href="{{ $profile->github }}" target="_blank" class="text-blue-600">GitHub</a>@endif
                        </div>
                        <button x-show="editMode" @click="editingProfile = true"
                            class="mt-4 text-sm px-3 py-1 bg-yellow-500 text-white rounded">Edit Data Diri</button>
                    </div>
                </div>
            </template>

            <template x-if="editingProfile && editMode">
                <form action="{{ route('profile.update', $profile) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf @method('PUT')
                    <div>
                        <label class="text-sm font-medium">Nama</label>
                        <input type="text" name="name" value="{{ $profile->name }}" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Headline</label>
                        <input type="text" name="headline" value="{{ $profile->headline }}" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Bio</label>
                        <textarea name="bio" class="w-full border rounded px-3 py-2">{{ $profile->bio }}</textarea>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="text-sm font-medium">Email</label>
                            <input type="email" name="email" value="{{ $profile->email }}" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm font-medium">LinkedIn</label>
                            <input type="text" name="linkedin" value="{{ $profile->linkedin }}" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm font-medium">GitHub</label>
                            <input type="text" name="github" value="{{ $profile->github }}" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Foto/Avatar</label>
                        <input type="file" name="avatar" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                        <button type="button" @click="editingProfile = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    </div>
                </form>
            </template>
            @else
            <p class="text-gray-500">Belum ada data profil. Jalankan seeder atau tambahkan lewat database.</p>
            @endif
        </section>

        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Pengalaman</h2>
                <button x-show="editMode" @click="showAddExp = !showAddExp" class="text-sm px-3 py-1 bg-green-600 text-white rounded">+ Tambah</button>
            </div>

            <form x-show="editMode && showAddExp" action="{{ route('experiences.store') }}" method="POST" class="bg-white p-4 rounded-xl shadow mb-4 space-y-2">
                @csrf
                <input type="text" name="position" placeholder="Posisi" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="institution" placeholder="Institusi" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="period" placeholder="Periode (mis: 2023 - Sekarang)" class="w-full border rounded px-3 py-2" required>
                <textarea name="description" placeholder="Deskripsi" class="w-full border rounded px-3 py-2"></textarea>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </form>

            <div class="space-y-3">
                @forelse ($experiences as $exp)
                <div class="bg-white p-5 rounded-xl shadow" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold">{{ $exp->position }} — {{ $exp->institution }}</h3>
                                <p class="text-sm text-gray-500">{{ $exp->period }}</p>
                                <p class="mt-1 text-gray-600">{{ $exp->description }}</p>
                            </div>
                            <div x-show="editMode" class="flex gap-2">
                                <button @click="editing = true" class="text-sm px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                                <form action="{{ route('experiences.destroy', $exp) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <template x-if="editing && editMode">
                        <form action="{{ route('experiences.update', $exp) }}" method="POST" class="space-y-2">
                            @csrf @method('PUT')
                            <input type="text" name="position" value="{{ $exp->position }}" class="w-full border rounded px-3 py-2" required>
                            <input type="text" name="institution" value="{{ $exp->institution }}" class="w-full border rounded px-3 py-2" required>
                            <input type="text" name="period" value="{{ $exp->period }}" class="w-full border rounded px-3 py-2" required>
                            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $exp->description }}</textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-gray-400">Belum ada data pengalaman.</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Pendidikan</h2>
                <button x-show="editMode" @click="showAddEdu = !showAddEdu" class="text-sm px-3 py-1 bg-green-600 text-white rounded">+ Tambah</button>
            </div>

            <form x-show="editMode && showAddEdu" action="{{ route('educations.store') }}" method="POST" class="bg-white p-4 rounded-xl shadow mb-4 space-y-2">
                @csrf
                <input type="text" name="institution" placeholder="Institusi" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="degree" placeholder="Jenjang (mis: S1 Teknik Informatika)" class="w-full border rounded px-3 py-2" required>
                <input type="text" name="period" placeholder="Periode" class="w-full border rounded px-3 py-2" required>
                <textarea name="description" placeholder="Deskripsi" class="w-full border rounded px-3 py-2"></textarea>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </form>

            <div class="space-y-3">
                @forelse ($educations as $edu)
                <div class="bg-white p-5 rounded-xl shadow" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold">{{ $edu->degree }} — {{ $edu->institution }}</h3>
                                <p class="text-sm text-gray-500">{{ $edu->period }}</p>
                                <p class="mt-1 text-gray-600">{{ $edu->description }}</p>
                            </div>
                            <div x-show="editMode" class="flex gap-2">
                                <button @click="editing = true" class="text-sm px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                                <form action="{{ route('educations.destroy', $edu) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <template x-if="editing && editMode">
                        <form action="{{ route('educations.update', $edu) }}" method="POST" class="space-y-2">
                            @csrf @method('PUT')
                            <input type="text" name="institution" value="{{ $edu->institution }}" class="w-full border rounded px-3 py-2" required>
                            <input type="text" name="degree" value="{{ $edu->degree }}" class="w-full border rounded px-3 py-2" required>
                            <input type="text" name="period" value="{{ $edu->period }}" class="w-full border rounded px-3 py-2" required>
                            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $edu->description }}</textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-gray-400">Belum ada data pendidikan.</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Proyek</h2>
                <button x-show="editMode" @click="showAddProj = !showAddProj" class="text-sm px-3 py-1 bg-green-600 text-white rounded">+ Tambah</button>
            </div>

            <form x-show="editMode && showAddProj" action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-xl shadow mb-4 space-y-2">
                @csrf
                <input type="text" name="name" placeholder="Nama Proyek" class="w-full border rounded px-3 py-2" required>
                <textarea name="description" placeholder="Deskripsi" class="w-full border rounded px-3 py-2"></textarea>
                <input type="url" name="link" placeholder="Link (https://...)" class="w-full border rounded px-3 py-2">
                <input type="text" name="tech_stack" placeholder="Tech stack, pisahkan koma (Laravel,Vue,MySQL)" class="w-full border rounded px-3 py-2">
                <input type="file" name="thumbnail" class="w-full border rounded px-3 py-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </form>

            <div class="grid md:grid-cols-2 gap-4">
                @forelse ($projects as $proj)
                <div class="bg-white p-5 rounded-xl shadow" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div>
                            @if ($proj->thumbnail)
                            <img src="{{ asset('storage/'.$proj->thumbnail) }}" class="w-full h-32 object-cover rounded mb-2">
                            @endif
                            <h3 class="font-semibold">{{ $proj->name }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ $proj->description }}</p>
                            @if ($proj->link)
                            <a href="{{ $proj->link }}" target="_blank" class="text-blue-600 text-sm">{{ $proj->link }}</a>
                            @endif
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach ($proj->techStackArray() as $tech)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $tech }}</span>
                                @endforeach
                            </div>
                            <div x-show="editMode" class="flex gap-2 mt-3">
                                <button @click="editing = true" class="text-sm px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                                <form action="{{ route('projects.destroy', $proj) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <template x-if="editing && editMode">
                        <form action="{{ route('projects.update', $proj) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $proj->name }}" class="w-full border rounded px-3 py-2" required>
                            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $proj->description }}</textarea>
                            <input type="url" name="link" value="{{ $proj->link }}" class="w-full border rounded px-3 py-2">
                            <input type="text" name="tech_stack" value="{{ $proj->tech_stack }}" class="w-full border rounded px-3 py-2">
                            <input type="file" name="thumbnail" class="w-full border rounded px-3 py-2">
                            <div class="flex gap-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                                <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-gray-400">Belum ada data proyek.</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Keahlian</h2>
                <button x-show="editMode" @click="showAddSkill = !showAddSkill" class="text-sm px-3 py-1 bg-green-600 text-white rounded">+ Tambah</button>
            </div>

            <form x-show="editMode && showAddSkill" action="{{ route('skills.store') }}" method="POST" class="bg-white p-4 rounded-xl shadow mb-4 flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nama skill" class="flex-1 border rounded px-3 py-2" required>
                <input type="text" name="level" placeholder="Level (opsional)" class="flex-1 border rounded px-3 py-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </form>

            <div class="flex flex-wrap gap-3">
                @forelse ($skills as $skill)
                <div class="bg-white px-4 py-2 rounded-xl shadow flex items-center gap-2" x-data="{ editing: false }">
                    <template x-if="!editing || !editMode">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ $skill->name }}</span>
                            @if ($skill->level)
                            <span class="text-xs text-gray-500">({{ $skill->level }})</span>
                            @endif
                            <template x-if="editMode">
                                <span class="flex gap-1 ml-2">
                                    <button @click="editing = true" class="text-xs px-2 py-0.5 bg-yellow-500 text-white rounded">Edit</button>
                                    <form action="{{ route('skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs px-2 py-0.5 bg-red-600 text-white rounded">Hapus</button>
                                    </form>
                                </span>
                            </template>
                        </div>
                    </template>
                    <template x-if="editing && editMode">
                        <form action="{{ route('skills.update', $skill) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $skill->name }}" class="border rounded px-2 py-1 text-sm w-28" required>
                            <input type="text" name="level" value="{{ $skill->level }}" class="border rounded px-2 py-1 text-sm w-24">
                            <button type="submit" class="text-xs px-2 py-1 bg-blue-600 text-white rounded">Simpan</button>
                            <button type="button" @click="editing = false" class="text-xs px-2 py-1 bg-gray-300 rounded">Batal</button>
                        </form>
                    </template>
                </div>
                @empty
                <p class="text-gray-400">Belum ada data skill.</p>
                @endforelse
            </div>
        </section>

    </div>
</body>

</html>