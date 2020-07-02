<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class IsDraft implements ICriterion
{

  public function apply($model)
  {
    return $model->where('is_live', false);
  }
}
