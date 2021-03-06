<?php

namespace Laravelha\Auth\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravelha\Support\Traits\RequestQueryBuildable;

class Role extends Model
{
    use RequestQueryBuildable;

    protected $guarded = ['id'];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @param  Permission $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return $this->permissions->contains($permission);
    }

    /**
     * @inheritDoc
     */
    public static function searchable(): array
    {
        return [
            'id' => '=',
            'name' => 'like',
        ];
    }

    /**
     * @param array $attributes
     * @return Builder|Model
     */
    public static function create(array $attributes = [])
    {
        $permissions = $attributes['permissions'] ?? null;
        if ($permissions) {
            unset($attributes['permissions']);
        }

        $model = static::query()->create($attributes);

        if ($permissions) {
            $model->permissions()->attach($permissions);
        }

        return $model;
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $permissions = $attributes['permissions'] ?? null;
        if ($permissions) {
            unset($attributes['permissions']);
        }

        $updated = parent::update($attributes, $options);

        if ($updated && $permissions) {
            $this->permissions()->sync($permissions);
        }

        return $updated;
    }
}
