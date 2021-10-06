<?php


namespace App\Services;

use App\Entity\Cartridge;
use App\Entity\Company;
use App\Entity\RecoveryBac;
use App\Repository\RecoveryBacRepository;
use App\Repository\CartridgeRepository;
use App\Repository\CompanyRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Exception;
use phpDocumentor\Reflection\Types\Void_;
use App\Entity\Consumable;
use App\Entity\ConsumableDelta;
use App\Entity\Printer;
use App\Repository\ConsumableDeltaRepository;
use App\Repository\ConsumableRepository;
use App\Repository\PrinterRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PrinterManager
{
    private
        $em, $managerXml, $printerRepository, $consumableRepository, $deltaRepository, $companyRepository,
        $notificationRepository, $cartridgeRepository, $emailService, $translator, $tokenStorage, $session, $userRepository,
        $notificationServices, $recoveryBacRepository;

    public function __construct(
        EntityManagerInterface $em,
        xmlManager $managerXml,
        PrinterRepository $printerRepository,
        ConsumableRepository $consumableRepository,
        ConsumableDeltaRepository $deltaRepository,
        CompanyRepository $companyRepository,
        NotificationRepository $notificationRepository,
        CartridgeRepository $cartridgeRepository,
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage,
        EmailService $emailService,
        TranslatorInterface $translator,
        SessionInterface $session,
        NotificationServices $notificationServices,
        RecoveryBacRepository $recoveryBacRepository
    )
    {
        $this->em = $em;
        $this->managerXml = $managerXml;
        $this->printerRepository = $printerRepository;
        $this->consumableRepository = $consumableRepository;
        $this->deltaRepository = $deltaRepository;
        $this->companyRepository = $companyRepository;
        $this->notificationRepository = $notificationRepository;
        $this->cartridgeRepository = $cartridgeRepository;
        $this->emailService = $emailService;
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->notificationServices = $notificationServices;
        $this->recoveryBacRepository = $recoveryBacRepository;
    }

    /**
     * @param $collection
     * @return array
     */
    public function sortingByDesc($collection): array
    {
        $results = [];
        $iterator = $collection->getIterator();
        $iterator->uasort(function ($a, $b) {
                return ($a->getDateUpdate() > $b->getDateUpdate()) ? -1 : 1;
        });
        $a_result = iterator_to_array($iterator);
        foreach ($a_result as $result) {
            array_push($results, $result);
        }
        return $results;
    }

    /**
     * Create an array with all information of xml file
     * @param $uri
     * @return array
     */
    public function createPrintArrayFromXmlFile($uri): array
    {
        // for each url of xml file
        $xml = $this->managerXml->ParserXml($uri);
        // retrieve the company name from the file name
        $companyName = strstr($uri, "- ");
        $companyName = substr($companyName, 2);
        $companyName = strstr($companyName, "-", true);
        $prints = [];
        foreach ($xml as $printer) {
            $print = [];
            $print['company'] = $companyName;
            foreach ($printer->property as $property) {
                // Retrieve the attributes as element indices
                switch ((string)$property['name']) {
                    case 'DNS':
                        $print['DNS'] = $property["value"];
                        break;
                    case 'DATE':
                        $print['DATE'] = $property["value"];
                        break;
                    case 'MBD':
                        $print['MBD'] = $property["value"];
                        break;
                    case 'DES':
                        $print['DES'] = $property["value"];
                        break;
                    case 'Office':
                        $print['Office'] = $property["value"];
                        break;
                    case 'MAC':
                        $print['MAC'] = $property["value"];
                        break;
                    case 'SN':
                        $print['SN'] = $property["value"];
                        break;
                    case 'SWV':
                        $print['SWV'] = $property["value"];
                        break;
                    case 'IP':
                        $print['IP'] = $property["value"];
                        break;
                    case 'A4M':
                        $print['A4M'] = $property["value"];
                        break;
                    case 'BIR':
                        $print['BIR'] = $property["value"];
                        break;
                    case 'CIR':
                        $print['CIR'] = $property["value"];
                        break;
                    case 'MIR':
                        $print['MIR'] = $property["value"];
                        break;
                    case 'YIR':
                        $print['YIR'] = $property["value"];
                        break;
                    case 'A4C':
                        $print['A4C'] = $property["value"];
                        break;
                    case 'A4DM':
                        $print['A4DM'] = $property["value"];
                        break;
                    case 'A4DC':
                        $print['A4DC'] = $property["value"];
                        break;
                    case 'A3M':
                        $print['A3M'] = $property["value"];
                        break;
                    case 'A3C':
                        $print['A3C'] = $property["value"];
                        break;
                    case 'A3DM':
                        $print['A3DM'] = $property["value"];
                        break;
                    case 'A3DC':
                        $print['A3DC'] = $property["value"];
                        break;
                    case 'TPP':
                        $print['TPP'] = $property["value"];
                        break;
                    case 'MPP':
                        $print['MPP'] = $property["value"];
                        break;
                    case 'CPP':
                        $print['CPP'] = $property["value"];
                        break;
                    case 'MBR':
                        $print['MBR'] = $property["value"];
                        break;
                    case 'BID':
                        $print['BID'] = $property["value"];
                        break;
                    case 'CID':
                        $print['CID'] = $property["value"];
                        break;
                    case 'YID':
                        $print['YID'] = $property["value"];
                        break;
                    case 'MID':
                        $print['MID'] = $property["value"];
                        break;
                    case 'CISN':
                        $print['CISN'] = $property["value"];
                        break;
                    case 'YISN':
                        $print['YISN'] = $property["value"];
                        break;
                    case 'MISN':
                        $print['MISN'] = $property["value"];
                        break;
                    case 'BISN':
                        $print['BISN'] = $property["value"];
                        break;
                }
            }
            array_push($prints, $print);
        }
        return $prints;
    }

    /**
     * This function retrieve all consumable information and add this in the new consumable entité if not exist
     * @param $prints
     * @return false|string
     * @throws Exception
     */
    public function addPrintWithConsumable($prints)
    {
        foreach ($prints as $print) {
            // Add this company at printer
            $date = new DateTime($print["DATE"]);
            $printer = $this->printerRepository->findOneBy(["sn" => $print['SN']]);
            $consumable = $this->consumableRepository->findOneBy(["date_update" => $date, "print" => $printer]);
            $cartridgeYellow = $this->em->getRepository(Cartridge::class)->findOneBy(["serial_number" => $print["YISN"]]);
            $cartridgeMagenta = $this->em->getRepository(Cartridge::class)->findOneBy(["serial_number" => $print["MISN"]]);
            $cartridgeCyan = $this->em->getRepository(Cartridge::class)->findOneBy(["serial_number" => $print["CISN"]]);
            $cartridgeBlack = $this->em->getRepository(Cartridge::class)->findOneBy(["serial_number" => $print["BISN"]]);
            $recoveryBac = $this->em->getRepository(RecoveryBac::class)->findOneBy(["name" => $print["MBD"]]);
            $company = $this->companyRepository->findOneBy(['name' => $print["company"]]);
            // verify if the consumable not exist
            if (!$consumable) {
                // verify if the print not exist for create an new print
                if (!$printer) {
                    $printer = new Printer();
                }
                // Each file is not identical we check if the property of file exist for avoid mistakes
                if (isset($print['DES'])) {
                    $printer->setDes($print["DES"]);
                }
                $printer->setEnable(1);
                if (isset($print['IP'])) {
                    $printer->setIp($print['IP']);
                }
                if (isset($print['MAC'])) {
                    $printer->setMac($print['MAC']);
                }
                if (isset($print['SN'])) {
                    $printer->setSn($print['SN']);
                }
                if (isset($print['SWV'])) {
                    $printer->setSwv($print['SWV']);
                }
                if (isset($print['Office'])) {
                    $printer->setOffice($print['Office']);
                }
                if (isset($print['DNS'])) {
                    $printer->setName($print['DNS']);
                }
                $printer->setState("printer.states.operational");

                /********************************************************************************************************************************************/
                /*YELLOW*/
                /********************************************************************************************************************************************/
                // verification of the existence of the ink cartridge in the database
                /** @var Cartridge $cartridgeYellow */
                if (!$cartridgeYellow) {
                    $cartridgeYellow = new Cartridge();
                    $cartridgeYellow
                        ->setName($print['YID'])
                        ->setColorCode('#ffff01')
                        ->setColor('yellow')
                        ->setPrinter($printer)
                        ->setSerialNumber($print["YISN"]);
                    // there are 4 kinds of espon cartridge for each color
                    // the type of cartridge it is in his name
                    //I check what type it is to determine the size of it
                    if (strpos($print['YID'], "T01D4")) {
                        $cartridgeYellow->setSize("XXL");
                        // if the cartridge is new
                        if ($print['YIR'] >= 99) {
                            // thus create a new average during a change
                            $consumableYirMax = $this->consumableRepository->findYellowmax($printer);
                            $consumableYirMin = $this->consumableRepository->findYellowMin($printer);
                            $prints = $consumableYirMin - $consumableYirMax;
                            $cartridgeYellow->setPrintAverage($prints);
                        } else {
                            // this average of print,is taken on the epson site
                            $cartridgeYellow->setPrintAverage(20000);
                        }
                    } elseif (strpos($print['YID'], "T9734") || strpos($print['YID'], "T7544")) {
                        $cartridgeYellow->setSize("XL");
                        if (strpos($print['YID'], "T9734")) {
                            if ($print['YIR'] >= 99) {
                                $consumableYirMax = $this->consumableRepository->findYellowmax($printer);
                                $consumableYirMin = $this->consumableRepository->findYellowMin($printer);
                                $prints = $consumableYirMin - $consumableYirMax;
                                $cartridgeYellow->setPrintAverage($prints);
                            } else {
                                $cartridgeYellow->setPrintAverage(22000);
                            }
                        } elseif (strpos($print['YID'], "T7544")) {
                            if ($print['YIR'] >= 99) {
                                $consumableYirMax = $this->consumableRepository->findYellowmax($printer);
                                $consumableYirMin = $this->consumableRepository->findYellowMin($printer);
                                $prints = $consumableYirMin - $consumableYirMax;
                                $cartridgeYellow->setPrintAverage($prints);
                            } else {
                                $cartridgeYellow->setPrintAverage(7000);
                            }
                        }
                        // the T07 is a small cartridge
                    } elseif (strpos($print['YID'], "T0754")) {
                        $cartridgeYellow->setSize("M");
                        if ($print['YIR'] >= 99) {
                            $consumableYirMax = $this->consumableRepository->findYellowmax($printer);
                            $consumableYirMin = $this->consumableRepository->findYellowMin($printer);
                            $prints = $consumableYirMin - $consumableYirMax;
                            $cartridgeYellow->setPrintAverage($prints);
                        } else {
                            $cartridgeYellow->setPrintAverage(170);
                        }
                    }
                    $this->em->persist($cartridgeYellow);
                }

                /********************************************************************************************************************************************/
                /*MAGENTA*/
                /********************************************************************************************************************************************/
                if (!$cartridgeMagenta) {
                    $cartridgeMagenta = new Cartridge();
                    $cartridgeMagenta
                        ->setName($print['MID'])
                        ->setColorCode('#a6375f')
                        ->setColor('magenta')
                        ->setPrinter($printer)
                        ->setSerialNumber($print["MISN"]);
                    if (strpos($print['MID'], "T01D3")) {
                        $cartridgeMagenta->setSize("XXL");
                        if ($print['MIR'] >= 99) {
                            $consumableMirMax = $this->consumableRepository->findMagentamax($printer);
                            $consumableMirMin = $this->consumableRepository->findMagentaMin($printer);
                            $prints = $consumableMirMin - $consumableMirMax;
                            $cartridgeMagenta->setPrintAverage($prints);
                        } else {
                            $cartridgeMagenta->setPrintAverage(20000);
                        }
                    } elseif (strpos($print['MID'], "T9733") || strpos($print['MID'], "T7543")) {
                        $cartridgeMagenta->setSize("XL");
                        if (strpos($print['MID'], "T7543")) {
                            if ($print['MIR'] >= 99) {
                                $consumableMirMax = $this->consumableRepository->findMagentamax($printer);
                                $consumableMirMin = $this->consumableRepository->findMagentaMin($printer);
                                $prints = $consumableMirMin - $consumableMirMax;
                                $cartridgeMagenta->setPrintAverage($prints);
                            } else {
                                $cartridgeMagenta->setPrintAverage(7000);
                            }
                        } elseif (strpos($print['MID'], "T9733")) {
                            if ($print['MIR'] >= 99) {
                                $consumableMirMax = $this->consumableRepository->findMagentamax($printer);
                                $consumableMirMin = $this->consumableRepository->findMagentaMin($printer);
                                $prints = $consumableMirMin - $consumableMirMax;
                                $cartridgeMagenta->setPrintAverage($prints);
                            } else {
                                $cartridgeMagenta->setPrintAverage(22000);
                            }
                        } elseif (strpos($print['BID'], "T0753")) {
                            $cartridgeMagenta->setSize("M");
                            if ($print['MIR'] >= 99) {
                                $consumableMirMax = $this->consumableRepository->findMagentamax($printer);
                                $consumableMirMin = $this->consumableRepository->findMagentaMin($printer);
                                $prints = $consumableMirMin - $consumableMirMax;
                                $cartridgeMagenta->setPrintAverage($prints);
                            } else {
                                $cartridgeMagenta->setPrintAverage(170);
                            }
                        }
                    }
                    $this->em->persist($cartridgeMagenta);
                }

                /********************************************************************************************************************************************/
                /*CYAN*/
                /********************************************************************************************************************************************/
                if (!$cartridgeCyan) {
                    $cartridgeCyan = new Cartridge();
                    $cartridgeCyan
                        ->setName($print['CID'])
                        ->setColorCode('#0098c0')
                        ->setColor('cyan')
                        ->setPrinter($printer)
                        ->setSerialNumber($print["CISN"]);
                    if (strpos($print['CID'], "T01D2")) {
                        $cartridgeCyan->setSize("XXL");
                        if (strpos($print['CID'], "T01D2")) {
                            if ($print['CIR'] >= 99) {
                                $consumableCirMax = $this->consumableRepository->findCyanMax($printer);
                                $consumableCirMin = $this->consumableRepository->findCyanMin($printer);
                                $prints = $consumableCirMin - $consumableCirMax;
                                $cartridgeCyan->setPrintAverage($prints);
                            } else {
                                $cartridgeCyan->setPrintAverage(20000);
                            }
                        }
                    } elseif (strpos($print['CID'], "T7542") || strpos($print['CID'], "T9732")) {
                        $cartridgeCyan->setSize("XL");
                        if (strpos($print['CID'], "T7542")) {
                            if ($print['CIR'] >= 99) {
                                $consumableCirMax = $this->consumableRepository->findCyanMax($printer);
                                $consumableCirMin = $this->consumableRepository->findCyanMin($printer);
                                $prints = $consumableCirMin - $consumableCirMax;
                                $cartridgeCyan->setPrintAverage($prints);
                            } else {
                                $cartridgeCyan->setPrintAverage(7000);
                            }
                        } elseif (strpos($print['CID'], "T9732")) {
                            if ($print['CIR'] >= 99) {
                                $consumableCirMax = $this->consumableRepository->findCyanMax($printer);
                                $consumableCirMin = $this->consumableRepository->findCyanMin($printer);
                                $prints = $consumableCirMin - $consumableCirMax;
                                $cartridgeCyan->setPrintAverage($prints);
                            } else {
                                $cartridgeCyan->setPrintAverage(22000);
                            }
                        }
                    } elseif (strpos($print['BID'], "T0752")) {
                        $cartridgeCyan->setSize("M");
                        if ($print['CIR'] >= 99) {
                            $consumableCirMax = $this->consumableRepository->findCyanMax($printer);
                            $consumableCirMin = $this->consumableRepository->findCyanMin($printer);
                            $prints = $consumableCirMin - $consumableCirMax;
                            $cartridgeCyan->setPrintAverage($prints);
                        } else {
                            $cartridgeCyan->setPrintAverage(170);
                        }
                    }
                    $this->em->persist($cartridgeCyan);
                }

                /********************************************************************************************************************************************/
                /*BLACK*/
                /********************************************************************************************************************************************/
                if (!$cartridgeBlack) {
                    $cartridgeBlack = new Cartridge();
                    $cartridgeBlack
                        ->setName($print['BID'])
                        ->setColorCode("#000000")
                        ->setColor('black')
                        ->setPrinter($printer)
                        ->setSerialNumber($print["BISN"]);
                    if (strpos($print['BID'], "T01D1")) {
                        $cartridgeBlack->setSize("XXL");
                        if ($print['BIR'] >= 99) {
                            $consumableBirMax = $this->consumableRepository->findBlackMax($printer);
                            $consumableBirMin = $this->consumableRepository->findBlackMin($printer);
                            $prints = $consumableBirMin - $consumableBirMax;
                            $cartridgeBlack->setPrintAverage($prints);
                        } else {
                            $cartridgeBlack->setPrintAverage(7000);
                        }
                    } elseif (strpos($print['BID'], "T7541") || strpos($print['BID'], "T9731")) {
                        $cartridgeBlack->setSize("XL");
                        if (strpos($print['BID'], "T7541")) {
                            if ($print['BIR'] >= 99) {
                                $consumableBirMax = $this->consumableRepository->findBlackMax($printer);
                                $consumableBirMin = $this->consumableRepository->findBlackMin($printer);
                                $prints = $consumableBirMin - $consumableBirMax;
                                $cartridgeBlack->setPrintAverage($prints);
                            } else {
                                $cartridgeBlack->setPrintAverage(10000);
                            }
                        } elseif (strpos($print['BID'], "T9731")) {
                            if ($print['BIR'] >= 99) {
                                $consumableBirMax = $this->consumableRepository->findBlackMax($printer);
                                $consumableBirMin = $this->consumableRepository->findBlackMin($printer);
                                $prints = $consumableBirMin - $consumableBirMax;
                                $cartridgeBlack->setPrintAverage($prints);
                            } else {
                                $cartridgeBlack->setPrintAverage(22500);
                            }
                        }
                    } elseif (strpos($print['BID'], "T0751")) {
                        $cartridgeBlack->setSize("M");
                        if ($print['BIR'] >= 99) {
                            $consumableBirMax = $this->consumableRepository->findBlackMax($printer);
                            $consumableBirMin = $this->consumableRepository->findBlackMin($printer);
                            $prints = $consumableBirMin - $consumableBirMax;
                            $cartridgeBlack->setPrintAverage($prints);
                        } else {
                            $cartridgeBlack->setPrintAverage(170);
                        }
                    }
                    $this->em->persist($cartridgeBlack);
                }

                /********************************************************************************************************************************************/
                /*Recovery_bac*/
                /********************************************************************************************************************************************/

                // it's same for the bac
                if (!$recoveryBac) {
                    $recoveryBac = new RecoveryBac();
                    $recoveryBac
                        ->setName($print['MBD'])
                        ->setPrinter($printer);
                    if ($print['MBR'] = 0) {
                        $consumableMbrMax = $this->consumableRepository->findBacMax($printer);
                        $consumableMbrMin = $this->consumableRepository->findBacMin($printer);
                        $prints = $consumableMbrMin - $consumableMbrMax;
                        $recoveryBac->setPrintAverage($prints);
                    } else {
                        $recoveryBac->setPrintAverage(50000);
                    }
                    $this->em->persist($recoveryBac);
                }
                // if the company not exist, we create a new
                if (!$company) {
                    $company = new Company();
                    $company
                        ->setIsEnabled(true)
                        ->setName($print['company'])
                        ->setUpdatedAt(new \DateTime())
                        ->setCode(strtoupper(substr($print['company'], 0, 2)))
                        // this breaking point is select arbitrarily
                        ->setBacBreakingUpLvl(70.00)
                        ->setInkBreakingUpDays(30.00)
                        ->setInkBreakingUpLvl(30.00)
                        ->setBacBreakingUpDays(30.00);
                    $this->em->persist($company);
                }
                $printer->setCompany($company);
                $printer->setUpdateAt(new DateTime());
                $this->em->persist($printer);
                $this->em->flush();

                // Creation of Consumable Object
                $consumable = new Consumable();
                $consumable
                    ->setPrint($printer)
                    ->setBlack($print["BIR"])
                    ->setMagenta($print["MIR"])
                    ->setCyan($print["CIR"])
                    ->setPPT($print['TPP'])
                    ->setCPP($print['CPP'])
                    ->setMPP($print['MPP'])
                    ->setYellow($print["YIR"])
                    ->setDateUpdate($date);
                // this verification is here because each file not containte this paper format
                if (isset($print["A3C"])) {
                    $consumable->setA3C($print["A3C"]);
                }
                if (isset($print["A3M"])) {
                    $consumable->setA3M($print["A3M"]);
                }
                if (isset($print["A3DC"])) {
                    $consumable->setA3DC($print["A3DC"]);
                }
                if (isset($print["A3DM"])) {
                    $consumable->setA3DM($print["A3DM"]);
                }
                if (isset($print["A4M"])) {
                    $consumable->setA4M($print["A4M"]);
                }
                if (isset($print["A4C"])) {
                    $consumable->setA4C($print["A4C"]);
                }
                if (isset($print["A4DM"])) {
                    $consumable->setA4DM($print["A4DM"]);
                }
                if (isset($print["A4DC"])) {
                    $consumable->setA4DC($print["A4DC"]);
                }
                if (isset($print["MBR"])) {
                    $consumable->setMBR($print["MBR"]);
                }
                $printer->setEnable(false);
                $this->em->persist($consumable);
                $this->em->flush();
            }
            $this->em->flush();
        }

        return Void_::class;
    }

    /**
     * this function leans on consumable for create all deltas
     * @param array $consumables
     * @param $printer
     * @return bool
     */
    public function addDeltas(array $consumables, $printer): bool
    {
        for ($i = 0; $i <= count($consumables); $i++) {
            //check if there are at least two consumables in the database
            if (isset($consumables[$i]) && isset($consumables[$i + 1])) {
                // Verification of the existence of the consumable
                $consumable_delta = $this->em->getRepository(ConsumableDelta::class)->findOneBy(["updateAt" => $consumables[$i]->getDateUpdate(), "printer" => $printer]);
                // If consumble not exist
                if (!$consumable_delta) {
                    // Creation of delta for each printer
                    $consumable_delta = new ConsumableDelta();
                    $consumable_delta
                        // to create a delta I take the previous value of the consumable and the current value, I subtract them
                        ->setMPPDelta($consumables[$i]->getMPP() - $consumables[$i + 1]->getMPP())
                        ->setPPTDelta($consumables[$i]->getPPT() - $consumables[$i + 1]->getPPT())
                        ->setCPPDelta($consumables[$i]->getCPP() - $consumables[$i + 1]->getCPP())
                        ->setA3CDelta($consumables[$i]->getA3C() - $consumables[$i + 1]->getA3C())
                        ->setA3DMDelta($consumables[$i]->getA3DM() - $consumables[$i + 1]->getA3DM())
                        ->setA3DCDelta($consumables[$i]->getA3DC() - $consumables[$i + 1]->getA3DC())
                        ->setA4DCDelta($consumables[$i]->getA4DC() - $consumables[$i + 1]->getA4DC())
                        ->setA4DMDelta($consumables[$i]->getA4DM() - $consumables[$i + 1]->getA4DM())
                        ->setMBRDelta($consumables[$i]->getMBR() - $consumables[$i + 1]->getMBR())
                        ->setA3MDelta($consumables[$i]->getA3M() - $consumables[$i + 1]->getA3M())
                        ->setA4CDelta($consumables[$i]->getA4C() - $consumables[$i + 1]->getA4C())
                        ->setA4MDelta($consumables[$i]->getA4M() - $consumables[$i + 1]->getA4M())
                        ->setCyanDelta($consumables[$i + 1]->getCyan() - $consumables[$i]->getCyan())
                        ->setYellowDelta($consumables[$i + 1]->getYellow() - $consumables[$i]->getYellow())
                        ->setBlackDelta($consumables[$i + 1]->getBlack() - $consumables[$i]->getBlack())
                        ->setMagentaDelta($consumables[$i + 1]->getMagenta() - $consumables[$i]->getMagenta())
                        ->setUpdateAt($consumables[$i]->getDateUpdate())
                        ->setConsumable($consumables[$i])
                        ->setPrinter($printer);
                    $this->em->persist($consumable_delta);
                }
                $this->em->flush();
            } else {
                return false;
            }
        }
        return Void_::class;
    }

    /**
     * This function create all average Weighted for cartridge print and bac
     * @param $printer
     * @return array|null
     */
    public function averageCalculationWeighted($printer): ?array
    {
        // we initialize each sum to zero
        $a_average = [];
        $i = 0;
        $sum_i = 0;
        $sumPPT_delta = 0;
        $sumMPP_delta = 0;
        $sumCPP_delta = 0;
        $sumBlack_delta = 0;
        $sumMagenta_delta = 0;
        $sumYellow_delta = 0;
        $sumCyan_delta = 0;
        $sumMbr = 0;
        // retrieve of the print delta for to multiply it by a value incremented at each loop revolution
        $deltaMPP = $this->em->getRepository(ConsumableDelta::class)->findBy(["printer" => $printer]);
        foreach ($deltaMPP as $cle => $delta) {
            $i++;
            $deltaMbr = $delta->getMBRDelta() * $i;
            $deltaPPT = $delta->getPPTDelta() * $i;
            $deltaPrintBlack = $delta->getMPPDelta() * $i;
            $deltaPrintColor = $delta->getCPPDelta() * $i;
            $deltaBlackInk = $delta->getBlackDelta() * $i;
            $deltaMagentaInk = $delta->getMagentaDelta() * $i;
            $deltaYellowInk = $delta->getYellowDelta() * $i;
            $deltaCyanInk = $delta->getCyanDelta() * $i;
            $sum_i += $i;
            $sumMbr -= $deltaMbr;
            $sumPPT_delta += $deltaPPT;
            $sumMPP_delta += $deltaPrintBlack;
            $sumCPP_delta += $deltaPrintColor;
            $sumBlack_delta += $deltaBlackInk;
            $sumMagenta_delta += $deltaMagentaInk;
            $sumYellow_delta += $deltaYellowInk;
            $sumCyan_delta += $deltaCyanInk;
        }
        if ($sum_i > 0) {
            $a_average['average_by_day_for_bac'] = $sumMbr / $sum_i;
            $a_average["average_by_day_for_allPrint"] = $sumPPT_delta / $sum_i;
            $a_average["average_by_day_for_bwPrint"] = $sumMPP_delta / $sum_i;
            $a_average["average_by_day_for_colorPrint"] = $sumCPP_delta / $sum_i;
            $a_average["average_by_day_for_black"] = $sumBlack_delta / $sum_i;
            $a_average["average_by_day_for_magenta"] = $sumMagenta_delta / $sum_i;
            $a_average["average_by_day_for_yellow"] = $sumYellow_delta / $sum_i;
            $a_average["average_by_day_for_cyan"] = $sumCyan_delta / $sum_i;
            return $a_average;
        }
        return null;
    }

    /**
     * This function calcule the rest of print for each cartridge and bac
     * @param Printer $printer
     * @return array
     */
    public function calculationCartridgeReplacement(Printer $printer): array
    {
        // retrieve all cartridge of this printer
        $cartridges = $this->em->getRepository(Cartridge::class)->findBy(['printer' => $printer]);
        // Retrieve last lvl ink and bac for this printer
        $consumable = $this->em->getRepository(Consumable::class)->findLastLvlByPrint($printer);
        // retrieve the bac average of this printer
        $recoveryBac = $this->em->getRepository(RecoveryBac::class)->findOneBy(['printer' => $printer]);

        $restPage = [];
        // calculates the remaining number of pages used per collection tray
        $bacPagesUtils = $recoveryBac->getPrintAverage() * $consumable["MBR"] / 100;
        // calcul of number page used for each cartridge
        foreach ($cartridges as $cartridge) {
            $cartridgePagesUtils = $cartridge->getPrintAverage() * $consumable[strtolower($cartridge->getColor())] / 100;
            $color = $cartridge->getColor();
            $restPage[$color] = $cartridge->getPrintAverage() - $cartridgePagesUtils;
        }
        $restPage['bac'] = $recoveryBac->getPrintAverage() - $bacPagesUtils;
        return $restPage;
    }

    /**
     * This function is here for send notification and mail if we detect a lack of ink or an overflow of the filling tank
     * @param Printer $printer
     * @return int
     */
    public function mailAndNotification(Printer $printer): int
    {
        // here we defined the sender and receiver of mail and notifications
        /** @var Company $companySender */
        $companySender = $printer->getCompany();
        $users = $this->userRepository->findBy(["company" => $companySender]);
        $adminCompany = $this->companyRepository->findOneBy(["name" => "AdminCompany"]);
        // notification count for display in the header
        $countNotification = 0;
        /** @var RecoveryBac $average */
        $average = $this->recoveryBacRepository->findOneBy(["printer" => $printer]);
        $cartridges = $this->em->getRepository(Cartridge::class)->findBy(['printer' => $printer]);

        ## Email alert and notification for bac lvl of each printer  START##
        if (round($average->getRestDays()) <= $companySender->getBacBreakingUpDays()) {
            #Notification - start#
            $message = $this->translator->trans("printer.bac.almostFull", [
                "%daysBac%" => round($average->getRestDays()),
                "%name%" => $printer->getName(),
                "%company%" => $printer->getCompany()->getName()
            ],
                "EpsonProjectTrans");
            $notif = $this->notificationServices->newNotification($message, $adminCompany, $companySender, ["user_printer_show", $printer->getId()]);
            if ($notif === true) {
                $countNotification++;
            }
            #Notification  - End#

            #Email - start#
            $subject = $this->translator->trans("printer.email.alert.averageFullBac", [], "EpsonProjectTrans");
            $context = [
                "bacFullDays" => true,
                'daysFullBac' => round($average->getRestDays()),
                "average" => $average,
                "printer" => $printer
            ];
            $this->emailService->sendMail($subject, $adminCompany->getEmail(), $users, $context);
            #Email - end#}
        }
        if ($average->getRestBacPercent() > $companySender->getBacBreakingUpLvl()) {
            #Notification - start#
            $message = $this->translator->trans("printer.bac.InkLvlFull", [
                "%fullyBac%" => $average->getRestBacPercent(),
                "%name%" => $printer->getName(),
                "%company%" => $printer->getCompany()->getName()
            ],
                "EpsonProjectTrans");
            $notif = $this->notificationServices->newNotification($message, $adminCompany, $companySender, ["user_printer_show", $printer->getId()]);
            if ($notif === true) {
                $countNotification++;
            }
            #Notification  - End#

            #Email - start#
            $subject = $this->translator->trans("printer.email.alert.fullBac", [], "EpsonProjectTrans");
            $context = [
                "bacFullPercent" => true,
                'average' => $average,
                "printer" => $printer,
            ];
            $this->emailService->sendMail($subject, $adminCompany->getEmail(), $users, $context);
            #Email - end#
        }
        ## Email alert and notification for bac lvl of each printer  END##

        #For each cartridge
        /** @var Cartridge $cartridge */
        foreach ($cartridges as $cartridge) {
            //If utilisation average it's < 30 i sent one email alert and create one notification
            if (round($cartridge->getRestDays()) <= $companySender->getInkBreakingUpDays()) {
                #Notification - start#
                $message = $this->translator->trans(
                    "printer.ink.almostEmpty", [
                    "%daysInk%" => round($cartridge->getRestDays()),
                    "%color%" => $cartridge->getColor(),
                    "%name%" => $printer->getName(),
                    "%company%" => $printer->getCompany()->getName()
                ],
                    "EpsonProjectTrans");
                $notif = $this->notificationServices->newNotification($message, $adminCompany, $companySender, ["user_printer_show", $printer->getId()]);
                if ($notif === true) {
                    $countNotification++;
                }
                #Notification  - End#

                #Email - start#
                $subject = $this->translator->trans("printer.email.alert.averageInk", [], "EpsonProjectTrans");
                $context = [
                    "inkEmptyDays" => true,
                    "cartridge" => $cartridge
                ];
                $this->emailService->sendMail($subject, $adminCompany->getEmail(), $users, $context);
                #Email - end#
                $this->em->flush();
            }
            //If rest ink in this cartridge i sent one email alert and create one notification
            if ($cartridge->getRestInkPercent() <= $companySender->getInkBreakingUpLvl()) {
                #Notification - start#
                $message = $this->translator->trans(
                    "printer.ink.InkLvlLow", [
                    "%restInk%" => $cartridge->getRestInkPercent(),
                    "%restDays%" => round($cartridge->getRestDays()),
                    "%color%" => $cartridge->getColor(),
                    "%name%" => $printer->getName(),
                    "%company%" => $printer->getCompany()->getName()
                ],
                    "EpsonProjectTrans");
                $notif = $this->notificationServices->newNotification($message, $adminCompany, $companySender, ["user_printer_show", $printer->getId()]);
                if ($notif === true) {
                    $countNotification++;
                }
                #Notification  - End#

                #Email - start#
                $subject = $this->translator->trans("printer.email.alert.restInk", [], "EpsonProjectTrans");
                $context = [
                    "inkEmptyPercent" => true,
                    "cartridge" => $cartridge
                ];
                $this->emailService->sendMail($subject, $adminCompany->getEmail(), $users, $context);
                #Email - end#
                $this->em->flush();
            }
        }
        // check if this printer has worked well for the last 10 days
        $sixLastConsumable = $this->consumableRepository->FindTenLastConsumables($printer);
        if ($sixLastConsumable[0]->getPPT() === $sixLastConsumable[8]->getPPT()) {
            $printer->setState("printer.states.broken");
            #Notification - start#
            $message = $this->translator->trans(
                "notification.printer.notUsed", [
                "%printer%" => $printer->getName(),
                "%company%" => $printer->getCompany()->getName(),
            ],
                "EpsonProjectTrans");
            $notif = $this->notificationServices->notificationOfPrintAnomaly($message, $printer, $sixLastConsumable, $adminCompany);
            if ($notif === true) {
                $countNotification++;
            }
            #Notification  - End#

            #Email - start#
            $subject = $this->translator->trans("notification.printer.notUsed", ["%printer%" => $printer->getName(), "%company%" => $printer->getCompany()->getName()], "EpsonProjectTrans");
            $context = [
                "notUsed" => true,
                'printer' => $printer,
                "company" => $printer->getCompany(),
            ];
            $this->emailService->sendMail($subject, $adminCompany->getEmail(), $users, $context);
            #Email - end#}
            $this->em->flush();
        }
        return $countNotification;
    }
}

