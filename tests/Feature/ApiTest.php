<?php


namespace Tests\Feature;


use Carbon\Carbon;
use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_api_current_date()
    {
        $response = $this->get("/api/predictor/amsterdam/celsius/");
        $response->assertStatus(200);
    }

    public function test_api_tomorrow()
    {
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');
        $response = $this->get("/api/predictor/amsterdam/celsius/$tomorrow");

        $response->assertStatus(200);
    }

    public function test_api_yesterday()
    {
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');
        $response = $this->get("/api/predictor/amsterdam/celsius/$yesterday");

        $response->assertStatus(400);

    }

    public function test_api_two_weeks()
    {
        $twoWeeks = Carbon::now()->addWeeks(2)->format('Y-m-d');
        $response = $this->get("/api/predictor/amsterdam/celsius/$twoWeeks");

        $response->assertStatus(400);

    }
}
