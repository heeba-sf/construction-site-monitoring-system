<?php
/**
 * Local LLM configuration (free).
 *
 * This project uses Ollama running locally on your machine:
 * - default URL: http://localhost:11434
 * - default model: phi3:mini (lighter for weak PCs)
 */

return [
    'provider' => 'ollama',
    'ollama_base_url' => getenv('OLLAMA_BASE_URL') ?: 'http://localhost:11434',
    'ollama_model' => getenv('OLLAMA_MODEL') ?: 'phi3:mini',
];

