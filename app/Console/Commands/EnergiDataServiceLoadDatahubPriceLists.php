<?php

namespace App\Console\Commands;

use App\Models\DatahubPriceList;
use App\Services\GetEnergiDataServiceChargeGroups;
use App\Services\GetDatahubPriceLists;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Str;

class EnergiDataServiceLoadDatahubPriceLists extends Command
{
    const INTEGRITY_CONSTRAINT_VIOLATION = 'SQLSTATE[23000]';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'energidata:request-and-store-datahub-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Requests all prices from EnergiDataService. Subarea for prices is called "Datahub Price List" at EnergiDataService. All data is stored to local data storage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var GetDatahubPriceLists $datahubPriceListsService */
        $datahubPriceListsService = app(GetDatahubPriceLists::class);
        /** @var GetEnergiDataServiceChargeGroups $datahubChargeGroups */
        $datahubChargeGroups = app(GetEnergiDataServiceChargeGroups::class);

        $more = 1;
        $i = 0;
        while ($more) {
            $records = $datahubPriceListsService->requestAllDatahubPriceListsFromEnergiDataService(100, $i);
            foreach ($records as $record) {
                //GLN-number hack because energinet doesn't return gln number as they should
                $chargeOwner = $record['ChargeOwner'];
                $gln = cache()->remember('ChargeOwner-' . $chargeOwner, 3600, function () use ($chargeOwner, $datahubChargeGroups) {
                    try {
                        $chargeGroup = $datahubChargeGroups->getChargeGroup($chargeOwner);
                    } catch (ModelNotFoundException $e) {
                        throw new RecordsNotFoundException($e->getMessage() . ' with GridOperatorName = ' . $chargeOwner);
                    }
                    return substr($chargeGroup->grid_operator_gln, 0, -4);
                });
                try {
                    DatahubPriceList::create([
                        'ChargeOwner' => $record['ChargeOwner'],
                        'GLN_Number' => $gln,
                        'ChargeType' => $record['ChargeType'],
                        'ChargeTypeCode' => $record['ChargeTypeCode'],
                        'Note' => $record['Note'],
                        'Description' => $record['Description'],
                        'ValidFrom' => $record['ValidFrom'],
                        'ValidTo' => $record['ValidTo'],
                        'VATClass' => $record['VATClass'],
                        'Price1' => $record['Price1'],
                        'Price2' => $record['Price2'],
                        'Price3' => $record['Price3'],
                        'Price4' => $record['Price4'],
                        'Price5' => $record['Price5'],
                        'Price6' => $record['Price6'],
                        'Price7' => $record['Price7'],
                        'Price8' => $record['Price8'],
                        'Price9' => $record['Price9'],
                        'Price10' => $record['Price10'],
                        'Price11' => $record['Price11'],
                        'Price12' => $record['Price12'],
                        'Price13' => $record['Price13'],
                        'Price14' => $record['Price14'],
                        'Price15' => $record['Price15'],
                        'Price16' => $record['Price16'],
                        'Price17' => $record['Price17'],
                        'Price18' => $record['Price18'],
                        'Price19' => $record['Price19'],
                        'Price20' => $record['Price20'],
                        'Price21' => $record['Price21'],
                        'Price22' => $record['Price22'],
                        'Price23' => $record['Price23'],
                        'Price24' => $record['Price24'],
                        'TransparentInvoicing' => $record['TransparentInvoicing'],
                        'TaxIndicator' => $record['TaxIndicator'],
                        'ResolutionDuration' => $record['ResolutionDuration']]);
                } catch (QueryException $e) {
                    //We have seen that data from EnergiDataService wasn't as expected from documentation
                    //The documentation states that ChargeType, ChargeTypeCode, Note, and ValidFrom are primary key for dataset
                    //but that gives integrity violations for primary key. Adding 'Note' to primary key seems to fix this.
                    //In case there is more to it, we'll handle integrity violations by writing a warning.
                    if (Str::contains($e->getMessage(), self::INTEGRITY_CONSTRAINT_VIOLATION)) {
                        logger()->warning('Duplicate entry for GLN: ' . $gln . ' ChargeType: ' . $record['ChargeType'] . ' ChargeTypeCode: ' . $record['ChargeTypeCode'] . ' ValidFrom: ' . $record['ValidFrom']);
                        logger()->warning('More from same record: ValidTo: ' . $record['ValidTo'] . ' VATClass: ' . $record['VATClass'] . ' Resolution: ' . $record['ResolutionDuration']. ' Description: ' . $record['Description']);
                    } else {
                        throw $e;
                    }
                }
            }

            $i += 100;
            $more = count($records) !== 0;
        }
        return Command::SUCCESS;

    }
}
