<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewMessageNotification;
use Tests\TestCase;

class MessageNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_message_triggers_recruiter_notification()
    {
        Notification::fake();

        // 1. Create Recruiter and Company
        $recruiter = User::create([
            'name' => 'John Recruiter',
            'email' => 'recruiter@example.com',
            'password' => bcrypt('password'),
            'role' => 'recruteur'
        ]);

        $company = Company::create([
            'name' => 'Tech Corp',
            'user_id' => $recruiter->id,
            'description' => 'Great tech company',
            'location' => 'Paris',
            'website' => 'https://techcorp.example.com'
        ]);

        // 2. Create Job
        $job = Job::create([
            'company_id' => $company->id,
            'title' => 'Software Engineer',
            'category' => 'Engineering',
            'description' => 'Develop cool stuff',
            'location' => 'Paris',
            'contract_type' => 'CDI',
            'education_level' => 'Master',
            'experience_required' => '3 years',
            'salary_range' => '50k - 60k',
            'is_active' => true
        ]);

        // 3. Create Candidate
        $candidate = User::create([
            'name' => 'Alice Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidat'
        ]);

        // 4. Create Application
        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'En attente',
            'cv_path' => 'cv.pdf'
        ]);

        // Act as Candidate
        $this->actingAs($candidate);

        // Send a message reply
        $response = $this->post(route('candidat.applications.replyMessage', $application->id), [
            'message' => 'Hello, this is my response to your offer.'
        ]);

        $response->assertRedirect();
        
        // Assert notification was sent
        Notification::assertSentTo(
            $recruiter,
            NewMessageNotification::class,
            function ($notification, $channels) use ($application) {
                return in_array('database', $channels) &&
                       in_array('mail', $channels) &&
                       $notification->toMail($application)->subject === '💬 Nouveau message de Alice Candidate';
            }
        );
    }

    public function test_recruiter_message_triggers_candidate_notification()
    {
        Notification::fake();

        // 1. Create Recruiter and Company
        $recruiter = User::create([
            'name' => 'John Recruiter',
            'email' => 'recruiter@example.com',
            'password' => bcrypt('password'),
            'role' => 'recruteur'
        ]);

        $company = Company::create([
            'name' => 'Tech Corp',
            'user_id' => $recruiter->id,
            'description' => 'Great tech company',
            'location' => 'Paris',
            'website' => 'https://techcorp.example.com'
        ]);

        // 2. Create Job
        $job = Job::create([
            'company_id' => $company->id,
            'title' => 'Software Engineer',
            'category' => 'Engineering',
            'description' => 'Develop cool stuff',
            'location' => 'Paris',
            'contract_type' => 'CDI',
            'education_level' => 'Master',
            'experience_required' => '3 years',
            'salary_range' => '50k - 60k',
            'is_active' => true
        ]);

        // 3. Create Candidate
        $candidate = User::create([
            'name' => 'Alice Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidat'
        ]);

        // 4. Create Application
        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'En attente',
            'cv_path' => 'cv.pdf'
        ]);

        // Act as Recruiter
        $this->actingAs($recruiter);

        // Send a message
        $response = $this->post(route('recruteur.applications.sendMessage', $application->id), [
            'message' => 'Hello Alice, when are you available for an interview?'
        ]);

        $response->assertRedirect();
        
        // Assert notification was sent
        Notification::assertSentTo(
            $candidate,
            NewMessageNotification::class,
            function ($notification, $channels) use ($application) {
                return in_array('database', $channels) &&
                       in_array('mail', $channels) &&
                       $notification->toMail($application)->subject === '💬 Nouveau message de Tech Corp';
            }
        );
    }
}
