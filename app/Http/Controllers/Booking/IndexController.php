<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use ItemInterface;

class IndexController extends Controller
{
    public function index()
    {
        Debugbar::alert(['test 2' => time()]);


        $paysHotels = Cache::remember(
            'reservation_home_paysHotels',
            3600,
            function () {

                DB::statement(
                    "CREATE TEMPORARY TABLE apt_valides
                    SELECT code_aeroport, code_pays, min(debut_validite) debut_validite, max(fin_validite) fin_validite
                    FROM (
                        -- get airports to which there's a valid transfer from a valid hotel
                        SELECT t.dpt_code_aeroport as code_aeroport
                            , code_pays
                            , min(c.debut_validite) debut_validite
                            , max(c.fin_validite) fin_validite
                        FROM hotels_new as h
                            JOIN lieux as l ON l.id_lieu = h.id_lieu
                            JOIN chambre as c ON c.id_hotel = h.id
                            JOIN transfert_new t ON t.arv_id_hotel = h.id
                        WHERE c.fin_validite > CURDATE()
                            AND t.fin_validite > CURDATE()
                        group by code_aeroport, code_pays
                    UNION ALL
                        -- get airports that are in the same region as a valid hotel
                        SELECT apt.code_aeroport
                            , l.code_pays
                            , min(c.debut_validite) debut
                            , max(c.fin_validite) fin_validite
                        FROM hotels_new as h
                            JOIN chambre as c ON c.id_hotel = h.id
                            JOIN lieux as l ON l.id_lieu = h.id_lieu
                            JOIN lieux as memeRegionLieu ON l.region_key = memeRegionLieu.region_key
                            JOIN aeroport as apt ON apt.id_lieu = memeRegionLieu.id_lieu
                        WHERE c.fin_validite > CURDATE()
                        group by code_aeroport, code_pays
                        order by code_aeroport
                    ) sub
                    GROUP BY code_aeroport, code_pays
                    order by code_pays, code_aeroport
                ",
                );

                // get all countries in which there are valid hotels
                $paysHotels = DB::select(
                    "SELECT p.code,
                        p.nom_fr_fr as pays,
                        MAKE_SET(BIT_OR(v.jours_depart),1,2,3,4,5,6,7) as jours_depart,
                        MIN(GREATEST(DATE_ADD(CURDATE(), INTERVAL 4 DAY), av.debut_validite, v.debut_voyage)) as min_valid,
                        MAX(LEAST(av.fin_validite, v.fin_voyage)) as max_valid
                    FROM pays as p
                        JOIN lieux as l ON l.code_pays = p.code
                        JOIN aeroport as apt ON apt.id_lieu = l.id_lieu
                            JOIN apt_valides av using (code_aeroport)
                        JOIN vols_new v ON v.code_apt_arrive = apt.code_aeroport
                    WHERE v.fin_voyage > CURDATE()
                    GROUP BY p.code, pays
                ",
                );

                $joursDepart = collect($paysHotels)
                    ->flatMap(fn($ph) => explode(',', $ph->jours_depart))
                    ->flip()->keys()->join(',');

                array_push($paysHotels, (object)[
                    'code'         => '',
                    'pays'         => 'Voir tous les produits',
                    'jours_depart' => $joursDepart,
                    'min_valid'    => null,
                    'max_valid'    => null,
                ]);

                return $paysHotels;
            }
        );


        return inertia(
            'Booking/Index',
            [
                'paysHotels' => $paysHotels,
                'message' => "Hellow from " . __FUNCTION__
            ],
        );
    }

    // public function show()
    // {
    //     return inertia('Booking/Show');
    // }
}