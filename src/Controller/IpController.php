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
   # public $ip = $request->getClientIp();

    public function getIP(Request $request) 
    {
        $ip = $request->getClientIp();
        return $ip;
    }    



    public function getWeather() 
    {
        $ch = HttpClient::create();
        $response = $ch->request('GET', 'https://api.openweathermap.org/data/2.5/weather?zip=94040,us&appid=26e29aa16ee3a3a8af761f4dd0410824');
        $content = $response->getContent();
        #curl_setopt($ch, CURLOPT_URL, 'api.openweathermap.org/data/2.5/weather?zip=94040,us&appid=26e29aa16ee3a3a8af761f4dd0410824');
        #curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        #curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        #$response = curl_exec($ch);
        
        // If using JSON...
        #$data = json_decode($response);
        #$data2 = 
        return $content;
    }    

    #[Route('/ip', name: 'ip')]
    public function index(): Response
    {
        
        return $this->render('ip/index.html.twig', [
            'controller_name' => 'IpController',
            'data' =>$this->getWeather()
        ]);
    }

}
