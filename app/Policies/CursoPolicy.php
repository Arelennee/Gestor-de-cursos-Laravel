<?php

namespace App\Policies;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CursoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Curso $curso): Response
    {
        // El admin o el profesor del curso siempre tienen acceso.
        if ($user->esAdmin() || $user->id === $curso->user_id) {
            return Response::allow();
        }

        // Hacemos una consulta directa y eficiente para ver si están inscritos.
        $isEnrolled = $user->inscripciones()->where('curso_id', $curso->id)->exists();

        if ($isEnrolled) {
            return Response::allow();
        }

        // Si la comprobación falla, denegamos el acceso con un mensaje de depuración detallado.
        $enrolled_courses = $user->inscripciones()->pluck('curso_id')->implode(', ');
        return Response::deny("DEBUG: El usuario {$user->id} NO está inscrito en el curso {$curso->id}. Cursos en los que SÍ está inscrito: [{$enrolled_courses}]");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->esProfesor() || $user->esAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Curso $curso): bool
    {
        return $user->id === $curso->user_id || $user->esAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Curso $curso
     * @return bool
     */
    public function delete(User $user, Curso $curso): bool
    {
        return $user->id === $curso->user_id || $user->esAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Curso $curso): bool
    {
        return $user->id === $curso->user_id || $user->esAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Curso $curso): bool
    {
        return $user->id === $curso->user_id || $user->esAdmin();
    }
}
