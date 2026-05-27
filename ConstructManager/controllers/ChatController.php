<?php
require_once 'config/database.php';

class ChatController {
    private array $llm;

    public function __construct() {
        $this->llm = require 'config/llm.php';
    }

    public function message() {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $raw = file_get_contents('php://input');
        $payload = json_decode($raw ?: '', true);
        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        $messages = $payload['messages'] ?? [];
        $system = (string)($payload['system'] ?? '');
        $model = (string)($payload['model'] ?? ($this->llm['ollama_model'] ?? 'llama3.1:8b'));

        if (!is_array($messages) || $messages === []) {
            http_response_code(400);
            echo json_encode(['error' => 'messages must be a non-empty array']);
            return;
        }

        $ollamaMessages = [];
        if ($system !== '') {
            $ollamaMessages[] = ['role' => 'system', 'content' => $system];
        }
        foreach ($messages as $m) {
            if (!is_array($m)) continue;
            $role = $m['role'] ?? '';
            $content = $m['content'] ?? '';
            if (!is_string($role) || !is_string($content)) continue;
            if (!in_array($role, ['user', 'assistant', 'system'], true)) continue;
            $ollamaMessages[] = ['role' => $role, 'content' => $content];
        }

        $baseUrl = rtrim((string)($this->llm['ollama_base_url'] ?? 'http://localhost:11434'), '/');
        $url = $baseUrl . '/api/chat';

        $ollamaRequest = [
            'model' => $model,
            'messages' => $ollamaMessages,
            'stream' => false,
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($ollamaRequest),
            CURLOPT_TIMEOUT => 60,
        ]);

        $respBody = curl_exec($ch);
        $curlErr = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($respBody === false) {
            http_response_code(502);
            echo json_encode([
                'error' => 'Local AI server not reachable',
                'hint' => 'Install/run Ollama, then ensure it is listening on http://localhost:11434',
                'details' => $curlErr ?: 'Unknown cURL error',
            ]);
            return;
        }

        $resp = json_decode($respBody, true);
        if (!is_array($resp)) {
            http_response_code(502);
            echo json_encode(['error' => 'Invalid upstream response', 'http_code' => $httpCode, 'raw' => $respBody]);
            return;
        }

        if ($httpCode >= 400) {
            http_response_code(502);
            echo json_encode([
                'error' => 'Local AI error',
                'http_code' => $httpCode,
                'upstream' => $resp,
            ]);
            return;
        }

        $reply = '';
        if (isset($resp['message']['content'])) {
            $reply = (string)$resp['message']['content'];
        }

        echo json_encode([
            'reply' => $reply,
            'raw' => $resp,
        ]);
    }
}

?>
