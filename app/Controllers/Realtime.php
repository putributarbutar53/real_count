<?php

namespace App\Controllers;

use App\Models\PaslonModel;
use App\Models\HasilModel;
use App\Models\KecamatanModel;
use CodeIgniter\API\ResponseTrait;
use Pusher\Pusher;
use GuzzleHttp\Client;

class Realtime extends BaseController
{
    protected $pusher;
    public function __construct() {
        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        $httpClient = new Client([
            'verify' => false,
        ]);

        $this->pusher = new Pusher(
            '9ac0d2af743317b62be2',
            '63c22ca53e56ea2bccba',
            '1889285',
            $options,
            $httpClient
        );
    }
    public function index()
    {
        $data = [
            'labels' => ['January', 'February', 'March', 'April', 'May'],
            'values' => [12, 19, 3, 5, 2]
        ];

        $this->pusher->trigger('chart-channel', 'update-event', $data);

        return json_encode(['status' => 'success']);
    }
}
