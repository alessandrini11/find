<?php

namespace App\Service;

use App\Entity\Declaration;
use App\Entity\Visitor;
use App\Repository\VisitorRepository;

class VisitorService
{
    private VisitorRepository $visitorRepository;

    public function __construct(VisitorRepository $visitorRepository)
    {
        $this->visitorRepository = $visitorRepository;
    }

    public function getByIp($ip): Visitor
    {
        $visitor = $this->visitorRepository->findOneBy(["ip" => $ip]);
        if(!$visitor){
            $visitor = (new Visitor())
                    ->setIp($ip);
            $this->visitorRepository->save($visitor, true);
            return $visitor;
        }
        return $visitor;
    }

    public function isDeclarationInVisitor($ip, Declaration $declaration): void
    {
        $visitor = $this->getByIp($ip);
        if (!array_search($declaration, $visitor->getDeclarations()->toArray()))
        {
            $visitor->addDeclaration($declaration);
            $this->visitorRepository->save($visitor, true);
        }
    }
}