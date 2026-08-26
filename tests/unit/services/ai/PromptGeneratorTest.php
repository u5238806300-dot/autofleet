<?php

declare(strict_types=1);

namespace tests\unit\services\ai;

use PHPUnit\Framework\TestCase;
use app\services\ai\PromptGenerator;

final class PromptGeneratorTest extends TestCase
{
    public function testGenerateForObd2ReturnsCorrectFormat(): void
    {
        $generator = new PromptGenerator();
        $prompt = $generator->generateForObd2('P0300', 'Volkswagen', 'Golf VII');

        $this->assertStringContainsString('P0300', $prompt);
        $this->assertStringContainsString('Volkswagen', $prompt);
        $this->assertStringContainsString('Golf VII', $prompt);

        // Weryfikacja czy prompt instruuje model do zwrotu JSONa
        $this->assertStringContainsString('JSON', $prompt, 'Prompt musi wymuszać format JSON');
        $this->assertStringContainsString('```json', $prompt) === false; // Brak markdown
    }
}
