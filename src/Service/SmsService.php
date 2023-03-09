<?php

namespace App\Service;

use App\DTO\SmsResponse;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class SmsService
{
    private HttpClientInterface $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    public function sendSms(SmsResponse $to, string $from, string $documentName): bool
    {
        $sender_id ='Find';
        $destinataire = $to->phoneNumber;
        $message ="bonjour M/Mme {$to->fullName}, le numéro {$from} souhaite entrer en possession de {$documentName}";
        $login ='690469551';
        $password ='wamba';
        $ext_id='0123456';
        $time ='0';
        $dest='https://sms.etech-keys.com/ss/api.php?login='.$login.'&password='.urlencode($password).'&sender_id='.urlencode($sender_id);
        $html_brand = $dest.'&destinataire='.trim($destinataire).'&message='.urlencode($message).'&ext_id='.$ext_id.'&programmation='.$time;
        try {
            $response = $this->client->request(
                'POST',
                $html_brand,
                ['headers' => []]
            );
            if($response->getStatusCode() === Response::HTTP_OK){
                return true;
            } else {
                return false;
            }
        } catch (ExceptionInterface $exception) {
            dd($exception->getMessage());
        }
    }
    public function getFund(): void
    {
        // Récupérer le contenu de la page Web à partir de l'url.
        $url = "https://sms.etech-keys.com/ss/api_credit.php?login=690469551&password=wamba";

// Initialisez une session CURL.
        $ch = curl_init();

// Récupérer le contenu de la page
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//Saisir l'URL et la transmettre à la variable.
        curl_setopt($ch, CURLOPT_URL, $url);
//Désactiver la vérification du certificat puisque waytolearnx utilise HTTPS
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//Exécutez la requête
        $result = curl_exec($ch);
//Afficher le résultat
        dd($result);
    }
}