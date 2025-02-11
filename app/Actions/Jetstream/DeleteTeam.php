<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     */
    public function delete(Team $team): void
    {
        try {
            $team->purge();
        } catch (\Exception $e) {
            echo 'Message: ' .$e->getMessage();    
        }
            
    }
}
