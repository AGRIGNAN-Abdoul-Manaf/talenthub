<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV - {{ $user->name }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0px;
            padding: 0px;
            color: #334155;
            font-size: 13px;
            line-height: 1.5;
            background-color: #f8fafc;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            height: 100%;
        }
        td {
            padding: 0px;
            vertical-align: top;
        }
        .sidebar {
            background-color: #0f172a;
            color: #f1f5f9;
            width: 32%;
            padding: 40px 20px 20px 25px;
            min-height: 100%;
        }
        .main {
            width: 68%;
            padding: 40px 30px;
            background-color: #ffffff;
        }
        .avatar-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #3b82f6;
            color: #ffffff;
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            line-height: 70px;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        .sidebar h3 {
            color: #38bdf8;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #334155;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 12px;
        }
        .contact-item {
            margin-bottom: 12px;
        }
        .contact-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: bold;
        }
        .contact-val {
            font-size: 11.5px;
            word-wrap: break-word;
        }
        .skill-tag {
            display: inline-block;
            background-color: #1e293b;
            color: #38bdf8;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 10.5px;
            margin-right: 4px;
            margin-bottom: 6px;
            border: 1px solid #334155;
        }
        .name {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 5px 0;
            line-height: 1.1;
        }
        .title-sub {
            font-size: 15px;
            color: #3b82f6;
            font-weight: 600;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .bio-text {
            color: #475569;
            font-size: 13px;
            text-align: justify;
        }
        .experience-item {
            margin-bottom: 20px;
        }
        .experience-header {
            font-weight: bold;
            font-size: 13.5px;
            color: #1e293b;
        }
        .experience-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
            margin-bottom: 8px;
        }
        .footer {
            margin-top: 60px;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <!-- Sidebar -->
            <td class="sidebar">
                <div class="avatar-placeholder">
                    {{ substr($user->name, 0, 2) }}
                </div>
                
                <h3>Contact</h3>
                <div class="contact-item">
                    <div class="contact-label">Email</div>
                    <div class="contact-val">{{ $user->email }}</div>
                </div>
                @if($user->phone)
                <div class="contact-item">
                    <div class="contact-label">Téléphone</div>
                    <div class="contact-val">{{ $user->phone }}</div>
                </div>
                @endif
                <div class="contact-item">
                    <div class="contact-label">Plateforme</div>
                    <div class="contact-val">TalentHub</div>
                </div>

                <h3>Compétences</h3>
                <div style="margin-top: 5px;">
                    @forelse(explode(',', $user->skills ?? '') as $skill)
                        @if(trim($skill))
                            <span class="skill-tag">{{ trim($skill) }}</span>
                        @endif
                    @empty
                        <span style="font-size: 11px; color: #64748b; font-style: italic;">Aucune compétence renseignée</span>
                    @endforelse
                </div>
            </td>
            
            <!-- Main Content -->
            <td class="main">
                <h1 class="name">{{ $user->name }}</h1>
                <div class="title-sub">Candidat inscrit @ TalentHub</div>
                
                <div class="section-title">Profil & Biographie</div>
                <div class="bio-text">
                    {!! nl2br(e($user->bio ?? "Ce candidat n'a pas encore rédigé de biographie. Rendez-vous sur son espace TalentHub pour plus d'informations.")) !!}
                </div>

                <div class="section-title">Parcours professionnel</div>
                <div class="experience-item">
                    <div class="experience-header">Recherche Active d'Opportunités</div>
                    <div class="experience-meta">Membre Actif TalentHub &bull; Présent</div>
                    <p style="margin: 0; color: #475569; font-size: 12.5px;">Profil vérifié, à l'écoute d'opportunités d'emplois (CDI, CDD, Alternance) correspondant aux compétences déclarées dans l'espace membre.</p>
                </div>

                @if(($applicationsCount ?? 0) > 0)
                <div class="section-title">Activité Plateforme</div>
                <p style="margin: 0; color: #475569; font-size: 12.5px;">Ce candidat a manifesté un intérêt actif pour <strong>{{ $applicationsCount }} offre(s)</strong> sur notre réseau de recrutement, démontrant sa motivation et sa proactivité professionnelle.</p>
                @endif

                <div class="footer">
                    Document généré automatiquement le {{ date('d/m/Y') }} par TalentHub.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
