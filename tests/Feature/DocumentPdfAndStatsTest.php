<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentPdfAndStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_stats_dashboard()
    {
        // 1. Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // 2. Create Recruiter and Company
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

        // 3. Create Job
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

        // 4. Create Candidate
        $candidate = User::create([
            'name' => 'Alice Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidat'
        ]);

        // 5. Create Application
        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'En attente',
            'cv_path' => 'cv.pdf'
        ]);

        // Act as Admin
        $this->actingAs($admin);

        // Fetch stats view
        $response = $this->get(route('admin.stats'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');
        $response->assertSee('Taux de recrutement global');
        $response->assertSee('Tech Corp');
    }

    public function test_candidate_can_download_cv_pdf()
    {
        // Create Candidate
        $candidate = User::create([
            'name' => 'Alice Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidat',
            'skills' => 'PHP,Laravel,Vue.js',
            'bio' => 'An enthusiastic developer',
            'phone' => '+33612345678'
        ]);

        // Act as Candidate
        $this->actingAs($candidate);

        $response = $this->get(route('candidat.cv.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_recruiter_can_export_application_pdf()
    {
        // Create Recruiter and Company
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

        // Create Job
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

        // Create Candidate
        $candidate = User::create([
            'name' => 'Alice Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidat',
            'skills' => 'PHP,Laravel,Vue.js',
            'bio' => 'An enthusiastic developer',
            'phone' => '+33612345678'
        ]);

        // Create Application
        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'En attente',
            'cv_path' => 'cv.pdf',
            'cover_letter' => 'I love programming!',
            'recruiter_notes' => 'Very good candidate.'
        ]);

        // Act as Recruiter
        $this->actingAs($recruiter);

        $response = $this->get(route('recruteur.applications.pdf', $application->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_admin_can_export_report_pdf()
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create Recruiter and Company
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

        // Create Job
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

        // Create Candidate
        $candidate = User::create([
            'name' => 'Alice Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidat'
        ]);

        // Create Application
        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'Acceptée',
            'cv_path' => 'cv.pdf'
        ]);

        // Act as Admin
        $this->actingAs($admin);

        $response = $this->get(route('admin.report.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
