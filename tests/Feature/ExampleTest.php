<?php

namespace Tests\Feature;

use App\Contracts\RepositoryContract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    public function testMainPage()
    {
        $response = $this->get('/');

        $response->assertSeeText('Laravel');
    }

    public function testSearch()
    {
        $response = $this->json('GET', '/search', ['query' => '@octocat git-consortium']);

        $result = new \stdClass();
        $result->file = 'README.md';
        $result->owner = 'octocat';
        $result->repository = 'octocat/git-consortium';

        $expectedFragment = [$result];

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonCount(1)
            ->assertJsonFragment($expectedFragment);
    }
}
