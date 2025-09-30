<?php

declare(strict_types=1);

namespace App\Component\Survey\Domain\ValueObject;

use App\Component\Survey\Domain\Entity\Question;
use App\Component\Survey\Domain\Exception\QuestionNotFoundException;
use Ramsey\Uuid\UuidInterface;

final class QuestionCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var Question[]
     */
    private array $questions = [];

    /**
     * @param Question[] $questions
     */
    public function __construct(array $questions = [])
    {
        foreach ($questions as $question) {
            $this->addQuestion($question);
        }
    }

    public function addQuestion(Question $question): self
    {
        $this->questions[(string) $question->getId()] = $question;

        return $this;
    }

    public function removeQuestion(UuidInterface $questionId): self
    {
        unset($this->questions[(string) $questionId]);

        return $this;
    }

    public function getQuestion(UuidInterface $questionId): Question
    {
        $question = $this->questions[(string) $questionId] ?? null;

        if ($question === null) {
            throw new QuestionNotFoundException('Question not found in collection');
        }

        return $question;
    }

    public function hasQuestion(UuidInterface $questionId): bool
    {
        return isset($this->questions[(string) $questionId]);
    }

    /**
     * @return Question[]
     */
    public function getQuestions(): array
    {
        return array_values($this->questions);
    }

    public function count(): int
    {
        return count($this->questions);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * @return \ArrayIterator<string, Question>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->questions);
    }

    /**
     * @param callable(Question): bool $predicate
     * @return Question[]
     */
    public function filter(callable $predicate): array
    {
        return array_filter($this->questions, $predicate);
    }

    /**
     * @return Question[]
     */
    public function getRequiredQuestions(): array
    {
        return $this->filter(static fn(Question $question): bool => $question->isRequired());
    }

    /**
     * @return Question[]
     */
    public function getOptionalQuestions(): array
    {
        return $this->filter(static fn(Question $question): bool => !$question->isRequired());
    }

    public function reorderQuestion(UuidInterface $questionId, int $newPosition): self
    {
        if (!$this->hasQuestion($questionId)) {
            throw new QuestionNotFoundException('Question not found in collection');
        }

        $questions = array_values($this->questions);
        $question = $this->getQuestion($questionId);

        // Remove question from current position
        $questions = array_filter($questions, static fn(Question $q): bool => $q->getId()->toString() !== $questionId->toString());

        // Insert at new position
        array_splice($questions, $newPosition, 0, [$question]);

        // Rebuild collection
        $this->questions = [];
        foreach ($questions as $q) {
            $this->addQuestion($q);
        }

        return $this;
    }
}