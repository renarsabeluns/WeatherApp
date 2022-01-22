<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\IPs;
use Symfony\Component\HttpClient\HttpClient;

class IpController extends AbstractController
{

    private $ip;

    private $region_name;
    private $country_code;
    public function getIP(Request $request)
    {
        $this->ip = '46.109.195.106';
        #return $ip;
    }


    public function getIPdata()
    {
        $client = HttpClient::create();

        $response = $client->request('GET', 'http://api.ipstack.com/' . $this->ip . '?access_key=4ad0e02fbf2e1a55a886b65c9d4a7644',  ['headers' => [
            'Accept' => 'application/json',
        ]],);
        $IpData = $response->toArray();
        $this ->region_name = $IpData['region_name'];
        $this ->country_code =$IpData['country_code'];
        
        return $IpData;
    }


    public function getWeather()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.openweathermap.org/data/2.5/weather?q='.$this->region_name.','.$this->country_code.'&appid=26e29aa16ee3a3a8af761f4dd0410824', ['headers' => [
            'Accept' => 'application/json',
        ]],);
        $WeatherData = $response->toArray();
        #curl_setopt($ch, CURLOPT_URL, 'api.openweathermap.org/data/2.5/weather?zip=94040,us&appid=26e29aa16ee3a3a8af761f4dd0410824');
        #curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        #curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        #$response = curl_exec($ch);

        // If using JSON...
        #$data = json_decode($response);
        #$data2 = 
        return $WeatherData;
    }

    #[Route('/ip', name: 'ip')]
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
