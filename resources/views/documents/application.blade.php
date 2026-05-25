<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candidature - {{ $application->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            font-size: 12.5px;
            line-height: 1.45;
            margin: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3px;
        }
        .logo-text {
            font-size: 18px;
            font-weight: 800;
            color: #4f46e5;
            text-align: right;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 6px 12px;
            margin-top: 25px;
            margin-bottom: 12px;
            border-left: 3px solid #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 6px 10px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #475569;
            width: 30%;
        }
        .info-val {
            color: #0f172a;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #d97706;
        }
        .badge-accepted {
            background-color: #d1fae5;
            color: #059669;
        }
        .badge-refused {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .badge-interview {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .content-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        .skill-tag {
            display: inline-block;
            background-color: #e2e8f0;
            color: #334155;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 11px;
            margin-right: 4px;
            margin-bottom: 5px;
        }
        .message-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e2e8f0;
        }
        .message-meta {
            font-size: 10.5px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .message-sender-recruteur {
            color: #4f46e5;
        }
        .message-sender-candidat {
            color: #0d9488;
        }
        .message-sender-system {
            color: #d97706;
        }
        .message-text {
            font-size: 11.5px;
            color: #334155;
            margin: 0;
            font-style: italic;
        }
        .footer {
            margin-top: 40px;
            font-size: 9.5px;
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
                <h1 class="title">Dossier RH - Candidature</h1>
                <div class="subtitle">Récapitulatif et Évaluation Candidat</div>
            </td>
            <td class="logo-text">
                RecruHub <span style="font-size: 10px; font-weight: normal; color: #64748b; block">Espace Pro</span>
            </td>
        </tr>
    </table>

    <!-- SECTION 1 : POSTE ET STATUT -->
    <div class="section-title">Détails de l'Offre & Statut</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Poste ciblé :</td>
            <td class="info-val" style="font-weight: bold; font-size: 14px; color: #4f46e5;">{{ $application->job->title }}</td>
        </tr>
        <tr>
            <td class="info-label">Entreprise :</td>
            <td class="info-val">{{ $application->job->company->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Type de contrat :</td>
            <td class="info-val">{{ $application->job->contract_type }} (📍 {{ $application->job->location }})</td>
        </tr>
        <tr>
            <td class="info-label">Statut actuel :</td>
            <td class="info-val">
                @php
                    $status = $application->status;
                    $badgeClass = 'badge-pending';
                    if($status === 'Acceptée') $badgeClass = 'badge-accepted';
                    elseif($status === 'Refusée') $badgeClass = 'badge-refused';
                    elseif($status === 'Entretien programmé') $badgeClass = 'badge-interview';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
            </td>
        </tr>
        <tr>
            <td class="info-label">Date de soumission :</td>
            <td class="info-val">{{ $application->created_at->format('d/m/Y à H:i') }}</td>
        </tr>
    </table>

    <!-- SECTION 2 : PROFIL DU CANDIDAT -->
    <div class="section-title">Profil du Candidat</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nom Complet :</td>
            <td class="info-val" style="font-weight: bold;">{{ $application->user->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Adresse Email :</td>
            <td class="info-val">{{ $application->user->email }}</td>
        </tr>
        @if($application->user->phone)
        <tr>
            <td class="info-label">Téléphone :</td>
            <td class="info-val">{{ $application->user->phone }}</td>
        </tr>
        @endif
        <tr>
            <td class="info-label">Compétences :</td>
            <td class="info-val">
                @forelse(explode(',', $application->user->skills ?? '') as $skill)
                    @if(trim($skill))
                        <span class="skill-tag">{{ trim($skill) }}</span>
                    @endif
                @empty
                    <span style="font-style: italic; color: #64748b;">Aucune compétence déclarée</span>
                @endforelse
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; color: #475569; margin-bottom: 5px; font-size: 11.5px;">Biographie du Candidat :</div>
    <div class="content-box">
        {!! nl2br(e($application->user->bio ?? "Ce candidat n'a pas rédigé de biographie.")) !!}
    </div>

    <!-- SECTION 3 : EVALUATION RH PRIVEE -->
    <div class="section-title">Évaluation Interne & Entretien (Privé Recruteur)</div>
    
    <div style="font-weight: bold; color: #475569; margin-bottom: 5px; font-size: 11.5px;">Notes de recrutement :</div>
    <div class="content-box" style="background-color: #fefeff; border-left: 3px solid #64748b;">
        {!! nl2br(e($application->recruiter_notes ?? "Aucune note d'évaluation interne n'a encore été rédigée sur cette candidature.")) !!}
    </div>

    @if($application->interview_date)
    <div style="font-weight: bold; color: #475569; margin-top: 15px; margin-bottom: 5px; font-size: 11.5px;">Modalités d'entretien programmé :</div>
    <table class="info-table" style="background-color: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 6px; padding: 10px;">
        <tr>
            <td class="info-label" style="width: 25%;">Date & Heure :</td>
            <td class="info-val" style="font-weight: bold; color: #4f46e5;">Le {{ \Carbon\Carbon::parse($application->interview_date)->format('d/m/Y') }} à {{ $application->interview_time }}</td>
        </tr>
        <tr>
            <td class="info-label">Lieu / Lien Zoom :</td>
            <td class="info-val" style="color: #4f46e5;">{{ $application->interview_location }}</td>
        </tr>
        @if($application->interview_details)
        <tr>
            <td class="info-label">Consignes additionnelles :</td>
            <td class="info-val" style="font-style: italic;">{{ $application->interview_details }}</td>
        </tr>
        @endif
    </table>
    @endif

    <!-- SECTION 4 : FIL DES DISCUSSIONS -->
    @if(is_array($application->messages) && count($application->messages) > 0)
    <div class="section-title">Fil des Échanges & Messagerie</div>
    <div class="content-box" style="background-color: #fafafa;">
        @foreach($application->messages as $msg)
            @php
                $sender = $msg['sender'] ?? 'system';
                $senderLabel = 'SYSTÈME';
                $senderClass = 'message-sender-system';
                if($sender === 'recruteur') {
                    $senderLabel = 'RECRUTEUR';
                    $senderClass = 'message-sender-recruteur';
                } elseif($sender === 'candidat') {
                    $senderLabel = 'CANDIDAT';
                    $senderClass = 'message-sender-candidat';
                }
            @endphp
            <div class="message-item">
                <div class="message-meta">
                    <span class="{{ $senderClass }}">{{ $senderLabel }}</span> &bull; 
                    <span style="color: #94a3b8; font-weight: normal;">{{ date('d/m/Y H:i', strtotime($msg['created_at'] ?? 'now')) }}</span>
                </div>
                <p class="message-text">"{!! e($msg['text'] ?? '') !!}"</p>
            </div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        Dossier de candidature édité le {{ date('d/m/Y') }} &bull; Confidentiel et réservé à l'usage interne de l'entreprise.
    </div>
</body>
</html>
