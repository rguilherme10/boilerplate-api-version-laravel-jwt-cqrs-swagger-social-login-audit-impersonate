<?php

namespace Modules\SocialLogin\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SocialUser extends Model
{
    use HasFactory, Notifiable;

    /**
     * Tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'social_users';

    /**
     * Atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'oauth_type',
        'oauth_id',
        'oauth_token',
        'oauth_avatar',
    ];

    /**
     * Atributos ocultos na serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'oauth_token',
    ];

    /**
     * Relacionamento com o usuário principal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Verifica se o usuário está vinculado a um provedor específico.
     *
     * @param string $provider
     * @return bool
     */
    public function isProvider(string $provider): bool
    {
        return $this->oauth_type === strtolower($provider);
    }
}