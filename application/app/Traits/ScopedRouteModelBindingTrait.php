<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait ScopedRouteModelBindingTrait
{
    /**
     * This ensures the route model binding always loads the record owned by the logged-in user
     *
     * @param $value
     * @param $field
     * @return Model|Builder
     */
    public function resolveRouteBinding($value, $field = null): Model|Builder
    {
        return $this
            ->query()
            ->where('id', $value)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }
}
