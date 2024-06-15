<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Carbon\Carbon;

class ExpiredEventScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('starts_at', '>=', Carbon::now());
    }
}
