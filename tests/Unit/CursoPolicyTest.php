<?php

use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\User;
use App\Policies\CursoPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->policy = new CursoPolicy();
});

test('admin user can view any curso', function () {
    $admin = User::factory()->create(['rol' => User::ROL_ADMIN]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);

    $response = $this->policy->view($admin, $curso);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->allowed())->toBeTrue();
});

test('course owner can view their own curso', function () {
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);

    $response = $this->policy->view($owner, $curso);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->allowed())->toBeTrue();
});

test('enrolled student can view curso', function () {
    $student = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    // Enroll the student in the course
    Inscripcion::factory()->create([
        'user_id' => $student->id,
        'curso_id' => $curso->id,
    ]);

    $response = $this->policy->view($student, $curso);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->allowed())->toBeTrue();
});

test('non-enrolled student cannot view curso', function () {
    $student = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);

    // Student is NOT enrolled in the course

    $response = $this->policy->view($student, $curso);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->allowed())->toBeFalse()
        ->and($response->message())->toContain('NO está inscrito');
});

test('non-enrolled profesor cannot view curso they do not own', function () {
    $profesor = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);

    $response = $this->policy->view($profesor, $curso);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->allowed())->toBeFalse();
});
