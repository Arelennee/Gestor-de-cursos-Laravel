<?php

use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Leccion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('complete method marks lesson as completed for authenticated user', function () {
    $user = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    // Enroll the user in the course
    Inscripcion::factory()->create([
        'user_id' => $user->id,
        'curso_id' => $curso->id,
    ]);

    $leccion = Leccion::factory()->create([
        'curso_id' => $curso->id,
        'titulo' => 'Test Lesson',
        'contenido' => 'Test content',
    ]);

    // Act as the authenticated user
    $this->actingAs($user);

    // Call the complete method
    $response = $this->post(route('lecciones.complete', $leccion));

    // Assert the lesson was marked as completed
    expect($user->leccionesCompletadas()->where('leccion_id', $leccion->id)->exists())
        ->toBeTrue();
    
    $response->assertRedirect()
        ->assertSessionHas('success', '¡Lección marcada como completada!');
});

test('complete method does not duplicate completion records', function () {
    $user = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    Inscripcion::factory()->create([
        'user_id' => $user->id,
        'curso_id' => $curso->id,
    ]);

    $leccion = Leccion::factory()->create([
        'curso_id' => $curso->id,
    ]);

    $this->actingAs($user);

    // Complete the lesson twice
    $this->post(route('lecciones.complete', $leccion));
    $this->post(route('lecciones.complete', $leccion));

    // Assert only one completion record exists
    $completionCount = $user->leccionesCompletadas()->where('leccion_id', $leccion->id)->count();
    expect($completionCount)->toBe(1);
});

test('complete method requires user to be enrolled in the course', function () {
    $user = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    // User is NOT enrolled in the course

    $leccion = Leccion::factory()->create([
        'curso_id' => $curso->id,
    ]);

    $this->actingAs($user);

    // Attempt to complete the lesson should fail authorization
    $response = $this->post(route('lecciones.complete', $leccion));
    
    $response->assertForbidden();
});

test('user leccionesCompletadas relationship correctly retrieves completed lessons', function () {
    $user = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    Inscripcion::factory()->create([
        'user_id' => $user->id,
        'curso_id' => $curso->id,
    ]);

    // Create multiple lessons
    $leccion1 = Leccion::factory()->create(['curso_id' => $curso->id, 'titulo' => 'Lesson 1']);
    $leccion2 = Leccion::factory()->create(['curso_id' => $curso->id, 'titulo' => 'Lesson 2']);
    $leccion3 = Leccion::factory()->create(['curso_id' => $curso->id, 'titulo' => 'Lesson 3']);

    // Mark some lessons as completed
    $user->leccionesCompletadas()->attach([$leccion1->id, $leccion2->id]);

    // Retrieve completed lessons
    $completedLessons = $user->leccionesCompletadas;

    // Assert the relationship returns correct lessons
    expect($completedLessons)->toHaveCount(2)
        ->and($completedLessons->pluck('id')->toArray())->toContain($leccion1->id, $leccion2->id)
        ->and($completedLessons->pluck('id')->toArray())->not->toContain($leccion3->id);
});

test('user leccionesCompletadas relationship includes timestamps', function () {
    $user = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    $leccion = Leccion::factory()->create(['curso_id' => $curso->id]);

    // Attach lesson with automatic timestamps
    $user->leccionesCompletadas()->attach($leccion->id);

    // Retrieve the pivot data
    $completedLesson = $user->leccionesCompletadas()->first();

    // Assert timestamps are present
    expect($completedLesson->pivot->created_at)->not->toBeNull()
        ->and($completedLesson->pivot->updated_at)->not->toBeNull();
});

test('multiple users can complete the same lesson independently', function () {
    $user1 = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $user2 = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
    $owner = User::factory()->create(['rol' => User::ROL_PROFESOR]);
    $curso = Curso::factory()->create(['user_id' => $owner->id]);
    
    Inscripcion::factory()->create(['user_id' => $user1->id, 'curso_id' => $curso->id]);
    Inscripcion::factory()->create(['user_id' => $user2->id, 'curso_id' => $curso->id]);

    $leccion = Leccion::factory()->create(['curso_id' => $curso->id]);

    // Both users complete the lesson
    $user1->leccionesCompletadas()->attach($leccion->id);
    $user2->leccionesCompletadas()->attach($leccion->id);

    // Assert both users have the lesson marked as completed
    expect($user1->leccionesCompletadas()->where('leccion_id', $leccion->id)->exists())->toBeTrue()
        ->and($user2->leccionesCompletadas()->where('leccion_id', $leccion->id)->exists())->toBeTrue()
        ->and($user1->leccionesCompletadas()->count())->toBe(1)
        ->and($user2->leccionesCompletadas()->count())->toBe(1);
});
