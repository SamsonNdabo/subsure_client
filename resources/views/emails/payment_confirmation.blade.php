<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de paiement - SubSure</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
        
        <div style="text-align: center; margin-bottom: 25px;">
            <img src="{{ asset('assets/images/logo-footer.png') }}" alt="SubSure" style="max-width: 120px;">
        </div>

        <h2 style="color: #2dce89; text-align: center;">✅ Paiement confirmé</h2>

        <p>Bonjour,</p>
        <p style="margin-bottom: 20px;">
            Nous confirmons la réception de votre paiement pour votre abonnement sur <strong>SubSure</strong>.
        </p>

        <h3 style="color: #333;">🧾 Détails du paiement :</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
            <tr>
                <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9;">Abonnement ID</td>
                <td style="padding: 10px; border: 1px solid #e0e0e0;">
                    {{ $abonnementId ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9;">Montant payé</td>
                <td style="padding: 10px; border: 1px solid #e0e0e0;">
                    ${{ isset($prix) ? number_format($prix, 2) : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #e0e0e0; background-color: #f9f9f9;">Date</td>
                <td style="padding: 10px; border: 1px solid #e0e0e0;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>

        <p>Merci de votre confiance et à bientôt sur notre plateforme !</p>

        <p style="margin-top: 40px; font-size: 14px; color: #888;">— L’équipe <strong>SubSure</strong></p>
    </div>
</body>
</html>
