<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_isolation_on_tickets()
    {
        $org1 = Organization::factory()->create();
        $org2 = Organization::factory()->create();

        $user1 = User::factory()->create(['organization_id' => $org1->id]);
        $user2 = User::factory()->create(['organization_id' => $org2->id]);

        $ticket1 = Ticket::create([
            'organization_id' => $org1->id,
            'user_id' => $user1->id,
            'subject' => 'Org 1 Issue',
            'priority' => 'medium',
            'status' => 'open'
        ]);

        $ticket2 = Ticket::create([
            'organization_id' => $org2->id,
            'user_id' => $user2->id,
            'subject' => 'Org 2 Issue',
            'priority' => 'medium',
            'status' => 'open'
        ]);

        $this->actingAs($user1)
            ->get(route('app.support.index'))
            ->assertSee('Org 1 Issue')
            ->assertDontSee('Org 2 Issue');

        $this->actingAs($user2)
            ->get(route('app.support.index'))
            ->assertSee('Org 2 Issue')
            ->assertDontSee('Org 1 Issue');

        // Test authorization on show route
        $this->actingAs($user1)
            ->get(route('app.support.show', $ticket2))
            ->assertStatus(403);
    }

    public function test_suspension_blocks_login()
    {
        $org = Organization::factory()->create(['status' => 'suspended']);
        $user = User::factory()->create(['organization_id' => $org->id]);

        $this->actingAs($user)
            ->get(route('app.dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }
}
