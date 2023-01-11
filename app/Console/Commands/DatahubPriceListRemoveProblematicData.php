<?php

namespace App\Console\Commands;

use App\Models\DatahubPriceList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatahubPriceListRemoveProblematicData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datahubpricelist:remove-problematic-data {--simulate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes data from datahub_price_lists that are problematic due to the fact that we have retrievals that should return one maybe two entries, but since we don\'t have whole key for that retrieval we\'re getting unwanted data - therefore we delete those as a hack';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count5790001089030=0;
        $count5790000610099=0;
        $count5790001088309=0;
        if($this->option('simulate')) {
            $this->info('SIMULATION ONLY!');
            $count5790001089030 = DB::table('datahub_price_lists')->where('ChargeType', 'D03')->whereIn('ChargeTypeCode', ['A1 Rådig R', 'A1R D', 'A2 R D', 'A2 Rådig R', 'A200GWH R', 'B1 R D', 'B1 Rådig R', 'B2 Rådig R', 'B20GWh R D', 'B2D R'])->where('GLN_Number','5790001089030')->whereIn('ValidFrom', ['2022-12-01', '2022-01-01'])->where('Note','Rabat på nettarif N1 A/S')->where('description', 'like', 'Rabat på nettarif N1 A/S%')->count();
            $count5790000610099 = DB::table('datahub_price_lists')->where('ChargeType', 'D03')->where('ChargeTypeCode', 'TCL<100_02')->where('GLN_Number','5790000610099')->where('ValidFrom', '2022-10-01')->where('Note','Nettarif C time')->where('Description', 'Tarif, hvor aftagepunktet typisk er i 0,4 kV-nettet med timeaflæst måler.')->count();
            $count5790001088309 = DB::table('datahub_price_lists')->where('ChargeType', 'D03')->where('ChargeTypeCode', 'STR-NT-21')->where('GLN_Number','5790001088309')->where('ValidFrom', '2022-01-01')->where('Note','Net rådighedstarif C')->where('Description','Rådighedstarif, hvor aftagepunktet typisk er i 0,4 kV-nettet.')->count();
        } else {
            $count5790001089030 = DB::table('datahub_price_lists')->where('ChargeType', 'D03')->whereIn('ChargeTypeCode', ['A1 Rådig R', 'A1R D', 'A2 R D', 'A2 Rådig R', 'A200GWH R', 'B1 R D', 'B1 Rådig R', 'B2 Rådig R', 'B20GWh R D', 'B2D R'])->where('GLN_Number','5790001089030')->whereIn('ValidFrom', ['2022-12-01', '2022-01-01'])->where('Note','Rabat på nettarif N1 A/S')->where('description', 'like', 'Rabat på nettarif N1 A/S%')->delete();
            $count5790000610099 = DB::table('datahub_price_lists')->where('ChargeType', 'D03')->where('ChargeTypeCode', 'TCL<100_02')->where('GLN_Number','5790000610099')->where('ValidFrom', '2022-10-01')->where('Note','Nettarif C time')->where('Description', 'Tarif, hvor aftagepunktet typisk er i 0,4 kV-nettet med timeaflæst måler.')->delete();
            $count5790001088309 = DB::table('datahub_price_lists')->where('ChargeType', 'D03')->where('ChargeTypeCode', 'STR-NT-21')->where('GLN_Number','5790001088309')->where('ValidFrom', '2022-01-01')->where('Note','Net rådighedstarif C')->where('Description','Rådighedstarif, hvor aftagepunktet typisk er i 0,4 kV-nettet.')->delete();
        }
        $this->info('Removed ' . $count5790001089030 . ' from datahub_price_lists for GLN: 5790001089030');
        $this->info('Removed ' . $count5790000610099 . ' from datahub_price_lists for GLN: 5790000610099');
        $this->info('Removed ' . $count5790001088309 . ' from datahub_price_lists for GLN: 5790001088309');
        $this->info('Removed total ' . ($count5790001089030 + $count5790000610099 + $count5790001088309) . ' from datahub_price_lists');

        return Command::SUCCESS;
    }
}
