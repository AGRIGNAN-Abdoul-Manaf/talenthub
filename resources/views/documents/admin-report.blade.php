<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport d'Activité - TalentHub</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 11.5px;
            line-height: 1.4;
            margin: 15px;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 19px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .subtitle {
            font-size: 10.5px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 3px;
        }
        .logo-text {
            font-size: 18px;
            font-weight: 800;
            color: #3b82f6;
            text-align: right;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 5px 10px;
            margin-top: 22px;
            margin-bottom: 10px;
            border-left: 3px solid #3b82f6;
            text-transform: uppercase;
        }
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 15px;
        }
        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
            width: 33.33%;
        }
        .kpi-val {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
            margin-top: 2px;
        }
        .kpi-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 10.5px;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .rate-badge {
            font-weight: bold;
            color: #059669;
            background-color: #ecfdf5;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }
        .footer {
            margin-top: 35px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">Rapport Global d'Activité</h1>
                <div class="subtitle">Statistiques & Analyses TalendHub</div>
            </td>
            <td class="logo-text">
                TalentHub <span style="font-size: 10px; font-weight: normal; color: #64748b; block">Espace Admin</span>
            </td>
        </tr>
    </table>

    <div style="font-size: 11px; color: #64748b; margin-bottom: 15px; text-align: right;">
        Édité le <strong>{{ date('d/m/Y') }}</strong> à <strong>{{ date('H:i') }}</strong>
    </div>

    <!-- SECTION 1 : COMPTEURS GLOBAUX -->
    <div class="section-title">Indicateurs de Performance (KPIs)</div>
    <table class="kpi-table">
        <tr>
            <td class="kpi-card">
                <div class="kpi-label">Offres Publiées</div>
                <div class="kpi-val">{{ $stats['total_jobs'] }}</div>
                <div style="font-size: 8.5px; color: #64748b; margin-top: 2px;">{{ $stats['active_jobs'] }} actives</div>
            </td>
            <td class="kpi-card">
                <div class="kpi-label">Candidatures</div>
                <div class="kpi-val">{{ $stats['total_applications'] }}</div>
                <div style="font-size: 8.5px; color: #64748b; margin-top: 2px;">Recueillies sur le portail</div>
            </td>
            <td class="kpi-card">
                <div class="kpi-label">Taux de Recrutement</div>
                <div class="kpi-val" style="color: #059669;">{{ $stats['recruitment_rate'] }}%</div>
                <div style="font-size: 8.5px; color: #64748b; margin-top: 2px;">Candidatures acceptées</div>
            </td>
        </tr>
    </table>

    <table class="kpi-table" style="margin-top: 10px;">
        <tr>
            <td class="kpi-card">
                <div class="kpi-label">Candidats Inscrits</div>
                <div class="kpi-val" style="color: #475569;">{{ $stats['total_candidates'] }}</div>
            </td>
            <td class="kpi-card">
                <div class="kpi-label">Recruteurs Inscrits</div>
                <div class="kpi-val" style="color: #475569;">{{ $stats['total_recruiters'] }}</div>
            </td>
            <td class="kpi-card">
                <div class="kpi-label">Entreprises Partenaires</div>
                <div class="kpi-val" style="color: #475569;">{{ $stats['total_companies'] }}</div>
            </td>
        </tr>
    </table>

    <!-- SECTION 2 : REPARTITIONS -->
    <div class="section-title">Répartitions & Réception des candidatures</div>
    <table style="width: 100%;">
        <tr>
            <!-- Table statuts -->
            <td style="width: 48%; padding-right: 15px; vertical-align: top;">
                <div style="font-weight: bold; margin-bottom: 5px; font-size: 11px;">Suivi des Candidatures par Statut</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th style="text-align: right;">Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['applications_by_status'] as $statusName => $count)
                        <tr>
                            <td>{{ $statusName }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>

            <!-- Table contrats -->
            <td style="width: 48%; vertical-align: top;">
                <div style="font-weight: bold; margin-bottom: 5px; font-size: 11px;">Répartition par Type de Contrat</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Contrat</th>
                            <th style="text-align: right;">Offres</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['jobs_by_contract'] as $contractType => $count)
                        <tr>
                            <td>{{ $contractType }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- SECTION 3 : STATISTIQUES PAR ENTREPRISE -->
    <div class="section-title">Statistiques & Performances des Entreprises</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Entreprise partenaire</th>
                <th style="text-align: center;">Offres Publiées</th>
                <th style="text-align: center;">Candidatures Reçues</th>
                <th style="text-align: right;">Taux de Recrutement</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies_stats as $c)
            <tr>
                <td style="font-weight: bold;">{{ $c['name'] }}</td>
                <td style="text-align: center;">{{ $c['jobs_count'] }}</td>
                <td style="text-align: center;">{{ $c['apps_count'] }}</td>
                <td style="text-align: right;">
                    <span class="rate-badge">{{ $c['recruitment_rate'] }}%</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #64748b; font-style: italic;">Aucune entreprise enregistrée.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SECTION 4 : DERNIERES INSCRIPTIONS -->
    <div class="section-title">Dernières Inscriptions & Activité Récente</div>
    <table style="width: 100%;">
        <tr>
            <td style="width: 48%; padding-right: 15px; vertical-align: top;">
                <div style="font-weight: bold; margin-bottom: 5px; font-size: 11px;">Nouveaux Membres</div>
                <table class="data-table" style="font-size: 10.5px;">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latest_users as $user)
                        <tr>
                            <td style="font-weight: 500;">{{ $user->name }}</td>
                            <td style="text-transform: capitalize; color: #475569;">{{ $user->role }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td style="width: 48%; vertical-align: top;">
                <div style="font-weight: bold; margin-bottom: 5px; font-size: 11px;">Nouvelles Entreprises</div>
                <table class="data-table" style="font-size: 10.5px;">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Localisation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latest_companies as $company)
                        <tr>
                            <td style="font-weight: bold; color: #3b82f6;">{{ $company->name }}</td>
                            <td>📍 {{ $company->location }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Rapport chiffré généré automatiquement par TalentHub &copy; {{ date('Y') }} &bull; Tous droits réservés.
    </div>
</body>
</html>
