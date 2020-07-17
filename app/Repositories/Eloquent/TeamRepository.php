<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Eloquent\BaseRepository;

class TeamRepository extends BaseRepository implements ITeam
{

  public function model()
  {
    return Team::class;
  }

  public function fetchUserTeams()
  {
    return auth()->user()->teams;
  }

  public function search(Request $request)
  {
    $query = (new $this->model)->newQuery();

    // return only designs assigned to teams
    if ($request->has_owner) {
      $query->has('owner');
      $query->where(function ($q) use ($request) {
        $q->where('owner_id', $request->owner_id);
      });
    }

    // search name and owner for provided string
    if (!$request->has_owner && $request->q) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->q . '%');
      });
    }

    // order the query by likes or latest first
    if ($request->orderBy == 'created_at') {
    } else {
      $query->latest();
    }

    return $query->get();
  }
}
