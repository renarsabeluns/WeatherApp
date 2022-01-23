<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\IPs;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;

class IpController extends AbstractController
{

    private $ip;

    private $region_name;
    private $country_code;
    public function getIP(Request $request,)
    {
        $this->ip = '46.109.195.106';
        #return $ip;
    }


    public function getIPdata()
    {
        $store = new Store('var\cache\dev\pools\app');
        $client = HttpClient::create();
        $client = new CachingHttpClient($client, $store);

        $response = $client->request('GET', 'http://api.ipstack.com/' . $this->ip . '?access_key=7cdd5c912853213038180e6765e3edc1',  ['headers' => [
            'Accept' => 'application/json',
        ]],);
        $IpData = $response->toArray();
        $this ->region_name = $IpData['region_name'];
        $this ->country_code =$IpData['country_code'];
        
        return $IpData;
    }


    public function getWeather()
    {
        $store = new Store('var\cache\dev\pools\app');
        $client = HttpClient::create();
        $client = new CachingHttpClient($client, $store);
        $response = $client->request('GET', 'https://api.openweathermap.org/data/2.5/weather?q='.$this->region_name.','.$this->country_code.'&appid=26e29aa16ee3a3a8af761f4dd0410824', ['headers' => [
            'Accept' => 'application/json',
        ]],);
        $WeatherData = $response->toArray();
        return $WeatherData;
    }

    #[Route('/', name: 'ip')]
    public function index(Request $request): Response
    {
        $this->getIP($request);
        $this->getIPdata();

        return $this->render('ip/index.html.twig', [
            'controller_name' => 'IpController',
            'WeatherData' => $this->getWeather(),
            'ip' => $this->ip,
            'IpData' => $this->getIPdata(),
            'country_code'=>$this->country_code

        ]);
    }
}
