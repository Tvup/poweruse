<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\MeteringPoint;
use App\Services\GetMeteringData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tvup\ElOverblikApi\ElOverblikApiException;

class MeteringPointController extends Controller
{
    private GetMeteringData $meteringDataService;


    public function __construct(GetMeteringData $meteringDataService)
    {
        $this->meteringDataService = $meteringDataService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response | JsonResponse
     */
    public function index()
    {
        $data = MeteringPoint::orderBy('id', 'desc')->paginate(5);
        if($data->count()!=0) {
            return response()->json($data);
        }

        try {
            $data = $this->meteringDataService->getMeteringPointData(auth('api')->user()->refresh_token);
        } catch (ElOverblikApiException $e) {
            switch ($e->getCode()) {
                case 400:
                case 503:
                    $error = $e->getErrors();
                    $payload = $error['Payload'] ? ' with ' . json_encode($error['Payload'], JSON_PRETTY_PRINT) : '';
                    $message = '<strong>Request for mertering-point data at eloverblik failed</strong>' . '<br/>';
                    $message = $message . 'Datahub-server for ' . $error['Verb'] . ' ' . '<i>' . $error['Endpoint'] . '</i>' . $payload . ' gave a code <strong>' . $error['Code'] . '</strong> and this response: ' . '<strong>' . $error['Response'] . '</strong>';
                    return response()->json(['error' => $message]);
                case 401:
                    return response()->json(['error' => 'Failed - cannot login with token']);
                default:
                    return response($e->getMessage(), $e->getCode())
                        ->header('Content-Type', 'text/plain');
            }
        }
        $meteringPoint = app()->make(MeteringPoint::class);
        $meteringPoint->metering_point_id = $data['meteringPointId'];
        $meteringPoint->type_of_mp = $data['typeOfMP'];
        $meteringPoint->settlement_method = $data['settlementMethod'];
        $meteringPoint->meter_number = $data['meterNumber'];
        $meteringPoint->consumer_c_v_r = $data['consumerCVR'];
        $meteringPoint->data_access_c_v_r = $data['dataAccessCVR'];
        $meteringPoint->consumer_start_date = $data['consumerStartDate'];
        $meteringPoint->meter_reading_occurrence = $data['meterReadingOccurrence'];
        $meteringPoint->balance_supplier_name = $data['balanceSupplierName'];
        $meteringPoint->street_code = $data['streetCode'];
        $meteringPoint->street_name = $data['streetName'];
        $meteringPoint->building_number = $data['buildingNumber'];
        $meteringPoint->floor_id = $data['floorId'];
        $meteringPoint->room_id = $data['roomId'];
        $meteringPoint->city_name = $data['cityName'];
        $meteringPoint->city_sub_division_name = $data['citySubDivisionName'];
        $meteringPoint->municipality_code = $data['municipalityCode'];
        $meteringPoint->location_description = $data['locationDescription'];
        $meteringPoint->first_consumer_party_name = $data['firstConsumerPartyName'];
        $meteringPoint->second_consumer_party_name = $data['secondConsumerPartyName'];
        $meteringPoint->hasRelation = $data['hasRelation'];

        $data = collect([$meteringPoint]);
        $data = PaginationHelper::paginate($data, 10);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return MeteringPoint
     */
    public function store(Request $request)
    {
        // use /validate method provided by Illuminate\Http\Request object to validate post data
        // if validation fails JSON response will be sent for AJAX requests
        $this->validate($request, [
                'metering_point_id' => 'required|string|max:18',
                'parent_id' => 'sometimes|string|max:18',
                'type_of_mp' => 'required|string|max:3',
                'estimated_annual_volume' => 'required|digits|max:18',
                'settlement_method' => 'required|string|max:3',
                'meter_number' => 'required|string|max:15',
                'consumer_c_v_r' => 'sometimes|string|max:10',
                'data_access_c_v_r' => 'sometimes|string|max:10',
                'consumer_start_date' => 'sometimes|date',
                'meter_reading_occurrence' => 'required|string|max:5',
                'balance_supplier_name' => 'required|string',
                'street_code' => 'required|string|max:4',
                'street_name' => 'required|string|max:40',
                'building_number' => 'required|string|max:6',
                'floor_id' => 'sometimes|string|max:4',
                'room_id' => 'sometimes|string|max:4',
                'city_name' => 'required|string|max:25',
                'city_sub_division_name' => 'sometimes|string|max:34',
                'municipality_code' => 'required|string|max:3',
                'location_description' => 'sometimes|string|max:132',
                'first_consumer_party_name' => 'sometimes|string|max:132',
                'second_consumer_party_name' => 'sometimes|string|max:132',
                'hasRelation' => 'required|boolean',
            ]
        );

        return MeteringPoint::create([
            'metering_point_id' => $request['metering_point_id'],
            'parent_id' => $request['parent_id'],
            'type_of_mp' => $request['type_of_mp'],
            'estimated_annual_volume' =>$request['estimated_annual_volume'],
            'settlement_method' => $request['settlement_method'],
            'meter_number' => $request['meter_number'],
            'consumer_c_v_r' => $request['consumer_c_v_r'],
            'data_access_c_v_r' => $request['data_access_c_v_r'],
            'consumer_start_date' => $request['consumer_start_date'],
            'meter_reading_occurrence' => $request['meter_reading_occurrence'],
            'balance_supplier_name' => $request['balance_supplier_name'],
            'street_code' => $request['street_code'],
            'street_name' => $request['street_name'],
            'building_number' => $request['building_number'],
            'floor_id' => $request['floor_id'],
            'room_id' => $request['room_id'],
            'city_name' => $request['city_name'],
            'city_sub_division_name' => $request['city_sub_division_name'],
            'municipality_code' => $request['municipality_code'],
            'location_description' => $request['location_description'],
            'first_consumer_party_name' => $request['first_consumer_party_name'],
            'second_consumer_party_name' => $request['second_consumer_party_name'],
            'hasRelation' => $request['hasRelation'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeteringPoint  $meteringPoint
     * @return \Illuminate\Http\Response
     */
    public function show(MeteringPoint $meteringPoint)
    {
        return response(MeteringPoint::first()); //TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeteringPoint  $meteringPoint
     * @return MeteringPoint
     */
    public function update(Request $request, MeteringPoint $meteringPoint) :MeteringPoint
    {
        $this->validate($request, [
                'metering_point_id' => 'required|string|max:18',
                'parent_id' => 'sometimes|string|max:18',
                'type_of_mp' => 'required|string|max:3',
                'estimated_annual_volume' => 'required|digits|max:18',
                'settlement_method' => 'required|string|max:3',
                'meter_number' => 'required|string|max:15',
                'consumer_c_v_r' => 'sometimes|string|max:10',
                'data_access_c_v_r' => 'sometimes|string|max:10',
                'consumer_start_date' => 'sometimes|date',
                'meter_reading_occurrence' => 'required|string|max:5',
                'balance_supplier_name' => 'required|string',
                'street_code' => 'required|string|max:4',
                'street_name' => 'required|string|max:40',
                'building_number' => 'required|string|max:6',
                'floor_id' => 'sometimes|string|max:4',
                'room_id' => 'sometimes|string|max:4',
                'city_name' => 'required|string|max:25',
                'city_sub_division_name' => 'sometimes|string|max:34',
                'municipality_code' => 'required|string|max:3',
                'location_description' => 'sometimes|string|max:132',
                'first_consumer_party_name' => 'sometimes|string|max:132',
                'second_consumer_party_name' => 'sometimes|string|max:132',
                'hasRelation' => 'required|boolean',
            ]
        );

        $meteringPoint->update($request->all());

        return $meteringPoint;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeteringPoint  $meteringPoint
     * @return MeteringPoint
     */
    public function destroy(MeteringPoint $meteringPoint):MeteringPoint
    {
        $meteringPoint->delete();

        return $meteringPoint;
    }
}
