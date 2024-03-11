<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_tags';

    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
    ];

    // Adicione a validação no momento da criação ou atualização
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            static::validateName($tag->name);
        });
    }

    // Método para validar o nome
    private static function validateName($name)
    {
        if (is_null($name) || strlen($name) <= 3) {
            throw new \InvalidArgumentException("O nome da categoria deve ter mais de 3 caracteres e não pode ser nulo.");
        }
    }

    // Override do método save para tratar a validação
    public function save(array $options = [])
    {
        static::validateName($this->name);

        parent::save($options);
    }
}
