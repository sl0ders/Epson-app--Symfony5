<?php


namespace App\Services;

use App\Entity\Consumable;
use App\Entity\ConsumableDelta;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart;
use Doctrine\ORM\EntityManagerInterface;

/**
 * this Service manage all google chart
 * Class ChartServices
 * @package App\Services
 */
class ChartServices
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * this function
     * @param array $array
     * @param int|string $position
     * @param mixed $insert
     */
    function array_insert(array &$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }

    /**
     * this function create the deltas google chart from a printer
     * @param $deltas
     * @return ColumnChart
     */
    public function printByDate($deltas): ColumnChart
    {
        $deltaP = [];
        /**
         * @var ConsumableDelta $delta
         */
        foreach ($deltas as $key => $delta) {
            // convert the delta date on a string
            $date = date_format($delta->getUpdateAt(), "d-m-Y");
            // insert in an array all information for the google chart
            array_push($deltaP, [$date, intval($delta->getPPTDelta()), $delta->getPPTDelta(), intval($delta->getMPPDelta()), $delta->getMPPDelta(), intval($delta->getCPPDelta()), $delta->getCPPDelta()]);
        }
        // Add the label's chart
        $label = ["Date D'impression", "Nombre d'impression total", ['role' => 'annotation'], "Nombre d'impression N&B", ['role' => 'annotation'], "Nombre d'impression Couleur", ['role' => 'annotation']];

        // insert the label in first position of deltasinformation array
        $this->array_insert($deltaP, 0, [$label]);

        // creation of the chart
        $histo = new ColumnChart();
        // and add the informations array in this
        $histo->getData()->setArrayToDataTable(
            $deltaP
        );
        // the chart option is here
        $histo->getOptions()
            ->getChart()->setTitle("Statistique d'impression")->setSubtitle('Impressions des 3 derniers mois');
        $histo->getOptions()
            ->setBars('vertical')
            ->setColors(['#1b9e77', '#d95f02', '#7570b3'])
            ->setHeight(400);
        $histo->getOptions()->getVAxis()->setFormat('decimal');
        return $histo;
    }

    /**
     * this function create the consumable google chart from a printer
     * @param $consumables
     * @return LineChart
     */
    public function inkByDate($consumables): LineChart
    {
        // initialize of a final array
        $deltaP = [];
        /**
         * @var Consumable $consumable
         */
        foreach ($consumables as $key => $consumable) {
            // find the rest of retrieve bac
            $bac = 100 - $consumable->getMBR();
            // convert the delta date on a string
            $date = date_format($consumable->getDateUpdate(), "d-m-Y");
            // insert in an array all information for the google chart
            array_push($deltaP, [$date, intval($consumable->getYellow()), $consumable->getYellow(), intval($bac), $bac, intval($consumable->getCyan()), $consumable->getCyan(), intval($consumable->getBlack()), $consumable->getBlack(), intval($consumable->getMagenta()), $consumable->getMagenta()]);
        }
        // Add the label's chart
        $label = ["Date d'utilisation encre", "Utilisation Jaune", ['role' => 'annotation'], "Remplissage Bac", ['role' => 'annotation'], "Utilisation cyan", ['role' => 'annotation'], "Utilisation Noir", ['role' => 'annotation'], "Utilisation Magenta", ['role' => 'annotation']];

        // insert the label in first position of deltasinformation array
        $this->array_insert($deltaP, 0, [$label]);

        // creation of the chart
        $histo = new LineChart();

        // and add the informations array in this
        $histo->getData()->setArrayToDataTable(
            $deltaP
        );
        // the chart option is here
        $histo->getOptions()
            ->setHeight(400)
            ->setColors(['#ffff01', '#01fdfd', '#0098c0', '#070404', '#a6375f']);
        $histo->getOptions()->getChart()->setTitle("Statistique d'utilisation encre")->setSubtitle("Utilisation de l'encre durant ces 3 derniers mois");
        $histo->getOptions()->getVAxis()->setFormat('decimal')->setDirection(-1);
        return $histo;
    }
}
