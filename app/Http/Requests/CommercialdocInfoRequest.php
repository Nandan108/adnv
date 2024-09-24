<?php

namespace App\Http\Requests;

use App\Enums\CommercialdocInfoType as InfoType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommercialdocInfoRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'type' => ['required', Rule::in(InfoType::cases())],
            'data' => 'required|array',
        ];

        $dataRules = $this->getDataValidationRules($this->input('type'));

        return array_merge($rules, $dataRules);
    }

    protected function getDataValidationRules(string $type)
    {
        $dataRules = [];

        switch ($type) {
            case InfoType::HeaderResId->value:
                $dataRules['data.name'] = 'required|string';
                $dataRules['data.id'] = 'required|string';
                break;

            case InfoType::FlightLine->value:
                $dataRules['data.date'] = 'required|date';
                $dataRules['data.airline'] = 'required|string';
                $dataRules['data.flight_num'] = 'required|string';

                $airportExists = Rule::exists('aeroport', 'code_aeroport');

                $dataRules['data.origin'] = ['required', 'regex:/^[A-Z]{3}$/', $airportExists];
                $dataRules['data.dest'] = ['required', 'regex:/^[A-Z]{3}$/', $airportExists];
                $dataRules['data.dep_time'] = 'required|date_format:H:i';
                $dataRules['data.arr_time'] = 'required|date_format:H:i';
                //$dataRules['data.arr_next_day'] = 'required|boolean';
                break;

            case InfoType::TransferLine->value:
                $dataRules['data.pickup'] = 'required|date';
                $dataRules['data.dropoff'] = 'required|date';
                $dataRules['data.duration'] = 'required|string';
                $dataRules['data.route'] = 'required|string';
                $dataRules['data.vehicle'] = 'required|string';
                break;

            case InfoType::HotelLine->value:
                $dataRules['data.checkin'] = 'required|date';
                $dataRules['data.checkout'] = 'required|date';
                $dataRules['data.hotel'] = 'required|string';
                $dataRules['data.room_type'] = 'required|string';
                $dataRules['data.meal_type'] = 'required|string';
                break;

            case InfoType::TransfertComments->value:
                $dataRules['data.comments'] = 'required|string';
                break;

            case InfoType::TravelerLine->value:
                $dataRules['data.name'] = 'required|string';
                $dataRules['data.ticket_num'] = 'required|string';
                break;

            case InfoType::TripInfo->value:
                $dataRules['data.info'] = 'required|string';
                break;

            default:
                break;
        }

        return $dataRules;
    }
}
