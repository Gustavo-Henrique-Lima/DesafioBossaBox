<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "tb_tools";
    private static $errors = [];

    protected $fillable = [
        "id",
        "title",
        "link",
        "description",
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "tb_tag_tool");
    }

    // Validação no momento da criação ou atualização
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tool) {
            static::$errors = [];
            static::validate($tool->title, "titulo");
            static::validate($tool->link, "link");
            static::validate($tool->description, "descrição");
            if (!empty(static::$errors)) {
                throw new \InvalidArgumentException(implode("", static::$errors));
            }
        });

        static::updated(function ($tool) {
            static::validate($tool->title, "title");
            static::validate($tool->link, "link");
            static::validate($tool->description, "description");
        });
    }

    // Método para validar os atributos
    private static function validate($value, $fieldName)
    {
        if (is_null($value) || strlen($value) <= 3) {
            static::$errors[] = "O(A) " . $fieldName . " da ferramenta deve ter mais de 3 caracteres e não pode ser nulo.";
        }
    }
}
