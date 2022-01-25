<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
        #Define cache storage
        $store = new Store('var\cache\dev\pools\app'); 

        $client = HttpClient::create();

        #Adds caching on top of HttpClient
        $client = new CachingHttpClient($client, $store); 

        #request IP data from ipstack.com/ where ip is gotten from client in the getIP function
        $response = $client->request('GET', 'http://api.ipstack.com/' . $this->ip . '?access_key=7489ef648a7c4de4a5a3a01296b1ee3a',  ['headers' => [
            'Accept' => 'application/json',
        ]],); 

        #Transform response to an array 
        $IpData = $response->toArray(); 

        #Put region_name and country_code into variables for usage in getWeather function
        if ($IpData['region_name'] != null && $IpData['country_code'] !=null ) {
            $this->region_name = $IpData['region_name'];
            $this->country_code = $IpData['country_code'];

            return $IpData;
        } else
        {
            $IpData = 'not available';
        }

    }


    public function getWeather()
    {
        $store = new Store('var\cache\dev\pools\app');
        $client = HttpClient::create();
        $client = new CachingHttpClient($client, $store);

        #request weather data from openweathermap.org where region_name and country_code is gotten from getIPdata function variables
        if ($this -> region_name != null && $this -> country_code != null) {
            $response = $client->request('GET', 'https://api.openweathermap.org/data/2.5/weather?q=' . $this->region_name . ',' . $this->country_code . '&appid=26e29aa16ee3a3a8af761f4dd0410824', ['headers' => [
            'Accept' => 'application/json',
        ]], );
        
            $WeatherData = $response->toArray();

            #encode the response array to json format
            return json_encode($WeatherData);

        } else {
            #I ran into a problem where the IP service said that monthly usage limit has been reached so this if is for checking if it is running.
            $WeatherData = 'Not available due to IP service - "info":"Your monthly usage limit has been reached. Please upgrade your Subscription Plan."}';
            return $WeatherData;
        }
    }

    #[Route('/', name: 'ip')]
    public function index(Request $request): Response
    {
        $this->getIP($request); 
        $this->getIPdata();

        return $this->render('ip/index.html.twig', [
            'controller_name' => 'IpController',
            'WeatherData' => $this->getWeather()

        ]);
    }
}
