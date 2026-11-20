<?php

use App\Models\Admin;
use App\Models\Event;
use App\Models\Registrasi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

function buatAdmin(array $attributes = []): Admin
{
    return Admin::create(array_merge([
        'nama_admin' => 'Admin Uji',
        'username' => 'admin'.Str::random(6),
        'email' => Str::random(6).'@example.com',
        'password' => Hash::make('password123'),
        'is_active' => true,
    ], $attributes));
}

function buatEvent(Admin $admin, array $attributes = []): Event
{
    $judul = $attributes['judul'] ?? 'Event Uji '.Str::random(4);

    return Event::create(array_merge([
        'admin_id' => $admin->id,
        'judul' => $judul,
        'slug' => Str::slug($judul).'-'.Str::random(5),
        'deskripsi' => 'Deskripsi event untuk pengujian.',
        'lokasi' => 'Jakarta',
        'tanggal_mulai' => now()->addDay(),
        'tanggal_selesai' => now()->addDays(2),
        'kuota' => 10,
        'harga' => 0,
        'status' => 'publish',
    ], $attributes));
}

function buatUser(array $attributes = []): User
{
    return User::create(array_merge([
        'name' => 'Peserta '.Str::random(4),
        'email' => Str::random(8).'@example.com',
        'password' => Hash::make('password123'),
    ], $attributes));
}

function buatRegistrasi(Event $event, User $user, array $attributes = []): Registrasi
{
    return Registrasi::create(array_merge([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'kode_registrasi' => 'EVT-'.strtoupper(Str::random(8)),
        'status' => 'pending',
        'tanggal_daftar' => now(),
    ], $attributes));
}

it('menampilkan hanya event publish di halaman utama', function () {
    $admin = buatAdmin();
    buatEvent($admin, ['judul' => 'Event Publik']);
    buatEvent($admin, ['judul' => 'Event Draft', 'status' => 'draft']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Event Publik')
        ->assertDontSee('Event Draft');
});

it('menampilkan detail event publish', function () {
    $event = buatEvent(buatAdmin());

    $this->get(route('event.detail', $event->slug))
        ->assertOk()
        ->assertSee($event->judul);
});

it('menolak akses detail event yang masih draft', function () {
    $event = buatEvent(buatAdmin(), ['status' => 'draft']);

    $this->get(route('event.detail', $event->slug))->assertNotFound();
});

it('mengarahkan tamu dari area peserta ke halaman login', function () {
    $this->get(route('user.dashboard'))->assertRedirect(route('login'));
});

it('mengarahkan tamu dari area admin ke halaman login admin', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    $this->get(route('admin.pendaftar'))->assertRedirect(route('admin.login'));
});

it('menolak login admin yang tidak aktif', function () {
    $admin = buatAdmin(['is_active' => false]);

    $this->post(route('admin.login.process'), [
        'username' => $admin->username,
        'password' => 'password123',
    ])->assertSessionHasErrors('username');

    $this->assertGuest('admin');
});

it('mengizinkan login admin aktif', function () {
    $admin = buatAdmin();

    $this->post(route('admin.login.process'), [
        'username' => $admin->username,
        'password' => 'password123',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin, 'admin');
});

it('mendaftarkan peserta ke event yang dipublikasikan', function () {
    $user = buatUser();
    $event = buatEvent(buatAdmin());

    $this->actingAs($user)
        ->post(route('event.daftar', $event->id))
        ->assertRedirect(route('user.dashboard'));

    $this->assertDatabaseHas('registrasis', [
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'pending',
    ]);
});

it('menolak pendaftaran ganda pada event yang sama', function () {
    $user = buatUser();
    $event = buatEvent(buatAdmin());
    buatRegistrasi($event, $user);

    $this->actingAs($user)
        ->post(route('event.daftar', $event->id))
        ->assertSessionHas('error');

    $this->assertDatabaseCount('registrasis', 1);
});

it('menolak pendaftaran ketika kuota sudah penuh', function () {
    $event = buatEvent(buatAdmin(), ['kuota' => 1]);
    buatRegistrasi($event, buatUser());

    $this->actingAs(buatUser())
        ->post(route('event.daftar', $event->id))
        ->assertSessionHas('error');

    $this->assertDatabaseCount('registrasis', 1);
});

it('menolak pendaftaran setelah event selesai', function () {
    $event = buatEvent(buatAdmin(), [
        'tanggal_mulai' => now()->subDays(3),
        'tanggal_selesai' => now()->subDays(2),
    ]);

    $this->actingAs(buatUser())
        ->post(route('event.daftar', $event->id))
        ->assertSessionHas('error');

    $this->assertDatabaseCount('registrasis', 0);
});

it('menolak pendaftaran ke event draft', function () {
    $event = buatEvent(buatAdmin(), ['status' => 'draft']);

    $this->actingAs(buatUser())
        ->post(route('event.daftar', $event->id))
        ->assertSessionHas('error');

    $this->assertDatabaseCount('registrasis', 0);
});

it('menampilkan statistik pendaftaran yang sebenarnya di dashboard admin', function () {
    $admin = buatAdmin();
    $event = buatEvent($admin);
    buatRegistrasi($event, buatUser(), ['status' => 'pending']);
    buatRegistrasi($event, buatUser(), ['status' => 'diterima']);

    $this->actingAs($admin, 'admin')
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewHas('totalEvents', 1)
        ->assertViewHas('totalRegistrations', 2)
        ->assertViewHas('pendingApprovals', 1);
});

it('hanya menampilkan pendaftar dari event milik admin yang login', function () {
    $adminA = buatAdmin();
    $adminB = buatAdmin();

    $eventA = buatEvent($adminA);
    $eventB = buatEvent($adminB);

    $pesertaA = buatUser(['name' => 'Peserta Event A']);
    $pesertaB = buatUser(['name' => 'Peserta Event B']);

    buatRegistrasi($eventA, $pesertaA);
    buatRegistrasi($eventB, $pesertaB);

    $this->actingAs($adminA, 'admin')
        ->get(route('admin.pendaftar'))
        ->assertOk()
        ->assertSee('Peserta Event A')
        ->assertDontSee('Peserta Event B');
});

it('mengizinkan admin memverifikasi pendaftar event miliknya', function () {
    $admin = buatAdmin();
    $registrasi = buatRegistrasi(buatEvent($admin), buatUser());

    $this->actingAs($admin, 'admin')
        ->put(route('admin.pendaftar.verifikasi', $registrasi->id), [
            'status' => 'diterima',
            'catatan' => 'Tiket valid',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('registrasis', [
        'id' => $registrasi->id,
        'status' => 'diterima',
        'catatan' => 'Tiket valid',
    ]);
});

it('menolak admin memverifikasi pendaftar dari event admin lain', function () {
    $adminA = buatAdmin();
    $adminB = buatAdmin();
    $registrasi = buatRegistrasi(buatEvent($adminB), buatUser());

    $this->actingAs($adminA, 'admin')
        ->put(route('admin.pendaftar.verifikasi', $registrasi->id), [
            'status' => 'diterima',
        ])
        ->assertNotFound();

    $this->assertDatabaseHas('registrasis', [
        'id' => $registrasi->id,
        'status' => 'pending',
    ]);
});
