<?php

namespace Tests\Services;

use App\Models\DatahubPriceList;
use App\Services\GetDatahubPriceLists;
use App\Services\GetMeteringData;
use App\Services\GetPreliminaryInvoice;
use App\Services\GetSpotPrices;
use Mockery\MockInterface;
use Tests\TestCase;

class GetPreliminaryInvoiceTest extends TestCase
{

    private $charges;
    private mixed $spotPrices;
    private mixed $testConsumptions;
    private mixed $testCharges;


    protected function setUp(): void
    {

        //Got hold of some real charges and decided to use them here - not so important anyway what the values are
        //but we need a reliable datastructure.
        $this->charges = $this->loadTestData(fixture_path('typical_charges.json'));

        $this->spotPrices = $this->loadTestData(fixture_path('spot_prices.json'));

        //Consumption is normally returned from the service as an array with {date_time=>usage}
        //So a key with 00:00 means the usage between 00:00 and 01:00
        $this->testConsumptions = $this->loadTestData(fixture_path('consumption_data.json'));

        $array = [];
        foreach($this->loadTestData(fixture_path('charges.json')) as $object) {
            $datahubPriceList = new DatahubPriceList();
            $datahubPriceList->fill($object);
            array_push($array, $datahubPriceList);
        }

        $this->testCharges = collect($array);


        parent::setUp();
    }

    /**
     * Goal here is to check that the calculations can be performed on the expected datastructes.
     * Won't test the actual data/data-structures from providers - this is should be done in
     * other test (and is also important)
     *
     * @throws \App\Exceptions\DataUnavailableException
     * @throws \Tvup\ElOverblikApi\ElOverblikApiException
     * @throws \Tvup\EwiiApi\EwiiApiException
     */
    public function testGetBill()
    {
        $this->mock(GetMeteringData::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('getData')
                ->once()
                ->andReturn($this->testConsumptions);
            $mock
                ->shouldReceive('getCharges')
                ->once()
                ->andReturn($this->charges);
        });

