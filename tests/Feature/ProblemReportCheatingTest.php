<?php

use App\Models\ProblemReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has cheating relation defined', function () {
    $report = new ProblemReport();
    $this->assertTrue(method_exists($report, 'cheatingUser'));
});

it('can associate cheating user and retrieve it', function () {
    $user = User::create([
        'fname' => 'John',
        'lname' => 'Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ]);

    $cheatingUser = User::create([
        'fname' => 'Cheater',
        'lname' => 'Bob',
        'email' => 'cheater@example.com',
        'password' => bcrypt('password'),
    ]);

    $report = ProblemReport::forceCreate([
        'user_id' => $user->id,
        'user_id_cheating' => $cheatingUser->id,
        'issue_type' => 'cheating',
        'status' => 'pending',
    ]);

    $retrievedReport = ProblemReport::with('cheatingUser')->find($report->id);

    expect($retrievedReport->cheatingUser->id)->toBe($cheatingUser->id);
    expect($retrievedReport->cheatingUser->fname)->toBe('Cheater');
});

it('validates store api call for cheating report', function () {
    $user = User::create([
        'fname' => 'Reporter',
        'lname' => 'User',
        'email' => 'reporter@example.com',
        'password' => bcrypt('password'),
    ]);

    $cheater = User::create([
        'fname' => 'Cheater',
        'lname' => 'Bob',
        'email' => 'cheater@example.com',
        'password' => bcrypt('password'),
    ]);

    // Make an API request to store cheating report without question_id
    $response = $this->postJson('/api/problem-report/create', [
        'user_id' => $user->id,
        'issue_type' => 'cheating',
        'user_id_cheating' => $cheater->id,
        'additional_notes' => 'He was cheating on the offline game.',
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'تم تسجيل البلاغ بنجاح',
    ]);

    $this->assertDatabaseHas('problem_reports', [
        'user_id' => $user->id,
        'user_id_cheating' => $cheater->id,
        'question_id' => null,
        'issue_type' => 'cheating',
        'additional_notes' => 'He was cheating on the offline game.',
    ]);
});
