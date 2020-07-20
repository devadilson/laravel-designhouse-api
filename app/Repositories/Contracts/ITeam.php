<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ITeam
{
  public function fetchUserTeams();
  public function fetchUserTeamsByUsername($user);
  public function search(Request $request);
}
