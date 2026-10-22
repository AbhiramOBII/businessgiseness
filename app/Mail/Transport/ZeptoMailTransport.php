<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

/**
 * Sends Laravel mail through the ZeptoMail (Zoho) email API.
 * Docs: POST https://{host}/v1.1/email with "Authorization: Zoho-enczapikey …".
 */
class ZeptoMailTransport extends AbstractTransport
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $host = 'api.zeptomail.in',
        private readonly int $timeout = 15,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $envelope = $message->getEnvelope();

        $from = $email->getFrom()[0] ?? $envelope->getSender();
        $to = $email->getTo() ?: $envelope->getRecipients();

        $payload = array_filter([
            'from' => $this->address($from),
            'to' => $this->recipients($to),
            'cc' => $this->recipients($email->getCc()),
            'bcc' => $this->recipients($email->getBcc()),
            'reply_to' => array_map(fn (Address $a) => $this->address($a), $email->getReplyTo()),
            'subject' => (string) $email->getSubject(),
            'htmlbody' => $email->getHtmlBody() !== null ? (string) $email->getHtmlBody() : null,
            'textbody' => $email->getTextBody() !== null ? (string) $email->getTextBody() : null,
            'attachments' => array_map(fn ($part) => [
                'content' => base64_encode($part->getBody()),
                'mime_type' => $part->getMediaType() . '/' . $part->getMediaSubtype(),
                'name' => $part->getFilename() ?? 'attachment',
            ], $email->getAttachments()),
        ], fn ($value) => $value !== null && $value !== []);

        $response = Http::timeout($this->timeout)
            ->acceptJson()
            ->withHeaders(['Authorization' => $this->authorization()])
            ->post('https://' . $this->host . '/v1.1/email', $payload);

        if ($response->failed()) {
            $details = $response->json('error.details.0.message') ?? $response->json('error.message') ?? $response->body();

            throw new TransportException(sprintf('ZeptoMail rejected the email (HTTP %d): %s', $response->status(), $details));
        }

        if ($requestId = $response->json('request_id')) {
            $message->setMessageId($requestId);
        }
    }

    public function __toString(): string
    {
        return 'zeptomail://' . $this->host;
    }

    /** Accept the key with or without the "Zoho-enczapikey " prefix. */
    private function authorization(): string
    {
        $key = trim($this->apiKey);

        return str_starts_with($key, 'Zoho-enczapikey') ? $key : 'Zoho-enczapikey ' . $key;
    }

    private function address(Address $address): array
    {
        return array_filter(['address' => $address->getAddress(), 'name' => $address->getName() ?: null]);
    }

    /** @param Address[] $addresses */
    private function recipients(array $addresses): array
    {
        return array_values(array_map(fn (Address $a) => ['email_address' => $this->address($a)], $addresses));
    }
}
