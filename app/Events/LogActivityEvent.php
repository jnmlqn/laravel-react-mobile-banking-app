<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class LogActivityEvent
{
    use SerializesModels;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var string
     */
    private ?string $oldData;

    /**
     * @var string
     */
    private ?string $newData;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @param  string  $type
     * 
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param  string  $description
     * 
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  string|null  $oldData
     * 
     * @return $this
     */
    public function setOldData(?string $oldData): self
    {
        $this->oldData = $oldData;

        return $this;
    }

    /**
     * @param  string|null  $newData
     * 
     * @return $this
     */
    public function setNewData(?string $newData): self
    {
        $this->newData = $newData;

        return $this;
    }

    /**
     * @param  int  $userId
     * 
     * @return $this
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getOldData(): ?string
    {
        return $this->oldData;
    }

    /**
     * @return string|null
     */
    public function getNewData(): ?string
    {
        return $this->newData;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