        $this->mock(GetSpotPrices::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('getData')
                ->once()
                ->andReturn($this->spotPrices);
        });

        $this->mock(GetDatahubPriceLists::class, function (MockInterface $mock) {
            $query = DatahubPriceList::whereNote('Transmissions nettarif')->whereGlnNumber('5790000432752')->whereDescription('Netafgiften, for b??de forbrugere og producenter, d??kker omkostninger til drift og vedligehold af det overordnede elnet (132/150 og 400 kv nettet) og drift og vedligehold af udlandsforbindelserne.')->whereRaw('NOT (ValidFrom > \'' . '2022-10-01' . '\' OR (IF(ValidTo is null,\'2030-01-01\',ValidTo) < \'' . '2022-10-02' . '\' ))');
            $mock
                ->shouldReceive('getQueryForFetchingSpecificTariffFromDB')
                ->withArgs(['Transmissions nettarif', '5790000432752', 'Netafgiften, for b??de forbrugere og producenter, d??kker omkostninger til drift og vedligehold af det overordnede elnet (132/150 og 400 kv nettet) og drift og vedligehold af udlandsforbindelserne.', '2022-10-02', '2022-10-01'])
                ->andReturn($query);

            $mock
                ->shouldReceive('getFromQuery')
                ->with($query)
                ->once()
                ->andReturn($this->testCharges->filter(function ($item) {
                    return $item->Note == 'Transmissions nettarif';
                }));

            $query = DatahubPriceList::whereNote('Systemtarif')->whereGlnNumber('5790000432752')->whereDescription('Systemafgiften d??kker omkostninger til forsyningssikkerhed og elforsyningens kvalitet.')->whereRaw('NOT (ValidFrom > \'' . '2022-10-01' . '\' OR (IF(ValidTo is null,\'2030-01-01\',ValidTo) < \'' . '2022-10-02' . '\' ))');
            $mock
                ->shouldReceive('getQueryForFetchingSpecificTariffFromDB')
                ->withArgs(['Systemtarif', '5790000432752', 'Systemafgiften d??kker omkostninger til forsyningssikkerhed og elforsyningens kvalitet.', '2022-10-02', '2022-10-01'])
                ->andReturn($query);
            $mock
                ->shouldReceive('getFromQuery')
                ->with($query)
                ->once()
                ->andReturn($this->testCharges->filter(function ($item) {
                    return $item->Note == 'Systemtarif';
                }));

            $query = DatahubPriceList::whereNote('Balancetarif for forbrug')->whereGlnNumber('5790000432752')->whereDescription('Balancetarif for forbrug')->whereRaw('NOT (ValidFrom > \'' . '2022-10-01' . '\' OR (IF(ValidTo is null,\'2030-01-01\',ValidTo) < \'' . '2022-10-02' . '\' ))');
            $mock
                ->shouldReceive('getQueryForFetchingSpecificTariffFromDB')
                ->withArgs(['Balancetarif for forbrug', '5790000432752', 'Balancetarif for forbrug', '2022-10-02', '2022-10-01'])
                ->andReturn($query);
            $mock
                ->shouldReceive('getFromQuery')
                ->with($query)
                ->once()
                ->andReturn($this->testCharges->filter(function ($item) {
                    return $item->Note == 'Balancetarif for forbrug';
                }));

            $query = DatahubPriceList::whereNote('Elafgift')->whereGlnNumber('5790000432752')->whereDescription('Elafgiften')->whereRaw('NOT (ValidFrom > \'' . '2022-10-01' . '\' OR (IF(ValidTo is null,\'2030-01-01\',ValidTo) < \'' . '2022-10-02' . '\' ))');
            $mock
                ->shouldReceive('getQueryForFetchingSpecificTariffFromDB')
                ->withArgs(['Elafgift', '5790000432752', 'Elafgiften', '2022-10-02', '2022-10-01'])
                ->andReturn($query);
            $mock
                ->shouldReceive('getFromQuery')
                ->with($query)
                ->once()
                ->andReturn($this->testCharges->filter(function ($item) {
                    return $item->Note == 'Elafgift';
                }));

            $query = DatahubPriceList::whereNote('Nettarif C time')->whereGlnNumber('5790000705689')->whereDescription('Nettarif C time')->whereRaw('NOT (ValidFrom > \'' . '2022-10-01' . '\' OR (IF(ValidTo is null,\'2030-01-01\',ValidTo) < \'' . '2022-10-02' . '\' ))');
            $mock
                ->shouldReceive('getQueryForFetchingSpecificTariffFromDB')
                ->withArgs(['Nettarif C time', '5790000705689', 'Nettarif C time', '2022-10-02', '2022-10-01'])
                ->andReturn($query);
            $mock
                ->shouldReceive('getFromQuery')
                ->with($query)
                ->once()
                ->andReturn($this->testCharges->filter(function ($item) {
                    return $item->Note == 'Nettarif C time';
                }));


        });

        $expectedResult = [
            'meta' =>
                [
                    'Interval' =>
                        [
                            'fra' =>
                                "2022-10-01",
                            'til' =>
                                "2022-10-02",
                            'antal dage' =>
                                1,
                            'antal timer i intervallet' =>
                                24,
                        ],
                    'Forbrug' =>
                        "33.68 kWh",
                    'Kilde for forbrugsdata' =>
                        "DATAHUB",
                ],
            'Transmissions nettarif' => 1.65,
            'Systemtarif' => 2.04,
            'Balancetarif for forbrug' => 0.05,
            'Elafgift' => 24.35,
            'Nettarif C time' => 10.69,
            'Spotpris' => 11.38,
            'Overhead' => 0.53,
            'Netabo C forbrug skabelon/flex (forholdsvis antal dage pr. m??ned, m??nedspris: 21)' => 0.68,
            'Elabonnement (forholdsvis antal dage pr. m??ned, m??nedspris: 23.2)' => 0.75,
            'Moms' => 13.03,
            'Total' => 65.15,
            'Statistik' =>
                [
                    'Gennemsnitspris, str??m inkl. moms' => "0.44 kr./kWh",
                    'Gennemsnitspris, alt tarifering inkl. moms' => "1.88 kr./kWh",
                    'Gennemsnitspris, i alt (abonnementer indregnet) inkl. moms' => "1.93 kr./kWh",
                ]
        ];

        $preLiminaryInvoice = app(GetPreliminaryInvoice::class);
        $start_date = '2022-10-01';
        $end_date = '2022-10-02';
        $price_area = 'DK2';
        $smartMeCredentials = null;
        $dataSource = null; //At moment of writing this defaults to 'Datahub'
        $refreshToken = 'someFakeRefreshToken'; //Won't be used because we're mocking the service that contacts datahub
        //Only six parameters are needed here, the rest has defaults. We'll test the simple one for now
        $returnArray = $preLiminaryInvoice->getBill($start_date, $end_date, $price_area, $smartMeCredentials, $dataSource, $refreshToken);
        $this->assertEquals($expectedResult,$returnArray);
    }
}
