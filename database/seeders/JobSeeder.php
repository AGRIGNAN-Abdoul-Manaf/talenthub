<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;
use App\Models\Company;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        // 1. On récupère ou crée un recruteur test
        $recruiter = User::where('email', 'recruteur@example.com')->first();
        
        if (!$recruiter) {
            $recruiter = User::create([
                'name' => 'Jean Recruteur',
                'email' => 'recruteur@example.com',
                'password' => bcrypt('password'),
                'role' => 'recruteur',
            ]);
        }

        // 2. On récupère ou crée une entreprise pour ce recruteur
        $company = $recruiter->company;

        if (!$company) {
            $company = Company::create([
                'user_id' => $recruiter->id,
                'name' => 'TechCorp Solutions',
                'slug' => 'techcorp-solutions',
                'description' => 'TechCorp Solutions est un leader innovant dans la conception de logiciels SaaS de pointe et le conseil en transformation digitale.',
                'location' => 'Paris (75) - Hybride',
                'website' => 'https://techcorp-solutions.example.com',
                'logo' => null,
            ]);
        }

        // 3. On crée plusieurs offres d'emploi variées pour tester la recherche, le tri et le filtrage !
        $jobs = [
            [
                'title' => 'Développeur Full-Stack PHP / Laravel',
                'category' => 'Informatique',
                'contract_type' => 'CDI',
                'location' => 'Paris (75) - Hybride',
                'salary_range' => '45000',
                'education_level' => 'Bac +2 / +3',
                'experience_required' => '1 à 3 ans',
                'description' => "Nous recherchons un(e) Développeur Full-Stack PHP/Laravel passionné(e) pour rejoindre notre équipe technique.\n\nVos missions :\n- Concevoir et développer de nouvelles fonctionnalités robustes sur nos plateformes SaaS.\n- Optimiser les performances de nos bases de données et requêtes SQL.\n- Collaborer avec l'équipe design pour intégrer des interfaces utilisateur élégantes.\n- Écrire des tests unitaires et d'intégration de qualité.\n\nCompétences clés : Laravel, Vue.js or React, Tailwind CSS, MySQL, Git.",
                'is_active' => true,
            ],
            [
                'title' => 'Designer UI / UX Senior',
                'category' => 'Design',
                'contract_type' => 'CDD',
                'location' => 'Lyon (69) - Télétravail',
                'salary_range' => '55000',
                'education_level' => 'Bac +5',
                'experience_required' => '3 à 5 ans',
                'description' => "Vous serez responsable de concevoir l'expérience et le design visuel de notre nouvelle application mobile.\n\nVos missions :\n- Réaliser des wireframes, mockups et prototypes interactifs de haute fidélité.\n- Mener des sessions de recherche utilisateur (user testing) et d'entretiens individuels.\n- Maintenir et enrichir notre Design System sur Figma.\n\nLogiciels maîtrisés : Figma, Adobe Creative Cloud, Framer.",
                'is_active' => true,
            ],
            [
                'title' => 'Product Manager - SaaS B2B',
                'category' => 'Marketing',
                'contract_type' => 'CDI',
                'location' => 'Paris (75) - Présentiel',
                'salary_range' => '65000',
                'education_level' => 'Bac +5',
                'experience_required' => '3 à 5 ans',
                'description' => "Au sein de l'équipe produit, vous pilotez la roadmap d'un de nos modules les plus stratégiques.\n\nVos missions :\n- Recueillir et analyser les besoins des utilisateurs.\n- Rédiger les user stories et spécifications fonctionnelles.\n- Collaborer étroitement avec les équipes de développement (méthode agile).\n- Analyser les KPIs de rétention et d'usage.",
                'is_active' => true,
            ],
            [
                'title' => 'Ingénieur DevOps & Cloud',
                'category' => 'Informatique',
                'contract_type' => 'CDI',
                'location' => 'Télétravail complet',
                'salary_range' => '70000',
                'education_level' => 'Bac +5',
                'experience_required' => 'Plus de 5 ans',
                'description' => "Rejoignez notre équipe Infrastructures pour accélérer notre migration vers une architecture multi-cloud sécurisée et auto-scalable.\n\nVos missions :\n- Gérer et optimiser notre infrastructure sur AWS.\n- Améliorer les pipelines de CI/CD (GitHub Actions, Jenkins).\n- Assurer la sécurité, le monitoring et les backups.\n\nCompétences : Docker, Kubernetes, Terraform, AWS, Bash.",
                'is_active' => true,
            ],
            [
                'title' => 'Inside Sales Specialist',
                'category' => 'Vente',
                'contract_type' => 'Freelance',
                'location' => 'Bordeaux (33) - Hybride',
                'salary_range' => '38000',
                'education_level' => 'Bac +2 / +3',
                'experience_required' => '1 à 3 ans',
                'description' => "Afin de soutenir notre croissance accélérée, nous recrutons un Inside Sales dynamique pour conquérir de nouveaux comptes clients.\n\nVos missions :\n- Qualifier les leads entrants (Inbound) et générer des opportunités sortantes (Outbound).\n- Réaliser des démonstrations en ligne de nos solutions de recrutement.\n- Assurer le suivi et la négociation jusqu'au closing commercial.",
                'is_active' => true,
            ],
            [
                'title' => 'Développeur Front-End React Junior',
                'category' => 'Informatique',
                'contract_type' => 'Stage',
                'location' => 'Nantes (44) - Hybride',
                'salary_range' => '12000',
                'education_level' => 'Bac +3 / +4',
                'experience_required' => 'Débutant accepté',
                'description' => "Vous recherchez un stage de fin d'études formateur et stimulant ? Rejoignez notre équipe front-end pour donner vie à nos maquettes.\n\nVos missions :\n- Participer au développement de composants réutilisables en React.\n- Intégrer les maquettes Figma dans le respect des standards CSS et de l'accessibilité.\n- Participer aux code reviews quotidiennes.",
                'is_active' => true,
            ]
        ];

        foreach ($jobs as $jobData) {
            $company->jobs()->create($jobData);
        }
    }
}
