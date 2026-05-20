<?php

namespace HCloud\HSign;

class Template
{
    private Client $client;
    private int $templateId;

    private array $recipients = [];
    private array $prefillFields = [];
    private array $overrides = []; // Nouvelle propriété pour stocker les overrides

    public function __construct(Client $client, int $templateId)
    {
        $this->client = $client;
        $this->templateId = $templateId;
    }

    /**
     * Ajoute ou met à jour un destinataire.
     */
    public function setRecipients(int $id, string $email, string $name): self
    {
        $this->recipients[$id] = [
            'id' => $id,
            'email' => $email,
            'name' => $name
        ];

        return $this;
    }

    /**
     * Ajoute ou met à jour un champ pré-rempli.
     */
    public function setFields(int $id, mixed $value, string $type = 'text'): self
    {
        $this->prefillFields[$id] = [
            'id' => $id,
            'type' => $type,
            'value' => $value
        ];

        return $this;
    }

    /**
     * Ajoute une option de surcharge (override).
     * Exemples de clés : 'title', 'subject', 'timezone', 'drawSignatureEnabled', etc.
     */
    public function setOverride(string $key, mixed $value): self
    {
        $this->overrides[$key] = $value;

        return $this;
    }

    /**
     * Ajoute plusieurs overrides d'un coup via un tableau associatif.
     */
    public function setOverrides(array $overrides): self
    {
        foreach ($overrides as $key => $value) {
            $this->overrides[$key] = $value;
        }

        return $this;
    }

    /**
     * Compile le payload et l'envoie à l'API.
     */
    public function send(bool $distributeDocument = true): array
    {
        $payload = [
            'templateId' => $this->templateId,
            'recipients' => array_values($this->recipients),
            'prefillFields' => array_values($this->prefillFields),
            'distributeDocument' => $distributeDocument
        ];

        // On injecte le bloc override uniquement s'il n'est pas vide
        if (!empty($this->overrides)) {
            $payload['override'] = $this->overrides;
        }

        return $this->client->request('POST', 'template/use', $payload);
    }
}