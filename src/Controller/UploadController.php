<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\CsvData;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use App\Message\CsvBatchInsertMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Repository\StatisticViewRepository;

class UploadController extends AbstractController
{
    private $statisticViewRepository;

    public function __construct(private StatisticViewRepository $repository)
    {
    }

    #[Route('/list', name: 'statistic_list', methods: ['GET'])]
    public function getList(Request $request): Response
    {
        // Get page and limit from the query parameters
        $page = $request->query->getInt('page', 1); // Default page value is 1 if not provided
        $limit = $request->query->getInt('limit', 10); // Default limit value is 10 if not provided

        // Retrieve statistics data using the StatisticViewRepository method
        [$data, $total] = $this->repository->getStatisticsData($page, $limit);
        
        if (empty($data)) {
            return $this->json(['message' => 'Empty data', 'data' => null, 'total' => $total, "success" => true]);
        }

        return $this->json(['message' => 'Successfully fetch', 'data' => $data, 'total' => $total, "success" => true]);
    }

    #[Route('/upload', name: 'upload_csv', methods: ['POST'])]
    public function upload(Request $request, MessageBusInterface $messageBus): Response
    {
        ini_set('max_execution_time', 300);
        $csvFile = $request->files->get('file');

        if ($csvFile) {
            $handle = fopen($csvFile->getPathname(), 'r');

            if ($handle !== false) {
                $batchSize = 100; // Set batch size as needed
                $batch = [];

                while (($row = fgetcsv($handle)) !== false) {
                    $dateTime = new \DateTime($row[1]);
                    $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);

                    $batch[] = [
                        'customer_id' => $row[0],
                        'created_at' => $dateTimeImmutable->format('Y-m-d H:i:s'),
                        'duration' => $row[2],
                        'phone' => $row[3],
                        'ipaddress' => $row[4],
                        'num_region' => $this->getContinentFromCountryCode(substr($row[3], 0, 3)),
                        'ip_region' => $this->getGeolocationData($row[4]),
                    ];

                    if (count($batch) >= $batchSize) {
                        $messageBus->dispatch(new CsvBatchInsertMessage($batch));
                        $batch = [];
                    }
                }

                fclose($handle);
            }

            // Insert any remaining records
            if (!empty($batch)) {
                $messageBus->dispatch(new CsvBatchInsertMessage($batch));
            }

            return $this->json(['message' => 'Data uploaded successfully', "success" => true]);
        }

        return $this->json(['message' => 'No file uploaded', "success" => false], 400);
    }

    private function getGeolocationData(string $ipAddress)
    {
        $httpClient = HttpClient::create();

        $queryParams = [
            'apiKey' => $_ENV['IPGEOLOCATION_KEY'],
            'ip' => $ipAddress,
            'lang' => 'en', // Optional: Modify as needed
            'fields' => '*', // Optional: Modify as needed
            'excludes' => '', // Optional: Modify as needed
        ];

        $response = $httpClient->request('GET', 'https://api.ipgeolocation.io/ipgeo', [
            'query' => $queryParams,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        return $response->toArray()['continent_code'];
    }

    private function getContinentFromCountryCode($phoneCountryCode)
    {
        $data = file_get_contents('http://download.geonames.org/export/dump/countryInfo.txt');

        // Initialize an array to store country code to continent mapping
        $countryToContinent = [];

        // Process the file line by line
        $lines = explode("\n", $data);
        foreach ($lines as $line) {
            // Skip commented lines
            if (strpos($line, '#') === 0) {
                continue;
            }

            // Split the line by tab delimiter
            $fields = explode("\t", $line);

            // Example: Extracting country code and continent
            if (count($fields) >= 2) {
                $phone = $fields[12];
                $continent = $fields[8]; // Column 8 contains the continent information

                // Store the country code to continent mapping in an array
                $countryToContinent[$phone] = $continent;
            }
        }

        // Function to determine continent from country code
        if (isset($countryToContinent[$phoneCountryCode])) {
            return $countryToContinent[$phoneCountryCode];
        } else {
            return false;
        }
    }

}
