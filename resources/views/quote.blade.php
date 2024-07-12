<!DOCTYPE html>
<html>
<head>
    <title>Quote</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .header, .footer { width: 100%; text-align: center; position: fixed; }
        .header { top: 0px; }
        .footer { bottom: 0px; font-size: 12px; }
        .page { page-break-after: always; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        .no-border { border: none; }
        .header-content { display: flex; justify-content: space-between; padding: 10px; }
        .header-left { text-align: left; }
        .header-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <img src="path/to/logo.png" alt="Logo" style="height: 50px;">
                <p>Anthony Cattan<br>Chemin François Chavaz 32<br>1213 Onex</p>
            </div>
            <div class="header-right">
                <p>Devis n° 16012023<br>Date: 16 janvier 2023<br>Echéance: 05 mars 2023<br>Monnaie: CHF (Franc suisse)</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>ADN Voyage SARL - Genève - Suisse | www.adnvoyage.com | info@adnvoyage.com</p>
    </div>

    <div class="content" style="margin-top: 100px; margin-bottom: 50px;">
        <h2>Client Details</h2>
        <p>CATTAN ANTHONY BENJAMIN MR</p>
        @foreach ($travelers as $traveler)
        <p>GENECAND SASKIA CORINNE MRS</p>
        <p>GENECAND INES NORA CHD (28 MARS 2019)</p>
        <p>GENECAND ELIJAH AARON CHD (28 MARS 2019)</p>
        <p>GENECAND JORDAN WILLIAM CHD (09 MARS 2015)</p>

        <h2>Invoice</h2>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qtté</th>
                    <th>Prix</th>
                    <th>Sous total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Séjour adultes 13 nuits à l’hôtel Canonnier Beachcomber Golf Resort & Spa 4*</td>
                    <td>2</td>
                    <td>4285.00</td>
                    <td>8570.00</td>
                </tr>
                <tr>
                    <td>Séjour enfant partageant le duplex des parents</td>
                    <td>3</td>
                    <td>630.00</td>
                    <td>1890.00</td>
                </tr>
                <tr>
                    <td>Les taxes d'aéroport et surcharge carburant</td>
                    <td>2</td>
                    <td>500.00</td>
                    <td>2500.00</td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border">Sous-total remise</td>
                    <td>-10%</td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border"></td>
                    <td>12960.00</td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border">Sous total en notre faveur</td>
                    <td>12960.00</td>
                </tr>
            </tbody>
        </table>

        <h2>Options supplémentaires commandées</h2>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qtté</th>
                    <th>Prix</th>
                    <th>Sous total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Visa</td>
                    <td>5</td>
                    <td>25.00</td>
                    <td>125.00</td>
                </tr>
                <tr>
                    <td>Repas</td>
                    <td>5</td>
                    <td>25.00</td>
                    <td>125.00</td>
                </tr>
                <tr>
                    <td>Excursion</td>
                    <td>5</td>
                    <td>30.00</td>
                    <td>150.00</td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border"></td>
                    <td>12960.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
