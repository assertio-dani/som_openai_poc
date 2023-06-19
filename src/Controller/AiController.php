<?php

namespace App\Controller;

use OpenAI\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AiController extends AbstractController
{
    #[Route('/ai', name: 'app_ai')]
    public function index(Client $openai): Response
    {
        $prompt = 'Privalia Brasil es';

        $result = $openai->completions()->create([
            'model' => 'text-davinci-003',
//            'model' => 'gpt-3.5-turbo',
            'prompt' => $prompt,
            'max_tokens' => 50,
        ]);

//        dd($result);

        $aiResponse = $result['choices'][0]['text'];


        return $this->render('ai/index.html.twig', [
            'controller_name' => 'AiController',
            'prompt' => $prompt,
            'ai_response' => $aiResponse
        ]);
    }
}
