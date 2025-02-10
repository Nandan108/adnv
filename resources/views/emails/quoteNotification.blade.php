<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre devis ADN Voyage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            padding: 20px;
            background-color: #f9f9f9;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            font-weight:bold;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }

        header table {
            color:#6f6f6f;
            font-size:12px
        }
    </style>
</head>

<body>


        <x-mails.base :subject="$subject">
            <p>{{ $quote->longTitle }} {{ $quote->lastname }},</p>


            <p>Nous vous prions de trouver ci-dessous le lien vers votre devis initial n°{{ $quote->doc_id }}, pour votre référence :</p>

            <p><a href="{{ route('reservation.quote.show', $quote->hashId) }}" class="btn">Voir votre devis</a></p>

            <p>Cordialement,<br>
            L'équipe d'ADN Voyage</p>

            <p>ADN Voyage SARL - Genève - Suisse<br />
                <a href="https://www.adnvoyage.com">www.adnvoyage.com</a> -
                <a href="mailto:info@adnvoyage.com" target="_blank">info@adnvoyage.com</a>
            </p>
        </x-mails.base>
    </div>

</body>

</html>